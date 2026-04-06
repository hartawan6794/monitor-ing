<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Controllers\Controller;

class InventoryMovingController extends Controller
{
    public function storeMoving(Request $request)
    {
        // Validasi input
        $request->validate([
            'transdate' => 'required|date',
            'fromdivision' => 'required|string',
            'fromdepartement' => 'required|string',
            'todivision' => 'required|string',
            'todepartement' => 'required|string',
            'details' => 'required|array',
            'usercreate' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            // 1. Generate TransID (Gunakan gudang asal sebagai referensi prefix)
            $div = DB::table('division')->where('id', $request->fromdivision)->first();
            $prefix = str_replace('%0:6.6d', '', $div->frminventoryid);
            $nextNumber = $div->inventoryidno + 1;
            $transId = $prefix . str_pad($nextNumber, 6, "0", STR_PAD_LEFT);

            // Update counter di tabel division
            DB::table('division')->where('id', $request->fromdivision)->update(['inventoryidno' => $nextNumber]);

            // 2. Insert Header (inventorymoving)
            DB::table('inventorymoving')->insert([
                'id' => $transId,
                'transdate' => $request->transdate,
                'transtime' => date('H:i:s'),
                'fromdivision' => $request->fromdivision,
                'fromdepartement' => $request->fromdepartement,
                'todivision' => $request->todivision,
                'todepartement' => $request->todepartement,
                'accountid' => '101.001',
                'memo' => $request->memo ?? 'Moving via App',
                'usercreate' => $request->usercreate,
            ]);

            foreach ($request->details as $item) {
                // 3. Insert Detail (inventorymovingdetail)
                DB::table('inventorymovingdetail')->insert([
                    'transid' => $transId,
                    'transdate' => $request->transdate . ' ' . date('H:i:s'),
                    'productid' => $item['productid'],
                    'unit' => $item['unit'],
                    'qty' => $item['qty'],
                    'usercreate' => $request->usercreate,
                ]);

                // 4. Update Buku Besar (inventory) - DUA BARIS
                // BARIS 1: Keluar dari gudang asal
                DB::table('inventory')->insert([
                    'transid' => $transId,
                    'transdate' => $request->transdate . ' ' . date('H:i:s'),
                    'division' => $request->fromdivision,
                    'departement' => $request->fromdepartement,
                    'supplier' => '001001',
                    'productid' => $item['productid'],
                    'invin' => 0,
                    'invout' => $item['qty'],
                    'reference' => $transId,
                    'datereference' => $request->transdate,
                    'transtype' => 2, // Kode 2 untuk Moving
                    'usercreate' => $request->usercreate,
                ]);

                // BARIS 2: Masuk ke gudang tujuan
                DB::table('inventory')->insert([
                    'transid' => $transId,
                    'transdate' => $request->transdate . ' ' . date('H:i:s'),
                    'division' => $request->todivision,
                    'departement' => $request->todepartement,
                    'supplier' => '001001',
                    'productid' => $item['productid'],
                    'invin' => $item['qty'],
                    'invout' => 0,
                    'reference' => $transId,
                    'datereference' => $request->transdate,
                    'transtype' => 2,
                    'usercreate' => $request->usercreate,
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'transid' => $transId]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}