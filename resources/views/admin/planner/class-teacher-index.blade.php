@extends("layouts.app")
@section("title", "Class Teacher Roster")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Class Teacher Roster — {{ $year }}</h1>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <input type="number" name="year" value="{{ $year }}" class="border rounded px-3 py-2 w-32">
 <button class="bg-blue-900 text-white px-4 py-2 rounded">View Year</button>
 </form>

 <div class="bg-white rounded shadow p-6 mb-6">
 <h2 class="font-bold text-blue-900 mb-3">Assign Class Teacher</h2>
 <form method="POST" action="{{ route("admin.planner.class-teachers.store") }}" class="grid grid-cols-6 gap-3">
 @csrf
 <select name="classroom_id" class="border rounded px-3 py-2 col-span-2" required>
 <option value="">-- Class --</option>
 @foreach($classrooms as $c)
 <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->level }})</option>
 @endforeach
 </select>
 <select name="staff_id" class="border rounded px-3 py-2 col-span-2" required>
 <option value="">-- Teacher --</option>
 @foreach($staffList as $s)
 <option value="{{ $s->id }}">{{ $s->full_name }}</option>
 @endforeach
 </select>
 <input type="number" name="year" value="{{ $year }}" class="border rounded px-3 py-2" required>
 <input type="date" name="start_date" value="{{ now()->startOfYear()->toDateString() }}" class="border rounded px-3 py-2" required>
 <button type="submit" class="bg-blue-900 text-white rounded px-4 py-2 col-span-6">Assign</button>
 </form>
 </div>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Class</th>
 <th class="p-3 text-left">Class Teacher</th>
 <th class="p-3 text-left">Term</th>
 <th class="p-3 text-left">Start</th>
 <th class="p-3 text-left">End</th>
 <th class="p-3 text-left">Active</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($rosters as $r)
 <tr class="border-b">
 <td class="p-3 font-semibold">{{ $r->classroom?->name }}</td>
 <td class="p-3">{{ $r->staff?->full_name }}</td>
 <td class="p-3">{{ $r->term ?? "Full Year" }}</td>
 <td class="p-3">{{ $r->start_date?->format("d M Y") }}</td>
 <td class="p-3">{{ $r->end_date?->format("d M Y") ?? "-" }}</td>
 <td class="p-3">
 @if($r->is_active)
 <span class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-800">Active</span>
 @else
 <span class="text-xs px-2 py-0.5 rounded bg-gray-200 text-gray-600">Inactive</span>
 @endif
 </td>
 <td class="p-3">
 @if($r->is_active)
 <form method="POST" action="{{ route("admin.planner.class-teachers.destroy", $r) }}" class="inline" onsubmit="return confirm(&quot;Deactivate?&quot;);">
 @csrf @method("DELETE")
 <button class="text-red-700 hover:underline">Deactivate</button>
 </form>
 @endif
 </td>
 </tr>
 @empty
 <tr><td colspan="7" class="p-6 text-center text-gray-500">No rosters yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $rosters->links() }}</div>
@endsection