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
        $sms->sendTemplate($this->parentPhone, "emergency", [
            "student_name"  => $this->studentName,
            "incident_type" => $this->incidentType,
            "description"   => $this->description,
        ], "en");
    }
}