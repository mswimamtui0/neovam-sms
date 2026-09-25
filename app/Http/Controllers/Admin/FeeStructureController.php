<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\School;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    public function index()
    {
        $fees = FeeStructure::orderBy("year","desc")->orderBy("term")->paginate(20);
        return view("admin.fees.structures.index", compact("fees"));
    }

    public function create()
    {
        return view("admin.fees.structures.create");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "level"           => "required|in:nursery,kg,pre_unit,primary,secondary,alevel",
            "term"            => "required|string|max:50",
            "year"            => "required|integer",
            "tuition_fee"     => "nullable|numeric|min:0",
            "transport_fee"   => "nullable|numeric|min:0",
            "meal_fee"        => "nullable|numeric|min:0",
            "development_fee" => "nullable|numeric|min:0",
            "exam_fee"        => "nullable|numeric|min:0",
            "other_fee"       => "nullable|numeric|min:0",
            "notes"           => "nullable|string",
        ]);

        $data["school_id"] = School::first()?->id;

        FeeStructure::updateOrCreate(
            [
                "school_id" => $data["school_id"],
                "level"     => $data["level"],
                "term"      => $data["term"],
                "year"      => $data["year"],
            ],
            $data
        );

        return redirect()->route("admin.fee-structures.index")->with("success","Fee structure saved.");
    }

    public function edit(FeeStructure $feeStructure)
    {
        return view("admin.fees.structures.edit", compact("feeStructure"));
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $data = $request->validate([
            "level"           => "required|in:nursery,kg,pre_unit,primary,secondary,alevel",
            "term"            => "required|string|max:50",
            "year"            => "required|integer",
            "tuition_fee"     => "nullable|numeric|min:0",
            "transport_fee"   => "nullable|numeric|min:0",
            "meal_fee"        => "nullable|numeric|min:0",
            "development_fee" => "nullable|numeric|min:0",
            "exam_fee"        => "nullable|numeric|min:0",
            "other_fee"       => "nullable|numeric|min:0",
            "notes"           => "nullable|string",
        ]);

        $feeStructure->update($data);

        return redirect()->route("admin.fee-structures.index")->with("success","Fee structure updated.");
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $feeStructure->delete();
        return back()->with("success","Fee structure removed.");
    }
}