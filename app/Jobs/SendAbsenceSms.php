<?php

namespace App\Jobs;

use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAbsenceSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $parentPhone,
        public string $studentName,
        public string $date,
    ) {}

    public function handle(SmsService $sms): void
    {
        $message = "Dear Parent, your child {$this->studentName} was not in school today ({$this->date}). Please contact the school.";
        $sms->send($this->parentPhone, $message, 'absence');
    }
}