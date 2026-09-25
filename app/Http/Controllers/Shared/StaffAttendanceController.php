<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffAttendance;
use Illuminate\Http\Request;

class StaffAttendanceController extends Controller
{
    protected function me(): ?Staff
    {
        return Staff::where("user_id", auth()->id())->first();
    }

    /**
     * Staff sees their own attendance dashboard.
     */
    public function myAttendance()
    {
        $staff = $this->me();
        if (!$staff) {
            return view("shared.attendance.no-profile");
        }

        $today    = now()->toDateString();
        $todayRow = StaffAttendance::where("staff_id", $staff->id)
            ->where("attendance_date", $today)
            ->first();

        $history = StaffAttendance::where("staff_id", $staff->id)
            ->orderByDesc("attendance_date")
            ->paginate(30);

        // Stats
        $thisMonth = StaffAttendance::where("staff_id", $staff->id)
            ->whereMonth("attendance_date", now()->month)
            ->whereYear("attendance_date", now()->year)
            ->get();

        $stats = [
            "present"      => $thisMonth->where("status","present")->count(),
            "late"         => $thisMonth->where("status","late")->count(),
            "absent"       => $thisMonth->where("status","absent")->count(),
            "total_hours"  => $thisMonth->sum("hours_worked"),
            "avg_hours"    => $thisMonth->count() > 0 ? round($thisMonth->avg("hours_worked"), 1) : 0,
        ];

        return view("shared.attendance.my", compact("staff","todayRow","history","stats"));
    }

    /**
     * Check in.
     */
    public function checkIn(Request $request)
    {
        $staff = $this->me();
        if (!$staff) return back()->with("error","No staff record.");

        $record = StaffAttendance::checkIn(
            $staff,
            $request->ip(),
            substr($request->userAgent() ?? "unknown", 0, 255)
        );

        return back()->with("success", "Checked in at " . $record->check_in);
    }

    /**
     * Check out.
     */
    public function checkOut(Request $request)
    {
        $staff = $this->me();
        if (!$staff) return back()->with("error","No staff record.");

        $record = StaffAttendance::checkOut($staff);

        if (!$record) {
            return back()->with("error","You must check in first.");
        }

        return back()->with("success", "Checked out at " . $record->check_out . " — {$record->hours_worked} hours worked.");
    }
}