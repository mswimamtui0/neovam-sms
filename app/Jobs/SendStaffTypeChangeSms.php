<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendStaffTypeChangeSms implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(
        public string $staffPhone,
        public string $staffName,
        public string $oldType,
        public string $newType,
        public string $newDepartment,
    ) {}
    public function handle(SmsService $sms): void {
        $msg = "Dear {$this->staffName}, your role has been updated from {$this->oldType} to {$this->newType} (Department: {$this->newDepartment}). Login to see your new workspace.";
        $sms->send($this->staffPhone, $msg, "staff_type_change");
    }
}