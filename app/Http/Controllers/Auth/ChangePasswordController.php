<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function show()
    {
        return view("auth.change-password");
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            "current_password" => "required|string",
            "password"         => "required|string|min:8|confirmed",
        ]);

        $user = auth()->user();

        if (!Hash::check($data["current_password"], $user->password)) {
            return back()->with("error", "Current password is incorrect.");
        }

        $user->update([
            "password"             => Hash::make($data["password"]),
            "must_change_password" => false,
        ]);

        return redirect()->route("dashboard")->with("success", "Password changed successfully.");
    }
}