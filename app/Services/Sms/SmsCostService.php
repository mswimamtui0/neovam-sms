<?php

namespace App\Services\Sms;

use App\Models\SmsBalanceTransaction;
use App\Models\SmsLog;
use App\Models\SmsPricingRule;
use Carbon\Carbon;

class SmsCostService
{
 /**
 * Calculate cost for N units at the current price.
 */
 public static function cost(int $units): float
 {
 $rule = SmsPricingRule::current();
 $price = $rule?->unit_price ?? 0;

 return round($units * (float) $price, 2);
 }

 /**
 * Post a debit transaction for the school's usage.
 */
 public static function debit(int $units, string $reference = null, string $description = null): SmsBalanceTransaction
 {
 $cost = self::cost($units);

 return SmsBalanceTransaction::create([
 "type" => "debit",
 "units" => -$units,
 "amount" => -$cost,
 "reference" => $reference,
 "description" => $description ?? "SMS usage: {$units} units",
 "created_by" => auth()->id(),
 ]);
 }

 /**
 * Post a top-up transaction.
 */
 public static function topup(int $units, float $amount, string $reference = null, string $description = null): SmsBalanceTransaction
 {
 return SmsBalanceTransaction::create([
 "type" => "topup",
 "units" => $units,
 "amount" => $amount,
 "reference" => $reference,
 "description" => $description ?? "SMS top-up: {$units} units",
 "created_by" => auth()->id(),
 ]);
 }

 /**
 * Cost summary for a period.
 */
 public static function summary(?Carbon $from = null, ?Carbon $to = null): array
 {
 $from = $from ?? now()->startOfMonth();
 $to = $to ?? now()->endOfMonth();

 $query = SmsLog::whereBetween("created_at", [$from, $to]);

 $units = (clone $query)->sum("units");
 $count = (clone $query)->count();
 $sent = (clone $query)->where("status","sent")->count();
 $failed = (clone $query)->where("status","failed")->count();

 $cost = self::cost((int) $units);

 // Cost breakdown by trigger
 $byTrigger = (clone $query)
 ->selectRaw("trigger, SUM(units) as units, COUNT(*) as count")
 ->groupBy("trigger")
 ->orderByDesc("units")
 ->get()
 ->map(fn($row) => [
 "trigger" => $row->trigger,
 "units" => (int) $row->units,
 "count" => (int) $row->count,
 "cost" => self::cost((int) $row->units),
 ]);

 // Cost breakdown by day
 $byDay = (clone $query)
 ->selectRaw("DATE(created_at) as day, SUM(units) as units, COUNT(*) as count")
 ->groupBy("day")
 ->orderBy("day")
 ->get()
 ->map(fn($row) => [
 "day" => $row->day,
 "units" => (int) $row->units,
 "cost" => self::cost((int) $row->units),
 ]);

 return [
 "from" => $from,
 "to" => $to,
 "units" => (int) $units,
 "count" => $count,
 "sent" => $sent,
 "failed" => $failed,
 "cost" => $cost,
 "byTrigger" => $byTrigger,
 "byDay" => $byDay,
 ];
 }

 public static function currentPricing(): ?SmsPricingRule
 {
 return SmsPricingRule::current();
 }
}