<?php
namespace App\Jobs;

use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTransferSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $staffPhone,
        public string $staffName,
        public string $transferType,
        public string $effectiveDate,
        public ?string $fromPosition = null,
        public ?string $toPosition = null,
    ) {}

    public function handle(SmsService $sms): void
    {
        $message = "Dear {$this->staffName}, your {$this->transferType} is effective from {$this->effectiveDate}. ";
        if ($this->fromPosition && $this->toPosition) {
            $message .= "From: {$this->fromPosition} to: {$this->toPosition}. ";
        }
        $message .= "Please contact the administration for details.";

        $sms->send($this->staffPhone, $message, "staff_transfer");
    }
}