<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmergencySms;
use App\Models\ClassRoom;
use App\Models\Incident;
use App\Models\Student;
use Illuminate\Http\Request;

class EmergencyController extends Controller
{
    public function index()
    {
        $incidents = Incident::with("student")->latest()->paginate(20);
        return view("admin.emergencies.index", compact("incidents"));
    }

    public function create()
    {
        $levels = \App\Services\Level\SchoolLevelService::all();
        return view("admin.emergencies.create", compact("levels"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "student_id"  => "required|exists:students,id",
            "type"        => "required|string|max:100",
            "description" => "required|string|max:500",
        ]);

        $data["reported_by"] = auth()->user()->name ?? "System";

        $incident = Incident::create($data);
        $student  = $incident->student;

        SendEmergencySms::dispatch(
            $student->parent_phone,
            $student->full_name,
            $data["type"],
            $data["description"]
        );

        $incident->update(["sms_sent" => true]);

        return redirect()->route("admin.emergencies.index")
            ->with("success", "Incident recorded. Parent notified via SMS.");
    }

    /* ============================================
       API endpoints for cascading dropdowns
       ============================================ */

    public function classesByLevel(Request $request)
    {
        $level = $request->query("level");
        if (!$level) return response()->json([]);

        $classes = ClassRoom::where("level", $level)
            ->orderBy("name")
            ->get(["id", "name"]);

        return response()->json($classes);
    }

    public function streamsByClass(Request $request)
    {
        $classId = $request->query("class_id");
        if (!$classId) return response()->json([]);

        $streams = ClassRoom::where("id", $classId)
            ->pluck("stream")
            ->filter()
            ->unique()
            ->values();

        // If no streams exist, return a single "A"
        if ($streams->isEmpty()) {
            $streams = collect(["A"]);
        }

        return response()->json($streams);
    }

    public function studentsByClass(Request $request)
    {
        $classId = $request->query("class_id");
        $stream  = $request->query("stream");

        if (!$classId) return response()->json([]);

        $query = Student::where("classroom_id", $classId)
            ->where("status", "active");

        if ($stream) {
            $query->whereHas("classroom", function ($q) use ($stream) {
                $q->where("stream", $stream);
            });
        }

        $students = $query->orderBy("first_name")
            ->get(["id", "admission_no", "first_name", "last_name", "parent_name", "parent_phone", "level", "classroom_id"]);

        return response()->json($students);
    }

    public function searchStudents(Request $request)
    {
        $q = trim($request->query("q", ""));

        if (strlen($q) < 2) return response()->json([]);

        $students = Student::with("classroom")
            ->where("status", "active")
            ->where(function ($query) use ($q) {
                $query->where("first_name", "like", "%{$q}%")
                      ->orWhere("last_name",  "like", "%{$q}%")
                      ->orWhere("admission_no", "like", "%{$q}%")
                      ->orWhereRaw("(first_name || \" \" || last_name) LIKE ?", ["%{$q}%"]);
            })
            ->limit(20)
            ->get(["id", "admission_no", "first_name", "last_name", "parent_name", "parent_phone", "level", "classroom_id"]);

        // Append classroom name
        $students->transform(function ($s) {
            $s->classroom_name = $s->classroom?->name;
            $s->classroom_stream = $s->classroom?->stream;
            return $s;
        });

        return response()->json($students);
    }
}