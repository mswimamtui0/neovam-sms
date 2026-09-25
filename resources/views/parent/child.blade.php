@extends("layouts.app")
@section("title", "Child Profile")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">{{ $student->full_name }}</h1>

    <div class="grid grid-cols-4 gap-4 mb-6">
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
        <div class="bg-white rounded shadow p-4">
            <div class="text-sm text-gray-500">Status</div>
            <div class="text-xl font-bold">{{ ucfirst($student->status) }}</div>
        </div>
    </div>

    <!-- Recent Results -->
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-blue-900 mb-3">Recent Results</h2>
        @forelse($student->results->take(10) as $r)
            <div class="border-b py-2 text-sm">
                <strong>{{ $r->subject }}:</strong> {{ $r->marks }} ({{ $r->grade }})
                <span class="text-gray-500 text-xs">— {{ $r->exam?->name }}</span>
            </div>
        @empty
            <p class="text-gray-500 text-sm">No results yet.</p>
        @endforelse
    </div>

    <!-- Recent Attendance -->
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-blue-900 mb-3">Recent Attendance</h2>
        @forelse($student->attendances->take(15) as $a)
            <div class="border-b py-1 text-sm flex justify-between">
                <span>{{ $a->date->format("Y-m-d") }}</span>
                <span class="{{ $a->status === "absent" ? "text-red-600" : "text-green-600" }}">
                    {{ ucfirst($a->status) }}
                </span>
            </div>
        @empty
            <p class="text-gray-500 text-sm">No attendance records.</p>
        @endforelse
    </div>

    <!-- Promotions -->
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-blue-900 mb-3">Promotion History</h2>
        @forelse($promotions as $p)
            <div class="border-b py-2 text-sm">
                <strong>{{ $p->fromClassroom?->name ?? "N/A" }}</strong> →
                <strong>{{ $p->toClassroom?->name ?? "N/A" }}</strong>
                <span class="text-xs text-gray-500">({{ $p->academic_year }} • {{ ucfirst($p->status) }})</span>
            </div>
        @empty
            <p class="text-gray-500 text-sm">No promotion records yet.</p>
        @endforelse
    </div>

    <!-- Fees -->
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-blue-900 mb-3">Fees</h2>
        @forelse($invoices as $inv)
            <div class="border-b py-2 text-sm flex justify-between">
                <div>
                    <strong>{{ $inv->term }} {{ $inv->year }}</strong> — {{ $inv->invoice_no }}
                </div>
                <div>
                    TZS {{ number_format($inv->amount) }} |
                    Paid: {{ number_format($inv->amount_paid) }} |
                    <span class="{{ $inv->balance > 0 ? "text-red-600" : "text-green-600" }}">
                        Balance: {{ number_format($inv->balance) }}
                    </span>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-sm">No invoices yet.</p>
        @endforelse
    </div>

    <!-- Timetable -->
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-bold text-blue-900 mb-3">Weekly Timetable</h2>
        @forelse($timetable as $day => $entries)
            <div class="mb-3">
                <div class="font-semibold text-blue-800">{{ $day }}</div>
                @foreach($entries as $e)
                    <div class="text-sm border-b py-1 ml-3">
                        {{ $e->start_time }} – {{ $e->end_time }} |
                        {{ $e->subject?->name ?? "-" }} |
                        {{ $e->staff?->full_name ?? "-" }}
                        @if($e->room) | Room {{ $e->room }} @endif
                    </div>
                @endforeach
            </div>
        @empty
            <p class="text-gray-500 text-sm">No timetable yet.</p>
        @endforelse
    </div>
@endsection