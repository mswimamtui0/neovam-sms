<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendRoleChangeSms implements ShouldQueue {
 use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

 public function __construct(
 public string $phone,
 public string $staffName,
 public string $oldRole,
 public string $newRole,
 ) {}

 public function handle(SmsService $sms): void {
 $message = "Dear {$this->staffName}, your role has been updated from {$this->oldRole} to {$this->newRole}. Login to see your new tools.";
 $sms->send($this->phone, $message, "role_change");
 }
}