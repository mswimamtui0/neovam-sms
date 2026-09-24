@extends("layouts.app")
@section("title", "Teacher Dashboard")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-2">Teacher Dashboard</h1>
    <p class="text-gray-600 mb-6">Today is {{ $todayName }} — {{ now()->format("d M Y") }}</p>

    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-sm text-gray-500">My Classes</div>
            <div class="text-3xl font-bold text-blue-900">{{ $classes->count() }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-sm text-gray-500">My Students</div>
            <div class="text-3xl font-bold text-green-900">{{ $studentCount }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
            <div class="text-sm text-gray-500">Weekly Periods</div>
            <div class="text-3xl font-bold text-purple-900">{{ $weeklyCount }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
            <div class="text-sm text-gray-500">Pending Reports</div>
            <div class="text-3xl font-bold text-yellow-900">{{ $pendingReports }}</div>
        </div>
    </div>

    <div class="bg-white rounded shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-blue-900">Today Timetable ({{ $todayName }})</h2>
            <a href="{{ route("teacher.timetable") }}" class="text-blue-700 text-sm hover:underline">View full week</a>
        </div>

        @if($todayTimetable->isEmpty())
            <p class="text-gray-500">No classes scheduled today.</p>
        @else
            <table class="w-full text-sm">
                <thead class="bg-blue-900 text-white">
                    <tr>
                        <th class="p-3 text-left">Time</th>
                        <th class="p-3 text-left">Period</th>
                        <th class="p-3 text-left">Class</th>
                        <th class="p-3 text-left">Subject</th>
                        <th class="p-3 text-left">Room</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todayTimetable as $t)
                        <tr class="border-b">
                            <td class="p-3">{{ $t->start_time }} – {{ $t->end_time }}</td>
                            <td class="p-3">{{ $t->period_label ?? "-" }}</td>
                            <td class="p-3">{{ $t->classroom?->name }}</td>
                            <td class="p-3">{{ $t->subject?->name ?? "-" }}</td>
                            <td class="p-3">{{ $t->room ?? "-" }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @if($todayDuty)
        <div class="bg-yellow-100 border-l-4 border-yellow-600 p-4 mb-6 rounded">
            <strong>Duty Today:</strong> {{ ucfirst($todayDuty->duty_type) }} — {{ $todayDuty->location ?? "General" }}
        </div>
    @endif

    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-bold text-blue-900 mb-4">My Classes</h2>
        @forelse($classes as $class)
            <div class="border-b py-2">{{ $class->name }} ({{ ucfirst($class->level) }})</div>
        @empty
            <p class="text-gray-500">No classes assigned.</p>
        @endforelse
    </div>
@endsection