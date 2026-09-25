<?php

namespace App\Console\Commands;

use App\Services\Finance\FeeReminderService;
use Illuminate\Console\Command;

class SendFeeReminders extends Command
{
 protected $signature = "fees:send-reminders";
 protected $description = "Send fee reminder SMS to parents with outstanding balances";

 public function handle(): int
 {
 $this->info("Scanning for pending fee reminders...");

 $result = FeeReminderService::sendAll();

 $this->info("Sent: {$result["sent"]} | Skipped: {$result["skipped"]}");

 return self::SUCCESS;
 }
}