<?php
namespace App\Jobs;

use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDepartmentChangeSms implements ShouldQueue
{
 use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

 public function __construct(
 public string $staffPhone,
 public string $staffName,
 public string $newDepartment,
 public string $newRoles,
 ) {}

 public function handle(SmsService $sms): void
 {
 $message = "Dear {$this->staffName}, your department has been updated to {$this->newDepartment}. Your login will now take you to your new department page.";
 $sms->send($this->staffPhone, $message, "department_change");
 }
}