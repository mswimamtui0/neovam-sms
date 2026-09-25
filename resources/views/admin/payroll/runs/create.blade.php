@extends("layouts.app")
@section("title", "New Payroll Run")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">New Payroll Run</h1>

    <form method="POST" action="{{ route("admin.payroll-runs.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Payroll Period</label>
            <input type="text" name="period" value="{{ now()->format("Y-m") }}" placeholder="2026-09" class="w-full border rounded px-3 py-2" required>
            <p class="text-xs text-gray-500 mt-1">Format: YYYY-MM (e.g. 2026-09 for September 2026).</p>
        </div>

        <div>
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" rows="2" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-600 p-4 rounded">
            <p class="text-sm"><strong>Note:</strong> Generating this run creates one payroll line for every staff member with an <strong>active salary structure</strong>. You can adjust items before approving.</p>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Generate Payroll Run</button>
    </form>
@endsection