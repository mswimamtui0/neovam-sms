@extends("layouts.app")
@section("title", "Edit Staff Attendance")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Edit Attendance — {{ $attendance->staff?->full_name }}</h1>

    <form method="POST" action="{{ route("admin.staff-attendance.update", $attendance) }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf @method("PUT")

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Check In</label>
                <input type="time" name="check_in" value="{{ $attendance->check_in }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Check Out</label>
                <input type="time" name="check_out" value="{{ $attendance->check_out }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2" required>
                @foreach(["present","absent","late","leave"] as $s)
                    <option value="{{ $s }}" {{ $attendance->status === $s ? "selected" : "" }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2">{{ $attendance->notes }}</textarea>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Update</button>
    </form>
@endsection