<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use Log;

class InventoryAdjustmentController extends Controller
{
    /**
     * 1. POST API: Simpan Transaksi Penyesuaian Stok
     */
    public function storeAdjustment(Request $request)
    {
        // A. Validasi Input JSON dari Android
        $validator = Validator::make($request->all(), [
            'transdate' => 'required|date',
            'division' => 'required|string',
            'usercreate' => 'required|string',
            'memo' => 'nullable|string',
            'accountid' => 'nullable|string', // Akun Modal / Saldo Awal, misal 300.001
            'details' => 'required|array|min:1',
            'details.*.productid' => 'required|string',
            'details.*.departement_id' => 'required|string', // Departemen per barang
            'details.*.supplier_id' => 'required|string',
            'details.*.unit' => 'required|string',
            'details.*.recorded' => 'required|numeric',
            'details.*.physical' => 'required|numeric',
            'details.*.memo' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak lengkap atau tidak valid.',
                'errors' => $validator->errors()
            ], 400);
        }

        // B. Mulai Database Transaction
        DB::beginTransaction();

        try {
            // C. AUTO-NUMBERING BERDASARKAN DIVISI
            $div = DB::table('division')->where('id', $request->division)->lockForUpdate()->first();

            if (!$div) {
                throw new \Exception("Divisi tidak ditemukan!"); // Tambahkan slash (\) agar tidak error namespace
            }

            // 1. Format ID Transaksi
            $rawFormat = $div->frminventoryid;
            $prefix = str_replace('%0:6.6d', '', $rawFormat);
            $nextNumber = $div->inventoryidno + 1;
            $transId = $prefix . str_pad($nextNumber, 6, "0", STR_PAD_LEFT);

            // Update nomor urut di tabel divisi
            DB::table('division')
                ->where('id', $request->division)
                ->update(['inventoryidno' => $nextNumber]);

            $currentTime = date('H:i:s');
            $currentDateTime = $request->transdate . ' ' . $currentTime;

            // D. INSERT KE TABEL HEADER (inventoryadjust)
            DB::table('inventoryadjust')->insert([
                'id' => $transId,
                'transdate' => $request->transdate,
                'transtime' => $currentTime,
                'division' => $request->division,
                'memo' => $request->memo ?? 'Stock Opname',
                'memoedit' => '',
                'posted' => 1,
                'usercreate' => $request->usercreate,
                'useredit' => '',
            ]);

            // 1. Ambil Harga (costprice) dan Akun Persediaan (inventoryacc) langsung dari tabel product
            $productsData = DB::table('product')
                ->whereIn('id', collect($request->details)->pluck('productid'))
                ->get()
                ->keyBy('id');

            // E. LOOPING INSERT DETAIL, BUKU BESAR & JURNAL
            foreach ($request->details as $detail) {
                $recorded = (float) $detail['recorded'];
                $physical = (float) $detail['physical'];
                $diff = $physical - $recorded;

                // Jika tidak ada selisih, lompati
                if ($diff == 0)
                    continue;

                // Amankan jika produk tidak ditemukan di database
                $productDb = $productsData[$detail['productid']] ?? null;

                // Jika produk tidak ada, beri nilai default agar tidak crash
                $cogs = $productDb ? (float) $productDb->costprice : 0;
                $inventoryAccount = ($productDb && !empty($productDb->inventoryacc)) ? $productDb->inventoryacc : '107.001';

                // 2. TENTUKAN QTY MASUK & KELUAR
                $inqty = $diff > 0 ? $diff : 0;
                $outqty = $diff < 0 ? abs($diff) : 0;

                // 3. HITUNG NOMINAL UANGNYA (Sesuai Qty * Harga)
                $invalue = $inqty * $cogs;
                $outvalue = $outqty * $cogs;
                $totalValue = abs($diff) * $cogs;

                $userAccountId = $request->accountid;

                $dateRef = date('Ymd', strtotime($request->transdate)) . mt_rand(10000000, 99999999);

                // 4. INSERT KE INVENTORY ADJUST DETAIL
                DB::table('inventoryadjustdetail')->insert([
                    'transid' => $transId,
                    'transdate' => $currentDateTime,
                    'division' => $request->division,
                    'departement' => $detail['departement_id'],
                    'productid' => $detail['productid'],
                    'snproduct' => '',
                    'unit' => $detail['unit'],
                    'recorded' => $recorded,
                    'physical' => $physical,
                    'inqty' => $inqty,
                    'outqty' => $outqty,
                    'invalue' => $invalue,
                    'outvalue' => $outvalue,
                    'cogs' => $cogs,
                    'memo' => $detail['memo'] ?? '',
                    'usercreate' => $request->usercreate,
                    'useredit' => '',
                ]);

                //transtype if adjust in = 6 else = 7
                $transtype = $inqty > 0 ? 6 : 7;
                // 5. INSERT KE INVENTORY (BUKU STOK)
                DB::table('inventory')->insert([
                    'transid' => $transId,
                    'transdate' => $currentDateTime,
                    'departement' => $detail['departement_id'],
                    'division' => $request->division,
                    'supplier' => $detail['supplier_id'],
                    'productid' => $detail['productid'],
                    'snproduct' => '',
                    'invin' => $inqty,
                    'invout' => $outqty,
                    'invvalue' => $cogs,
                    'reference' => $transId,
                    'datereference' => $dateRef,
                    'transtype' => $transtype,
                    'memo' => 'Adjustment: ' . ($detail['memo'] ?? ''),
                    'usercreate' => $request->usercreate,
                    'useredit' => '',
                    'isempty' => 0
                ]);

                // 6. INSERT JURNAL (PASTI DIEKSEKUSI JIKA COGS > 0)
                if ($totalValue > 0) {
                    // Jurnal Debit/Kredit Sisi Persediaan
                    DB::table('journaltrans')->insert([
                        'jtid' => $transId,
                        'jtdate' => $request->transdate . ' 00:00:00',
                        'memo' => $detail['memo'] ?? '',
                        'accountid' => $inventoryAccount,
                        'debit' => $invalue,
                        'credit' => $outvalue,
                        'division' => $request->division,
                        'reftransaction' => $transId,
                        'usercreate' => $request->usercreate,
                        'useredit' => ''
                    ]);

                    // Jurnal Debit/Kredit Sisi Akun User (Dibalik posisinya)
                    DB::table('journaltrans')->insert([
                        'jtid' => $transId,
                        'jtdate' => $request->transdate . ' 00:00:00',
                        'memo' => $detail['memo'] ?? '',
                        'accountid' => $userAccountId,
                        'debit' => $outvalue,
                        'credit' => $invalue,
                        'division' => $request->division,
                        'reftransaction' => $transId,
                        'usercreate' => $request->usercreate,
                        'useredit' => ''
                    ]);
                }
            }

            // F. JIKA SEMUA BERHASIL, SIMPAN PERMANEN
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Penyesuaian stok berhasil disimpan!',
                'transid' => $transId
            ], 201);

        } catch (\Exception $e) { // Tambahkan slash (\) di sini juga
            // G. JIKA ADA ERROR, BATALKAN SEMUA PERUBAHAN
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan penyesuaian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 2. POST API: Simpan Inventory Awal (Initial Stock)
     * Dipanggil setelah membuat produk baru jika ada stok awal.
     */
    public function storeInitialInventory(Request $request)
    {
        // A. Validasi Input
        $validator = Validator::make($request->all(), [
            'division' => 'required|string',
            'usercreate' => 'required|string',
            'memo' => 'nullable|string',
            'accountid' => 'nullable|string', // Akun Modal / Saldo Awal, misal 300.001
            'details' => 'required|array|min:1',
            'details.*.productid' => 'required|string',
            'details.*.departement_id' => 'required|string', // Departemen per barang
            'details.*.unit' => 'required|string',
            'details.*.recorded' => 'required|numeric',
            'details.*.physical' => 'required|numeric',
            'details.*.memo' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak lengkap atau tidak valid.',
                'errors' => $validator->errors()
            ], 400);
        }

        DB::beginTransaction();

        try {
            $transdate = $request->transdate ?? date('Y-m-d');

            // AUTO-NUMBERING BERDASARKAN DIVISI
            $div = DB::table('division')->where('id', $request->division)->lockForUpdate()->first();
            if (!$div) {
                throw new \Exception("Divisi tidak ditemukan!");
            }

            // Format ID Transaksi
            $rawFormat = $div->frminventoryid;
            $prefix = str_replace('%0:6.6d', '', $rawFormat);
            $nextNumber = $div->inventoryidno + 1;
            $transId = $prefix . str_pad($nextNumber, 6, "0", STR_PAD_LEFT);

            // Update nomor urut di tabel divisi
            DB::table('division')
                ->where('id', $request->division)
                ->update(['inventoryidno' => $nextNumber]);

            $currentTime = date('H:i:s');
            $currentDateTime = $transdate . ' ' . $currentTime;
            $dateRef = date('Ymd', strtotime($transdate)) . mt_rand(10000000, 99999999);

            // KITA SKIP INSERT KE inventoryadjust & inventoryadjustdetail
            // Langsung dicatat ke BUKU STOK (inventory) dan JURNAL (journaltrans)

            foreach ($request->details as $detail) {
                // Cek produk per detail
                $productDb = DB::table('product')->where('id', $detail['productid'])->first();
                if (!$productDb) {
                    throw new \Exception("Produk dengan ID " . $detail['productid'] . " tidak ditemukan!");
                }

                $physical = (float) $detail['physical'];
                $recorded = (float) $detail['recorded'];
                $qty = $physical - $recorded;

                // Abaikan jika tidak ada selisih/penambahan
                if ($qty == 0) {
                    continue;
                }

                $cogs = (float) $productDb->costprice;
                $inventoryAccount = !empty($productDb->inventoryacc) ? $productDb->inventoryacc : '107.001';

                // Gunakan accountid dari detail, jika kosong gunakan dari root (untuk kompatibilitas)
                $userAccountId = $detail['accountid'] ?? ($request->accountid ?? '300.001');

                $totalValue = $qty * $cogs;
                $memoDetail = $detail['memo'] ?? ($request->memo ?? 'Saldo Awal Produk ' . $productDb->name);

                // INSERT KE INVENTORY (BUKU STOK)
                DB::table('inventory')->insert([
                    'transid' => $transId,
                    'transdate' => $currentDateTime,
                    'departement' => $detail['departement_id'],
                    'division' => $request->division,
                    'supplier' => $productDb->supplier,
                    'productid' => $detail['productid'],
                    'snproduct' => '',
                    'invin' => $qty > 0 ? $qty : 0,
                    'invout' => $qty < 0 ? abs($qty) : 0,
                    'invvalue' => $cogs,
                    'reference' => $transId,
                    'datereference' => $dateRef,
                    'transtype' => 99, // 99 = Saldo Awal
                    'memo' => $memoDetail,
                    'usercreate' => $request->usercreate,
                    'useredit' => '',
                    'isempty' => 0
                ]);

                // INSERT JURNAL (JIKA COGS > 0)
                if (abs($totalValue) > 0) {
                    if ($qty > 0) {
                        // Debit: Persediaan
                        DB::table('journaltrans')->insert([
                            'jtid' => $transId,
                            'jtdate' => $transdate . ' 00:00:00',
                            'memo' => $memoDetail,
                            'accountid' => $inventoryAccount,
                            'debit' => $totalValue,
                            'credit' => 0,
                            'division' => $request->division,
                            'reftransaction' => $transId,
                            'usercreate' => $request->usercreate,
                            'useredit' => ''
                        ]);

                        // Kredit: Modal/Saldo Awal
                        DB::table('journaltrans')->insert([
                            'jtid' => $transId,
                            'jtdate' => $transdate . ' 00:00:00',
                            'memo' => $memoDetail,
                            'accountid' => $userAccountId,
                            'debit' => 0,
                            'credit' => $totalValue,
                            'division' => $request->division,
                            'reftransaction' => $transId,
                            'usercreate' => $request->usercreate,
                            'useredit' => ''
                        ]);
                    } else {
                        // Jika qty < 0 (Koreksi Minus)
                        $absTotalValue = abs($totalValue);

                        // Debit: Modal/Saldo Awal
                        DB::table('journaltrans')->insert([
                            'jtid' => $transId,
                            'jtdate' => $transdate . ' 00:00:00',
                            'memo' => 'Koreksi Minus ' . $memoDetail,
                            'accountid' => $userAccountId,
                            'debit' => $absTotalValue,
                            'credit' => 0,
                            'division' => $request->division,
                            'reftransaction' => $transId,
                            'usercreate' => $request->usercreate,
                            'useredit' => ''
                        ]);

                        // Kredit: Persediaan
                        DB::table('journaltrans')->insert([
                            'jtid' => $transId,
                            'jtdate' => $transdate . ' 00:00:00',
                            'memo' => 'Koreksi Minus ' . $memoDetail,
                            'accountid' => $inventoryAccount,
                            'debit' => 0,
                            'credit' => $absTotalValue,
                            'division' => $request->division,
                            'reftransaction' => $transId,
                            'usercreate' => $request->usercreate,
                            'useredit' => ''
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Inventory awal berhasil disimpan!',
                'transid' => $transId
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan inventory awal: ' . $e->getMessage()
            ], 500);
        }
    }
}