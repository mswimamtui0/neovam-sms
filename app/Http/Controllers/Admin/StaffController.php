<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\Staff;
use App\Models\Subject;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    protected array $types;

    public function __construct()
    {
        $this->types = config("staff_types", []);
    }

    public function index(Request $request)
    {
        $query = Staff::query();

        if ($request->filled("staff_type")) {
            $query->where("staff_type", $request->staff_type);
        }
        if ($request->filled("department")) {
            $query->where("department", $request->department);
        }

        $staff = $query->latest()->paginate(20);
        $types = $this->types;

        return view("admin.staff.index", compact("staff", "types"));
    }

    public function create()
    {
        $types      = $this->types;
        $subjects   = Subject::where("is_active", true)->orderBy("name")->get();
        $classrooms = ClassRoom::orderBy("name")->get();

        return view("admin.staff.create", compact("types", "subjects", "classrooms"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "first_name"           => "required|string|max:100",
            "last_name"            => "required|string|max:100",
            "gender"               => "required|in:male,female",
            "phone"                => "required|string|max:30",
            "email"                => "nullable|email",
            "staff_type"           => "required|string",
            "role_title"           => "nullable|string|max:100",
            "dob"                  => "nullable|date",
            "nida"                 => "nullable|string|max:50",
            "address"              => "nullable|string|max:255",
            "employment_date"      => "nullable|date",
            "employment_type"      => "nullable|string|max:50",
            "qualification"        => "nullable|string|max:100",
            "field_of_study"       => "nullable|string|max:100",
            "institution"          => "nullable|string|max:150",
            "year_graduated"       => "nullable|integer|min:1950|max:2100",
            "emergency_contact_name"  => "nullable|string|max:150",
            "emergency_contact_phone" => "nullable|string|max:30",
            "bank_name"            => "nullable|string|max:100",
            "bank_account"         => "nullable|string|max:50",
            "tin_number"           => "nullable|string|max:50",
            "subjects"             => "nullable|array",
            "subjects.*"           => "exists:subjects,id",
            "classrooms"           => "nullable|array",
            "classrooms.*"         => "exists:classrooms,id",
            "class_teacher_of"     => "nullable|exists:classrooms,id",
        ]);

        $data["department"] = $this->types[$data["staff_type"]] ?? "Other";
        $data["school_id"]  = School::first()?->id;
        $data["staff_no"]   = "STF-" . strtoupper(uniqid());
        $data["status"]     = "active";

        // Combine assigned classrooms
        $classrooms = $data["classrooms"] ?? [];
        if (!empty($classrooms)) {
            $data["assigned_classrooms"] = implode(",", $classrooms);
        }

        $subjects = $data["subjects"] ?? [];
        $classTeacherOf = $data["class_teacher_of"] ?? null;

        unset($data["subjects"], $data["classrooms"], $data["class_teacher_of"]);

        $staff = Staff::create($data);

        if ($staff->isTeacher() && !empty($subjects)) {
            $staff->subjects()->sync($subjects);
        }

        // Set class teacher if selected
        if ($classTeacherOf) {
            // Remove this teacher from any other class first
            ClassRoom::where("class_teacher_id", $staff->id)->update(["class_teacher_id" => null]);
            // Assign to new class
            ClassRoom::where("id", $classTeacherOf)->update(["class_teacher_id" => $staff->id]);
        }

        return redirect()->route("admin.staff.index")
            ->with("success", "Staff added: " . $staff->full_name);
    }

    public function show(Staff $staff)
    {
        $staff->load("subjects");
        $classTeacherOf = ClassRoom::where("class_teacher_id", $staff->id)->first();
        $assignedClasses = ClassRoom::whereIn("id", $staff->classroomIds())->get();

        return view("admin.staff.show", compact("staff", "classTeacherOf", "assignedClasses"));
    }

    public function edit(Staff $staff)
    {
        $types      = $this->types;
        $subjects   = Subject::where("is_active", true)->orderBy("name")->get();
        $classrooms = ClassRoom::orderBy("name")->get();
        $assigned   = $staff->subjects->pluck("id")->toArray();
        $assignedClasses = $staff->classroomIds();
        $classTeacherOf  = ClassRoom::where("class_teacher_id", $staff->id)->first();

        return view("admin.staff.edit", compact(
            "staff", "types", "subjects", "classrooms",
            "assigned", "assignedClasses", "classTeacherOf"
        ));
    }

    public function update(Request $request, Staff $staff)
    {
        $data = $request->validate([
            "first_name"           => "required|string|max:100",
            "last_name"            => "required|string|max:100",
            "gender"               => "required|in:male,female",
            "phone"                => "required|string|max:30",
            "email"                => "nullable|email",
            "staff_type"           => "required|string",
            "role_title"           => "nullable|string|max:100",
            "dob"                  => "nullable|date",
            "nida"                 => "nullable|string|max:50",
            "address"              => "nullable|string|max:255",
            "employment_date"      => "nullable|date",
            "employment_type"      => "nullable|string|max:50",
            "qualification"        => "nullable|string|max:100",
            "field_of_study"       => "nullable|string|max:100",
            "institution"          => "nullable|string|max:150",
            "year_graduated"       => "nullable|integer|min:1950|max:2100",
            "emergency_contact_name"  => "nullable|string|max:150",
            "emergency_contact_phone" => "nullable|string|max:30",
            "bank_name"            => "nullable|string|max:100",
            "bank_account"         => "nullable|string|max:50",
            "tin_number"           => "nullable|string|max:50",
            "subjects"             => "nullable|array",
            "subjects.*"           => "exists:subjects,id",
            "classrooms"           => "nullable|array",
            "classrooms.*"         => "exists:classrooms,id",
            "class_teacher_of"     => "nullable|exists:classrooms,id",
        ]);

        $data["department"] = $this->types[$data["staff_type"]] ?? "Other";

        // Combine assigned classrooms
        $classrooms = $data["classrooms"] ?? [];
        $data["assigned_classrooms"] = !empty($classrooms) ? implode(",", $classrooms) : null;

        $subjects = $data["subjects"] ?? [];
        $classTeacherOf = $data["class_teacher_of"] ?? null;

        unset($data["subjects"], $data["classrooms"], $data["class_teacher_of"]);

        $staff->update($data);

        // Sync subjects
        if ($staff->isTeacher()) {
            $staff->subjects()->sync($subjects);
        } else {
            $staff->subjects()->sync([]);
        }

        // Handle class teacher assignment
        ClassRoom::where("class_teacher_id", $staff->id)->update(["class_teacher_id" => null]);
        if ($classTeacherOf) {
            ClassRoom::where("id", $classTeacherOf)->update(["class_teacher_id" => $staff->id]);
        }

        return redirect()->route("admin.staff.index")
            ->with("success", "Staff updated.");
    }

    public function destroy(Staff $staff)
    {
        ClassRoom::where("class_teacher_id", $staff->id)->update(["class_teacher_id" => null]);
        $staff->subjects()->detach();
        $staff->delete();
        return back()->with("success", "Staff removed.");
    }
}