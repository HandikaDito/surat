<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        // 🔥 FIX UTAMA: convert semua role ke integer
        $roles = array_map('intval', $roles);

        // 🔥 gunakan strict check
        if (!in_array($user->role_level, $roles, true)) {
            abort(403, 'Tidak punya akses');
        }

        return $next($request);
    }
}