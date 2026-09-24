<?php

namespace App\Jobs;

use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmergencySms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $parentPhone,
        public string $studentName,
        public string $incidentType,
        public string $description,
    ) {}

    public function handle(SmsService $sms): void
    {
        $message = "URGENT: {$this->studentName} — {$this->incidentType}. {$this->description}. Please contact the school immediately.";
        $sms->send($this->parentPhone, $message, 'emergency');
    }
}