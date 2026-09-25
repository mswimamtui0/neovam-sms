@extends("layouts.app")
@section("title", "Log Department Activity")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Log Department Activity</h1>

    <form method="POST" action="{{ route("admin.department-activities.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Department</label>
                <select name="department" class="w-full border rounded px-3 py-2" required>
                    @foreach($departments as $d)
                        <option value="{{ $d }}">{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Staff (assigned to)</label>
                <select name="staff_id" class="w-full border rounded px-3 py-2">
                    <option value="">-- Optional --</option>
                    @foreach($staffList as $s)
                        <option value="{{ $s->id }}">{{ $s->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full border rounded px-3 py-2" required></textarea>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Activity Type</label>
                <select name="activity_type" class="w-full border rounded px-3 py-2" required>
                    <option value="task">Task</option>
                    <option value="meeting">Meeting</option>
                    <option value="event">Event</option>
                    <option value="inspection">Inspection</option>
                    <option value="report">Report</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Date</label>
                <input type="date" name="activity_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Priority</label>
                <select name="priority" class="w-full border rounded px-3 py-2" required>
                    <option value="low">Low</option>
                    <option value="normal" selected>Normal</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2" required>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Outcome</label>
            <textarea name="outcome" rows="3" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Log Activity</button>
    </form>
@endsection