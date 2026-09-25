<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendClassTeacherChangeSms implements ShouldQueue {
 use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

 public function __construct(
 public string $phone,
 public string $staffName,
 public string $className,
 public string $action, // assigned | removed
 ) {}

 public function handle(SmsService $sms): void {
 if ($this->action === "assigned") {
 $message = "Dear {$this->staffName}, you have been assigned as Class Teacher of {$this->className}.";
 } else {
 $message = "Dear {$this->staffName}, you have been removed as Class Teacher of {$this->className}.";
 }
 $sms->send($this->phone, $message, "class_teacher_change");
 }
}