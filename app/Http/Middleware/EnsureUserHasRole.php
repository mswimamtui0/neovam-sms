<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
 public function handle(Request $request, Closure $next, string ...$roles): Response
 {
 if (!auth()->check()) {
 abort(403, 'Unauthenticated.');
 }

 foreach ($roles as $role) {
 if (auth()->user()->hasRole($role)) {
 return $next($request);
 }
 }

 abort(403, 'Unauthorized. Missing required role.');
 }
}