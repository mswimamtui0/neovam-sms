<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendLeaveApprovedSms implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(
        public string $phone,
        public string $staffName,
        public string $startDate,
        public string $endDate,
    ) {}
    public function handle(SmsService $sms): void {
        $msg = "Dear {$this->staffName}, your leave from {$this->startDate} to {$this->endDate} has been APPROVED.";
        $sms->send($this->phone, $msg, "leave_approved");
    }
}