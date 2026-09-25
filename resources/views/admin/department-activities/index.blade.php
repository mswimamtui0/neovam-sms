@extends("layouts.app")
@section("title", "Department Activities")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Department Activities</h1>
 <div class="flex gap-2">
 <a href="{{ route("admin.department-activities.dashboard") }}" class="bg-gray-700 text-white px-4 py-2 rounded">Dashboard</a>
 <a href="{{ route("admin.department-activities.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Log Activity</a>
 </div>
 </div>

 <div class="grid grid-cols-5 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">Total</div>
 <div class="text-2xl font-bold text-blue-800">{{ $counts["total"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Pending</div>
 <div class="text-2xl font-bold text-yellow-800">{{ $counts["pending"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">In Progress</div>
 <div class="text-2xl font-bold text-blue-800">{{ $counts["in_progress"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Completed</div>
 <div class="text-2xl font-bold text-green-800">{{ $counts["completed"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
 <div class="text-xs text-gray-500">Urgent</div>
 <div class="text-2xl font-bold text-red-800">{{ $counts["urgent"] }}</div>
 </div>
 </div>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex flex-wrap gap-3">
 <select name="department" class="border rounded px-3 py-2">
 <option value="">All Departments</option>
 @foreach($departments as $d)
 <option value="{{ $d }}" {{ request("department") === $d ? "selected" : "" }}>{{ $d }}</option>
 @endforeach
 </select>
 <select name="status" class="border rounded px-3 py-2">
 <option value="">All Statuses</option>
 @foreach(["pending","in_progress","completed","cancelled"] as $s)
 <option value="{{ $s }}" {{ request("status") === $s ? "selected" : "" }}>{{ ucfirst(str_replace("_"," ",$s)) }}</option>
 @endforeach
 </select>
 <select name="priority" class="border rounded px-3 py-2">
 <option value="">All Priorities</option>
 @foreach(["low","normal","high","urgent"] as $p)
 <option value="{{ $p }}" {{ request("priority") === $p ? "selected" : "" }}>{{ ucfirst($p) }}</option>
 @endforeach
 </select>
 <input type="date" name="from" value="{{ request("from") }}" class="border rounded px-3 py-2">
 <input type="date" name="to" value="{{ request("to") }}" class="border rounded px-3 py-2">
 <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
 <a href="{{ route("admin.department-activities.index") }}" class="text-gray-600 px-4 py-2">Reset</a>
 </form>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Department</th>
 <th class="p-3 text-left">Title</th>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Priority</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">By</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($activities as $a)
 <tr class="border-b hover:bg-gray-50">
 <td class="p-3 whitespace-nowrap">{{ $a->activity_date->format("d M Y") }}</td>
 <td class="p-3 font-semibold">{{ $a->department }}</td>
 <td class="p-3">{{ $a->title }}</td>
 <td class="p-3">{{ ucfirst($a->activity_type) }}</td>
 <td class="p-3">
 <span class="text-xs px-2 py-0.5 rounded
 @if($a->priority === "urgent") bg-red-100 text-red-800
 @elseif($a->priority === "high") bg-orange-100 text-orange-800
 @elseif($a->priority === "low") bg-gray-100 text-gray-800
 @else bg-blue-100 text-blue-800 @endif">
 {{ ucfirst($a->priority) }}
 </span>
 </td>
 <td class="p-3">
 <span class="text-xs px-2 py-0.5 rounded
 @if($a->status === "completed") bg-green-100 text-green-800
 @elseif($a->status === "in_progress") bg-blue-100 text-blue-800
 @elseif($a->status === "cancelled") bg-red-100 text-red-800
 @else bg-yellow-100 text-yellow-800 @endif">
 {{ ucfirst(str_replace("_"," ",$a->status)) }}
 </span>
 </td>
 <td class="p-3 text-xs">{{ $a->staff?->full_name ?? "-" }}</td>
 <td class="p-3">
 <a href="{{ route("admin.department-activities.show", $a) }}" class="text-blue-700 hover:underline">View</a>
 <a href="{{ route("admin.department-activities.edit", $a) }}" class="text-yellow-700 hover:underline ml-2">Edit</a>
 </td>
 </tr>
 @empty
 <tr><td colspan="8" class="p-6 text-center text-gray-500">No activities yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $activities->links() }}</div>
@endsection