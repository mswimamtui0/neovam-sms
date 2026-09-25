@extends("layouts.app")
@section("title", "Add Invoice")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Invoice</h1>
    <form method="POST" action="{{ route("admin.invoices.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf
        <div>
            <label class="block font-semibold mb-1">Student</label>
            <select name="student_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Select --</option>
                @foreach($students as $s)
                    <option value="{{ $s->id }}">{{ $s->full_name }} ({{ $s->classroom?->name }})</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Term</label>
                <input type="text" name="term" value="Term 1" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Year</label>
                <input type="number" name="year" value="{{ date("Y") }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Amount</label>
                <input type="number" step="0.01" name="amount" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Due Date</label>
                <input type="date" name="due_date" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Create Invoice</button>
    </form>
@endsection