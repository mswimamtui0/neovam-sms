<?php
namespace App\Jobs;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendLibraryOverdueSms implements ShouldQueue {
 use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

 public function __construct(
 public string $phone,
 public string $studentName,
 public string $bookTitle,
 public string $dueDate,
 ) {}

 public function handle(SmsService $sms): void {
 $message = "Dear Parent, the library book '{$this->bookTitle}' borrowed by {$this->studentName} was due on {$this->dueDate}. Please return it.";
 $sms->send($this->phone, $message, "library_overdue");
 }
}