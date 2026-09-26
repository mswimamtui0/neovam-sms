<?php

namespace App\Http\Controllers;

use App\Models\Staff;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) return redirect()->route("login");

        $staff = Staff::where("user_id", $user->id)->first();

        // ============ STAFF → straight to their home department ============
        if ($staff) {
            $route = $staff->landingRoute();
            if ($route) {
                return redirect()->route($route);
            }
            // fallback: hub only if nothing assigned
            return redirect()->route("hub.index");
        }

        // ============ Non-staff (admin, parent) ============
        if (method_exists($user, "hasRole") && $user->hasRole("admin")) {
            return view("dashboard");
        }

        return view("dashboard");
    }
}