<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendLoginAlertSms implements ShouldQueue {
 use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

 public function __construct(
 public string $phone,
 public string $userName,
 public string $ipAddress,
 public string $time,
 ) {}

 public function handle(SmsService $sms): void {
 $message = "Dear {$this->userName}, a new login to your NEOVAM account was detected from IP {$this->ipAddress} at {$this->time}. If this wasn't you, change your password immediately.";
 $sms->send($this->phone, $message, "login_alert");
 }
}