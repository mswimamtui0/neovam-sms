<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AttendanceExport;
use App\Exports\PaymentsExport;
use App\Exports\ResultsExport;
use App\Exports\SmsExport;
use App\Exports\StaffExport;
use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
 public function index()
 {
 $exams = Exam::latest()->take(20)->get();
 return view("admin.exports.index", compact("exams"));
 }

 /* ============ EXCEL ============ */
 public function studentsExcel(Request $request)
 {
 return Excel::download(
 new StudentsExport($request->query("level")),
 "students-" . date("Y-m-d") . ".xlsx"
 );
 }

 public function staffExcel()
 {
 return Excel::download(new StaffExport, "staff-" . date("Y-m-d") . ".xlsx");
 }

 public function paymentsExcel(Request $request)
 {
 $from = $request->filled("from") ? \Carbon\Carbon::parse($request->from) : null;
 $to = $request->filled("to") ? \Carbon\Carbon::parse($request->to) : null;

 return Excel::download(new PaymentsExport($from, $to), "payments-" . date("Y-m-d") . ".xlsx");
 }

 public function attendanceExcel(Request $request)
 {
 $from = $request->filled("from") ? \Carbon\Carbon::parse($request->from) : null;
 $to = $request->filled("to") ? \Carbon\Carbon::parse($request->to) : null;

 return Excel::download(new AttendanceExport($from, $to), "attendance-" . date("Y-m-d") . ".xlsx");
 }

 public function smsExcel(Request $request)
 {
 $from = $request->filled("from") ? \Carbon\Carbon::parse($request->from) : null;
 $to = $request->filled("to") ? \Carbon\Carbon::parse($request->to) : null;

 return Excel::download(new SmsExport($from, $to), "sms-" . date("Y-m-d") . ".xlsx");
 }

 public function resultsExcel(Request $request)
 {
 return Excel::download(
 new ResultsExport($request->query("exam_id")),
 "results-" . date("Y-m-d") . ".xlsx"
 );
 }

 /* ============ PDF ============ */
 public function studentsPdf(Request $request)
 {
 $students = \App\Models\Student::withoutGlobalScopes()
 ->with("classroom")
 ->when($request->query("level"), fn($q, $lvl) => $q->where("level", $lvl))
 ->orderBy("level")->orderBy("first_name")
 ->get();

 $school = \App\Models\School::first();

 $pdf = Pdf::loadView("admin.exports.students-pdf", compact("students","school"))
 ->setPaper("A4", "landscape");

 return $pdf->download("students-" . date("Y-m-d") . ".pdf");
 }

 public function staffPdf()
 {
 $staff = \App\Models\Staff::where("status","active")
 ->orderBy("department")->orderBy("first_name")->get();
 $school = \App\Models\School::first();

 $pdf = Pdf::loadView("admin.exports.staff-pdf", compact("staff","school"))
 ->setPaper("A4", "landscape");

 return $pdf->download("staff-" . date("Y-m-d") . ".pdf");
 }

 public function paymentsPdf(Request $request)
 {
 $from = $request->filled("from") ? \Carbon\Carbon::parse($request->from) : now()->startOfMonth();
 $to = $request->filled("to") ? \Carbon\Carbon::parse($request->to) : now()->endOfMonth();

 $payments = \App\Models\Payment::with(["student","invoice"])
 ->whereBetween("payment_date", [$from, $to])
 ->orderByDesc("payment_date")->get();

 $total = $payments->sum("amount");
 $school = \App\Models\School::first();

 $pdf = Pdf::loadView("admin.exports.payments-pdf", compact("payments","school","from","to","total"))
 ->setPaper("A4", "landscape");

 return $pdf->download("payments-" . $from->format("Y-m-d") . "-to-" . $to->format("Y-m-d") . ".pdf");
 }

 public function attendancePdf(Request $request)
 {
 $from = $request->filled("from") ? \Carbon\Carbon::parse($request->from) : now()->startOfMonth();
 $to = $request->filled("to") ? \Carbon\Carbon::parse($request->to) : now()->endOfMonth();

 $records = \App\Models\Attendance::with(["student","classroom"])
 ->whereBetween("date", [$from, $to])
 ->orderByDesc("date")->get();

 $school = \App\Models\School::first();

 $pdf = Pdf::loadView("admin.exports.attendance-pdf", compact("records","school","from","to"))
 ->setPaper("A4", "landscape");

 return $pdf->download("attendance-" . $from->format("Y-m-d") . "-to-" . $to->format("Y-m-d") . ".pdf");
 }

 public function smsPdf(Request $request)
 {
 $from = $request->filled("from") ? \Carbon\Carbon::parse($request->from) : now()->startOfMonth();
 $to = $request->filled("to") ? \Carbon\Carbon::parse($request->to) : now()->endOfMonth();

 $logs = \App\Models\SmsLog::whereBetween("created_at", [$from, $to])
 ->orderByDesc("created_at")->get();

 $school = \App\Models\School::first();

 $pdf = Pdf::loadView("admin.exports.sms-pdf", compact("logs","school","from","to"))
 ->setPaper("A4", "landscape");

 return $pdf->download("sms-" . $from->format("Y-m-d") . "-to-" . $to->format("Y-m-d") . ".pdf");
 }
}