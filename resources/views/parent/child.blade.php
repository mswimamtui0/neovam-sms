@extends("layouts.app")
@section("title", "Child Details")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">{{ $student->full_name }}</h1>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4">
            <div class="text-sm text-gray-500">Class</div>
            <div class="text-xl font-bold">{{ $student->classroom?->name ?? "-" }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-sm text-gray-500">Level</div>
            <div class="text-xl font-bold">{{ ucfirst($student->level) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-sm text-gray-500">Admission No</div>
            <div class="text-xl font-bold">{{ $student->admission_no }}</div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded shadow p-4">
            <h3 class="font-bold text-blue-900 mb-3">Recent Results</h3>
            @forelse($student->results as $r)
                <div class="border-b py-2 text-sm">
                    <strong>{{ $r->subject }}:</strong> {{ $r->marks }} ({{ $r->grade }})
                    <span class="text-gray-500 text-xs"> - {{ $r->exam?->name }}</span>
                </div>
            @empty
                <p class="text-gray-500 text-sm">No results yet.</p>
            @endforelse
        </div>

        <div class="bg-white rounded shadow p-4">
            <h3 class="font-bold text-blue-900 mb-3">Recent Attendance</h3>
            @forelse($student->attendances->take(10) as $a)
                <div class="border-b py-2 text-sm">
                    {{ $a->date->format("Y-m-d") }} -
                    <span class="{{ $a->status === "absent" ? "text-red-600" : "text-green-600" }}">
                        {{ ucfirst($a->status) }}
                    </span>
                </div>
            @empty
                <p class="text-gray-500 text-sm">No attendance records.</p>
            @endforelse
        </div>
    </div>
@endsection