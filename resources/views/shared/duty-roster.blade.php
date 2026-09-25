@extends("layouts.app")
@section("title", "Duty Roster")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Duty Roster — Week</h1>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <input type="date" name="week_start" value="{{ $weekStart }}" class="border rounded px-3 py-2">
 <button class="bg-blue-900 text-white px-4 py-2 rounded">View Week</button>
 </form>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Teacher</th>
 <th class="p-3 text-left">Duty</th>
 <th class="p-3 text-left">Shift</th>
 <th class="p-3 text-left">Location</th>
 </tr>
 </thead>
 <tbody>
 @forelse($duties as $d)
 <tr class="border-b">
 <td class="p-3">{{ $d->duty_date->format("l, d M Y") }}</td>
 <td class="p-3 font-semibold">{{ $d->staff?->full_name }}</td>
 <td class="p-3">{{ ucfirst($d->duty_type) }}</td>
 <td class="p-3">{{ ucfirst($d->shift) }}</td>
 <td class="p-3">{{ $d->location ?? "General" }}</td>
 </tr>
 @empty
 <tr><td colspan="5" class="p-6 text-center text-gray-500">No duties scheduled for this week.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
@endsection