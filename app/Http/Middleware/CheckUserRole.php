<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role = 'admin'): Response
    {
        // 1. Pastikan user terautentikasi (oleh Sanctum)
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Please login first.'
            ], 401);
        }

        // 2. Cek di tabel usersconfig pada database yang sedang aktif
        // Sesuai instruksi: "id dimenu users 'admin'"
        // Kita mencari data di usersconfig yang merepresentasikan role admin
        $hasAccess = DB::table('usersconfig')
            ->where('userid', $user->id)
            ->where('configvalues', true)
            ->exists();

        if (!$hasAccess) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. You do not have the ' . $role . ' role.'
            ], 403);
        }

        return $next($request);
    }
}
