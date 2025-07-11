<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CheckAdminExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Check if there's any user in the database
        $hasUser = User::exists();

        // If any user exists and user is trying to access register route
        if ($hasUser && $request->routeIs('register')) {
            return redirect()->route('login')
                ->with('error', 'Registration is disabled. An account already exists.')
                ->with('user_exists', true);
        }

        return $next($request);
    }
}
