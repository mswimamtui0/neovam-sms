<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendStaffDeactivatedSms implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(
        public string $staffPhone,
        public string $staffName,
    ) {}
    public function handle(SmsService $sms): void {
        $msg = "Dear {$this->staffName}, your NEOVAM account has been deactivated. Contact the school administration.";
        $sms->send($this->staffPhone, $msg, "staff_deactivated");
    }
}