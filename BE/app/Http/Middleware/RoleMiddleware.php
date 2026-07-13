<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['status' => 'error', 'message' => 'Unauthenticated.'], 401);
            }
            return redirect('login');
        }

        $user = auth()->user();

        // Superadmin (Role 1) has full access to all protected routes
        if ((int) $user->role === \App\Models\User::ROLE_SUPERADMIN || in_array('superadmin', $roles) || in_array('1', $roles) || in_array(1, $roles)) {
            return $next($request);
        }

        $allowedNumericRoles = [];
        foreach ($roles as $role) {
            if (is_numeric($role)) {
                $allowedNumericRoles[] = (int) $role;
            } elseif ($role === 'superadmin') {
                $allowedNumericRoles[] = \App\Models\User::ROLE_SUPERADMIN;
            } elseif ($role === 'rt') {
                $allowedNumericRoles[] = \App\Models\User::ROLE_RT;
            } elseif ($role === 'bendahara') {
                $allowedNumericRoles[] = \App\Models\User::ROLE_BENDAHARA;
            } elseif ($role === 'warga') {
                $allowedNumericRoles[] = \App\Models\User::ROLE_WARGA;
            }
        }

        if (!in_array((int) $user->role, $allowedNumericRoles)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['status' => 'error', 'message' => 'Forbidden. Hak akses tidak mencukupi.'], 403);
            }
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}