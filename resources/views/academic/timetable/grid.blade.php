@extends("layouts.app")
@section("title", "Timetable Grid")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Timetable Grid</h1>

    <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
        <select name="staff_id" class="border rounded px-3 py-2">
            <option value="">All Teachers</option>
            @foreach($teachers as $t)
                <option value="{{ $t->id }}" {{ $staffId == $t->id ? "selected" : "" }}>{{ $t->full_name }}</option>
            @endforeach
        </select>
        <select name="classroom_id" class="border rounded px-3 py-2">
            <option value="">All Classes</option>
            @foreach($classrooms as $c)
                <option value="{{ $c->id }}" {{ $classId == $c->id ? "selected" : "" }}>{{ $c->name }}</option>
            @endforeach
        </select>
        <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route("academic.timetable.grid") }}" class="text-gray-600 px-4 py-2">Reset</a>
    </form>

    @foreach($days as $day)
        @php $dayEntries = $entries->where("day_of_week", $day); @endphp
        <div class="bg-white rounded shadow p-4 mb-4">
            <h2 class="text-lg font-bold text-blue-900 mb-3">{{ $day }}</h2>
            @if($dayEntries->isEmpty())
                <p class="text-gray-400 text-sm">No entries.</p>
            @else
                <div class="space-y-2">
                    @foreach($dayEntries->sortBy("start_time") as $e)
                        <div class="border-l-4 border-blue-600 pl-3 py-2 bg-gray-50 rounded">
                            <div class="flex justify-between">
                                <span class="font-semibold">{{ $e->start_time }} – {{ $e->end_time }}</span>
                                <span class="text-xs text-gray-500">{{ $e->period_label }}</span>
                            </div>
                            <div class="text-sm">
                                {{ $e->subject?->name ?? "-" }} |
                                {{ $e->classroom?->name }} |
                                {{ $e->staff?->full_name }}
                                @if($e->room) | Room {{ $e->room }} @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
@endsection