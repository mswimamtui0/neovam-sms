<?php

namespace App\Services\Finance;

use App\Jobs\SendFeeReminderSms;
use App\Models\FeeReminder;
use App\Models\Invoice;
use Carbon\Carbon;

class FeeReminderService
{
    /**
     * Find invoices that need reminders — returns grouped results.
     */
    public static function pending(): array
    {
        $today = now()->startOfDay();

        $before = Invoice::where("balance", ">", 0)
            ->whereNotNull("due_date")
            ->whereDate("due_date", $today->copy()->addDays(3))
            ->with("student")
            ->get();

        $onDue = Invoice::where("balance", ">", 0)
            ->whereNotNull("due_date")
            ->whereDate("due_date", $today)
            ->with("student")
            ->get();

        $afterDue = Invoice::where("balance", ">", 0)
            ->whereNotNull("due_date")
            ->whereDate("due_date", "<", $today)
            ->whereDate("due_date", ">=", $today->copy()->subDays(7))
            ->with("student")
            ->get();

        $final = Invoice::where("balance", ">", 0)
            ->whereNotNull("due_date")
            ->whereDate("due_date", "<", $today->copy()->subDays(7))
            ->with("student")
            ->get();

        // Filter out ones that already got their reminder today
        return [
            "before_due"    => self::filterUnreminded($before,    "before_due",    $today),
            "on_due"        => self::filterUnreminded($onDue,      "on_due",        $today),
            "after_due"     => self::filterUnreminded($afterDue,   "after_due",     $today),
            "overdue_final" => self::filterUnreminded($final,      "overdue_final", $today),
        ];
    }

    /**
     * Filter invoices that already received that reminder type today.
     */
    protected static function filterUnreminded($invoices, string $type, Carbon $today)
    {
        return $invoices->filter(function ($inv) use ($type, $today) {
            return !FeeReminder::where("invoice_id", $inv->id)
                ->where("reminder_type", $type)
                ->whereDate("created_at", $today)
                ->exists();
        })->values();
    }

    /**
     * Send reminders for one group.
     * Returns [sent, skipped].
     */
    public static function sendBatch(string $type): array
    {
        $pending = self::pending();
        $invoices = $pending[$type] ?? collect();

        $sent = 0;
        $skipped = 0;

        foreach ($invoices as $inv) {
            $student = $inv->student;
            if (!$student || !$student->parent_phone) { $skipped++; continue; }

            // Record the reminder
            $reminder = FeeReminder::create([
                "invoice_id"    => $inv->id,
                "student_id"    => $student->id,
                "reminder_type" => $type,
                "due_date"      => $inv->due_date,
                "balance"       => $inv->balance,
                "sms_sent"      => false,
            ]);

            // Send SMS
            try {
                SendFeeReminderSms::dispatch(
                    $student->parent_phone,
                    $student->full_name,
                    (float) $inv->balance,
                    $inv->due_date?->format("d M Y") ?? "-",
                );

                $reminder->update([
                    "sms_sent"    => true,
                    "sms_sent_at" => now(),
                    "sms_status"  => "queued",
                ]);
                $sent++;
            } catch (\Throwable $e) {
                \Log::warning("Fee reminder SMS failed", [
                    "invoice" => $inv->id,
                    "error"   => $e->getMessage(),
                ]);
                $skipped++;
            }
        }

        return compact("sent","skipped");
    }

    /**
     * Send all reminder types at once (used by scheduled task).
     */
    public static function sendAll(): array
    {
        $types = ["before_due","on_due","after_due","overdue_final"];
        $totals = ["sent" => 0, "skipped" => 0];

        foreach ($types as $type) {
            $result = self::sendBatch($type);
            $totals["sent"]    += $result["sent"];
            $totals["skipped"] += $result["skipped"];
        }

        return $totals;
    }

    /**
     * Stats for dashboard.
     */
    public static function stats(): array
    {
        return [
            "total_reminders"   => FeeReminder::count(),
            "sent_today"        => FeeReminder::whereDate("created_at", now()->toDateString())->count(),
            "sent_this_month"   => FeeReminder::whereMonth("created_at", now()->month)
                                        ->whereYear("created_at", now()->year)->count(),
            "failed"            => FeeReminder::where("sms_sent", false)->count(),
            "by_type"           => FeeReminder::selectRaw("reminder_type, COUNT(*) as count")
                                        ->groupBy("reminder_type")->pluck("count", "reminder_type")->toArray(),
        ];
    }
}