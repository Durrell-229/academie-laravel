<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InstrctorMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect('/login');
        }
        
        // Allow instructor-like roles: instructor, professeur, inspecteur, conseiller pédagogique, support staff
        $allowed = ['instructor', 'professeur', 'teacher', 'professor', 'inspecteur', 'conseiller-pedagogique', 'support-staff', 'advisor', 'conseiller', 'counselor'];
        if (!in_array(auth()->user()->role->slug, $allowed, true)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
