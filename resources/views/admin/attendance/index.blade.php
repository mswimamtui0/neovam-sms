@extends("layouts.app")
@section("title", "Attendance")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Attendance</h1>
 <a href="{{ route("admin.attendance.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">+ Mark Attendance</a>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Student</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">SMS Sent</th>
 <th class="p-3 text-left">Recorded By</th>
 </tr>
 </thead>
 <tbody>
 @forelse($attendances as $a)
 <tr class="border-b hover:bg-gray-50">
 <td class="p-3">{{ $a->date->format("Y-m-d") }}</td>
 <td class="p-3">{{ $a->student?->full_name ?? "-" }}</td>
 <td class="p-3">
 <span class="px-2 py-1 rounded text-xs
 @if($a->status === "present") bg-green-100 text-green-800
 @elseif($a->status === "absent") bg-red-100 text-red-800
 @else bg-yellow-100 text-yellow-800 @endif">
 {{ ucfirst($a->status) }}
 </span>
 </td>
 <td class="p-3">{{ $a->sms_sent ? "Yes" : "No" }}</td>
 <td class="p-3">{{ $a->recorded_by }}</td>
 </tr>
 @empty
 <tr><td colspan="5" class="p-6 text-center text-gray-500">No attendance records.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $attendances->links() }}</div>
@endsection