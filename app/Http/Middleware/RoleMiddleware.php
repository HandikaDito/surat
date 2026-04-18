<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$levels)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        // 🔥 langsung cek role_level
        if (!in_array($user->role_level, $levels)) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}