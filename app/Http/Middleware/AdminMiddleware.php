<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if the user is authenticated AND
        // 2. Check if their 'role' attribute is exactly 'admin'
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // --- Handle Unauthorized Access ---

        // If the user is not logged in, redirect them to the login page.
        if (!auth()->check()) {
             return redirect()->route('login'); // Assuming you have a route named 'login'
        }

        // If the user is logged in but not an admin, redirect to home page with a warning.
        return redirect()->route('home')->with('error', 'You do not have administrative access.');
    }
}