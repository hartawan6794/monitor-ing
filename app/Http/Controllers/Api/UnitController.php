<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    /**
     * @OA\Get(
     *     path="/units",
     *     tags={"Units"},
     *     summary="Daftar unit",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Response(response=200, description="Sukses")
     * )
     */
    public function index()
    {
        try {
            $data = DB::table('units')->get();
            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/units",
     *     tags={"Units"},
     *     summary="Tambah unit baru",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="unit", type="string"),
     *             @OA\Property(property="quantity", type="number"),
     *             @OA\Property(property="qtyfrom", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Sukses")
     * )
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'unit' => 'required|string|max:10',
                'quantity' => 'required|numeric',
                'qtyfrom' => 'required|string|max:10',
                'description' => 'nullable|string|max:50'
            ]);

            $user = Auth::user();
            $username = $user ? ($user->username ?? $user->name) : 'system';

            DB::table('units')->insert([
                'unit' => $request->unit,
                'quantity' => $request->quantity,
                'qtyfrom' => $request->qtyfrom,
                'description' => $request->description ?? '',
                'usercreate' => $username,
                'useredit' => $username,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Unit created successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/units/{id}",
     *     tags={"Units"},
     *     summary="Detail unit",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Sukses")
     * )
     */
    public function show($id)
    {
        try {
            $data = DB::table('units')->where('unit', $id)->first();
            if (!$data) {
                return response()->json(['status' => 'error', 'message' => 'Unit not found'], 404);
            }
            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/units/{id}",
     *     tags={"Units"},
     *     summary="Update unit",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Sukses")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => 'numeric',
                'qtyfrom' => 'string|max:10',
                'description' => 'nullable|string|max:50'
            ]);

            $user = Auth::user();
            $username = $user ? ($user->username ?? $user->name) : 'system';

            $updateData = $request->only(['quantity', 'qtyfrom', 'description']);
            $updateData['useredit'] = $username;

            DB::table('units')->where('unit', $id)->update($updateData);
            return response()->json(['status' => 'success', 'message' => 'Unit updated successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/units/{id}",
     *     tags={"Units"},
     *     summary="Hapus unit",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Sukses")
     * )
     */
    public function destroy($id)
    {
        try {
            DB::table('units')->where('unit', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Unit deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/units/getsatuan",
     *     tags={"Units"},
     *     summary="Daftar qtyfrom (satuan)",
     *     description="Mengambil data unik dari kolom qtyfrom",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Response(response=200, description="Sukses")
     * )
     */
    public function getsatuan()
    {
        try {
            $data = DB::table('units')
                ->whereNotNull('qtyfrom')
                ->where('qtyfrom', '!=', '')
                ->distinct()
                ->select('qtyfrom as id', 'qtyfrom as name')
                ->get();

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/units/getdefunit",
     *     tags={"Units"},
     *     summary="Daftar unit berdasarkan qtyfrom",
     *     description="Filter data unit berdasarkan qtyfrom yang dipilih",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(name="qtyfrom", in="query", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Sukses")
     * )
     */
    public function getdefunit(Request $request)
    {
        try {
            $qtyfrom = $request->query('qtyfrom');
            if (!$qtyfrom) {
                return response()->json(['status' => 'error', 'message' => 'qtyfrom parameter is required'], 400);
            }

            $data = DB::table('units')
                ->where('qtyfrom', $qtyfrom)
                ->select('unit as id', 'unit as name', 'quantity', 'description')
                ->get();

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
