@extends("layouts.app")
@section("title", "Exam Timetable")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Exam Timetable — {{ $exam->name }}</h1>

    <form method="POST" action="{{ route("academic.exams.timetable.store", $exam) }}" class="bg-white rounded shadow p-6 mb-6 grid grid-cols-6 gap-3">
        @csrf
        <div class="col-span-2">
            <label class="block font-semibold mb-1 text-sm">Subject</label>
            <select name="subject_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Select --</option>
                @foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="block font-semibold mb-1 text-sm">Date</label>
            <input type="date" name="exam_date" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-semibold mb-1 text-sm">Start</label>
            <input type="time" name="start_time" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-semibold mb-1 text-sm">End</label>
            <input type="time" name="end_time" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-semibold mb-1 text-sm">Venue</label>
            <input type="text" name="venue" class="w-full border rounded px-3 py-2">
        </div>
        <div class="col-span-6">
            <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Add Entry</button>
        </div>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Subject</th>
                    <th class="p-3 text-left">Time</th>
                    <th class="p-3 text-left">Venue</th>
                    <th class="p-3 text-left">Invigilator</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $e)
                    <tr class="border-b">
                        <td class="p-3">{{ $e->exam_date->format("Y-m-d") }}</td>
                        <td class="p-3">{{ $e->subject?->name }}</td>
                        <td class="p-3">{{ $e->start_time }} – {{ $e->end_time }}</td>
                        <td class="p-3">{{ $e->venue ?? "-" }}</td>
                        <td class="p-3">{{ $e->invigilator ?? "-" }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-gray-500">No entries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection