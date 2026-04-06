<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Exception;

class DivisionController extends Controller
{
    /**
     * GET: Ambil daftar semua Divisi (Gudang fisik)
     */
    public function index()
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
}