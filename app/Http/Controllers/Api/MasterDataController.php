<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class MasterDataController extends Controller
{
    /**
     * GET: Ambil daftar Divisi
     */
    public function divisions()
    {
        try {
            $divisions = DB::table('division')
                ->select('id', 'description')
                ->where('isgroup', 0)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $divisions
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * GET: Ambil daftar Departemen (Bisa difilter by Divisi)
     */
    public function departments(Request $request)
    {
        try {
            $query = DB::table('departement')->where('isactive', 1);

            if ($request->has('division_id')) {
                $query->where('division', $request->division_id);
            }

            $data = $query->select('id', 'name', 'division')->get();
            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * GET: Ambil daftar Supplier
     */
    public function suppliers()
    {
        try {
            $data = DB::table('supplier')->where('isactive', 1)->select('id', 'name')->get();
            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * GET: Ambil daftar Group Produk
     */
    public function productGroups()
    {
        try {
            $data = DB::table('productgroup')->select('id', 'name')->get();
            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function productBrands()
    {
        // Mengambil id dan nama brand dari tabel productbrand
        try {
            $brands = DB::table('productbrand')->select('id', 'name')->get();

            return response()->json([
                'status' => 'success',
                'data' => $brands
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function accounts()
    {
        try {
            $data = DB::table('account')->select('id', 'name')->get();
            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}