<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Jobs\SendPromotionSms;
use App\Models\AcademicYear;
use App\Models\Promotion;
use App\Models\School;
use App\Models\Student;
use App\Services\Academic\PromotionService;
use App\Services\Level\SchoolLevelService;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /* ============ INDEX — list academic years ============ */
    public function index()
    {
        $years = AcademicYear::orderBy("year","desc")->paginate(20);
        return view("academic.promotions.index", compact("years"));
    }

    /* ============ CREATE ACADEMIC YEAR ============ */
    public function createYear()
    {
        return view("academic.promotions.years.create");
    }

    public function storeYear(Request $request)
    {
        $data = $request->validate([
            "year"       => "required|integer|min:2000|max:2100",
            "label"      => "required|string|max:50",
            "start_date" => "required|date",
            "end_date"   => "required|date|after:start_date",
            "is_current" => "nullable|boolean",
        ]);

        $data["school_id"]  = School::first()?->id;
        $data["is_current"] = $request->boolean("is_current");

        if ($data["is_current"]) {
            AcademicYear::where("is_current", true)->update(["is_current" => false]);
        }

        AcademicYear::create($data);

        return redirect()->route("academic.promotions")->with("success","Academic year created.");
    }

    /* ============ PREVIEW ============ */
    public function preview(Request $request)
    {
        $level    = $request->query("level");
        $preview  = PromotionService::preview($level);
        $levels   = SchoolLevelService::all();
        $year     = AcademicYear::current()?->label ?? date("Y");

        // Breakdown
        $counts = [
            "will_promote"         => 0,
            "graduated"            => 0,
            "next_class_missing"   => 0,
            "no_class"             => 0,
            "no_mapping"           => 0,
        ];
        foreach ($preview as $p) {
            $counts[$p["status"]] = ($counts[$p["status"]] ?? 0) + 1;
        }

        return view("academic.promotions.preview", compact("preview","levels","year","level","counts"));
    }

    /* ============ EXECUTE ============ */
    public function execute(Request $request)
    {
        $data = $request->validate([
            "student_ids"   => "required|array|min:1",
            "student_ids.*" => "exists:students,id",
            "academic_year" => "required|string",
            "send_sms"      => "nullable|boolean",
        ]);

        $result = PromotionService::promote($data["student_ids"], $data["academic_year"]);

        // SMS to each promoted/graduated student
        if ($request->boolean("send_sms")) {
            $promotions = Promotion::whereIn("student_id", $data["student_ids"])
                ->where("academic_year", $data["academic_year"])
                ->where("sms_sent", false)
                ->with(["student","fromClassroom","toClassroom"])
                ->get();

            $sent = 0;
            foreach ($promotions as $p) {
                $student = $p->student;
                if (!$student || !$student->parent_phone) continue;

                SendPromotionSms::dispatch(
                    $student->parent_phone,
                    $student->full_name,
                    $p->fromClassroom?->name,
                    $p->toClassroom?->name,
                    $p->status,
                );
                $p->update(["sms_sent" => true]);
                $sent++;
            }
            $result["sms_sent"] = $sent;
        }

        return redirect()->route("academic.promotions")
            ->with("success",
                "Promoted: {$result["promoted"]} | Graduated: {$result["graduated"]} | Skipped: {$result["skipped"]}" .
                (isset($result["sms_sent"]) ? " | SMS sent: {$result["sms_sent"]}" : "")
            );
    }

    /* ============ HISTORY ============ */
    public function history(Request $request)
    {
        $query = Promotion::with(["student","fromClassroom","toClassroom"])->latest();

        if ($request->filled("academic_year")) {
            $query->where("academic_year", $request->academic_year);
        }
        if ($request->filled("status")) {
            $query->where("status", $request->status);
        }

        $promotions = $query->paginate(30);
        $years = Promotion::distinct()->pluck("academic_year")->sort()->reverse();

        return view("academic.promotions.history", compact("promotions","years"));
    }

    /* ============ HOLD BACK / REPEAT ============ */
    public function holdBack(Request $request)
    {
        $data = $request->validate([
            "student_id"    => "required|exists:students,id",
            "academic_year" => "required|string",
            "notes"         => "nullable|string",
        ]);

        $student = Student::with("classroom")->find($data["student_id"]);

        Promotion::create([
            "student_id"        => $student->id,
            "from_classroom_id" => $student->classroom_id,
            "to_classroom_id"   => $student->classroom_id,
            "from_level"        => $student->level,
            "to_level"          => $student->level,
            "academic_year"     => $data["academic_year"],
            "status"            => "repeated",
            "notes"             => $data["notes"] ?? null,
            "promoted_by"       => auth()->id(),
            "promoted_at"       => now(),
        ]);

        return back()->with("success","Student held back: {$student->full_name}");
    }
}