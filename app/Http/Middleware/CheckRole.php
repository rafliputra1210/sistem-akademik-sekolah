<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Jika role tidak cocok, arahkan ke dashboard masing-masing dengan pesan error
        $redirectRoute = match ($userRole) {
            'admin'  => 'admin.dashboard',
            'guru'   => 'guru.dashboard',
            'kepsek' => 'kepsek.dashboard',
            default  => 'dashboard',
        };

        return redirect()->route($redirectRoute)->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
    }
}