@extends("layouts.app")
@section("title", "Yearly Duty Calendars")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Yearly Duty Calendars</h1>
 <a href="{{ route("admin.planner.duty.create", ["year" => $year]) }}" class="bg-blue-900 text-white px-4 py-2 rounded">New Calendar</a>
 </div>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <input type="number" name="year" value="{{ $year }}" class="border rounded px-3 py-2 w-32">
 <button class="bg-blue-900 text-white px-4 py-2 rounded">View Year</button>
 </form>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Year</th>
 <th class="p-3 text-left">Title</th>
 <th class="p-3 text-right">Assignments</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($calendars as $c)
 <tr class="border-b">
 <td class="p-3 font-bold">{{ $c->year }}</td>
 <td class="p-3">{{ $c->title }}</td>
 <td class="p-3 text-right">{{ $c->assignments()->count() }}</td>
 <td class="p-3">
 <span class="text-xs px-2 py-0.5 rounded
 @if($c->status === "active") bg-green-100 text-green-800
 @elseif($c->status === "archived") bg-gray-200 text-gray-800
 @else bg-yellow-100 text-yellow-800 @endif">
 {{ ucfirst($c->status) }}
 </span>
 </td>
 <td class="p-3">
 <a href="{{ route("admin.planner.duty.show", $c) }}" class="text-blue-700 hover:underline">Open</a>
 </td>
 </tr>
 @empty
 <tr><td colspan="5" class="p-6 text-center text-gray-500">No calendars yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $calendars->links() }}</div>
@endsection