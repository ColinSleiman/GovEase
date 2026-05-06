<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()
                ->route('portal.access')
                ->with('error', 'Please log in to continue.');
        }

        if (empty($roles)) {
            return $next($request);
        }

        $allowedRoles = [];
        foreach ($roles as $role) {
            $allowedRoles[] = strtolower(trim($role));
        }

        $user = Auth::user();
        $currentRole = '';
        if ($user && $user->role && $user->role->name) {
            $currentRole = strtolower($user->role->name);
        }

        if (!in_array($currentRole, $allowedRoles, true)) {
            return redirect()
                ->route('home')
                ->with('error', 'You are not authorized to access that page.');
        }

        return $next($request);
    }
}
