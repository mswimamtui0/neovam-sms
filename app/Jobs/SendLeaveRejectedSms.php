<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendLeaveRejectedSms implements ShouldQueue {
 use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

 public function __construct(
 public string $phone,
 public string $staffName,
 public string $reason,
 ) {}

 public function handle(SmsService $sms): void {
 $message = "Dear {$this->staffName}, your leave request has been REJECTED. Reason: {$this->reason}.";
 $sms->send($this->phone, $message, "leave_rejected");
 }
}