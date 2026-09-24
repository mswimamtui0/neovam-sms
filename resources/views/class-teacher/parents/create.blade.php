@extends("layouts.app")
@section("title", "Log Parent Contact")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Log Parent Contact</h1>
    <form method="POST" action="{{ route("class-teacher.parents.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf
        <div>
            <label class="block font-semibold mb-1">Student</label>
            <select name="student_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Select --</option>
                @foreach($students as $s)<option value="{{ $s->id }}">{{ $s->full_name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="block font-semibold mb-1">Type</label>
            <select name="contact_type" class="w-full border rounded px-3 py-2" required>
                <option value="call">Phone Call</option>
                <option value="sms">SMS</option>
                <option value="meeting">Meeting</option>
                <option value="visit">Home Visit</option>
                <option value="note">Written Note</option>
            </select>
        </div>
        <div>
            <label class="block font-semibold mb-1">Reason</label>
            <textarea name="reason" rows="3" class="w-full border rounded px-3 py-2" required></textarea>
        </div>
        <div>
            <label class="block font-semibold mb-1">Outcome</label>
            <textarea name="outcome" rows="3" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Contact</button>
    </form>
@endsection