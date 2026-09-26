<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Emergency;
use App\Models\School;
use App\Models\Staff;
use App\Services\EmergencySmsService;
use Illuminate\Http\Request;

class EmergencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Emergency::latest();

        if ($request->filled("severity")) {
            $query->where("severity", $request->severity);
        }
        if ($request->filled("status")) {
            $query->where("status", $request->status);
        }

        $emergencies = $query->paginate(30);

        $counts = [
            "today"    => Emergency::whereDate("created_at", today())->count(),
            "critical" => Emergency::where("severity", "critical")->whereDate("created_at", today())->count(),
            "urgent"   => Emergency::where("severity", "urgent")->whereDate("created_at", today())->count(),
            "open"     => Emergency::whereIn("status", ["open","monitoring"])->count(),
        ];

        return view("emergencies.index", compact("emergencies", "counts"));
    }

    public function create()
    {
        return view("emergencies.create");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "student_name" => "required|string|max:150",
            "class_name"   => "nullable|string|max:50",
            "type"         => "required|in:illness,injury,emotional,other",
            "severity"     => "required|in:critical,urgent,normal,info",
            "description"  => "required|string|max:1000",
            "action_taken" => "nullable|string|max:1000",
            "location"     => "nullable|string|max:150",
        ]);

        $school = School::first();
        $dept   = Department::where("code", "health")->first();
        $staff  = Staff::where("user_id", auth()->id())->first();

        $data["school_id"]     = $school?->id;
        $data["department_id"] = $dept?->id;
        $data["staff_id"]      = $staff?->id;
        $data["recorded_by"]   = $staff?->full_name ?? auth()->user()->name;
        $data["status"]        = "open";

        $emergency = Emergency::create($data);

        // Fire the 3 SMS: Head + Parent + Health Teacher
        try {
            app(EmergencySmsService::class)->dispatchAll($emergency);
        } catch (\Throwable $e) {
            \Log::error("Emergency dispatch failed", [
                "emergency_id" => $emergency->id,
                "error"        => $e->getMessage(),
            ]);
        }

        return redirect()->route("emergencies.show", $emergency->id)
            ->with("success", "Emergency logged. SMS sent to Headmaster, Parent and Health Teacher.");
    }

    public function show($id)
    {
        $emergency = Emergency::findOrFail($id);
        return view("emergencies.show", compact("emergency"));
    }

    public function edit($id)
    {
        $emergency = Emergency::findOrFail($id);
        return view("emergencies.edit", compact("emergency"));
    }

    public function update(Request $request, $id)
    {
        $emergency = Emergency::findOrFail($id);

        $data = $request->validate([
            "description"   => "nullable|string|max:1000",
            "action_taken"  => "nullable|string|max:1000",
            "parent_response" => "nullable|string|max:1000",
            "follow_up"     => "nullable|string|max:1000",
            "status"        => "required|in:open,monitoring,resolved,closed",
        ]);

        if ($data["status"] === "resolved" && !$emergency->resolved_at) {
            $data["resolved_at"] = now();
            $emergency->update($data);

            // Send resolution SMS
            try {
                app(EmergencySmsService::class)->resolveSms($emergency);
            } catch (\Throwable $e) {
                \Log::error("Resolution SMS failed", ["emergency_id" => $emergency->id]);
            }

            return redirect()->route("emergencies.show", $emergency->id)
                ->with("success", "Emergency resolved. Parent notified.");
        }

        $emergency->update($data);
        return redirect()->route("emergencies.show", $emergency->id)->with("success", "Emergency updated.");
    }

    public function destroy($id)
    {
        Emergency::findOrFail($id)->delete();
        return redirect()->route("emergencies.index")->with("success", "Emergency deleted.");
    }

    /**
     * Resend SMS to all 3 recipients.
     */
    public function resend($id)
    {
        $emergency = Emergency::findOrFail($id);
        app(EmergencySmsService::class)->dispatchAll($emergency);
        return back()->with("success", "SMS resent to Headmaster, Parent and Health Teacher.");
    }
}