@extends("layouts.app")
@section("title", "Timetable")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Timetable</h1>
 <div class="flex gap-2">
 <a href="{{ route("academic.timetable.grid") }}" class="bg-gray-700 text-white px-4 py-2 rounded">Grid View</a>
 <a href="{{ route("academic.timetable.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Entry</a>
 </div>
 </div>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <select name="staff_id" class="border rounded px-3 py-2">
 <option value="">All Teachers</option>
 @foreach($teachers as $t)
 <option value="{{ $t->id }}" {{ request("staff_id") == $t->id ? "selected" : "" }}>{{ $t->full_name }}</option>
 @endforeach
 </select>
 <select name="classroom_id" class="border rounded px-3 py-2">
 <option value="">All Classes</option>
 @foreach($classrooms as $c)
 <option value="{{ $c->id }}" {{ request("classroom_id") == $c->id ? "selected" : "" }}>{{ $c->name }}</option>
 @endforeach
 </select>
 <select name="day_of_week" class="border rounded px-3 py-2">
 <option value="">All Days</option>
 @foreach($days as $d)
 <option value="{{ $d }}" {{ request("day_of_week") === $d ? "selected" : "" }}>{{ $d }}</option>
 @endforeach
 </select>
 <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
 <a href="{{ route("academic.timetable") }}" class="text-gray-600 px-4 py-2">Reset</a>
 </form>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Day</th>
 <th class="p-3 text-left">Time</th>
 <th class="p-3 text-left">Period</th>
 <th class="p-3 text-left">Teacher</th>
 <th class="p-3 text-left">Class</th>
 <th class="p-3 text-left">Subject</th>
 <th class="p-3 text-left">Room</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($entries as $e)
 <tr class="border-b hover:bg-gray-50">
 <td class="p-3">{{ $e->day_of_week }}</td>
 <td class="p-3">{{ $e->start_time }} – {{ $e->end_time }}</td>
 <td class="p-3">{{ $e->period_label ?? "-" }}</td>
 <td class="p-3">{{ $e->staff?->full_name }}</td>
 <td class="p-3">{{ $e->classroom?->name }}</td>
 <td class="p-3">{{ $e->subject?->name ?? "-" }}</td>
 <td class="p-3">{{ $e->room ?? "-" }}</td>
 <td class="p-3">
 <a href="{{ route("academic.timetable.edit", $e) }}" class="text-yellow-700 hover:underline">Edit</a>
 <form method="POST" action="{{ route("academic.timetable.destroy", $e) }}" class="inline" onsubmit="return confirm(&quot;Delete?&quot;);">
 @csrf @method("DELETE")
 <button class="text-red-700 hover:underline ml-2">Delete</button>
 </form>
 </td>
 </tr>
 @empty
 <tr><td colspan="8" class="p-6 text-center text-gray-500">No timetable entries yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $entries->links() }}</div>
@endsection