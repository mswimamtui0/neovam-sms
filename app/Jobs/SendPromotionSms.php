<?php
namespace App\Jobs;

use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPromotionSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $parentPhone,
        public string $studentName,
        public ?string $oldClass,
        public ?string $newClass,
        public string $status,
    ) {}

    public function handle(SmsService $sms): void
    {
        if ($this->status === "graduated") {
            $sms->sendTemplate($this->parentPhone, "graduation", [
                "student_name" => $this->studentName,
                "old_class"    => $this->oldClass ?? "-",
            ], "en");
        } else {
            $sms->sendTemplate($this->parentPhone, "promotion", [
                "student_name" => $this->studentName,
                "old_class"    => $this->oldClass ?? "-",
                "new_class"    => $this->newClass ?? "-",
            ], "en");
        }
    }
}