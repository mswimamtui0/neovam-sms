@extends("layouts.app")
@section("title", "Staff Attendance")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Staff Attendance — {{ $date }}</h1>
 <div class="flex gap-2">
 <a href="{{ route("admin.staff-attendance.missing") }}" class="bg-yellow-700 text-white px-4 py-2 rounded">Missing Today</a>
 <a href="{{ route("admin.staff-attendance.report") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Monthly Report</a>
 </div>
 </div>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <input type="date" name="date" value="{{ $date }}" class="border rounded px-3 py-2">
 <button class="bg-blue-900 text-white px-4 py-2 rounded">View</button>
 </form>

 <div class="grid grid-cols-4 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">Total Staff</div>
 <div class="text-2xl font-bold">{{ $totalStaff }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Present</div>
 <div class="text-2xl font-bold text-green-800">{{ $presentToday }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Late</div>
 <div class="text-2xl font-bold text-yellow-800">{{ $lateToday }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
 <div class="text-xs text-gray-500">Not Recorded</div>
 <div class="text-2xl font-bold text-red-800">{{ $absentToday }}</div>
 </div>
 </div>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Staff</th>
 <th class="p-3 text-left">Check In</th>
 <th class="p-3 text-left">Check Out</th>
 <th class="p-3 text-left">Hours</th>
 <th class="p-3 text-left">Late</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($records as $r)
 <tr class="border-b">
 <td class="p-3 font-semibold">{{ $r->staff?->full_name ?? "-" }}</td>
 <td class="p-3">{{ $r->check_in ?? "—" }}</td>
 <td class="p-3">{{ $r->check_out ?? "—" }}</td>
 <td class="p-3">{{ $r->hours_worked }}h</td>
 <td class="p-3">{{ $r->minutes_late }}m</td>
 <td class="p-3">
 <span class="text-xs px-2 py-0.5 rounded
 @if($r->status === "present") bg-green-100 text-green-800
 @elseif($r->status === "late") bg-yellow-100 text-yellow-800
 @elseif($r->status === "absent") bg-red-100 text-red-800
 @else bg-blue-100 text-blue-800 @endif">
 {{ ucfirst($r->status) }}
 </span>
 </td>
 <td class="p-3">
 <a href="{{ route("admin.staff-attendance.edit", $r) }}" class="text-yellow-700 hover:underline">Edit</a>
 </td>
 </tr>
 @empty
 <tr><td colspan="7" class="p-6 text-center text-gray-500">No records for this date.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $records->links() }}</div>
@endsection