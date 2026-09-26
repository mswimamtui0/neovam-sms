<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendStaffCredentialsSms implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(
        public string $staffPhone,
        public string $staffName,
        public string $email,
        public string $password,
    ) {}
    public function handle(SmsService $sms): void {
        $loginUrl = url("/login");
        $msg = "Dear {$this->staffName}, your NEOVAM SMS account is ready. Login: {$this->email} | Password: {$this->password} | Login: {$loginUrl} | Change password after first login.";
        $sms->send($this->staffPhone, $msg, "staff_credentials");
    }
}