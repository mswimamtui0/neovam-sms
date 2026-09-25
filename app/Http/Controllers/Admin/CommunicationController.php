<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\SmsLog;
use App\Models\Staff;
use App\Models\Student;
use App\Services\Level\SchoolLevelService;
use App\Services\Sms\SmsService;
use Illuminate\Http\Request;

class CommunicationController extends Controller
{
 public function __construct(protected SmsService $sms) {}

 public function index(Request $request)
 {
 $query = SmsLog::latest();

 if ($request->filled("trigger")) {
 $query->where("trigger", $request->trigger);
 }

 $logs = $query->paginate(30);

 $totalUnits = SmsLog::sum("units");
 $monthUnits = SmsLog::whereMonth("created_at", now()->month)
 ->whereYear("created_at", now()->year)
 ->sum("units");

 return view("admin.communication.index", compact("logs", "totalUnits", "monthUnits"));
 }

 public function create()
 {
 $levels = SchoolLevelService::all();
 return view("admin.communication.create", compact("levels"));
 }

 public function send(Request $request)
 {
 $data = $request->validate([
 "audience" => "required|in:parents,by_level,by_class,by_stream,staff,custom",
 "level" => "nullable|string",
 "classroom_id" => "nullable|exists:classrooms,id",
 "stream" => "nullable|string",
 "phones" => "nullable|string",
 "message" => "required|string|max:500",
 ]);

 $recipients = $this->resolveRecipients($data);

 if (empty($recipients)) {
 return back()->with("error", "No recipients matched your selection.")->withInput();
 }

 $sent = 0;
 $units = SmsService::calculateUnits($data["message"]);
 $totalUnits = 0;

 foreach ($recipients as $phone) {
 if ($this->sms->send($phone, $data["message"], "bulk")) {
 $sent++;
 $totalUnits += $units;
 }
 }

 return back()->with(
 "success",
 "SMS dispatched to {$sent} recipients. Total units used: {$totalUnits} (per-recipient units: {$units})."
 );
 }

 protected function resolveRecipients(array $data): array
 {
 switch ($data["audience"]) {
 case "parents":
 return Student::where("status", "active")
 ->pluck("parent_phone")->filter()->unique()->values()->toArray();

 case "by_level":
 return Student::where("status", "active")
 ->where("level", $data["level"])
 ->pluck("parent_phone")->filter()->unique()->values()->toArray();

 case "by_class":
 return Student::where("status", "active")
 ->where("classroom_id", $data["classroom_id"])
 ->pluck("parent_phone")->filter()->unique()->values()->toArray();

 case "by_stream":
 if (!$data["classroom_id"]) return [];
 $stream = $data["stream"] ?? null;
 return Student::where("status", "active")
 ->where("classroom_id", $data["classroom_id"])
 ->when($stream, function ($q) use ($stream) {
 $q->whereHas("classroom", function ($sub) use ($stream) {
 $sub->where("stream", $stream);
 });
 })
 ->pluck("parent_phone")->filter()->unique()->values()->toArray();

 case "staff":
 return Staff::where("status", "active")
 ->pluck("phone")->filter()->unique()->values()->toArray();

 case "custom":
 return array_filter(array_map("trim", explode(",", $data["phones"] ?? "")));
 }

 return [];
 }

 /**
 * API: classrooms filtered by level (for cascading dropdown).
 */
 public function classroomsByLevel(Request $request)
 {
 $level = $request->query("level");
 if (!$level) return response()->json([]);

 $classrooms = ClassRoom::where("level", $level)
 ->orderBy("name")
 ->get(["id", "name", "stream"]);

 return response()->json($classrooms);
 }

 /**
 * API: streams for a given classroom.
 */
 public function streamsByClassroom(Request $request)
 {
 $classId = $request->query("classroom_id");
 if (!$classId) return response()->json([]);

 $streams = ClassRoom::where("id", $classId)
 ->pluck("stream")
 ->filter()
 ->unique()
 ->values();

 if ($streams->isEmpty()) {
 $streams = collect(["A"]);
 }

 return response()->json($streams);
 }

 /**
 * API: preview recipient count.
 */
 public function previewRecipients(Request $request)
 {
 $data = $request->all();
 $recipients = $this->resolveRecipients($data);
 return response()->json(["count" => count($recipients)]);
 }
}