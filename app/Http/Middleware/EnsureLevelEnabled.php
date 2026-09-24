<?php

namespace App\Http\Middleware;

use App\Services\Level\SchoolLevelService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLevelEnabled
{
 /**
 * Handle an incoming request.
 * Usage in routes: ->middleware('level:primary')
 */
 public function handle(Request $request, Closure $next, string $level): Response
 {
 if (!SchoolLevelService::enabled($level)) {
 abort(404, "This section ({$level}) is not enabled for this school.");
 }

 return $next($request);
 }
}