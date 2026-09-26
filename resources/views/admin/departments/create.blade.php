@extends("layouts.app")
@section("title", "Add Department")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Department</h1>

    <form method="POST" action="{{ route("admin.departments.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Department Name</label>
            <input type="text" name="name" value="{{ old("name") }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Code (unique)</label>
            <input type="text" name="code" value="{{ old("code") }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Type</label>
            <select name="type" class="w-full border rounded px-3 py-2" required>
                <option value="teaching"     {{ old("type") === "teaching" ? "selected" : "" }}>Teaching Department</option>
                <option value="non_teaching" {{ old("type") === "non_teaching" ? "selected" : "" }}>Non-Teaching Department</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Color</label>
            <select name="color" class="w-full border rounded px-3 py-2" required>
                @foreach(["blue","green","red","yellow","orange","purple","indigo","teal","gray"] as $c)
                    <option value="{{ $c }}" {{ old("color") === $c ? "selected" : "" }}>{{ ucfirst($c) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old("description") }}</textarea>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Department</button>
    </form>
@endsection