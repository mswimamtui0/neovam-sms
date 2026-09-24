@extends("layouts.app")
@section("title", "My Timetable")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">My Weekly Timetable</h1>

    @if($entries->isEmpty())
        <div class="bg-white rounded shadow p-6 text-gray-500">No timetable entries yet.</div>
    @else
        @foreach($days as $day)
            @php $dayEntries = $entries->get($day, collect()); @endphp
            @if($dayEntries->isNotEmpty())
                <div class="bg-white rounded shadow p-6 mb-4">
                    <h2 class="text-xl font-bold text-blue-900 mb-3">{{ $day }}</h2>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 text-left">Time</th>
                                <th class="p-2 text-left">Period</th>
                                <th class="p-2 text-left">Class</th>
                                <th class="p-2 text-left">Subject</th>
                                <th class="p-2 text-left">Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dayEntries as $e)
                                <tr class="border-b">
                                    <td class="p-2">{{ $e->start_time }} – {{ $e->end_time }}</td>
                                    <td class="p-2">{{ $e->period_label ?? "-" }}</td>
                                    <td class="p-2">{{ $e->classroom?->name }}</td>
                                    <td class="p-2">{{ $e->subject?->name ?? "-" }}</td>
                                    <td class="p-2">{{ $e->room ?? "-" }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endforeach
    @endif
@endsection