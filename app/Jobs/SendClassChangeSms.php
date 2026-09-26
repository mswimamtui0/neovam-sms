<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendClassChangeSms implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(
        public string $staffPhone,
        public string $staffName,
        public string $classes,
    ) {}
    public function handle(SmsService $sms): void {
        $msg = "Dear {$this->staffName}, your classes have been updated: {$this->classes}. Login to view.";
        $sms->send($this->staffPhone, $msg, "class_change");
    }
}