@extends("layouts.app")
@section("title", "Mark Attendance")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Mark Attendance</h1>
 <form method="POST" action="{{ route("teacher.attendance.mark") }}" class="bg-white rounded shadow p-6">
 @csrf
 <div class="mb-4">
 <label class="block font-semibold mb-1">Date</label>
 <input type="date" name="date" value="{{ now()->toDateString() }}" class="border rounded px-3 py-2" required>
 </div>
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Student</th>
 <th class="p-3 text-left">Class</th>
 <th class="p-3 text-left">Status</th>
 </tr>
 </thead>
 <tbody>
 @forelse(\App\Models\Student::with("classroom")->get() as $student)
 <tr class="border-b">
 <td class="p-3">{{ $student->full_name }}</td>
 <td class="p-3">{{ $student->classroom?->name ?? "-" }}</td>
 <td class="p-3">
 <select name="statuses[{{ $student->id }}]" class="border rounded px-2 py-1">
 <option value="present">Present</option>
 <option value="absent">Absent</option>
 <option value="late">Late</option>
 </select>
 </td>
 </tr>
 @empty
 <tr><td colspan="3" class="p-6 text-center text-gray-500">No students.</td></tr>
 @endforelse
 </tbody>
 </table>
 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded mt-4">Save Attendance</button>
 </form>
@endsection