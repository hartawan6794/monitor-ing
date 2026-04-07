<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Montor-ing API",
 *     version="1.0.0",
 *     description="Dokumentasi API untuk aplikasi Montor-ing (Inventory & Sales Monitoring)."
 * )
 * @OA\Parameter(
 *         name="X-Server-IP",
 *         in="header",
 *         description="Alamat server untuk koneksi database",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="X-Database-Name",
 *         in="header",
 *         description="Nama database yang digunakan",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="X-DB-Username",
 *         in="header",
 *         description="Username untuk koneksi database",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="X-DB-Password",
 *         in="header",
 *         description="Password untuk koneksi database",
 *         required=true,
 *         @OA\Schema(type="string")
 *     )
 * )
 *
 * @OA\Server(
 *     url="/api",
 *     description="Server API Utama"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Masukkan token dari endpoint /login. Format: Bearer {token}"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
