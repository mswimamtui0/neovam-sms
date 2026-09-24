@extends("layouts.app")
@section("title", "Create Handover")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Create Handover Note</h1>
    <form method="POST" action="{{ route("duty.handovers.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf
        <div>
            <label class="block font-semibold mb-1">To Teacher</label>
            <select name="to_staff_id" class="w-full border rounded px-3 py-2">
                <option value="">-- Select --</option>
                @foreach($teachers as $t)<option value="{{ $t->id }}">{{ $t->full_name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="block font-semibold mb-1">Date</label>
            <input type="date" name="handover_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" rows="5" class="w-full border rounded px-3 py-2" required></textarea>
        </div>
        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Create Handover</button>
    </form>
@endsection