<?php

namespace App\Services\Sms;

use App\Models\SmsControlSetting;
use App\Models\SmsLog;
use App\Models\SmsSkippedMessage;
use App\Models\SmsTemplate;
use App\Models\SmsTriggerSetting;

class SmsService
{
    public const SINGLE_MAX = 160;
    public const MULTI_PART_MAX = 153;

    public function __construct(
        protected NeovamGateway $gateway
    ) {}

    public static function calculateUnits(string $message): int
    {
        $len = mb_strlen($message);
        if ($len === 0) return 0;
        if ($len <= self::SINGLE_MAX) return 1;
        return (int) ceil($len / self::MULTI_PART_MAX);
    }

    /**
     * Send an SMS with all gate checks.
     * Passes trigger as event_type to the gateway.
     */
    public function send(string $to, string $message, string $trigger = "general"): bool
    {
        // ============ GATE 1: Trigger enabled? ============
        $triggerEnabled = SmsTriggerSetting::enabled($trigger);

        // ============ GATE 2: Master switch ============
        $masterOn = SmsControlSetting::masterEnabled();

        // ============ GATE 3: Test mode ============
        $testMode = SmsControlSetting::testMode();

        // ============ GATE 4: Schedule ============
        $insideSchedule = SmsControlSetting::insideScheduleWindow();

        // ============ GATE 5: Rate limits ============
        $hourlyCount = SmsLog::where("created_at", ">=", now()->subHour())->count();
        $dailyCount  = SmsLog::whereDate("created_at", now()->toDateString())->count();
        $withinRate  = $hourlyCount < SmsControlSetting::rateLimitPerHour()
                    && $dailyCount  < SmsControlSetting::rateLimitPerDay();

        // ============ DECISION ============
        $skipReason = null;
        if (!$triggerEnabled)     $skipReason = "trigger_off";
        elseif (!$masterOn)       $skipReason = "master_off";
        elseif (!$insideSchedule) $skipReason = "schedule";
        elseif (!$withinRate)     $skipReason = "rate_limit";

        if ($skipReason) {
            SmsSkippedMessage::create([
                "recipient"          => $to,
                "message"            => $message,
                "trigger"            => $trigger,
                "skip_reason"        => $skipReason,
                "would_have_sent_at" => now(),
            ]);
            return false;
        }

        // ============ TEST MODE (logged but not sent) ============
        if ($testMode) {
            $charCount = mb_strlen($message);
            $units     = self::calculateUnits($message);

            SmsLog::create([
                "recipient"       => $to,
                "message"         => $message,
                "trigger"         => $trigger . "_test",
                "units"           => $units,
                "char_count"      => $charCount,
                "status"          => "test",
                "delivery_status" => "test",
            ]);
            return true;
        }

        // ============ ACTUAL SEND ============
        $charCount = mb_strlen($message);
        $units     = self::calculateUnits($message);

        $log = SmsLog::create([
            "recipient"       => $to,
            "message"         => $message,
            "trigger"         => $trigger,
            "units"           => $units,
            "char_count"      => $charCount,
            "status"          => "pending",
            "delivery_status" => "pending",
        ]);

        // Pass trigger as event_type to gateway (required by NEOVAM gateway)
        $result = $this->gateway->send($to, $message, $trigger);

        if ($result["success"]) {
            $log->markSent($result["reference"] ?? null);

            // If gateway already told us it's delivered
            if (!empty($result["delivered"])) {
                $log->markDelivered();
            }

            try {
                SmsCostService::debit(
                    $units,
                    $result["reference"] ?? null,
                    "SMS to {$to} ({$trigger})"
                );
                $log->update(["cost" => SmsCostService::cost($units)]);
            } catch (\Throwable $e) {
                \Log::warning("SMS cost recording failed", ["error" => $e->getMessage()]);
            }

            return true;
        }

        $log->markFailed($result["error"] ?? "Unknown error", $result["error_code"] ?? null);
        return false;
    }

    /**
     * Render a template and send.
     */
    public function sendTemplate(string $to, string $key, array $data = [], string $language = "en"): bool
    {
        $message = SmsTemplate::render($key, $data, $language);
        if (!$message) {
            \Log::warning("SMS template not found", ["key" => $key, "language" => $language]);
            return false;
        }
        return $this->send($to, $message, $key);
    }

    public static function preview(string $message, int $recipientCount): array
    {
        $units = self::calculateUnits($message);
        return [
            "units"       => $units,
            "recipients"  => $recipientCount,
            "total_units" => $units * $recipientCount,
        ];
    }

    public function gateway(): NeovamGateway
    {
        return $this->gateway;
    }
}