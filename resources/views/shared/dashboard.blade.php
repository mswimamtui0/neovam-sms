@extends("layouts.app")
@section("title", "Staff Dashboard")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-2">Staff Dashboard</h1>
    <p class="text-gray-600 mb-6">Welcome, {{ $staff?->full_name ?? auth()->user()->name }} — {{ now()->format("l, d M Y") }}</p>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-sm text-gray-500">Total Students</div>
            <div class="text-3xl font-bold text-blue-900">{{ $totalStudents }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-sm text-gray-500">Total Staff</div>
            <div class="text-3xl font-bold text-green-900">{{ $totalStaff }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
            <div class="text-sm text-gray-500">Total Classes</div>
            <div class="text-3xl font-bold text-purple-900">{{ $totalClasses }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
            <div class="text-sm text-gray-500">Attendance Today</div>
            <div class="text-3xl font-bold text-yellow-900">{{ $attendanceRate }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-sm text-gray-500">Present Today</div>
            <div class="text-2xl font-bold">{{ $presentCount }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
            <div class="text-sm text-gray-500">Absent Today</div>
            <div class="text-2xl font-bold">{{ $absentCount }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-indigo-600">
            <div class="text-sm text-gray-500">Staff Present</div>
            <div class="text-2xl font-bold">{{ $staffPresent }}</div>
        </div>
    </div>

    @if($dutyToday)
        <div class="bg-yellow-100 border-l-4 border-yellow-600 p-4 mb-6 rounded">
            <strong>On Duty Today:</strong> {{ $dutyToday->staff?->full_name }} — {{ ucfirst($dutyToday->duty_type) }} ({{ $dutyToday->location ?? "General" }})
        </div>
    @endif

    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-4">Latest Announcements</h2>
            @forelse($announcements as $a)
                <div class="border-b py-2">
                    <div class="font-semibold">{{ $a->title }}
                        @if($a->is_pinned) <span class="text-xs bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded ml-2">Pinned</span> @endif
                    </div>
                    <div class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($a->body, 100) }}</div>
                    <div class="text-xs text-gray-400">{{ $a->publish_date?->format("Y-m-d") }}</div>
                </div>
            @empty
                <p class="text-gray-500">No announcements.</p>
            @endforelse
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-4">Quick Links</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route("shared.class-teachers") }}" class="bg-blue-100 hover:bg-blue-200 text-blue-900 p-3 rounded text-center">Class Teachers</a>
                <a href="{{ route("shared.classes") }}" class="bg-blue-100 hover:bg-blue-200 text-blue-900 p-3 rounded text-center">Classes Overview</a>
                <a href="{{ route("shared.attendance") }}" class="bg-blue-100 hover:bg-blue-200 text-blue-900 p-3 rounded text-center">Attendance</a>
                <a href="{{ route("shared.duty-roster") }}" class="bg-blue-100 hover:bg-blue-200 text-blue-900 p-3 rounded text-center">Duty Roster</a>
                <a href="{{ route("shared.announcements") }}" class="bg-blue-100 hover:bg-blue-200 text-blue-900 p-3 rounded text-center">Announcements</a>
                <a href="{{ route("shared.directory") }}" class="bg-blue-100 hover:bg-blue-200 text-blue-900 p-3 rounded text-center">Staff Directory</a>
            </div>
        </div>
    </div>
@endsection