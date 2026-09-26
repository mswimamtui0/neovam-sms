@extends("layouts.app")
@section("title", "Departments")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Departments</h1>
        <a href="{{ route("admin.departments.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">Add Department</a>
    </div>

    @if(session("success"))
        <div class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 mb-4 rounded">{{ session("success") }}</div>
    @endif
    @if(session("error"))
        <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 mb-4 rounded">{{ session("error") }}</div>
    @endif

    <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
        <select name="type" class="border rounded px-3 py-2">
            <option value="">All Types</option>
            <option value="teaching"     {{ request("type") === "teaching" ? "selected" : "" }}>Teaching</option>
            <option value="non_teaching" {{ request("type") === "non_teaching" ? "selected" : "" }}>Non-Teaching</option>
        </select>
        <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route("admin.departments.index") }}" class="text-gray-600 px-4 py-2">Reset</a>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Code</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Subjects</th>
                    <th class="p-3 text-left">Staff</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $d)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $d->name }}</td>
                        <td class="p-3 font-mono text-xs">{{ $d->code }}</td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded text-xs {{ $d->type === "teaching" ? "bg-blue-100 text-blue-800" : "bg-gray-100 text-gray-800" }}">
                                {{ $d->type === "teaching" ? "Teaching" : "Non-Teaching" }}
                            </span>
                        </td>
                        <td class="p-3">{{ $d->subjects_count }}</td>
                        <td class="p-3">{{ $d->staff_count }}</td>
                        <td class="p-3">{{ $d->is_active ? "Active" : "Inactive" }}</td>
                        <td class="p-3">
                            <a href="{{ route("admin.departments.edit", $d) }}" class="text-yellow-700 hover:underline">Edit</a>
                            <form method="POST" action="{{ route("admin.departments.destroy", $d) }}" class="inline ml-2"
                                  onsubmit="return confirm('Delete {{ $d->name }}?');">
                                @csrf @method("DELETE")
                                <button class="text-red-700 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-6 text-center text-gray-500">No departments.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $departments->links() }}</div>
@endsection