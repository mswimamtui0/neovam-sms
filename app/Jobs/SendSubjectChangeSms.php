<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSubjectChangeSms implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(
        public string $staffPhone,
        public string $staffName,
        public string $subjects,
    ) {}
    public function handle(SmsService $sms): void {
        $msg = "Dear {$this->staffName}, your subjects have been updated: {$this->subjects}. Login to view.";
        $sms->send($this->staffPhone, $msg, "subject_change");
    }
}