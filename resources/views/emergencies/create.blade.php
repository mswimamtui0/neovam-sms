@extends("layouts.app")
@section("title", "Log Emergency")
@section("content")
<div class="max-w-3xl mx-auto">
    <a href="{{ route("emergencies.index") }}" class="text-blue-700 hover:underline text-sm">Back to Emergencies</a>
    <h1 class="text-3xl font-bold text-red-700 mt-2 mb-1">Log Emergency</h1>
    <p class="text-gray-600 mb-6">
        SMS will go automatically to the <strong>Headmaster</strong>, the <strong>Parent</strong>, and the <strong>Health Teacher</strong>.
    </p>

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 mb-4 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route("emergencies.store") }}" class="bg-white rounded shadow p-6 space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Student Name</label>
                <input type="text" name="student_name" value="{{ old("student_name") }}"
                       class="w-full border rounded px-3 py-2" required autofocus>
            </div>
            <div>
                <label class="block font-semibold mb-1">Class</label>
                <input type="text" name="class_name" value="{{ old("class_name") }}"
                       class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Type</label>
                <select name="type" class="w-full border rounded px-3 py-2" required>
                    @foreach(\App\Models\Emergency::typeOptions() as $key => $label)
                        <option value="{{ $key }}" {{ old("type") === $key ? "selected" : "" }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Severity</label>
                <select name="severity" class="w-full border rounded px-3 py-2" required>
                    @foreach(\App\Models\Emergency::severityOptions() as $key => $label)
                        <option value="{{ $key }}" {{ old("severity","urgent") === $key ? "selected" : "" }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Description (what happened)</label>
            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2" required>{{ old("description") }}</textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Action Taken (optional)</label>
            <textarea name="action_taken" rows="2" class="w-full border rounded px-3 py-2">{{ old("action_taken") }}</textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Location (optional)</label>
            <input type="text" name="location" value="{{ old("location") }}"
                   class="w-full border rounded px-3 py-2" placeholder="Sick bay / Classroom / Field">
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 text-sm text-yellow-900 rounded">
            Once you click "Send Emergency Alert", SMS will be sent to:
            <strong>Headmaster</strong> &middot; <strong>Parent</strong> &middot; <strong>Health Teacher</strong>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-red-700 text-white px-8 py-3 rounded font-bold hover:bg-red-800"
                    onclick="return confirm('Send emergency alert to Headmaster, Parent and Health Teacher?');">
                Send Emergency Alert
            </button>
            <a href="{{ route("emergencies.index") }}"
               class="bg-gray-200 text-gray-800 px-6 py-3 rounded hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</div>
@endsection