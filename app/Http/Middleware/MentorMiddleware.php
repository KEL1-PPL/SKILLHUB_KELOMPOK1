<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MentorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }
        
        $user = auth()->user();
        if (!$user || !isset($user->role) || $user->role !== 'mentor') {
            return redirect()->route('home')->with('error', 'Access denied. Mentor only area.');
        }

        return $next($request);
    }
}
