<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $days = Timetable::days();

        $query = Timetable::with(["staff","classroom","subject"]);

        if ($request->filled("staff_id")) {
            $query->where("staff_id", $request->staff_id);
        }
        if ($request->filled("classroom_id")) {
            $query->where("classroom_id", $request->classroom_id);
        }
        if ($request->filled("day_of_week")) {
            $query->where("day_of_week", $request->day_of_week);
        }

        $entries   = $query->orderByRaw("CASE day_of_week
                            WHEN 'Monday' THEN 1
                            WHEN 'Tuesday' THEN 2
                            WHEN 'Wednesday' THEN 3
                            WHEN 'Thursday' THEN 4
                            WHEN 'Friday' THEN 5
                            WHEN 'Saturday' THEN 6
                            WHEN 'Sunday' THEN 7 END")
                        ->orderBy("start_time")
                        ->paginate(50);

        $teachers  = Staff::where("status","active")->orderBy("first_name")->get();
        $classrooms = ClassRoom::orderBy("name")->get();

        return view("academic.timetable.index", compact("entries","teachers","classrooms","days"));
    }

    public function create()
    {
        $teachers   = Staff::where("status","active")->orderBy("first_name")->get();
        $classrooms = ClassRoom::orderBy("name")->get();
        $subjects   = Subject::where("is_active", true)->orderBy("name")->get();
        $days       = Timetable::days();

        return view("academic.timetable.create", compact("teachers","classrooms","subjects","days"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "staff_id"     => "required|exists:staff,id",
            "classroom_id" => "required|exists:classrooms,id",
            "subject_id"   => "nullable|exists:subjects,id",
            "day_of_week"  => "required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday",
            "start_time"   => "required",
            "end_time"     => "required|after:start_time",
            "period_label" => "nullable|string|max:30",
            "room"         => "nullable|string|max:50",
            "notes"        => "nullable|string",
        ]);

        Timetable::create($data);

        return redirect()->route("academic.timetable")->with("success", "Timetable entry added.");
    }

    public function edit(Timetable $timetable)
    {
        $teachers   = Staff::where("status","active")->orderBy("first_name")->get();
        $classrooms = ClassRoom::orderBy("name")->get();
        $subjects   = Subject::where("is_active", true)->orderBy("name")->get();
        $days       = Timetable::days();

        return view("academic.timetable.edit", compact("timetable","teachers","classrooms","subjects","days"));
    }

    public function update(Request $request, Timetable $timetable)
    {
        $data = $request->validate([
            "staff_id"     => "required|exists:staff,id",
            "classroom_id" => "required|exists:classrooms,id",
            "subject_id"   => "nullable|exists:subjects,id",
            "day_of_week"  => "required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday",
            "start_time"   => "required",
            "end_time"     => "required|after:start_time",
            "period_label" => "nullable|string|max:30",
            "room"         => "nullable|string|max:50",
            "notes"        => "nullable|string",
        ]);

        $timetable->update($data);

        return redirect()->route("academic.timetable")->with("success", "Timetable updated.");
    }

    public function destroy(Timetable $timetable)
    {
        $timetable->delete();
        return back()->with("success", "Entry removed.");
    }

    /**
     * Bulk grid view — weekly timetable for one teacher or one class.
     */
    public function grid(Request $request)
    {
        $days = Timetable::days();

        $staffId = $request->query("staff_id");
        $classId = $request->query("classroom_id");

        $query = Timetable::with(["staff","classroom","subject"]);

        if ($staffId) $query->where("staff_id", $staffId);
        if ($classId) $query->where("classroom_id", $classId);

        $entries = $query->orderBy("start_time")->get();

        $teachers   = Staff::where("status","active")->orderBy("first_name")->get();
        $classrooms = ClassRoom::orderBy("name")->get();

        return view("academic.timetable.grid", compact("entries","days","teachers","classrooms","staffId","classId"));
    }
}