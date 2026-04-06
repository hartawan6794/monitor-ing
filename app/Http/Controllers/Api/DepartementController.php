<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class DepartementController extends Controller
{
    /**
     * GET: Ambil daftar Departemen (Bisa difilter by Divisi)
     */
    public function index(Request $request)
    {
        try {
            $query = DB::table('departement')->where('isactive', 1);

            // Filter by division_id jika dikirim dari frontend
            if ($request->has('division_id')) {
                $query->where('division', $request->division_id);
            }

            $departements = $query->select('id', 'name', 'division')->get();

            return response()->json([
                'status' => 'success',
                'data' => $departements
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}