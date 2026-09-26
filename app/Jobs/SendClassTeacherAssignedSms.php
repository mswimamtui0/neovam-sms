<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendClassTeacherAssignedSms implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(
        public string $staffPhone,
        public string $staffName,
        public string $className,
    ) {}
    public function handle(SmsService $sms): void {
        $msg = "Dear {$this->staffName}, you have been assigned as Class Teacher of {$this->className}.";
        $sms->send($this->staffPhone, $msg, "class_teacher_assigned");
    }
}