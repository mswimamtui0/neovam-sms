<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\Student;
use App\Services\Level\SchoolLevelService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with("classroom")->latest()->paginate(20);
        return view("admin.students.index", compact("students"));
    }

    public function create()
    {
        $levels = SchoolLevelService::all();
        $classrooms = ClassRoom::whereIn("level", $levels)->get();
        return view("admin.students.create", compact("classrooms", "levels"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "first_name"   => "required|string|max:100",
            "last_name"    => "required|string|max:100",
            "gender"       => "required|in:male,female",
            "dob"          => "nullable|date",
            "level"        => "required|in:nursery,kg,pre_unit,primary,secondary,alevel",
            "classroom_id" => "nullable|exists:classrooms,id",
            "parent_name"  => "required|string|max:150",
            "parent_phone" => "required|string|max:30",
            "parent_email" => "nullable|email",
        ]);

        if (!SchoolLevelService::enabled($data["level"])) {
            return back()->withErrors(["level" => "This level is not enabled for this school."])->withInput();
        }

        $data["school_id"]    = School::first()?->id;
        $data["admission_no"] = "NEO-" . strtoupper(uniqid());

        $student = Student::create($data);

        return redirect()->route("admin.students.index")
            ->with("success", "Student admitted: " . $student->full_name);
    }

    public function show(Student $student)
    {
        $student->load(["classroom", "attendances", "results", "incidents"]);
        return view("admin.students.show", compact("student"));
    }

    public function edit(Student $student)
    {
        $levels = SchoolLevelService::all();
        $classrooms = ClassRoom::whereIn("level", $levels)->get();
        return view("admin.students.edit", compact("student", "classrooms", "levels"));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            "first_name"   => "required|string|max:100",
            "last_name"    => "required|string|max:100",
            "gender"       => "required|in:male,female",
            "dob"          => "nullable|date",
            "level"        => "required|in:nursery,kg,pre_unit,primary,secondary,alevel",
            "classroom_id" => "nullable|exists:classrooms,id",
            "parent_name"  => "required|string|max:150",
            "parent_phone" => "required|string|max:30",
            "parent_email" => "nullable|email",
        ]);

        $student->update($data);

        return redirect()->route("admin.students.index")
            ->with("success", "Student updated.");
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return back()->with("success", "Student removed.");
    }

    public function classroomsByLevel(Request $request)
    {
        $level = $request->query("level");

        if (!$level) {
            return response()->json([]);
        }

        $classrooms = ClassRoom::where("level", $level)
            ->orderBy("name")
            ->get(["id", "name", "stream"]);

        return response()->json($classrooms);
    }
}