<?php
namespace App\Jobs;

use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPaymentSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $parentPhone,
        public string $studentName,
        public float $amount,
        public float $balance,
        public string $receiptNo,
    ) {}

    public function handle(SmsService $sms): void
    {
        $sms->sendTemplate($this->parentPhone, "payment", [
            "student_name" => $this->studentName,
            "amount"       => number_format($this->amount),
            "balance"      => number_format($this->balance),
            "receipt"      => $this->receiptNo,
        ], "en");
    }
}