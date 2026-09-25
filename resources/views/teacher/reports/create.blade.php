@extends("layouts.app")
@section("title", "Submit Report")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Submit Performance Report</h1>
    <form method="POST" action="{{ route("teacher.reports.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf
        <div>
            <label class="block font-semibold mb-1">Report Type</label>
            <select name="report_type" class="w-full border rounded px-3 py-2" required>
                <option value="daily">Daily</option>
                <option value="weekly" selected>Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="termly">Termly</option>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Period Start</label>
                <input type="date" name="week_start" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Period End</label>
                <input type="date" name="week_end" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Periods Taught</label>
                <input type="number" name="periods_taught" min="0" value="0" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Students Absent</label>
                <input type="number" name="students_absent" min="0" value="0" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        <div>
            <label class="block font-semibold mb-1">Summary</label>
            <textarea name="summary" rows="4" class="w-full border rounded px-3 py-2" required></textarea>
        </div>
        <div>
            <label class="block font-semibold mb-1">Challenges</label>
            <textarea name="challenges" rows="3" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <div>
            <label class="block font-semibold mb-1">Next Plan</label>
            <textarea name="next_plan" rows="3" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Submit Report</button>
    </form>
@endsection