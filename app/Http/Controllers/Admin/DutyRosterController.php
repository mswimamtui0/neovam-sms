<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DutyRoster;
use App\Models\Staff;
use Illuminate\Http\Request;

class DutyRosterController extends Controller
{
    public function index(Request $request)
    {
        $weekStart = $request->query("week_start", now()->startOfWeek()->toDateString());
        $weekEnd   = \Carbon\Carbon::parse($weekStart)->endOfWeek()->toDateString();

        $duties = DutyRoster::with("staff")
            ->whereBetween("duty_date", [$weekStart, $weekEnd])
            ->orderBy("duty_date")
            ->orderBy("shift")
            ->paginate(50);

        $teachers = Staff::where("status","active")->orderBy("first_name")->get();

        return view("admin.duty-rosters.index", compact("duties","teachers","weekStart","weekEnd"));
    }

    public function create()
    {
        $teachers = Staff::where("status","active")->orderBy("first_name")->get();
        return view("admin.duty-rosters.create", compact("teachers"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "staff_id"   => "required|exists:staff,id",
            "duty_date"  => "required|date",
            "duty_type"  => "required|in:general,gate,assembly,break,lunch,evening,night,exam",
            "shift"      => "required|in:day,evening,night",
            "location"   => "nullable|string|max:100",
            "notes"      => "nullable|string",
        ]);

        DutyRoster::create($data);

        return redirect()->route("admin.duty-rosters.index")->with("success","Duty roster entry added.");
    }

    public function edit(DutyRoster $dutyRoster)
    {
        $teachers = Staff::where("status","active")->orderBy("first_name")->get();
        return view("admin.duty-rosters.edit", compact("dutyRoster","teachers"));
    }

    public function update(Request $request, DutyRoster $dutyRoster)
    {
        $data = $request->validate([
            "staff_id"   => "required|exists:staff,id",
            "duty_date"  => "required|date",
            "duty_type"  => "required|in:general,gate,assembly,break,lunch,evening,night,exam",
            "shift"      => "required|in:day,evening,night",
            "location"   => "nullable|string|max:100",
            "notes"      => "nullable|string",
        ]);

        $dutyRoster->update($data);

        return redirect()->route("admin.duty-rosters.index")->with("success","Duty roster updated.");
    }

    public function destroy(DutyRoster $dutyRoster)
    {
        $dutyRoster->delete();
        return back()->with("success","Entry removed.");
    }
}