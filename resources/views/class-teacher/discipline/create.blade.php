@extends("layouts.app")
@section("title", "Add Discipline Log")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Discipline Log</h1>
    <form method="POST" action="{{ route("class-teacher.discipline.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
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
                    <option value="warning">Warning</option>
                    <option value="detention">Detention</option>
                    <option value="suspension">Suspension</option>
                    <option value="praise">Praise</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Date</label>
                <input type="date" name="log_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>
        <div>
            <label class="block font-semibold mb-1">Reason</label>
            <textarea name="reason" rows="3" class="w-full border rounded px-3 py-2" required></textarea>
        </div>
        <div>
            <label class="block font-semibold mb-1">Action Taken</label>
            <textarea name="action_taken" rows="2" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Log</button>
    </form>
@endsection