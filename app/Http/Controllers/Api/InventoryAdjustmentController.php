<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

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
            // Gunakan lockForUpdate() agar jika ada 2 user input bersamaan, tidak terjadi ID bentrok ganda
            $div = DB::table('division')->where('id', $request->division)->lockForUpdate()->first();

            if (!$div) {
                throw new Exception("Divisi tidak ditemukan!");
            }

            // 1. Ambil format mentah dari DB (Isinya: "I/IM-%0:6.6d")
            $rawFormat = $div->frminventoryid;

            // 2. Bersihkan format aneh tersebut. 
// Kita hapus bagian "%0:6.6d" agar tersisa hanya prefix "I/IM-"
            $prefix = str_replace('%0:6.6d', '', $rawFormat);

            // 3. Ambil nomor urut berikutnya
            $nextNumber = $div->inventoryidno + 1;

            // 4. Gabungkan secara manual dengan str_pad
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
                'posted' => 1,
                'usercreate' => $request->usercreate,
            ]);

            // E. LOOPING INSERT DETAIL & BUKU BESAR
            foreach ($request->details as $detail) {
                $recorded = (float) $detail['recorded'];
                $physical = (float) $detail['physical'];
                $diff = $physical - $recorded;

                $inqty = $diff > 0 ? $diff : 0;
                $outqty = $diff < 0 ? abs($diff) : 0;

                // 1. Insert ke tabel detail (inventoryadjustdetail)
                DB::table('inventoryadjustdetail')->insert([
                    'transid' => $transId,
                    'transdate' => $currentDateTime,
                    'division' => $request->division,
                    'departement' => $detail['departement'],
                    'productid' => $detail['productid'],
                    'unit' => $detail['unit'],
                    'recorded' => $recorded,
                    'physical' => $physical,
                    'inqty' => $inqty,
                    'outqty' => $outqty,
                    'memo' => $detail['memo'] ?? '',
                    'usercreate' => $request->usercreate,
                ]);

                // 2. Insert ke Buku Besar Inventaris JIKA ADA SELISIH (inventory)
                if ($diff != 0) {
                    DB::table('inventory')->insert([
                        'transid' => $transId,
                        'transdate' => $currentDateTime,
                        'departement' => $detail['departement'],
                        'division' => $request->division,
                        'supplier' => '001001', // TAMBAHKAN INI (Sesuaikan dengan ID supplier dummy di DB-mu)
                        'productid' => $detail['productid'],
                        'invin' => $inqty,
                        'invout' => $outqty,
                        'reference' => $transId,
                        'datereference' => date('Y-m-d', strtotime($request->transdate)),
                        'transtype' => 1,
                        'memo' => 'Adjustment: ' . ($detail['memo'] ?? ''),
                        'usercreate' => $request->usercreate,
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

        } catch (Exception $e) {
            // G. JIKA ADA ERROR, BATALKAN SEMUA PERUBAHAN
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan penyesuaian: ' . $e->getMessage()
            ], 500);
        }
    }
}