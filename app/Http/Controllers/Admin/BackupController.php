<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Models\School;
use App\Services\Backup\BackupService;
use Illuminate\Http\Request;

class BackupController extends Controller
{
 public function index()
 {
 $backups = Backup::with(["school","creator"])->latest()->paginate(30);

 $stats = [
 "total" => Backup::count(),
 "completed" => Backup::where("status","completed")->count(),
 "failed" => Backup::where("status","failed")->count(),
 "total_size" => Backup::sum("size"),
 "last_backup" => Backup::latest()->first(),
 ];

 $schools = School::orderBy("name")->get();

 return view("admin.backups.index", compact("backups","stats","schools"));
 }

 /**
 * Create a full system backup.
 */
 public function createFull(Request $request)
 {
 $data = $request->validate([
 "notes" => "nullable|string|max:500",
 ]);

 $backup = BackupService::createFullBackup($data["notes"] ?? null);

 return back()->with("success",
 "Backup created: {$backup->filename} (" . $backup->humanSize() . ")"
 );
 }

 /**
 * Create a school-specific backup.
 */
 public function createSchool(Request $request)
 {
 $data = $request->validate([
 "school_id" => "required|exists:schools,id",
 "notes" => "nullable|string|max:500",
 ]);

 $backup = BackupService::createSchoolBackup($data["school_id"], $data["notes"] ?? null);

 return back()->with("success",
 "School backup created: {$backup->filename} (" . $backup->humanSize() . ")"
 );
 }

 /**
 * Download the backup file.
 */
 public function download(Backup $backup)
 {
 $path = storage_path("app/" . $backup->path);
 if (!file_exists($path)) {
 return back()->with("error", "Backup file not found.");
 }

 return response()->download($path, $backup->filename);
 }

 /**
 * Restore a backup (destructive).
 */
 public function restore(Request $request, Backup $backup)
 {
 $data = $request->validate([
 "confirm" => "required|in:RESTORE",
 ]);

 $result = BackupService::restore($backup);

 if (!$result["success"]) {
 return back()->with("error", $result["message"] ?? "Restore failed.");
 }

 $message = "Restore complete. Restored: {$result["restored"]} tables, Skipped: {$result["skipped"]}.";
 if (!empty($result["errors"])) {
 $message .= " Warnings: " . implode(", ", array_slice($result["errors"], 0, 3));
 }

 return redirect()->route("admin.backups.index")->with("success", $message);
 }

 /**
 * Delete a backup.
 */
 public function destroy(Backup $backup)
 {
 BackupService::delete($backup);
 return back()->with("success", "Backup deleted.");
 }

 /**
 * Prune old backups.
 */
 public function prune(Request $request)
 {
 $data = $request->validate([
 "days" => "required|integer|min:1|max:365",
 ]);

 $count = BackupService::pruneOldBackups($data["days"]);

 return back()->with("success", "Deleted {$count} old backups (older than {$data["days"]} days).");
 }
}