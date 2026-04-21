<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * @OA\Post(
     *     path="/customers",
     *     tags={"Master Data"},
     *     summary="Tambah Pelanggan Baru",
     *     description="Endpoint untuk menambahkan data pelanggan baru ke database secara autopilot.",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Budi Sentosa"),
     *             @OA\Property(property="id", type="string", example="CUST-0001", description="Opsional. Jika kosong akan auto-generate."),
     *             @OA\Property(property="customergroup", type="string", example="001", description="ID Grup Pelanggan"),
     *             @OA\Property(property="address", type="string", example="Jl. Merdeka No. 45"),
     *             @OA\Property(property="telephone", type="string", example="081234567890"),
     *             @OA\Property(property="usercreate", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Pelanggan berhasil ditambahkan"),
     *     @OA\Response(response=400, description="Validasi gagal"),
     *     @OA\Response(response=500, description="Server Error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'id' => 'nullable|string|max:30|unique:customer,id',
            'customergroup' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:200',
            'telephone' => 'nullable|string|max:50',
            'creditlimit' => 'nullable|numeric',
            'defsalesmanid' => 'nullable|string|max:16',
            'pricelevel' => 'nullable|numeric',
            'usercreate' => 'nullable|string|max:16'
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
            $customerId = $request->id;

            // Generate ID Otomatis jika kosong
            if (empty($customerId)) {
                $prefix = !empty($request->customergroup) ? $request->customergroup : 'CUST-';

                $latestCustomer = DB::table('customer')
                    ->where('id', 'LIKE', $prefix . '%')
                    ->orderBy('id', 'desc')
                    ->first();

                $nextNumber = 1;
                if ($latestCustomer) {
                    $prefixLength = strlen($prefix);
                    $numPart = substr($latestCustomer->id, $prefixLength);
                    if (is_numeric($numPart)) {
                        $nextNumber = intval($numPart) + 1;
                    }
                }
                $customerId = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                // Pastikan tidak ada duplikasi
                while (DB::table('customer')->where('id', $customerId)->exists()) {
                    $nextNumber++;
                    $customerId = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                }
            }

            $now = Carbon::now();

            // Insert ke tabel customer dengan mapping default account Acosys
            DB::table('customer')->insert([
                'id' => $customerId,
                'customergroup' => $request->customergroup ?? '',
                'name' => $request->name,
                'address' => $request->address ?? '',
                'telephone' => $request->telephone ?? '',
                'mobilephone' => $request->telephone ?? '',
                'faximile' => '',
                'shipto' => $request->address ?? '',
                'category' => 0,
                'contactperson' => '',
                'religion' => 0,
                'sex' => 0,
                'email' => '',
                'website' => '',
                'country' => 'IDN',
                'creditlimit' => $request->creditlimit ?? 500000000,
                'creditlimittype' => 0,
                'registerdate' => $now->toDateTimeString(),
                'activeuntil' => '9999-12-31 00:00:00',
                'taxid' => '',
                'taxable' => 0,
                'termofpayment' => '',
                'isactive' => 1,
                'defsalesmanid' => $request->defsalesmanid ?? '001',
                'salesdiscrules' => '',
                'salesproductrewardrules' => '',
                'salespointrewardrules' => '',
                'paymentdiscrules' => '',
                'receivableacc' => '104.001',
                'rewardacc' => '601.001',
                'downpaymentacc' => '20.005',
                'salesdiscacc' => '401.002',
                'gainlossreceivableacc' => '701.005',
                'point' => 0,
                'usercreate' => $request->usercreate ?? 'admin',
                'useredit' => '',
                'pricelevel' => $request->pricelevel ?? 1,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Telah berhasil menambahkan pelanggan baru',
                'data' => [
                    'id' => $customerId,
                    'name' => $request->name
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}
