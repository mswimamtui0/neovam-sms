<?php
namespace App\Jobs;

use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWelcomeSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $parentPhone,
        public string $studentName,
        public string $admissionNo,
        public string $className,
        public string $level,
        public ?float $feeAmount = null,
    ) {}

    public function handle(SmsService $sms): void
    {
        $school = \App\Models\School::first();

        $sms->sendTemplate($this->parentPhone, "welcome", [
            "school_name"  => $school?->name ?? "the school",
            "student_name" => $this->studentName,
            "admission_no" => $this->admissionNo,
            "class_name"   => $this->className,
            "level"        => $this->level,
            "fee"          => $this->feeAmount ? number_format($this->feeAmount) : "0",
        ], "en");
    }
}