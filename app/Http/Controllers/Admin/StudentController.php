<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\Student;
use App\Jobs\SendWelcomeSms;
use App\Services\Level\SchoolLevelService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with("classroom")->latest();

        if ($request->filled("login_status")) {
            $query->where("can_login", $request->login_status === "enabled");
        }

        if ($request->filled("level")) {
            $query->where("level", $request->level);
        }

        $students = $query->paginate(30)->withQueryString();
        $levels   = SchoolLevelService::all();

        return view("admin.students.index", compact("students","levels"));
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
            "enable_login" => "nullable|boolean",
        ]);

        if (!SchoolLevelService::enabled($data["level"])) {
            return back()->withErrors(["level" => "This level is not enabled."])->withInput();
        }

        $enableLogin = $request->boolean("enable_login");
        unset($data["enable_login"]);

        $data["school_id"]    = School::first()?->id;
        $data["admission_no"] = "NEO-" . strtoupper(uniqid());
        $data["can_login"]    = false;

        $student = Student::create($data);

        // Load classroom name
        $className = $student->classroom?->name ?? "General";

        // Look up fee structure for this level / current term
        $term = "Term 1";
        $year = date("Y");
        $fee  = \App\Models\FeeStructure::where("level", $student->level)
            ->where("term", $term)
            ->where("year", $year)
            ->first();
        $feeAmount = $fee ? $fee->total() : null;

        // Dispatch welcome SMS
        if ($student->parent_phone) {
            SendWelcomeSms::dispatch(
                $student->parent_phone,
                $student->full_name,
                $student->admission_no,
                $className,
                ucfirst($student->level),
                $feeAmount,
            );
        }

        // Optionally enable login
        if ($enableLogin) {
            $student->enableLogin();
        }

        return redirect()->route("admin.students.index")
            ->with("success", "Student admitted: " . $student->full_name .
                ($enableLogin ? " (login enabled)" : "") .
                " | Welcome SMS sent to parent.");
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
        if ($student->user_id) {
            $student->disableLogin();
        }
        $student->delete();
        return back()->with("success", "Student removed.");
    }

    /* ============ SINGLE ENABLE / DISABLE ============ */
    public function enableLogin(Student $student)
    {
        if ($student->can_login) {
            return back()->with("info", "Login is already enabled.");
        }
        $user = $student->enableLogin();
        return back()->with("success", "Login enabled for {$student->full_name}. Email: {$user->email} | Password: Student@123");
    }

    public function disableLogin(Student $student)
    {
        if (!$student->can_login) {
            return back()->with("info", "Login is already disabled.");
        }
        $student->disableLogin();
        return back()->with("success", "Login disabled for {$student->full_name}.");
    }

    /* ============ BULK ENABLE / DISABLE ============ */
    public function bulkEnable(Request $request)
    {
        $data = $request->validate([
            "student_ids"   => "required|array|min:1",
            "student_ids.*" => "exists:students,id",
        ]);

        $students = Student::withoutGlobalScopes()->whereIn("id", $data["student_ids"])->get();

        $enabled = 0;
        foreach ($students as $student) {
            if (!$student->can_login) {
                $student->enableLogin();
                $enabled++;
            }
        }

        return back()->with("success", "Login enabled for {$enabled} students.");
    }

    public function bulkDisable(Request $request)
    {
        $data = $request->validate([
            "student_ids"   => "required|array|min:1",
            "student_ids.*" => "exists:students,id",
        ]);

        $students = Student::withoutGlobalScopes()->whereIn("id", $data["student_ids"])->get();

        $disabled = 0;
        foreach ($students as $student) {
            if ($student->can_login) {
                $student->disableLogin();
                $disabled++;
            }
        }

        return back()->with("success", "Login disabled for {$disabled} students.");
    }

    /* ============ ENABLE / DISABLE ALL (filter-aware) ============ */
    public function enableAll(Request $request)
    {
        $query = Student::withoutGlobalScopes()->where("can_login", false);

        if ($request->filled("level")) {
            $query->where("level", $request->level);
        }

        $students = $query->get();

        $enabled = 0;
        foreach ($students as $student) {
            $student->enableLogin();
            $enabled++;
        }

        return back()->with("success", "Login enabled for {$enabled} students.");
    }

    public function disableAll(Request $request)
    {
        $query = Student::withoutGlobalScopes()->where("can_login", true);

        if ($request->filled("level")) {
            $query->where("level", $request->level);
        }

        $students = $query->get();

        $disabled = 0;
        foreach ($students as $student) {
            $student->disableLogin();
            $disabled++;
        }

        return back()->with("success", "Login disabled for {$disabled} students.");
    }

    public function classroomsByLevel(Request $request)
    {
        $level = $request->query("level");
        if (!$level) return response()->json([]);
        $classrooms = ClassRoom::where("level", $level)->orderBy("name")->get(["id", "name", "stream"]);
        return response()->json($classrooms);
    }
}