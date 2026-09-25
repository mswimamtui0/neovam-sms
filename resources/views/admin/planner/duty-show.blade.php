@extends("layouts.app")
@section("title", "Duty — " . $calendar->title)
@section("content")
 <div class="flex justify-between items-center mb-6">
 <div>
 <h1 class="text-3xl font-bold text-blue-900">{{ $calendar->title }}</h1>
 <p class="text-gray-600">Year {{ $calendar->year }} — Status: {{ ucfirst($calendar->status) }}</p>
 </div>
 <div class="flex gap-2">
 @if($calendar->status === "draft")
 <form method="POST" action="{{ route("admin.planner.duty.activate", $calendar) }}" class="inline">
 @csrf
 <button class="bg-green-700 text-white px-4 py-2 rounded">Activate</button>
 </form>
 @endif
 <a href="{{ route("admin.planner.duty.index") }}" class="text-blue-700 px-4 py-2 hover:underline">Back</a>
 </div>
 </div>

 <div class="grid grid-cols-2 gap-6 mb-6">

 {{-- Single assignment --}}
 <div class="bg-white rounded shadow p-6">
 <h2 class="font-bold text-blue-900 mb-3">Add Single Duty</h2>
 <form method="POST" action="{{ route("admin.planner.duty.assign", $calendar) }}" class="space-y-3">
 @csrf
 <select name="staff_id" class="w-full border rounded px-3 py-2" required>
 <option value="">-- Teacher --</option>
 @foreach($staffList as $s)
 <option value="{{ $s->id }}">{{ $s->full_name }}</option>
 @endforeach
 </select>
 <input type="date" name="duty_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
 <div class="grid grid-cols-2 gap-2">
 <select name="duty_type" class="w-full border rounded px-3 py-2" required>
 @foreach(["general","gate","assembly","break","lunch","evening","night","exam"] as $t)
 <option value="{{ $t }}">{{ ucfirst($t) }}</option>
 @endforeach
 </select>
 <select name="shift" class="w-full border rounded px-3 py-2" required>
 <option value="day">Day</option>
 <option value="evening">Evening</option>
 <option value="night">Night</option>
 </select>
 </div>
 <input type="text" name="location" placeholder="Location" class="w-full border rounded px-3 py-2">
 <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded w-full">Add Duty</button>
 </form>
 </div>

 {{-- Bulk assignment --}}
 <div class="bg-white rounded shadow p-6">
 <h2 class="font-bold text-blue-900 mb-3">Bulk Assign Same Teacher</h2>
 <form method="POST" action="{{ route("admin.planner.duty.bulk", $calendar) }}" class="space-y-3">
 @csrf
 <select name="staff_id" class="w-full border rounded px-3 py-2" required>
 <option value="">-- Teacher --</option>
 @foreach($staffList as $s)
 <option value="{{ $s->id }}">{{ $s->full_name }}</option>
 @endforeach
 </select>
 <textarea name="dates" rows="3" placeholder="2026-10-01, 2026-10-08, 2026-10-15" class="w-full border rounded px-3 py-2" required></textarea>
 <div class="grid grid-cols-2 gap-2">
 <select name="duty_type" class="w-full border rounded px-3 py-2" required>
 @foreach(["general","gate","assembly","break","lunch","evening","night","exam"] as $t)
 <option value="{{ $t }}">{{ ucfirst($t) }}</option>
 @endforeach
 </select>
 <select name="shift" class="w-full border rounded px-3 py-2" required>
 <option value="day">Day</option>
 <option value="evening">Evening</option>
 <option value="night">Night</option>
 </select>
 </div>
 <input type="text" name="location" placeholder="Location" class="w-full border rounded px-3 py-2">
 <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded w-full">Bulk Add</button>
 </form>
 </div>
 </div>

 <h2 class="text-xl font-bold text-blue-900 mb-3">Assignments ({{ $assignments->total() }})</h2>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Teacher</th>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Shift</th>
 <th class="p-3 text-left">Location</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($assignments as $a)
 <tr class="border-b">
 <td class="p-3">{{ $a->duty_date?->format("D, d M Y") }}</td>
 <td class="p-3 font-semibold">{{ $a->staff?->full_name }}</td>
 <td class="p-3">{{ ucfirst($a->duty_type) }}</td>
 <td class="p-3">{{ ucfirst($a->shift) }}</td>
 <td class="p-3">{{ $a->location ?? "-" }}</td>
 <td class="p-3">
 <form method="POST" action="{{ route("admin.planner.duty.destroy", $a) }}" class="inline" onsubmit="return confirm(&quot;Remove?&quot;);">
 @csrf @method("DELETE")
 <button class="text-red-700 hover:underline">Remove</button>
 </form>
 </td>
 </tr>
 @empty
 <tr><td colspan="6" class="p-6 text-center text-gray-500">No assignments yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $assignments->links() }}</div>
@endsection