<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendExamScheduleSms implements ShouldQueue {
 use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

 public function __construct(
 public string $phone,
 public string $studentName,
 public string $examName,
 public string $newDate,
 public string $newTime,
 ) {}

 public function handle(SmsService $sms): void {
 $message = "Dear Parent, exam schedule for {$this->studentName} ({$this->examName}) has changed. New date: {$this->newDate}, time: {$this->newTime}.";
 $sms->send($this->phone, $message, "exam_schedule_change");
 }
}