@extends("layouts.app")
@section("title", "Add Welfare Note")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Welfare Note</h1>
    <form method="POST" action="{{ route("class-teacher.welfare.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf
        <div>
            <label class="block font-semibold mb-1">Student</label>
            <select name="student_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Select --</option>
                @foreach($students as $s)<option value="{{ $s->id }}">{{ $s->full_name }}</option>@endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Type</label>
                <select name="type" class="w-full border rounded px-3 py-2" required>
                    <option value="health">Health</option>
                    <option value="hygiene">Hygiene</option>
                    <option value="uniform">Uniform</option>
                    <option value="food">Food</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Date</label>
                <input type="date" name="note_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>
        <div>
            <label class="block font-semibold mb-1">Observation</label>
            <textarea name="observation" rows="3" class="w-full border rounded px-3 py-2" required></textarea>
        </div>
        <div>
            <label class="block font-semibold mb-1">Action Taken</label>
            <textarea name="action" rows="2" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Note</button>
    </form>
@endsection