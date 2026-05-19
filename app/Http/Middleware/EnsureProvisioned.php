<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProvisioned
{
    /**
     * Rute yang dikecualikan dari pengecekan provisioning.
     * User boleh mengakses halaman ini meski belum provisioned.
     */
    protected array $except = [
        'my-subscription*',
        'login',
        'logout',
        'register',
        'home',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Hanya berlaku untuk user yang sudah login
        if (!$user) {
            return $next($request);
        }

        // Cek apakah rute ini dikecualikan
        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        // Jika user status = 'pending' → sudah bayar tapi belum ada DB terhubung
        if ($user->provisioning_status === 'pending') {
            // Jangan redirect loop jika sudah di halaman provisioning
            if ($request->is('provisioning-pending')) {
                return $next($request);
            }
            return redirect()->route('provisioning.pending');
        }

        return $next($request);
    }
}
