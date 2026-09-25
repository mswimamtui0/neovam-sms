<?php

namespace App\Console\Commands;

use App\Services\Backup\BackupService;
use Illuminate\Console\Command;

class CreateBackup extends Command
{
    protected $signature   = "backup:create {--prune=30}";
    protected $description = "Create a full system backup and prune old ones";

    public function handle(): int
    {
        $this->info("Creating full backup...");

        $backup = BackupService::createFullBackup("Automated backup");
        $backup->update(["type" => "auto"]);

        $this->info("Backup created: {$backup->filename} (" . $backup->humanSize() . ")");

        // Prune
        $days = (int) $this->option("prune");
        $deleted = BackupService::pruneOldBackups($days);
        $this->info("Pruned {$deleted} old backups (older than {$days} days).");

        return self::SUCCESS;
    }
}