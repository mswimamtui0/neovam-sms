<?php
namespace App\Jobs;

use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSalarySms implements ShouldQueue
{
 use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

 public function __construct(
 public string $staffPhone,
 public string $staffName,
 public string $period,
 public float $netPay,
 ) {}

 public function handle(SmsService $sms): void
 {
 $message = "Dear {$this->staffName}, your salary for {$this->period} of TZS " . number_format($this->netPay) . " has been processed. Thank you.";
 $sms->send($this->staffPhone, $message, "salary");
 }
}