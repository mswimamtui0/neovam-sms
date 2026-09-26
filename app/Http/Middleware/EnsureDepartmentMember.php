<?php

namespace App\Http\Middleware;

use App\Models\Staff;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDepartmentMember
{
    /**
     * Usage in routes:  ->middleware("dept:health")
     * Allows: admin, staff whose primary dept matches, or staff with the role ticked.
     */
    public function handle(Request $request, Closure $next, string $departmentCode): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route("login");
        }

        // Admins see everything
        if (method_exists($user, "hasRole") && $user->hasRole("admin")) {
            return $next($request);
        }

        $staff = Staff::where("user_id", $user->id)->first();

        if (!$staff) {
            abort(403, "You are not assigned to any department.");
        }

        $allowed = false;

        // 1. Primary department matches
        if (optional($staff->primaryDepartment)->code === $departmentCode) {
            $allowed = true;
        }

        // 2. Role ticked matches
        if (!$allowed && in_array($departmentCode, $staff->allRoles(), true)) {
            $allowed = true;
        }

        // 3. Their department string contains the code (fallback)
        if (!$allowed && $staff->department && stripos($staff->department, $departmentCode) !== false) {
            $allowed = true;
        }

        if (!$allowed) {
            abort(403, "You are not a member of the {$departmentCode} department.");
        }

        return $next($request);
    }
}