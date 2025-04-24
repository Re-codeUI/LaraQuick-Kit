<?php

namespace MagicBox\LaraQuickKit\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    public function handle(Request $request, Closure $next, $permissions)
    {
        if (!Auth::check()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED)
                : redirect()->route('login');
        }

        $user = Auth::user();

        // Buat cache key dengan lebih aman
        $cacheKey = 'user_permissions_' . md5($user->id . '_' . $user->email);

        // Cache permission user
        $cachedPermissions = Cache::remember(
            $cacheKey,
            now()->addMinutes(10),
            function () use ($user) {
                return $user->getAllPermissions()->pluck('name')->toArray();
            }
        );

        $hasPermission = false;

        if (str_contains($permissions, '|')) {
            $permissionsArray = explode('|', $permissions);
            foreach ($permissionsArray as $perm) {
                if ($this->matchPermission($perm, $cachedPermissions)) {
                    $hasPermission = true;
                    break;
                }
            }
        } elseif (str_contains($permissions, ',')) {
            $permissionsArray = explode(',', $permissions);
            $hasPermission = collect($permissionsArray)->every(fn($perm) => $this->matchPermission($perm, $cachedPermissions));
        } else {
            $hasPermission = $this->matchPermission($permissions, $cachedPermissions);
        }

        if (!$hasPermission) {
            Log::warning("User ID {$user->id} mencoba akses tanpa permission: {$permissions}");
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }

    private function matchPermission(string $permission, array $userPermissions): bool
    {
        if (str_contains($permission, '*')) {
            $pattern = str_replace('*', '.*', preg_quote($permission, '/'));
            return collect($userPermissions)->filter(fn($p) => preg_match("/^{$pattern}$/", $p))->isNotEmpty();
        }

        return in_array($permission, $userPermissions);
    }
}
