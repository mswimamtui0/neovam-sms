<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\Invoice;
use App\Models\School;
use App\Models\Student;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with("student")->latest();

        if ($request->filled("status")) {
            $query->where("status", $request->status);
        }

        $invoices = $query->paginate(30);

        $totals = [
            "total"  => Invoice::sum("amount"),
            "paid"   => Invoice::sum("amount_paid"),
            "unpaid" => Invoice::sum("balance"),
        ];

        return view("admin.fees.invoices.index", compact("invoices","totals"));
    }

    public function create()
    {
        $students = Student::where("status","active")->with("classroom")->get();
        return view("admin.fees.invoices.create", compact("students"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "student_id" => "required|exists:students,id",
            "term"       => "required|string|max:50",
            "year"       => "required|integer",
            "amount"     => "required|numeric|min:0",
            "due_date"   => "nullable|date",
        ]);

        $data["school_id"]  = School::first()?->id;
        $data["invoice_no"] = "INV-" . strtoupper(uniqid());
        $data["balance"]    = $data["amount"];
        $data["status"]     = "pending";

        Invoice::create($data);

        return redirect()->route("admin.invoices.index")->with("success","Invoice created.");
    }

    /**
     * Bulk generate invoices from fee structure for a term/year.
     */
    public function generate(Request $request)
    {
        $data = $request->validate([
            "level"    => "required|string",
            "term"     => "required|string",
            "year"     => "required|integer",
            "due_date" => "nullable|date",
        ]);

        $fee = FeeStructure::where("level", $data["level"])
            ->where("term", $data["term"])
            ->where("year", $data["year"])
            ->first();

        if (!$fee) {
            return back()->with("error","No fee structure found for that level / term / year.");
        }

        $students = Student::where("status","active")
            ->where("level", $data["level"])
            ->get();

        $created = 0;
        foreach ($students as $student) {
            $existing = Invoice::where("student_id", $student->id)
                ->where("term", $data["term"])
                ->where("year", $data["year"])
                ->first();

            if ($existing) continue;

            Invoice::create([
                "school_id"  => $student->school_id,
                "student_id" => $student->id,
                "invoice_no" => "INV-" . strtoupper(uniqid()),
                "term"       => $data["term"],
                "year"       => $data["year"],
                "amount"     => $fee->total(),
                "balance"    => $fee->total(),
                "due_date"   => $data["due_date"] ?? null,
                "status"     => "pending",
            ]);
            $created++;
        }

        return back()->with("success","Generated {$created} invoices.");
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(["student","payments"]);
        return view("admin.fees.invoices.show", compact("invoice"));
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return back()->with("success","Invoice deleted.");
    }
}