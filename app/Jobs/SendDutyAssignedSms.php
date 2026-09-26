<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDutyAssignedSms implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(
        public string $staffPhone,
        public string $staffName,
        public string $dutyDate,
        public string $dutyType,
        public string $shift,
    ) {}
    public function handle(SmsService $sms): void {
        $msg = "Dear {$this->staffName}, you are on duty on {$this->dutyDate} ({$this->dutyType}, {$this->shift} shift). Please report on time.";
        $sms->send($this->staffPhone, $msg, "duty_assigned");
    }
}