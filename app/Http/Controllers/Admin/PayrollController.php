<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendSalarySms;
use App\Models\PayrollItem;
use App\Models\PayrollRun;
use App\Services\Finance\PayrollService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
 public function index()
 {
 $runs = PayrollRun::with(["creator","approver"])->latest()->paginate(20);
 $stats = PayrollService::stats();

 return view("admin.payroll.runs.index", compact("runs","stats"));
 }

 public function create()
 {
 return view("admin.payroll.runs.create");
 }

 public function store(Request $request)
 {
 $data = $request->validate([
 "period" => "required|string|max:10",
 "notes" => "nullable|string",
 ]);

 $run = PayrollService::generate($data["period"], $data["notes"] ?? null);

 return redirect()->route("admin.payroll-runs.show", $run)
 ->with("success","Payroll run generated with {$run->staff_count} items.");
 }

 public function show(PayrollRun $run)
 {
 $run->load(["items.staff","creator","approver"]);
 return view("admin.payroll.runs.show", compact("run"));
 }

 public function approve(PayrollRun $run)
 {
 if ($run->status !== "draft") {
 return back()->with("error","Only draft runs can be approved.");
 }

 PayrollService::approve($run);

 return back()->with("success","Payroll approved.");
 }

 public function markPaid(Request $request, PayrollRun $run)
 {
 if (!in_array($run->status, ["draft","approved"])) {
 return back()->with("error","This run cannot be marked as paid.");
 }

 PayrollService::markPaid($run);

 // Send SMS to each staff
 if ($request->boolean("send_sms")) {
 $sent = 0;
 foreach ($run->items as $item) {
 $staff = $item->staff;
 if (!$staff || !$staff->phone) continue;

 SendSalarySms::dispatch(
 $staff->phone,
 $staff->full_name,
 $run->label,
 (float) $item->net_pay,
 );
 $sent++;
 }
 }

 return back()->with("success","Payroll marked as paid." . (isset($sent) ? " {$sent} SMS sent." : ""));
 }

 public function cancel(PayrollRun $run)
 {
 if ($run->status === "paid") {
 return back()->with("error","Paid runs cannot be cancelled.");
 }

 $run->update(["status" => "cancelled"]);

 return back()->with("success","Payroll cancelled.");
 }

 public function destroy(PayrollRun $run)
 {
 if ($run->status === "paid") {
 return back()->with("error","Paid runs cannot be deleted.");
 }

 $run->items()->delete();
 $run->delete();

 return redirect()->route("admin.payroll-runs.index")->with("success","Payroll run deleted.");
 }

 /**
 * Mark a single staff payment as paid.
 */
 public function payItem(Request $request, PayrollItem $item)
 {
 $data = $request->validate([
 "payment_reference" => "nullable|string|max:100",
 ]);

 PayrollService::markItemPaid($item, $data["payment_reference"] ?? null);

 return back()->with("success","Payment recorded for {$item->staff->full_name}.");
 }
}