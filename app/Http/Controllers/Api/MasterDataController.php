<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Log;

class MasterDataController extends Controller
{
    //    buat swagger
    /**
     * @OA\Get(
     *     path="/master/divisions",
     *     tags={"Master"},
     *     summary="Daftar divisi",
     *     description="Mengambil data devisi",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\Response(
     *         response=200,description="Sukses",
     *         @OA\JsonContent(@OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
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
     * @OA\Get(
     *     path="/master/departments",
     *     tags={"Master"},
     *     summary="Daftar departemen",
     *     description="Mengambil data departemen",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\Parameter(
     *         name="division_id",
     *         in="query",
     *         description="ID divisi",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,description="Sukses",
     *         @OA\JsonContent(@OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
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
     * @OA\Get(
     *     path="/master/suppliers",
     *     tags={"Master"},
     *     summary="Daftar supplier",
     *     description="Mengambil data supplier",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\Response(
     *         response=200,description="Sukses",
     *         @OA\JsonContent(@OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
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
     * @OA\Get(
     *     path="/master/product-groups",
     *     tags={"Master"},
     *     summary="Daftar group produk",
     *     description="Mengambil data group produk",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\Response(
     *         response=200,description="Sukses",
     *         @OA\JsonContent(@OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
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

    /**
     * @OA\Get(
     *     path="/master/product-brands",
     *     tags={"Master"},
     *     summary="Daftar brand produk",
     *     description="Mengambil data brand produk",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\Response(
     *         response=200,description="Sukses",
     *         @OA\JsonContent(@OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/master/accounts",
     *     tags={"Master"},
     *     summary="Daftar akun",
     *     description="Mengambil data akun",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\Response(
     *         response=200,description="Sukses",
     *         @OA\JsonContent(@OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function accounts()
    {
        try {
            $data = DB::table('account')->select('id', 'name')->get();
            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/master/user-config-rules",
     *     tags={"Master"},
     *     summary="Daftar user config rules",
     *     description="Mengambil data user config rules",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\Response(
     *         response=200,description="Sukses",
     *         @OA\JsonContent(@OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function userConfigRules()
    {
        try {
            $sections = DB::table('userconfigrules')
                ->distinct()
                ->pluck('section');

            return response()->json(['status' => 'success', 'data' => $sections]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/master/customer-groups",
     *     tags={"Master"},
     *     summary="Daftar group pelanggan",
     *     description="Mengambil data group pelanggan",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Pencarian Nama Group Pelanggan",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,description="Sukses",
     *         @OA\JsonContent(@OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function customerGroups(Request $request)
    {
        try {
            $query = DB::table('customergroup');

            // Gunakan filled() untuk memastikan query string tidak kosong
            if ($request->filled('query')) {
                $searchTerm = $request->input('query');

                // Proteksi minimal 3 karakter
                if (strlen($searchTerm) >= 3) {
                    $query->where('description', 'like', '%' . $searchTerm . '%');
                } else {
                    // Jika user mengetik tapi kurang dari 3 huruf, 
                    // kita kembalikan data kosong agar tidak membebani server
                    return response()->json(['status' => 'success', 'data' => []]);
                }
            }

            // Ambil data dengan urutan abjad dan limitasi
            $data = $query->select('id', 'description as name')
                ->orderBy('description', 'asc')
                ->limit(20) // Batasi 20-30 data saja sudah cukup untuk pencarian group
                ->get();

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            // Log error di server, tapi beri pesan simpel ke user/FE
            Log::error("Error Customer Groups: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal mengambil data group.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/master/customers",
     *     tags={"Master"},
     *     summary="Daftar pelanggan",
     *     description="Mengambil data pelanggan",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\Parameter(
     *         name="customergroup_id",
     *         in="query",
     *         description="ID Group Pelanggan",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Pencarian Nama Pelanggan",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,description="Sukses",
     *         @OA\JsonContent(@OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function customers(Request $request)
    {
        try {
            $query = DB::table('customer')->where('isactive', 1);

            if ($request->filled('customergroup_id')) {
                $query->where('customergroup', $request->customergroup_id);
            }

            $searchTerm = $request->input('query');

            // Pastikan backend hanya memproses jika 3 karakter atau lebih
            if ($searchTerm && strlen($searchTerm) >= 3) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            } else {
                // Jika kurang dari 3, kembalikan data kosong atau pesan instruksi
                return response()->json(['status' => 'success', 'data' => []]);
            }

            // WAJIB: Gunakan limit agar response cepat sampai ke HP user
            $data = $query->select(
                    'id', 
                    'name', 
                    'address', 
                    'telephone', 
                    'creditlimit',
                    // Subquery untuk menghitung Piutang berjalan (Total Penjualan Kredit - Total Pembayaran Piutang)
                    DB::raw("(
                        (SELECT COALESCE(SUM(sd.netamount), 0) FROM sales s JOIN salesdetail sd ON s.salesid = sd.salesid WHERE s.customerid = customer.id AND s.salestype = 2) 
                        - 
                        (SELECT COALESCE(SUM(rp.amount), 0) FROM receivablepayment rp WHERE rp.customerid = customer.id)
                    ) as piutang")
                )
                ->orderBy('name', 'asc')
                ->limit(20)
                ->get();

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error'], 500);
        }
    }
}