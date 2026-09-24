<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();

        if ($request->filled("level")) {
            $query->where("levels", "like", "%" . $request->level . "%");
        }

        if ($request->filled("category")) {
            $query->where("category", $request->category);
        }

        $subjects = $query->orderBy("name")->paginate(30);

        return view("admin.subjects.index", compact("subjects"));
    }

    public function create()
    {
        return view("admin.subjects.create");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "name"     => "required|string|max:100",
            "code"     => "required|string|max:20|unique:subjects,code",
            "levels"   => "required|array|min:1",
            "levels.*" => "in:nursery,kg,pre_unit,primary,secondary,alevel",
            "category" => "required|string|max:50",
        ]);

        $data["school_id"] = School::first()?->id;
        $data["levels"]    = implode(",", $data["levels"]);
        $data["is_active"] = true;

        Subject::create($data);

        return redirect()->route("admin.subjects.index")
            ->with("success", "Subject added.");
    }

    public function edit(Subject $subject)
    {
        return view("admin.subjects.edit", compact("subject"));
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            "name"     => "required|string|max:100",
            "code"     => "required|string|max:20|unique:subjects,code," . $subject->id,
            "levels"   => "required|array|min:1",
            "levels.*" => "in:nursery,kg,pre_unit,primary,secondary,alevel",
            "category" => "required|string|max:50",
            "is_active"=> "nullable|boolean",
        ]);

        $data["levels"]    = implode(",", $data["levels"]);
        $data["is_active"] = $request->boolean("is_active");

        $subject->update($data);

        return redirect()->route("admin.subjects.index")
            ->with("success", "Subject updated.");
    }

    public function destroy(Subject $subject)
    {
        if ($subject->staff()->count() > 0) {
            return back()->with("error", "Cannot delete. Teachers are assigned to this subject.");
        }
        $subject->delete();
        return back()->with("success", "Subject deleted.");
    }
}