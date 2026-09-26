<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTimetableChangedSms implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(
        public string $phone,
        public string $recipientName,
        public string $className,
        public string $change,
    ) {}
    public function handle(SmsService $sms): void {
        $msg = "Dear {$this->recipientName}, the timetable for {$this->className} has changed: {$this->change}.";
        $sms->send($this->phone, $msg, "timetable_changed");
    }
}