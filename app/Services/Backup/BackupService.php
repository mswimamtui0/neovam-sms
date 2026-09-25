<?php

namespace App\Services\Backup;

use App\Models\Backup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class BackupService
{
    /**
     * Directory where backups are stored.
     */
    public static function backupDir(): string
    {
        $dir = storage_path("app/backups");
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        return $dir;
    }

    /**
     * Get all tables (excluding system tables).
     */
    public static function getTables(?array $exclude = null): array
    {
        $exclude = $exclude ?? [
            "migrations", "sessions", "cache", "cache_locks",
            "jobs", "job_batches", "failed_jobs", "password_reset_tokens",
        ];

        $driver = DB::connection()->getDriverName();

        if ($driver === "sqlite") {
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            $tables = array_map(fn($t) => $t->name, $tables);
        } else {
            // MySQL / PostgreSQL
            $tables = array_map(
                fn($t) => array_values((array) $t)[0],
                DB::select("SHOW TABLES")
            );
        }

        return array_values(array_diff($tables, $exclude));
    }

    /**
     * Create a full backup of all tables.
     */
    public static function createFullBackup(?string $notes = null): Backup
    {
        $timestamp = date("Y-m-d_His");
        $filename  = "backup_full_{$timestamp}.json";
        $path      = self::backupDir() . DIRECTORY_SEPARATOR . $filename;

        $tables = self::getTables();
        $data = [
            "version"     => "1.0",
            "created_at"  => now()->toIso8601String(),
            "driver"      => DB::connection()->getDriverName(),
            "type"        => "full",
            "tables"      => [],
        ];

        $rowCount = 0;
        foreach ($tables as $table) {
            try {
                $rows = DB::table($table)->get()->map(fn($row) => (array) $row)->toArray();
                $data["tables"][$table] = $rows;
                $rowCount += count($rows);
            } catch (\Throwable $e) {
                $data["tables"][$table] = [];
                \Log::warning("Backup: failed table {$table}", ["error" => $e->getMessage()]);
            }
        }

        $data["meta"] = [
            "table_count" => count($tables),
            "row_count"   => $rowCount,
        ];

        File::put($path, json_encode($data, JSON_PRETTY_PRINT));

        $backup = Backup::create([
            "filename"   => $filename,
            "disk"       => "local",
            "path"       => "backups/" . $filename,
            "size"       => File::size($path),
            "type"       => "manual",
            "scope"      => "full",
            "status"     => "completed",
            "notes"      => $notes,
            "meta"       => $data["meta"],
            "created_by" => auth()->id(),
        ]);

        return $backup;
    }

    /**
     * Create a backup of one school only (all its related data).
     */
    public static function createSchoolBackup(int $schoolId, ?string $notes = null): Backup
    {
        $timestamp = date("Y-m-d_His");
        $filename  = "backup_school_{$schoolId}_{$timestamp}.json";
        $path      = self::backupDir() . DIRECTORY_SEPARATOR . $filename;

        $tables = self::getTables();
        $data = [
            "version"     => "1.0",
            "created_at"  => now()->toIso8601String(),
            "driver"      => DB::connection()->getDriverName(),
            "type"        => "school",
            "school_id"   => $schoolId,
            "tables"      => [],
        ];

        $rowCount = 0;
        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, "school_id")) {
                // special-case: users, students etc. that have school_id via scope
                continue;
            }

            try {
                $rows = DB::table($table)
                    ->where("school_id", $schoolId)
                    ->get()
                    ->map(fn($row) => (array) $row)
                    ->toArray();
                $data["tables"][$table] = $rows;
                $rowCount += count($rows);
            } catch (\Throwable $e) {
                $data["tables"][$table] = [];
            }
        }

        $data["meta"] = [
            "table_count" => count($data["tables"]),
            "row_count"   => $rowCount,
        ];

        File::put($path, json_encode($data, JSON_PRETTY_PRINT));

        return Backup::create([
            "school_id"  => $schoolId,
            "filename"   => $filename,
            "disk"       => "local",
            "path"       => "backups/" . $filename,
            "size"       => File::size($path),
            "type"       => "manual",
            "scope"      => "school",
            "status"     => "completed",
            "notes"      => $notes,
            "meta"       => $data["meta"],
            "created_by" => auth()->id(),
        ]);
    }

    /**
     * Restore from a backup file.
     * DANGEROUS — overwrites current data.
     */
    public static function restore(Backup $backup, bool $preRestoreBackup = true): array
    {
        $path = storage_path("app/" . $backup->path);
        if (!File::exists($path)) {
            return ["success" => false, "message" => "Backup file not found."];
        }

        // Safety: take a pre-restore backup
        if ($preRestoreBackup) {
            self::createFullBackup("Pre-restore snapshot for backup #{$backup->id}");
        }

        $data = json_decode(File::get($path), true);
        if (!$data || !isset($data["tables"])) {
            return ["success" => false, "message" => "Invalid backup file."];
        }

        $restored = 0;
        $skipped  = 0;
        $errors   = [];

        DB::beginTransaction();
        try {
            // Disable foreign key checks
            if (DB::connection()->getDriverName() === "sqlite") {
                DB::statement("PRAGMA foreign_keys = OFF;");
            } else {
                DB::statement("SET FOREIGN_KEY_CHECKS = 0;");
            }

            foreach ($data["tables"] as $table => $rows) {
                if (!Schema::hasTable($table)) {
                    $skipped++;
                    continue;
                }

                try {
                    DB::table($table)->truncate();

                    foreach (array_chunk($rows, 500) as $chunk) {
                        DB::table($table)->insert($chunk);
                    }
                    $restored++;
                } catch (\Throwable $e) {
                    $errors[] = "{$table}: " . $e->getMessage();
                    $skipped++;
                }
            }

            if (DB::connection()->getDriverName() === "sqlite") {
                DB::statement("PRAGMA foreign_keys = ON;");
            } else {
                DB::statement("SET FOREIGN_KEY_CHECKS = 1;");
            }

            DB::commit();

            return [
                "success"  => true,
                "restored" => $restored,
                "skipped"  => $skipped,
                "errors"   => $errors,
            ];

        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                "success" => false,
                "message" => "Restore failed: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a backup (file + record).
     */
    public static function delete(Backup $backup): bool
    {
        $path = storage_path("app/" . $backup->path);
        if (File::exists($path)) {
            File::delete($path);
        }
        $backup->delete();
        return true;
    }

    /**
     * Retention: delete old backups based on policy.
     */
    public static function pruneOldBackups(int $keepDays = 30): int
    {
        $cutoff = now()->subDays($keepDays);

        $old = Backup::where("type", "auto")
            ->where("created_at", "<", $cutoff)
            ->get();

        $deleted = 0;
        foreach ($old as $b) {
            self::delete($b);
            $deleted++;
        }

        return $deleted;
    }
}