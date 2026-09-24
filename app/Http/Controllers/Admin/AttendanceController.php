<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendAbsenceSms;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
 public function index(Request $request)
 {
 $date = $request->date ?? now()->toDateString();
 $attendances = Attendance::with('student')
 ->whereDate('date', $date)
 ->paginate(50);

 return view('admin.attendance.index', compact('attendances', 'date'));
 }

 public function create()
 {
 $students = Student::where('status', 'active')->get();
 return view('admin.attendance.create', compact('students'));
 }

 public function store(Request $request)
 {
 $data = $request->validate([
 'date' => 'required|date',
 'statuses' => 'required|array',
 'statuses.*' => 'in:present,absent,late',
 ]);

 $recordedBy = auth()->user()->name ?? 'System';

 foreach ($data['statuses'] as $studentId => $status) {
 $student = Student::find($studentId);
 if (!$student) continue;

 $attendance = Attendance::updateOrCreate(
 [
 'student_id' => $student->id,
 'date' => $data['date'],
 ],
 [
 'classroom_id' => $student->classroom_id,
 'status' => $status,
 'recorded_by' => $recordedBy,
 'sms_sent' => false,
 ]
 );

 // AUTO SMS ON ABSENCE
 if ($status === 'absent' && !$attendance->sms_sent) {
 SendAbsenceSms::dispatch(
 $student->parent_phone,
 $student->full_name,
 $data['date']
 );
 $attendance->update(['sms_sent' => true]);
 }
 }

 return redirect()->route('admin.attendance.index')
 ->with('success', 'Attendance recorded. SMS sent for absentees.');
 }
}