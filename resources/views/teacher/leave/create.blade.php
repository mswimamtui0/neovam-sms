@extends("layouts.app")
@section("title", "Apply for Leave")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Apply for Leave</h1>
    <form method="POST" action="{{ route("teacher.leave.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf
        <div>
            <label class="block font-semibold mb-1">Leave Type</label>
            <select name="leave_type" class="w-full border rounded px-3 py-2" required>
                <option value="casual">Casual</option>
                <option value="sick">Sick</option>
                <option value="annual">Annual</option>
                <option value="maternity">Maternity</option>
                <option value="emergency">Emergency</option>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Start Date</label>
                <input type="date" name="start_date" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">End Date</label>
                <input type="date" name="end_date" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>
        <div>
            <label class="block font-semibold mb-1">Reason</label>
            <textarea name="reason" rows="4" class="w-full border rounded px-3 py-2" required></textarea>
        </div>
        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Submit Request</button>
    </form>
@endsection