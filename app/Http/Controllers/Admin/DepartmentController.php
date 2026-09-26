<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\School;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::withCount(["subjects","staff"])->orderBy("type")->orderBy("name");

        if ($request->filled("type")) {
            $query->where("type", $request->type);
        }

        $departments = $query->paginate(30);

        return view("admin.departments.index", compact("departments"));
    }

    public function create()
    {
        return view("admin.departments.create");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "name"        => "required|string|max:100|unique:departments,name",
            "code"        => "required|string|max:50|unique:departments,code",
            "type"        => "required|in:teaching,non_teaching",
            "color"       => "required|in:blue,green,red,yellow,orange,purple,indigo,teal,gray",
            "description" => "nullable|string|max:500",
        ]);

        $data["school_id"] = School::first()?->id;
        $data["is_active"] = true;

        Department::create($data);

        return redirect()->route("admin.departments.index")
            ->with("success", "Department '{$data["name"]}' created.");
    }

    public function edit(Department $department)
    {
        return view("admin.departments.edit", compact("department"));
    }

    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            "name"        => "required|string|max:100|unique:departments,name," . $department->id,
            "code"        => "required|string|max:50|unique:departments,code," . $department->id,
            "type"        => "required|in:teaching,non_teaching",
            "color"       => "required|in:blue,green,red,yellow,orange,purple,indigo,teal,gray",
            "description" => "nullable|string|max:500",
            "is_active"   => "nullable|boolean",
        ]);

        $data["is_active"] = $request->boolean("is_active");
        $department->update($data);

        return redirect()->route("admin.departments.index")->with("success", "Department updated.");
    }

    public function destroy(Department $department)
    {
        $staffCount = \App\Models\Staff::where("department", $department->name)->count();

        if ($staffCount > 0) {
            return back()->with("error", "Cannot delete — {$staffCount} staff assigned.");
        }

        $department->subjects()->update(["department_id" => null]);
        $department->delete();

        return back()->with("success", "Department deleted.");
    }
}