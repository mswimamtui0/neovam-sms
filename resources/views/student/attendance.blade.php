@extends("layouts.app")
@section("title", "My Attendance")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">My Attendance</h1>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">Recorded By</th>
 </tr>
 </thead>
 <tbody>
 @forelse($student->attendances as $a)
 <tr class="border-b">
 <td class="p-3">{{ $a->date->format("Y-m-d") }}</td>
 <td class="p-3">
 <span class="{{ $a->status === "absent" ? "text-red-600" : "text-green-600" }}">
 {{ ucfirst($a->status) }}
 </span>
 </td>
 <td class="p-3">{{ $a->recorded_by }}</td>
 </tr>
 @empty
 <tr><td colspan="3" class="p-6 text-center text-gray-500">No attendance records.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
@endsection