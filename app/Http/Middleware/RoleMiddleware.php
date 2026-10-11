<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Superadmin memiliki hak akses penuh ke seluruh resource
        if ($user->role === \App\Models\User::ROLE_SUPERADMIN || in_array($user->role, $roles)) {
            return $next($request);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized. Role ' . $user->role . ' tidak memiliki akses ke resource ini.'
        ], 403);

        return $next($request);
    }
}
