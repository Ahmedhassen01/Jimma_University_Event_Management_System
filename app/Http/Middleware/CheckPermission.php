<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Super admin bypass
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        if (!$user->hasPermission($permission)) {
            abort(403, 'This action is unauthorized.');
        }

        return $next($request);
    }
}