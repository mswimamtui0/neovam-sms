@extends("layouts.app")
@section("title", "Edit Duty")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Edit Duty Entry</h1>
    <form method="POST" action="{{ route("admin.duty-rosters.update", $dutyRoster) }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf @method("PUT")
        <div>
            <label class="block font-semibold mb-1">Teacher</label>
            <select name="staff_id" class="w-full border rounded px-3 py-2" required>
                @foreach($teachers as $t)
                    <option value="{{ $t->id }}" {{ $dutyRoster->staff_id == $t->id ? "selected" : "" }}>{{ $t->full_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Date</label>
                <input type="date" name="duty_date" value="{{ $dutyRoster->duty_date->format("Y-m-d") }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Shift</label>
                <select name="shift" class="w-full border rounded px-3 py-2" required>
                    @foreach(["day","evening","night"] as $s)
                        <option value="{{ $s }}" {{ $dutyRoster->shift === $s ? "selected" : "" }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Duty Type</label>
                <select name="duty_type" class="w-full border rounded px-3 py-2" required>
                    @foreach(["general","gate","assembly","break","lunch","evening","night","exam"] as $t)
                        <option value="{{ $t }}" {{ $dutyRoster->duty_type === $t ? "selected" : "" }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Location</label>
                <input type="text" name="location" value="{{ $dutyRoster->location }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        <div>
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" rows="2" class="w-full border rounded px-3 py-2">{{ $dutyRoster->notes }}</textarea>
        </div>
        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Update</button>
    </form>
@endsection