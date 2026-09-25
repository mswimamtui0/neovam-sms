@extends("layouts.app")
@section("title", "My Attendance")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-2">My Attendance</h1>
 <p class="text-gray-600 mb-6">{{ now()->format("l, d M Y") }}</p>

 {{-- Today Card --}}
 <div class="bg-white rounded shadow p-6 mb-6">
 <h2 class="text-xl font-bold text-blue-900 mb-4">Today</h2>

 @if($todayRow && $todayRow->check_in && !$todayRow->check_out)
 {{-- Currently checked in --}}
 <div class="grid grid-cols-3 gap-4 mb-4">
 <div class="bg-green-50 rounded p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Checked In</div>
 <div class="text-2xl font-bold text-green-900">{{ $todayRow->check_in }}</div>
 </div>
 <div class="bg-gray-50 rounded p-4 border-l-4 border-gray-400">
 <div class="text-xs text-gray-500">Checked Out</div>
 <div class="text-2xl font-bold text-gray-400">—</div>
 </div>
 <div class="bg-yellow-50 rounded p-4 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Status</div>
 <div class="text-2xl font-bold text-yellow-900">{{ ucfirst($todayRow->status) }}</div>
 </div>
 </div>

 <form method="POST" action="{{ route("shared.my-attendance.check-out") }}">
 @csrf
 <button class="bg-red-700 text-white px-6 py-3 rounded text-lg font-semibold">Check Out Now
 </button>
 </form>

 @elseif($todayRow && $todayRow->check_in && $todayRow->check_out)
 {{-- Already finished for today --}}
 <div class="grid grid-cols-4 gap-4">
 <div class="bg-green-50 rounded p-4">
 <div class="text-xs text-gray-500">Checked In</div>
 <div class="text-xl font-bold">{{ $todayRow->check_in }}</div>
 </div>
 <div class="bg-blue-50 rounded p-4">
 <div class="text-xs text-gray-500">Checked Out</div>
 <div class="text-xl font-bold">{{ $todayRow->check_out }}</div>
 </div>
 <div class="bg-purple-50 rounded p-4">
 <div class="text-xs text-gray-500">Hours</div>
 <div class="text-xl font-bold">{{ $todayRow->hours_worked }}h</div>
 </div>
 <div class="bg-yellow-50 rounded p-4">
 <div class="text-xs text-gray-500">Late</div>
 <div class="text-xl font-bold">{{ $todayRow->minutes_late }} min</div>
 </div>
 </div>
 <p class="text-green-700 mt-4 font-semibold">You have completed today's attendance.</p>

 @else
 {{-- Not yet checked in --}}
 <p class="text-gray-600 mb-4">You have not checked in yet today.</p>
 <form method="POST" action="{{ route("shared.my-attendance.check-in") }}">
 @csrf
 <button class="bg-green-700 text-white px-6 py-3 rounded text-lg font-semibold">Check In Now
 </button>
 </form>
 @endif
 </div>

 {{-- This Month Stats --}}
 <div class="grid grid-cols-5 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Present</div>
 <div class="text-2xl font-bold text-green-800">{{ $stats["present"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Late</div>
 <div class="text-2xl font-bold text-yellow-800">{{ $stats["late"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
 <div class="text-xs text-gray-500">Absent</div>
 <div class="text-2xl font-bold text-red-800">{{ $stats["absent"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">Total Hours</div>
 <div class="text-2xl font-bold text-blue-800">{{ $stats["total_hours"] }}h</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
 <div class="text-xs text-gray-500">Avg Hours</div>
 <div class="text-2xl font-bold text-purple-800">{{ $stats["avg_hours"] }}h</div>
 </div>
 </div>

 {{-- History --}}
 <h2 class="text-xl font-bold text-blue-900 mb-3">History</h2>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Check In</th>
 <th class="p-3 text-left">Check Out</th>
 <th class="p-3 text-left">Hours</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">Late</th>
 </tr>
 </thead>
 <tbody>
 @forelse($history as $h)
 <tr class="border-b">
 <td class="p-3">{{ $h->attendance_date->format("D, d M Y") }}</td>
 <td class="p-3">{{ $h->check_in ?? "—" }}</td>
 <td class="p-3">{{ $h->check_out ?? "—" }}</td>
 <td class="p-3">{{ $h->hours_worked }}h</td>
 <td class="p-3">
 <span class="text-xs px-2 py-0.5 rounded
 @if($h->status === "present") bg-green-100 text-green-800
 @elseif($h->status === "late") bg-yellow-100 text-yellow-800
 @elseif($h->status === "absent") bg-red-100 text-red-800
 @else bg-blue-100 text-blue-800 @endif">
 {{ ucfirst($h->status) }}
 </span>
 </td>
 <td class="p-3">{{ $h->minutes_late }} min</td>
 </tr>
 @empty
 <tr><td colspan="6" class="p-6 text-center text-gray-500">No records yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $history->links() }}</div>
@endsection