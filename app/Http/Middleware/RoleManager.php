<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user account is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated.');
        }

        // Switch statement for role-based access
        switch ($user->role) {
            case 'admin':
                // Admin can access everything
                return $next($request);

            case 'teacher':
                // Teacher can only access if allowed roles include teacher
                if (in_array('teacher', $roles) || in_array('*', $roles)) {
                    return $next($request);
                }
                break;

            case 'accountant':
                // Accountant can only access if allowed roles include accountant
                if (in_array('accountant', $roles) || in_array('*', $roles)) {
                    return $next($request);
                }
                break;

            default:
                // Unknown role - logout and redirect
                Auth::logout();
                return redirect()->route('login')->with('error', 'Invalid role assigned.');
        }

        // If we get here, user doesn't have required role
        abort(403, 'Unauthorized access. You do not have the required permissions.');
    }
}
