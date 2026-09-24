<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\Student;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function dashboard()
    {
        $children = Student::with("classroom")->where("status", "active")->get();
        return view("parent.dashboard", compact("children"));
    }

    public function child(Student $student)
    {
        // ParentStudentScope already enforces ownership
        $student->load(["classroom", "attendances", "results.exam", "incidents"]);
        return view("parent.child", compact("student"));
    }

    public function results()
    {
        $children = Student::with(["results.exam"])->get();
        return view("parent.results", compact("children"));
    }

    public function attendance()
    {
        $children = Student::with(["attendances"])->get();
        return view("parent.attendance", compact("children"));
    }

    public function incidents()
    {
        $children = Student::pluck("id")->toArray();
        $incidents = Incident::whereIn("student_id", $children)->with("student")->latest()->paginate(20);
        return view("parent.incidents", compact("incidents"));
    }
}