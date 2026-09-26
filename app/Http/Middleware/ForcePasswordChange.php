<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            // Allow access to password change routes only
            $allowedRoutes = [
                "password.change",
                "password.change.update",
                "logout",
            ];

            if (!in_array($request->route()?->getName(), $allowedRoutes)) {
                return redirect()->route("password.change")
                    ->with("info", "You must change your password before continuing.");
            }
        }

        return $next($request);
    }
}