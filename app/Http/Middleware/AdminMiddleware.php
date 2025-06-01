<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }
        
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Access denied. Admin only area.');
        }

        return $next($request);
    }
} 