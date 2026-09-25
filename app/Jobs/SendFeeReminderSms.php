<?php
namespace App\Jobs;

use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFeeReminderSms implements ShouldQueue
{
 use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

 public function __construct(
 public string $parentPhone,
 public string $studentName,
 public float $balance,
 public string $dueDate,
 ) {}

 public function handle(SmsService $sms): void
 {
 $sms->sendTemplate($this->parentPhone, "fee_reminder", [
 "student_name" => $this->studentName,
 "balance" => number_format($this->balance),
 "due_date" => $this->dueDate,
 ], "en");
 }
}