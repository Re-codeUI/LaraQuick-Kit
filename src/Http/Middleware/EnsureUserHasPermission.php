<?php

namespace MagicBox\LaraQuickKit\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permissions
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permissions)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED)
                : redirect()->route('login');
        }

        $user = Auth::user();

        // Ambil permission dari cache untuk meningkatkan performa
        $cachedPermissions = Cache::remember(
            'user_permissions_' . $user->id,
            now()->addMinutes(10),
            function () use ($user) {
                return $user->getAllPermissions()->pluck('name')->toArray();
            }
        );

        // Mendukung multi-permission (AND = ',', OR = '|')
        if (str_contains($permissions, '|')) {
            $permissionsArray = explode('|', $permissions);
            if (!array_intersect($permissionsArray, $cachedPermissions)) {
                return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
            }
        } elseif (str_contains($permissions, ',')) {
            $permissionsArray = explode(',', $permissions);
            if (array_diff($permissionsArray, $cachedPermissions)) {
                return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
            }
        } else {
            if (!in_array($permissions, $cachedPermissions)) {
                return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
            }
        }

        return $next($request);
    }
}
