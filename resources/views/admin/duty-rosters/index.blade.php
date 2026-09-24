@extends("layouts.app")
@section("title", "Duty Rosters")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Duty Rosters</h1>
        <a href="{{ route("admin.duty-rosters.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Duty</a>
    </div>

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
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($duties as $d)
                    <tr class="border-b">
                        <td class="p-3">{{ $d->duty_date->format("l, d M") }}</td>
                        <td class="p-3 font-semibold">{{ $d->staff?->full_name }}</td>
                        <td class="p-3">{{ ucfirst($d->duty_type) }}</td>
                        <td class="p-3">{{ ucfirst($d->shift) }}</td>
                        <td class="p-3">{{ $d->location ?? "-" }}</td>
                        <td class="p-3">
                            <a href="{{ route("admin.duty-rosters.edit", $d) }}" class="text-yellow-700 hover:underline">Edit</a>
                            <form method="POST" action="{{ route("admin.duty-rosters.destroy", $d) }}" class="inline" onsubmit="return confirm(&quot;Delete?&quot;);">
                                @csrf @method("DELETE")
                                <button class="text-red-700 hover:underline ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-6 text-center text-gray-500">No duties this week.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $duties->links() }}</div>
@endsection