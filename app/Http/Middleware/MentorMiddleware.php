<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MentorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }
        
        if (auth()->user()->role !== 'mentor') {
            return redirect()->route('home')->with('error', 'Access denied. Mentor only area.');
        }

        return $next($request);
    }
}
