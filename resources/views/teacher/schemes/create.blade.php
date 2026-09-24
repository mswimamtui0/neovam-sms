@extends("layouts.app")
@section("title", "Add Scheme of Work")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Scheme of Work</h1>
    <form method="POST" action="{{ route("teacher.schemes.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Term</label>
                <input type="text" name="term" class="w-full border rounded px-3 py-2" placeholder="e.g. Term 1" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Year</label>
                <input type="number" name="year" value="{{ date("Y") }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Class</label>
                <select name="classroom_id" class="w-full border rounded px-3 py-2">
                    <option value="">-- Select --</option>
                    @foreach($classes as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Subject</label>
                <select name="subject_id" class="w-full border rounded px-3 py-2">
                    <option value="">-- Select --</option>
                    @foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="block font-semibold mb-1">Content</label>
            <textarea name="content" rows="10" class="w-full border rounded px-3 py-2" required></textarea>
        </div>
        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Scheme</button>
    </form>
@endsection