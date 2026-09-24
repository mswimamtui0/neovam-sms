@extends("layouts.app")
@section("title", "Teacher on Duty")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-2">Teacher on Duty</h1>
    <p class="text-gray-600 mb-6">Today is {{ now()->format("l, d M Y") }}</p>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-sm text-gray-500">On Duty Today</div>
            <div class="text-xl font-bold text-blue-900">{{ $todayDuty ? "Yes" : "No" }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-sm text-gray-500">Supervisions Done</div>
            <div class="text-3xl font-bold text-green-900">{{ count($doneAreas) }} / {{ count($supervisionAreas) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
            <div class="text-sm text-gray-500">Incidents Today</div>
            <div class="text-3xl font-bold text-red-900">{{ $incidentsToday }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
            <div class="text-sm text-gray-500">Pending Reports</div>
            <div class="text-3xl font-bold text-yellow-900">{{ $pendingReports }}</div>
        </div>
    </div>

    <!-- Today's Supervision Checklist -->
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-blue-900 mb-4">Today's Supervision</h2>
        <div class="grid grid-cols-4 gap-3">
            @foreach($supervisionAreas as $area)
                <form method="POST" action="{{ route("duty.supervision.log") }}">
                    @csrf
                    <input type="hidden" name="area" value="{{ $area }}">
                    <input type="hidden" name="status" value="done">
                    <button class="w-full border rounded px-3 py-2 text-left {{ in_array($area, $doneAreas) ? "bg-green-100 border-green-600" : "hover:bg-blue-50" }}">
                        <div class="font-semibold">{{ ucfirst($area) }}</div>
                        <div class="text-xs text-gray-500">
                            {{ in_array($area, $doneAreas) ? "Logged" : "Tap to log" }}
                        </div>
                    </button>
                </form>
            @endforeach
        </div>
    </div>

    <!-- Upcoming Duties -->
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-bold text-blue-900 mb-4">Upcoming Duties</h2>
        @forelse($upcomingDuties as $d)
            <div class="border-b py-2 flex justify-between">
                <span>{{ $d->duty_date->format("l, d M Y") }}</span>
                <span class="text-gray-600">{{ ucfirst($d->duty_type) }} — {{ $d->location ?? "General" }}</span>
            </div>
        @empty
            <p class="text-gray-500">No duties scheduled.</p>
        @endforelse
    </div>
@endsection