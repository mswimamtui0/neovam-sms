<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordOtp extends Model {
    use HasFactory;

    protected $fillable = [
        "identifier","channel","otp_code","expires_at",
        "attempts","verified","verified_at",
    ];

    protected $casts = [
        "expires_at"  => "datetime",
        "verified_at" => "datetime",
        "verified"    => "boolean",
        "attempts"    => "integer",
    ];

    /**
     * Generate a new OTP for an identifier + channel.
     */
    public static function generate(string $identifier, string $channel = "sms"): self
    {
        // Delete any existing unverified OTPs for this identifier
        self::where("identifier", $identifier)
            ->where("channel", $channel)
            ->where("verified", false)
            ->delete();

        return self::create([
            "identifier" => $identifier,
            "channel"    => $channel,
            "otp_code"   => str_pad((string) random_int(0, 999999), 6, "0", STR_PAD_LEFT),
            "expires_at" => now()->addMinutes(10),
            "attempts"   => 0,
            "verified"   => false,
        ]);
    }

    /**
     * Verify an OTP.
     */
    public static function verify(string $identifier, string $channel, string $code): array
    {
        $otp = self::where("identifier", $identifier)
            ->where("channel", $channel)
            ->where("verified", false)
            ->latest()
            ->first();

        if (!$otp) {
            return ["success" => false, "message" => "No OTP found. Please request a new one."];
        }

        if ($otp->expires_at->isPast()) {
            return ["success" => false, "message" => "OTP has expired. Please request a new one."];
        }

        if ($otp->attempts >= 5) {
            return ["success" => false, "message" => "Too many attempts. Please request a new OTP."];
        }

        if ($otp->otp_code !== $code) {
            $otp->increment("attempts");
            return ["success" => false, "message" => "Invalid OTP code."];
        }

        $otp->update([
            "verified"    => true,
            "verified_at" => now(),
        ]);

        return ["success" => true, "message" => "OTP verified."];
    }

    /**
     * Check if an identifier has a verified OTP.
     */
    public static function hasVerified(string $identifier, string $channel = "sms"): bool
    {
        return self::where("identifier", $identifier)
            ->where("channel", $channel)
            ->where("verified", true)
            ->where("verified_at", ">=", now()->subMinutes(15))
            ->exists();
    }
}