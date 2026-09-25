<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffAttendance;
use Illuminate\Http\Request;

class StaffAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query("date", now()->toDateString());

        $records = StaffAttendance::with("staff")
            ->whereDate("attendance_date", $date)
            ->orderBy("check_in")
            ->paginate(50);

        $totalStaff   = Staff::where("status", "active")->count();
        $presentToday = $records->where("status", "present")->count();
        $lateToday    = $records->where("status", "late")->count();
        $absentToday  = $totalStaff - $records->count();

        return view("admin.staff-attendance.index", compact(
            "records","date","totalStaff","presentToday","lateToday","absentToday"
        ));
    }

    /**
     * Staff missing from today's attendance.
     */
    public function missing(Request $request)
    {
        $date = $request->query("date", now()->toDateString());

        $presentIds = StaffAttendance::whereDate("attendance_date", $date)->pluck("staff_id");
        $missing = Staff::where("status", "active")
            ->whereNotIn("id", $presentIds)
            ->orderBy("first_name")
            ->paginate(50);

        return view("admin.staff-attendance.missing", compact("missing","date"));
    }

    /**
     * Monthly report per staff member.
     */
    public function report(Request $request)
    {
        $month = $request->query("month", now()->month);
        $year  = $request->query("year", now()->year);

        $staff = Staff::where("status", "active")->orderBy("first_name")->get();

        $data = $staff->map(function ($s) use ($month, $year) {
            $records = StaffAttendance::where("staff_id", $s->id)
                ->whereMonth("attendance_date", $month)
                ->whereYear("attendance_date", $year)
                ->get();

            return [
                "staff"         => $s,
                "present"       => $records->where("status", "present")->count(),
                "late"          => $records->where("status", "late")->count(),
                "absent"        => $records->where("status", "absent")->count(),
                "total_hours"   => $records->sum("hours_worked"),
                "avg_hours"     => $records->count() > 0 ? round($records->avg("hours_worked"), 1) : 0,
                "total_late_min"=> $records->sum("minutes_late"),
            ];
        });

        return view("admin.staff-attendance.report", compact("data","month","year"));
    }

    /**
     * Manual entry (admin records attendance on behalf of a staff member).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "staff_id"        => "required|exists:staff,id",
            "attendance_date" => "required|date",
            "check_in"        => "nullable",
            "check_out"       => "nullable",
            "status"          => "required|in:present,absent,late,leave",
            "notes"           => "nullable|string",
        ]);

        // Calculate hours if both in/out present
        if (!empty($data["check_in"]) && !empty($data["check_out"])) {
            $in  = \Carbon\Carbon::parse($data["check_in"]);
            $out = \Carbon\Carbon::parse($data["check_out"]);
            $data["hours_worked"] = (int) round($in->diffInMinutes($out) / 60);
        }

        StaffAttendance::updateOrCreate(
            ["staff_id" => $data["staff_id"], "attendance_date" => $data["attendance_date"]],
            $data
        );

        return back()->with("success","Attendance recorded.");
    }

    /**
     * Manual edit page.
     */
    public function edit(StaffAttendance $attendance)
    {
        $staffList = Staff::where("status","active")->orderBy("first_name")->get();
        return view("admin.staff-attendance.edit", compact("attendance","staffList"));
    }

    public function update(Request $request, StaffAttendance $attendance)
    {
        $data = $request->validate([
            "check_in"  => "nullable",
            "check_out" => "nullable",
            "status"    => "required|in:present,absent,late,leave",
            "notes"     => "nullable|string",
        ]);

        if (!empty($data["check_in"]) && !empty($data["check_out"])) {
            $in  = \Carbon\Carbon::parse($data["check_in"]);
            $out = \Carbon\Carbon::parse($data["check_out"]);
            $data["hours_worked"] = (int) round($in->diffInMinutes($out) / 60);
        }

        $attendance->update($data);

        return redirect()->route("admin.staff-attendance.index")->with("success","Attendance updated.");
    }
}