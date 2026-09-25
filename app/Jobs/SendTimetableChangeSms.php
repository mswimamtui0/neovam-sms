<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTimetableChangeSms implements ShouldQueue {
 use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

 public function __construct(
 public string $phone,
 public string $recipientName,
 public string $className,
 public string $day,
 public string $period,
 ) {}

 public function handle(SmsService $sms): void {
 $message = "Dear {$this->recipientName}, the timetable for {$this->className} has been updated. {$this->day}, {$this->period}. Please check your schedule.";
 $sms->send($this->phone, $message, "timetable_change");
 }
}