<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffWorkspaceController extends Controller
{
    /**
     * The teacher's personal workspace:
     *  - Their subjects
     *  - Their classes
     *  - Their students
     */
    public function index(Request $request)
    {
        $user  = $request->user();
        $staff = Staff::where("user_id", $user->id)
            ->with(["subjects", "primaryDepartment"])
            ->firstOrFail();

        if (!$staff->isTeacher()) {
            return redirect()->route("hub.index");
        }

        $subjects        = $staff->subjects;
        $assignedClasses = $staff->assignedClasses();
        $classTeacherOf  = ClassRoom::where("class_teacher_id", $staff->id)->first();

        return view("workspace.index", compact(
            "staff", "subjects", "assignedClasses", "classTeacherOf"
        ));
    }
}