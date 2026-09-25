@extends("layouts.app")
@section("title", "Edit Activity")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Edit Activity</h1>

    <form method="POST" action="{{ route("admin.department-activities.update", $activity) }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
        @csrf @method("PUT")

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Department</label>
                <select name="department" class="w-full border rounded px-3 py-2" required>
                    @foreach($departments as $d)
                        <option value="{{ $d }}" {{ $activity->department === $d ? "selected" : "" }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Staff</label>
                <select name="staff_id" class="w-full border rounded px-3 py-2">
                    <option value="">-- Optional --</option>
                    @foreach($staffList as $s)
                        <option value="{{ $s->id }}" {{ $activity->staff_id == $s->id ? "selected" : "" }}>{{ $s->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" name="title" value="{{ $activity->title }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full border rounded px-3 py-2" required>{{ $activity->description }}</textarea>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Type</label>
                <select name="activity_type" class="w-full border rounded px-3 py-2" required>
                    @foreach(["task","meeting","event","inspection","report","other"] as $t)
                        <option value="{{ $t }}" {{ $activity->activity_type === $t ? "selected" : "" }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Date</label>
                <input type="date" name="activity_date" value="{{ $activity->activity_date->format("Y-m-d") }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Priority</label>
                <select name="priority" class="w-full border rounded px-3 py-2" required>
                    @foreach(["low","normal","high","urgent"] as $p)
                        <option value="{{ $p }}" {{ $activity->priority === $p ? "selected" : "" }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2" required>
                @foreach(["pending","in_progress","completed","cancelled"] as $s)
                    <option value="{{ $s }}" {{ $activity->status === $s ? "selected" : "" }}>{{ ucfirst(str_replace("_"," ",$s)) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Outcome</label>
            <textarea name="outcome" rows="3" class="w-full border rounded px-3 py-2">{{ $activity->outcome }}</textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2">{{ $activity->notes }}</textarea>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Update</button>
    </form>
@endsection