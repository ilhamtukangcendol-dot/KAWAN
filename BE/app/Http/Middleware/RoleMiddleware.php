<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

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
        if ((int) $user->role === User::ROLE_SUPERADMIN || in_array('superadmin', $roles) || in_array('1', $roles) || in_array(1, $roles)) {
            return $next($request);
        }

        $allowedNumericRoles = [];
        foreach ($roles as $role) {
            if (is_numeric($role)) {
                $allowedNumericRoles[] = (int) $role;
            } else {
                switch (strtolower(trim($role))) {
                    case 'superadmin':
                    case 'admin':
                        $allowedNumericRoles[] = User::ROLE_SUPERADMIN;
                        break;
                    case 'rw':
                    case 'ketua_rw':
                        $allowedNumericRoles[] = User::ROLE_RW;
                        break;
                    case 'sekretaris_rw':
                        $allowedNumericRoles[] = User::ROLE_SEKRETARIS_RW;
                        break;
                    case 'bendahara_rw':
                        $allowedNumericRoles[] = User::ROLE_BENDAHARA_RW;
                        break;
                    case 'rt':
                    case 'ketua_rt':
                        $allowedNumericRoles[] = User::ROLE_RT;
                        break;
                    case 'sekretaris_rt':
                    case 'sekretaris':
                        $allowedNumericRoles[] = User::ROLE_SEKRETARIS_RT;
                        break;
                    case 'bendahara_rt':
                    case 'bendahara':
                        $allowedNumericRoles[] = User::ROLE_BENDAHARA_RT;
                        break;
                    case 'warga':
                        $allowedNumericRoles[] = User::ROLE_WARGA;
                        break;
                    case 'pengurus':
                        $allowedNumericRoles = array_merge($allowedNumericRoles, [
                            User::ROLE_SUPERADMIN,
                            User::ROLE_RW,
                            User::ROLE_SEKRETARIS_RW,
                            User::ROLE_BENDAHARA_RW,
                            User::ROLE_RT,
                            User::ROLE_SEKRETARIS_RT,
                            User::ROLE_BENDAHARA_RT,
                        ]);
                        break;
                    case 'keuangan':
                        $allowedNumericRoles = array_merge($allowedNumericRoles, [
                            User::ROLE_SUPERADMIN,
                            User::ROLE_RW,
                            User::ROLE_RT,
                            User::ROLE_BENDAHARA_RT,
                            User::ROLE_BENDAHARA_RW,
                        ]);
                        break;
                }
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
