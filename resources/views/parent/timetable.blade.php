@extends("layouts.app")
@section("title", "Timetable")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Children Timetable</h1>
    @foreach($children as $child)
        <div class="bg-white rounded shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">{{ $child->full_name }} — {{ $child->classroom?->name }}</h2>
            @forelse(($timetables[$child->id] ?? collect()) as $day => $entries)
                <div class="mb-3">
                    <div class="font-semibold text-blue-800">{{ $day }}</div>
                    @foreach($entries as $e)
                        <div class="text-sm border-b py-1 ml-3">
                            {{ $e->start_time }} – {{ $e->end_time }} |
                            {{ $e->subject?->name ?? "-" }} |
                            {{ $e->staff?->full_name ?? "-" }}
                            @if($e->room) | {{ $e->room }} @endif
                        </div>
                    @endforeach
                </div>
            @empty
                <p class="text-gray-500 text-sm">No timetable yet.</p>
            @endforelse
        </div>
    @endforeach
@endsection