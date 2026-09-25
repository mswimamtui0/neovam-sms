<?php

namespace App\Services\Sms;

use App\Models\SmsLog;
use App\Models\SmsTemplate;

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

    public function send(string $to, string $message, string $trigger = "general"): bool
    {
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

        $result = $this->gateway->send($to, $message);

        if ($result["success"]) {
            $log->markSent($result["reference"] ?? null);

            // Record cost as a balance debit
            try {
                \App\Services\Sms\SmsCostService::debit(
                    $units,
                    $result["reference"] ?? null,
                    "SMS to {$to} ({$trigger})"
                );
                // Save cost on the log
                $log->update([
                    "cost" => \App\Services\Sms\SmsCostService::cost($units),
                ]);
            } catch (\Throwable $e) {
                \Log::warning("SMS cost recording failed", ["error" => $e->getMessage()]);
            }

            // If gateway already returned delivered status, mark it
            if (!empty($result["delivered"])) {
                $log->markDelivered();
            }

            return true;
        }

        $log->markFailed($result["error"] ?? "Unknown error", $result["error_code"] ?? null);
        return false;
    }

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