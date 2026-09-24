<?php

namespace App\Jobs;

use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendResultSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $parentPhone,
        public string $studentName,
        public string $examName,
        public string $summary,
    ) {}

    public function handle(SmsService $sms): void
    {
        $message = "Dear Parent, results for {$this->studentName} ({$this->examName}) are ready. {$this->summary}. Login to view full report.";
        $sms->send($this->parentPhone, $message, 'result');
    }
}