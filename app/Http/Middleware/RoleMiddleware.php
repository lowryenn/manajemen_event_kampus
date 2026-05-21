<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Please login to access this page.'
            ]);
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->withErrors([
                    'unauthorized' => 'You do not have access to that resource.'
                ]);
            }
            return redirect()->route('user.home')->withErrors([
                'unauthorized' => 'You do not have access to that resource.'
            ]);
        }

        return $next($request);
    }
}
