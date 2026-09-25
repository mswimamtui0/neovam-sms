<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalaryStructure;
use App\Models\Staff;
use Illuminate\Http\Request;

class SalaryStructureController extends Controller
{
    public function index()
    {
        $structures = SalaryStructure::with("staff")->latest()->paginate(30);
        return view("admin.payroll.structures.index", compact("structures"));
    }

    public function create(Request $request)
    {
        $staffList = Staff::where("status","active")->orderBy("first_name")->get();
        $preselect = $request->query("staff_id");
        return view("admin.payroll.structures.create", compact("staffList","preselect"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "staff_id"            => "required|exists:staff,id",
            "basic_salary"        => "required|numeric|min:0",
            "allowance_house"     => "nullable|numeric|min:0",
            "allowance_transport" => "nullable|numeric|min:0",
            "allowance_meal"      => "nullable|numeric|min:0",
            "allowance_other"     => "nullable|numeric|min:0",
            "deduction_tax"       => "nullable|numeric|min:0",
            "deduction_nssf"      => "nullable|numeric|min:0",
            "deduction_loan"      => "nullable|numeric|min:0",
            "deduction_other"     => "nullable|numeric|min:0",
            "bank_name"           => "nullable|string|max:100",
            "bank_account"        => "nullable|string|max:50",
            "payment_method"      => "required|in:bank,cash,mobile_money,cheque",
            "effective_from"      => "required|date",
            "notes"               => "nullable|string",
        ]);

        // Deactivate older structures for this staff
        SalaryStructure::where("staff_id", $data["staff_id"])->update(["is_active" => false]);

        $data["is_active"] = true;

        SalaryStructure::create($data);

        return redirect()->route("admin.salary-structures.index")
            ->with("success","Salary structure saved.");
    }

    public function edit(SalaryStructure $salaryStructure)
    {
        $staffList = Staff::where("status","active")->orderBy("first_name")->get();
        return view("admin.payroll.structures.edit", compact("salaryStructure","staffList"));
    }

    public function update(Request $request, SalaryStructure $salaryStructure)
    {
        $data = $request->validate([
            "basic_salary"        => "required|numeric|min:0",
            "allowance_house"     => "nullable|numeric|min:0",
            "allowance_transport" => "nullable|numeric|min:0",
            "allowance_meal"      => "nullable|numeric|min:0",
            "allowance_other"     => "nullable|numeric|min:0",
            "deduction_tax"       => "nullable|numeric|min:0",
            "deduction_nssf"      => "nullable|numeric|min:0",
            "deduction_loan"      => "nullable|numeric|min:0",
            "deduction_other"     => "nullable|numeric|min:0",
            "bank_name"           => "nullable|string|max:100",
            "bank_account"        => "nullable|string|max:50",
            "payment_method"      => "required|in:bank,cash,mobile_money,cheque",
            "effective_from"      => "required|date",
            "notes"               => "nullable|string",
        ]);

        $salaryStructure->update($data);

        return redirect()->route("admin.salary-structures.index")
            ->with("success","Salary structure updated.");
    }

    public function destroy(SalaryStructure $salaryStructure)
    {
        $salaryStructure->update(["is_active" => false]);
        return back()->with("success","Salary structure deactivated.");
    }
}