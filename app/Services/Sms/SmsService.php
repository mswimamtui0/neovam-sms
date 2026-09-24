<?php

namespace App\Services\Sms;

use App\Models\SmsLog;

class SmsService
{
    public const SINGLE_MAX = 160;
    public const MULTI_PART_MAX = 153;

    public function __construct(
        protected NeovamGateway $gateway
    ) {}

    /**
     * Calculate how many SMS units a message needs.
     */
    public static function calculateUnits(string $message): int
    {
        $len = mb_strlen($message);

        if ($len === 0) return 0;
        if ($len <= self::SINGLE_MAX) return 1;

        return (int) ceil($len / self::MULTI_PART_MAX);
    }

    /**
     * Send an SMS and log it.
     */
    public function send(string $to, string $message, string $trigger = "general"): bool
    {
        $charCount = mb_strlen($message);
        $units     = self::calculateUnits($message);

        $log = SmsLog::create([
            "recipient"  => $to,
            "message"    => $message,
            "trigger"    => $trigger,
            "units"      => $units,
            "char_count" => $charCount,
            "status"     => "pending",
        ]);

        $result = $this->gateway->send($to, $message);

        if ($result["success"]) {
            $log->markSent($result["reference"]);
            return true;
        }

        $log->markFailed();
        return false;
    }

    /**
     * Preview cost for a message sent to N recipients.
     * Returns [units, recipients, total_units]
     */
    public static function preview(string $message, int $recipientCount): array
    {
        $units = self::calculateUnits($message);
        return [
            "units"        => $units,
            "recipients"   => $recipientCount,
            "total_units"  => $units * $recipientCount,
        ];
    }
}