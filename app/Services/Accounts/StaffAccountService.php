<?php

namespace App\Services\Accounts;

use App\Models\School;
use App\Models\Staff;
use App\Models\User;
use App\Services\Sms\SmsService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class StaffAccountService
{
    public static function ensureAccount(Staff $staff, bool $resetPassword = false): array
    {
        $isNew = false;
        $plainPassword = null;

        $email = $staff->email ?: strtolower("staff" . $staff->id . "@" . (School::first()?->code ?? "neovam") . ".co.tz");

        Log::info("StaffAccountService: ensuring account", [
            "staff_id" => $staff->id,
            "email"    => $email,
        ]);

        $user = User::where("email", $email)->first();

        if (!$user) {
            $plainPassword = self::generatePassword();
            $user = User::create([
                "name"                 => $staff->full_name,
                "email"                => $email,
                "password"             => Hash::make($plainPassword),
                "phone"                => $staff->phone,
                "school_id"            => $staff->school_id,
                "must_change_password" => true,
                "credentials_sent_at"  => now(),
            ]);
            $isNew = true;

            Log::info("StaffAccountService: user created", [
                "staff_id" => $staff->id,
                "user_id"  => $user->id,
            ]);
        } elseif ($resetPassword) {
            $plainPassword = self::generatePassword();
            $user->update([
                "password"             => Hash::make($plainPassword),
                "must_change_password" => true,
                "credentials_sent_at"  => now(),
            ]);
        }

        if ($staff->user_id !== $user->id) {
            $staff->update(["user_id" => $user->id]);
        }

        self::assignRoles($user, $staff);

        return [$user, $isNew, $plainPassword];
    }

    public static function assignRoles(User $user, Staff $staff): void
    {
        $roles = ["staff"];

        foreach ($staff->roleList() as $deptRole) {
            $role = match ($deptRole) {
                "academic", "class_teacher", "health", "discipline", "sports",
                "boarding", "feeding", "library", "guidance", "environment", "security" => "teacher",
                "duty"           => "teacher_on_duty",
                "finance"        => "bursar",
                "administration" => "admin",
                default          => "teacher",
            };
            if (!in_array($role, $roles, true)) $roles[] = $role;
        }

        foreach ($roles as $roleName) {
            Role::firstOrCreate(["name" => $roleName, "guard_name" => "web"]);
        }

        $user->syncRoles($roles);

        Log::info("StaffAccountService: roles assigned", [
            "user_id" => $user->id,
            "roles"   => $roles,
        ]);
    }

    public static function sendCredentialsSms(Staff $staff, User $user, string $plainPassword): bool
    {
        if (!$staff->phone) {
            Log::warning("StaffAccountService: no phone — cannot send SMS", ["staff_id" => $staff->id]);
            return false;
        }

        $loginUrl = url("/login");
        $message = "Dear {$staff->full_name}, your NEOVAM SMS account is ready. "
                 . "Login: {$user->email} | Password: {$plainPassword} | "
                 . "Login: {$loginUrl} | Change password after first login.";

        Log::info("StaffAccountService: sending credentials SMS", [
            "staff_id" => $staff->id,
            "phone"    => $staff->phone,
        ]);

        try {
            $sms = app(SmsService::class);
            $result = $sms->send($staff->phone, $message, "staff_credentials");

            Log::info("StaffAccountService: SMS result", [
                "staff_id" => $staff->id,
                "success"  => $result,
            ]);

            return $result;
        } catch (\Throwable $e) {
            Log::error("StaffAccountService: SMS exception", [
                "staff_id" => $staff->id,
                "error"    => $e->getMessage(),
            ]);
            return false;
        }
    }

    public static function resendCredentials(Staff $staff): array
    {
        [$user, $isNew, $plainPassword] = self::ensureAccount($staff, true);

        if (!$plainPassword) {
            return ["success" => false, "message" => "Password was not regenerated."];
        }

        $sent = self::sendCredentialsSms($staff, $user, $plainPassword);

        return [
            "success" => true,
            "message" => $sent ? "Credentials sent to {$staff->phone}." : "Account updated but SMS failed.",
            "user"    => $user,
        ];
    }

    public static function generatePassword(): string
    {
        $upper  = "ABCDEFGHJKLMNPQRSTUVWXYZ";
        $lower  = "abcdefghijkmnopqrstuvwxyz";
        $digit  = "23456789";
        $symbol = "@#$%!&*";

        $password  = $upper[random_int(0, strlen($upper) - 1)];
        $password .= $lower[random_int(0, strlen($lower) - 1)];
        $password .= $digit[random_int(0, strlen($digit) - 1)];
        $password .= $symbol[random_int(0, strlen($symbol) - 1)];

        $all = $upper . $lower . $digit . $symbol;
        for ($i = 0; $i < 6; $i++) {
            $password .= $all[random_int(0, strlen($all) - 1)];
        }

        return str_shuffle($password);
    }
}