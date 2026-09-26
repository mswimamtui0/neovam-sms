<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordOtp;
use App\Models\User;
use App\Services\Sms\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OtpPasswordResetController extends Controller
{
    /**
     * Show the "forgot password" form with SMS / Email choice.
     */
    public function showRequestForm()
    {
        return view("auth.otp-request");
    }

    /**
     * Send OTP via SMS or Email.
     */
    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            "identifier" => "required|string|max:150",
            "channel"    => "required|in:sms,email",
        ]);

        $identifier = trim($data["identifier"]);
        $channel    = $data["channel"];

        // Find user
        if ($channel === "sms") {
            $phone = preg_replace('/[^0-9]/', '', $identifier);
            $user = User::where("phone", "like", "%" . substr($phone, -9))->first();
        } else {
            $user = User::where("email", $identifier)->first();
        }

        if (!$user) {
            return back()->with("error", "No account found for this {$channel}.")->withInput();
        }

        // Rate limit: only allow 1 OTP per 60 seconds
        $recent = PasswordOtp::where("identifier", $identifier)
            ->where("channel", $channel)
            ->where("created_at", ">=", now()->subSeconds(60))
            ->exists();

        if ($recent) {
            return back()->with("error", "Please wait 60 seconds before requesting another code.")->withInput();
        }

        // Generate OTP
        $otp = PasswordOtp::generate($identifier, $channel);

        // Send via SMS or Email
        if ($channel === "sms") {
            $message = "Your NEOVAM SMS password reset code is: {$otp->otp_code}. It expires in 10 minutes. If this wasn't you, ignore this message.";
            try {
                app(SmsService::class)->send($user->phone ?: $identifier, $message, "password_reset_otp");
            } catch (\Throwable $e) {
                \Log::error("OTP SMS send failed", ["error" => $e->getMessage()]);
                return back()->with("error", "Failed to send SMS. Try email instead.")->withInput();
            }
        } else {
            try {
                \Mail::raw(
                    "Your NEOVAM SMS password reset code is: {$otp->otp_code}\n\nIt expires in 10 minutes.",
                    function ($m) use ($user) {
                        $m->to($user->email)->subject("NEOVAM SMS - Password Reset Code");
                    }
                );
            } catch (\Throwable $e) {
                \Log::error("OTP email send failed", ["error" => $e->getMessage()]);
                return back()->with("error", "Failed to send email. Try SMS instead.")->withInput();
            }
        }

        return redirect()->route("password.otp.verify", [
            "identifier" => $identifier,
            "channel"    => $channel,
        ])->with("success", "A 6-digit code has been sent to your {$channel}.");
    }

    /**
     * Show OTP verification form.
     */
    public function showVerifyForm(Request $request)
    {
        $identifier = $request->query("identifier");
        $channel    = $request->query("channel", "sms");

        if (!$identifier) return redirect()->route("password.otp.request");

        return view("auth.otp-verify", compact("identifier","channel"));
    }

    /**
     * Verify the OTP code.
     */
    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            "identifier" => "required|string",
            "channel"    => "required|in:sms,email",
            "otp_code"   => "required|string|size:6",
        ]);

        $result = PasswordOtp::verify($data["identifier"], $data["channel"], $data["otp_code"]);

        if (!$result["success"]) {
            return back()->with("error", $result["message"])->withInput();
        }

        return redirect()->route("password.otp.reset", [
            "identifier" => $data["identifier"],
            "channel"    => $data["channel"],
        ])->with("success", "Code verified. Set your new password.");
    }

    /**
     * Show new-password form.
     */
    public function showResetForm(Request $request)
    {
        $identifier = $request->query("identifier");
        $channel    = $request->query("channel", "sms");

        if (!$identifier) return redirect()->route("password.otp.request");

        // Only allow if OTP was verified
        if (!PasswordOtp::hasVerified($identifier, $channel)) {
            return redirect()->route("password.otp.request")->with("error", "Please verify your code first.");
        }

        return view("auth.otp-reset", compact("identifier","channel"));
    }

    /**
     * Save the new password.
     */
    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            "identifier" => "required|string",
            "channel"    => "required|in:sms,email",
            "password"   => "required|string|min:8|confirmed",
        ]);

        if (!PasswordOtp::hasVerified($data["identifier"], $data["channel"])) {
            return redirect()->route("password.otp.request")->with("error", "Please verify your code first.");
        }

        // Find user
        if ($data["channel"] === "sms") {
            $phone = preg_replace('/[^0-9]/', '', $data["identifier"]);
            $user = User::where("phone", "like", "%" . substr($phone, -9))->first();
        } else {
            $user = User::where("email", $data["identifier"])->first();
        }

        if (!$user) {
            return redirect()->route("password.otp.request")->with("error", "Account not found.");
        }

        $user->update([
            "password"             => Hash::make($data["password"]),
            "must_change_password" => false,
        ]);

        // Clear all OTPs for this identifier
        PasswordOtp::where("identifier", $data["identifier"])->delete();

        return redirect()->route("login")->with("success", "Password changed. Log in with your new password.");
    }
}