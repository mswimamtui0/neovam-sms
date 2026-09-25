@extends("layouts.app")
@section("title", "Add Template")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Add SMS Template</h1>

    <form method="POST" action="{{ route("admin.sms-templates.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Key</label>
                <input type="text" name="key" placeholder="e.g. leave_approved" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Language</label>
                <select name="language" class="w-full border rounded px-3 py-2" required>
                    <option value="en">English</option>
                    <option value="sw">Kiswahili</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Body</label>
            <textarea name="body" rows="5" maxlength="600" class="w-full border rounded px-3 py-2" required></textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Description</label>
            <input type="text" name="description" class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Template</button>
    </form>
@endsection