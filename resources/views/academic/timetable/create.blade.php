@extends("layouts.app")
@section("title", "Add Timetable Entry")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Timetable Entry</h1>
    <form method="POST" action="{{ route("academic.timetable.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
        @csrf
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Teacher</label>
                <select name="staff_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Select --</option>
                    @foreach($teachers as $t)<option value="{{ $t->id }}">{{ $t->full_name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Class</label>
                <select name="classroom_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Select --</option>
                    @foreach($classrooms as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Subject</label>
                <select name="subject_id" class="w-full border rounded px-3 py-2">
                    <option value="">-- Optional --</option>
                    @foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-5 gap-4">
            <div>
                <label class="block font-semibold mb-1">Day</label>
                <select name="day_of_week" class="w-full border rounded px-3 py-2" required>
                    @foreach($days as $d)<option value="{{ $d }}">{{ $d }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Start</label>
                <input type="time" name="start_time" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">End</label>
                <input type="time" name="end_time" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Period Label</label>
                <input type="text" name="period_label" placeholder="Period 1" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Room</label>
                <input type="text" name="room" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" rows="2" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Entry</button>
    </form>
@endsection