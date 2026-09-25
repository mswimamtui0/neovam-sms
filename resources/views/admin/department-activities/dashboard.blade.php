@extends("layouts.app")
@section("title", "Department Activity Dashboard")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Department Activity Dashboard</h1>
 <a href="{{ route("admin.department-activities.index") }}" class="bg-blue-900 text-white px-4 py-2 rounded">All Activities</a>
 </div>

 <div class="bg-white rounded shadow overflow-hidden mb-6">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Department</th>
 <th class="p-3 text-center">Total</th>
 <th class="p-3 text-center">Pending</th>
 <th class="p-3 text-center">In Progress</th>
 <th class="p-3 text-center">Completed</th>
 <th class="p-3 text-center">Urgent</th>
 </tr>
 </thead>
 <tbody>
 @foreach($byDepartment as $dept => $stats)
 <tr class="border-b hover:bg-gray-50">
 <td class="p-3 font-semibold">{{ $dept }}</td>
 <td class="p-3 text-center">{{ $stats["total"] }}</td>
 <td class="p-3 text-center text-yellow-700 font-bold">{{ $stats["pending"] }}</td>
 <td class="p-3 text-center text-blue-700 font-bold">{{ $stats["in_progress"] }}</td>
 <td class="p-3 text-center text-green-700 font-bold">{{ $stats["completed"] }}</td>
 <td class="p-3 text-center text-red-700 font-bold">{{ $stats["urgent"] }}</td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>

 <h2 class="text-xl font-bold text-blue-900 mb-3">Recent Activities</h2>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Department</th>
 <th class="p-3 text-left">Title</th>
 <th class="p-3 text-left">Status</th>
 </tr>
 </thead>
 <tbody>
 @forelse($recent as $r)
 <tr class="border-b">
 <td class="p-3">{{ $r->activity_date->format("d M Y") }}</td>
 <td class="p-3">{{ $r->department }}</td>
 <td class="p-3">{{ $r->title }}</td>
 <td class="p-3">{{ ucfirst(str_replace("_"," ",$r->status)) }}</td>
 </tr>
 @empty
 <tr><td colspan="4" class="p-6 text-center text-gray-500">No activities yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
@endsection