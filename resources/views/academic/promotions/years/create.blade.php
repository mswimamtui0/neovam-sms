@extends("layouts.app")
@section("title", "Add Academic Year")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Academic Year</h1>
    <form method="POST" action="{{ route("academic.promotions.years.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Label</label>
                <input type="text" name="label" value="{{ date("Y") }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Year</label>
                <input type="number" name="year" value="{{ date("Y") }}" min="2000" max="2100" class="w-full border rounded px-3 py-2" required>
            </div>
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
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_current" value="1">
            <span>Mark as current academic year</span>
        </label>
        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save</button>
    </form>
@endsection