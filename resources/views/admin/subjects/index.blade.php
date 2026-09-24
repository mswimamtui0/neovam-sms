@extends("layouts.app")
@section("title", "Subjects")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Subjects</h1>
        <a href="{{ route("admin.subjects.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Subject</a>
    </div>

    <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
        <select name="level" class="border rounded px-3 py-2">
            <option value="">All Levels</option>
            @foreach(["nursery","kg","pre_unit","primary","secondary","alevel"] as $lvl)
                <option value="{{ $lvl }}" {{ request("level") === $lvl ? "selected" : "" }}>{{ ucfirst($lvl) }}</option>
            @endforeach
        </select>
        <select name="category" class="border rounded px-3 py-2">
            <option value="">All Categories</option>
            @foreach(["Core","Science","Arts","Commerce","Technical","Religious"] as $cat)
                <option value="{{ $cat }}" {{ request("category") === $cat ? "selected" : "" }}>{{ $cat }}</option>
            @endforeach
        </select>
        <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route("admin.subjects.index") }}" class="text-gray-600 px-4 py-2">Reset</a>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Code</th>
                    <th class="p-3 text-left">Levels</th>
                    <th class="p-3 text-left">Category</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $subject)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $subject->name }}</td>
                        <td class="p-3">{{ $subject->code }}</td>
                        <td class="p-3">{{ $subject->levels }}</td>
                        <td class="p-3">{{ $subject->category }}</td>
                        <td class="p-3">{{ $subject->is_active ? "Active" : "Inactive" }}</td>
                        <td class="p-3">
                            <a href="{{ route("admin.subjects.edit", $subject) }}" class="text-yellow-700 hover:underline">Edit</a>
                            <form method="POST" action="{{ route("admin.subjects.destroy", $subject) }}" class="inline" onsubmit="return confirm(&quot;Delete this subject?&quot;);">
                                @csrf @method("DELETE")
                                <button class="text-red-700 hover:underline ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-6 text-center text-gray-500">No subjects yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $subjects->links() }}</div>
@endsection