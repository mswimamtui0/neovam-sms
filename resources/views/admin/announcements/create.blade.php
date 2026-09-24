@extends("layouts.app")
@section("title", "New Announcement")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">New Announcement</h1>
    <form method="POST" action="{{ route("admin.announcements.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf
        <div>
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-semibold mb-1">Body</label>
            <textarea name="body" rows="5" class="w-full border rounded px-3 py-2" required></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Audience</label>
                <select name="audience" class="w-full border rounded px-3 py-2" required>
                    <option value="all">All</option>
                    <option value="teachers">Teachers</option>
                    <option value="parents">Parents</option>
                    <option value="students">Students</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Publish Date</label>
                <input type="date" name="publish_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Expiry Date (optional)</label>
                <input type="date" name="expiry_date" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex items-center gap-2 mt-6">
                <input type="checkbox" name="is_pinned" value="1">
                <label>Pin to top</label>
            </div>
        </div>
        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Publish</button>
    </form>
@endsection