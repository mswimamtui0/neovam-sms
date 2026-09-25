<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendTransferSms;
use App\Models\Staff;
use App\Models\StaffTransfer;
use Illuminate\Http\Request;

class StaffTransferController extends Controller
{
 public function index(Request $request)
 {
 $query = StaffTransfer::with(["staff","requester","approver"])->latest();

 if ($request->filled("status")) {
 $query->where("status", $request->status);
 }
 if ($request->filled("type")) {
 $query->where("transfer_type", $request->type);
 }

 $transfers = $query->paginate(30);

 $counts = [
 "pending" => StaffTransfer::where("status","pending")->count(),
 "approved" => StaffTransfer::where("status","approved")->count(),
 "rejected" => StaffTransfer::where("status","rejected")->count(),
 "completed" => StaffTransfer::where("status","completed")->count(),
 ];

 return view("admin.staff-transfers.index", compact("transfers","counts"));
 }

 public function create(Request $request)
 {
 $staffList = Staff::where("status","active")->orderBy("first_name")->get();
 $preselect = $request->query("staff_id");

 return view("admin.staff-transfers.create", compact("staffList","preselect"));
 }

 public function store(Request $request)
 {
 $data = $request->validate([
 "staff_id" => "required|exists:staff,id",
 "transfer_type" => "required|in:transfer_in,transfer_out,internal_move,promotion,demotion",
 "from_position" => "nullable|string|max:100",
 "to_position" => "nullable|string|max:100",
 "from_department" => "nullable|string|max:100",
 "to_department" => "nullable|string|max:100",
 "from_school" => "nullable|string|max:150",
 "to_school" => "nullable|string|max:150",
 "effective_date" => "required|date",
 "reason" => "nullable|string",
 "notes" => "nullable|string",
 ]);

 $data["status"] = "pending";
 $data["requested_by"] = auth()->id();

 // Auto-fill from_position / from_department if blank
 $staff = Staff::find($data["staff_id"]);
 if (!$data["from_position"]) $data["from_position"] = $staff->role_title;
 if (!$data["from_department"]) $data["from_department"] = $staff->department;

 StaffTransfer::create($data);

 return redirect()->route("admin.staff-transfers.index")
 ->with("success","Transfer request created. Pending approval.");
 }

 public function show(StaffTransfer $transfer)
 {
 $transfer->load(["staff","requester","approver"]);
 return view("admin.staff-transfers.show", compact("transfer"));
 }

 public function edit(StaffTransfer $transfer)
 {
 if ($transfer->status !== "pending") {
 return back()->with("error", "Only pending transfers can be edited.");
 }

 $staffList = Staff::where("status","active")->orderBy("first_name")->get();
 return view("admin.staff-transfers.edit", compact("transfer","staffList"));
 }

 public function update(Request $request, StaffTransfer $transfer)
 {
 if ($transfer->status !== "pending") {
 return back()->with("error","Only pending transfers can be edited.");
 }

 $data = $request->validate([
 "transfer_type" => "required|in:transfer_in,transfer_out,internal_move,promotion,demotion",
 "from_position" => "nullable|string|max:100",
 "to_position" => "nullable|string|max:100",
 "from_department" => "nullable|string|max:100",
 "to_department" => "nullable|string|max:100",
 "from_school" => "nullable|string|max:150",
 "to_school" => "nullable|string|max:150",
 "effective_date" => "required|date",
 "reason" => "nullable|string",
 "notes" => "nullable|string",
 ]);

 $transfer->update($data);

 return redirect()->route("admin.staff-transfers.index")->with("success","Transfer updated.");
 }

 public function destroy(StaffTransfer $transfer)
 {
 if ($transfer->status === "approved") {
 return back()->with("error","Cannot delete an approved transfer.");
 }

 $transfer->delete();
 return back()->with("success","Transfer removed.");
 }

 /**
 * Approve a pending transfer.
 */
 public function approve(Request $request, StaffTransfer $transfer)
 {
 if ($transfer->status !== "pending") {
 return back()->with("error","Only pending transfers can be approved.");
 }

 $data = $request->validate([
 "approval_notes" => "nullable|string|max:500",
 "send_sms" => "nullable|boolean",
 ]);

 $transfer->approve($data["approval_notes"] ?? null);

 // SMS to staff
 if ($request->boolean("send_sms")) {
 $staff = $transfer->staff;
 if ($staff && $staff->phone) {
 SendTransferSms::dispatch(
 $staff->phone,
 $staff->full_name,
 $transfer->typeLabel(),
 $transfer->effective_date->format("d M Y"),
 $transfer->from_position,
 $transfer->to_position,
 );
 }
 }

 return back()->with("success","Transfer approved.");
 }

 /**
 * Reject a pending transfer.
 */
 public function reject(Request $request, StaffTransfer $transfer)
 {
 if ($transfer->status !== "pending") {
 return back()->with("error","Only pending transfers can be rejected.");
 }

 $data = $request->validate([
 "approval_notes" => "required|string|max:500",
 ]);

 $transfer->reject($data["approval_notes"]);

 return back()->with("success","Transfer rejected.");
 }
}