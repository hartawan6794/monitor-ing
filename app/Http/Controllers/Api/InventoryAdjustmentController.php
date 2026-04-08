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
            'details' => 'required|array|min:1',
            'details.*.productid' => 'required|string',
            'details.*.departement' => 'required|string', // Departemen per barang
            'details.*.unit' => 'required|string',
            'details.*.recorded' => 'required|numeric',
            'details.*.physical' => 'required|numeric',
            'details.*.memo' => 'nullable|string',
            'details.*.accountid' => 'required|string',
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

                $userAccountId = $detail['accountid'] ?? '300.001';
                // HAPUS BARIS INI: $inventoryAccount = '107.001'; (Karena akan menimpa akun dinamis dari DB)

                $dateRef = date('Ymd', strtotime($request->transdate)) . mt_rand(10000000, 99999999);

                // 4. INSERT KE INVENTORY ADJUST DETAIL
                DB::table('inventoryadjustdetail')->insert([
                    'transid' => $transId,
                    'transdate' => $currentDateTime,
                    'division' => $request->division,
                    'departement' => $detail['departement'],
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

                // 5. INSERT KE INVENTORY (BUKU STOK)
                DB::table('inventory')->insert([
                    'transid' => $transId,
                    'transdate' => $currentDateTime,
                    'departement' => $detail['departement'],
                    'division' => $request->division,
                    'supplier' => '001001',
                    'productid' => $detail['productid'],
                    'snproduct' => '',
                    'invin' => $inqty,
                    'invout' => $outqty,
                    'invvalue' => $cogs,
                    'reference' => $transId,
                    'datereference' => $dateRef,
                    'transtype' => 6,
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
}