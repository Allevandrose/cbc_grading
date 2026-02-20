<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeacherIsSetup
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */
        $user = Auth::user();

        // 1. Check if logged in and is a teacher
        if ($user && $user->isTeacher()) {

            // 2. Check if onboarding is missing and user isn't already on the setup page
            if (!$user->onboarding_completed_at && !$request->routeIs('teacher.setup*')) {
                return redirect()->route('teacher.setup');
            }
        }

        return $next($request);
    }
}
