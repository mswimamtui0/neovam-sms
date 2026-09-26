@extends("layouts.app")
@section("title", "Update Emergency")
@section("content")
<div class="max-w-3xl mx-auto">
    <a href="{{ route("emergencies.show", $emergency->id) }}" class="text-blue-700 hover:underline text-sm">Back to Emergency</a>
    <h1 class="text-3xl font-bold text-red-700 mt-2 mb-6">Update Emergency</h1>

    <form method="POST" action="{{ route("emergencies.update", $emergency->id) }}" class="bg-white rounded shadow p-6 space-y-4">
        @csrf @method("PUT")

        <div>
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old("description", $emergency->description) }}</textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Action Taken</label>
            <textarea name="action_taken" rows="2" class="w-full border rounded px-3 py-2">{{ old("action_taken", $emergency->action_taken) }}</textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Parent Response</label>
            <textarea name="parent_response" rows="2" class="w-full border rounded px-3 py-2">{{ old("parent_response", $emergency->parent_response) }}</textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Follow-up</label>
            <textarea name="follow_up" rows="2" class="w-full border rounded px-3 py-2">{{ old("follow_up", $emergency->follow_up) }}</textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2" required>
                @foreach(["open","monitoring","resolved","closed"] as $s)
                    <option value="{{ $s }}" {{ old("status", $emergency->status) === $s ? "selected" : "" }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">
                Choosing "Resolved" will send a follow-up SMS to Parent and Headmaster.
            </p>
        </div>

        <button type="submit" class="bg-red-700 text-white px-8 py-3 rounded font-bold hover:bg-red-800">
            Save Update
        </button>
    </form>
</div>
@endsection