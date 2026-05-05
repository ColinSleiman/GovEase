<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()
                ->route('portal.access')
                ->with('error', 'Please log in to continue.');
        }

        if (Auth::user()->role?->name !== 'Administrator') {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
