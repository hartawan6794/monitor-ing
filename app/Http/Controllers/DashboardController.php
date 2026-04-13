<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Cek apakah sudah ada token aktif untuk session ini
        // Jika belum ada, buat token baru (berlaku 1 hari)
        $tokenName = 'dashboard-session-' . session()->getId();
        $existingToken = $user->tokens()->where('name', $tokenName)->first();

        if ($existingToken) {
            // Token sudah ada, kita perlu trik kecil: simpan plain text di session
            $plainToken = session('_api_token_plain');
            if (!$plainToken) {
                // Token ada di DB tapi plain-nya tidak di session, buat baru
                $existingToken->delete();
                $newToken   = $user->createToken($tokenName, ['*'], now()->addDay());
                $plainToken = $newToken->plainTextToken;
                session(['_api_token_plain' => $plainToken]);
            }
        } else {
            $newToken   = $user->createToken($tokenName, ['*'], now()->addDay());
            $plainToken = $newToken->plainTextToken;
            session(['_api_token_plain' => $plainToken]);
        }

        return view('moduls.dashboard', ['apiToken' => $plainToken]);
    }
}
