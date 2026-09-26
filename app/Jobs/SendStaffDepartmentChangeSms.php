<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendStaffDepartmentChangeSms implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(
        public string $staffPhone,
        public string $staffName,
        public string $oldDepartment,
        public string $newDepartment,
    ) {}
    public function handle(SmsService $sms): void {
        $msg = "Dear {$this->staffName}, your department has been changed from {$this->oldDepartment} to {$this->newDepartment}. Login to see your new department tools.";
        $sms->send($this->staffPhone, $msg, "staff_department_change");
    }
}