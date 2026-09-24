@extends("layouts.app")
@section("title", "Edit Subject")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Edit Subject</h1>

    <form method="POST" action="{{ route("admin.subjects.update", $subject) }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf @method("PUT")
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Subject Name</label>
                <input type="text" name="name" value="{{ old("name", $subject->name) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Code</label>
                <input type="text" name="code" value="{{ old("code", $subject->code) }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Category</label>
            <select name="category" class="w-full border rounded px-3 py-2" required>
                @foreach(["Core","Science","Arts","Commerce","Technical","Religious"] as $cat)
                    <option value="{{ $cat }}" {{ old("category", $subject->category) === $cat ? "selected" : "" }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Levels</label>
            <div class="grid grid-cols-3 gap-2">
                @php $currentLevels = old("levels", explode(",", $subject->levels)); @endphp
                @foreach(["nursery","kg","pre_unit","primary","secondary","alevel"] as $lvl)
                    <label class="flex items-center gap-2 border rounded px-3 py-2">
                        <input type="checkbox" name="levels[]" value="{{ $lvl }}"
                               {{ in_array($lvl, $currentLevels) ? "checked" : "" }}>
                        <span>{{ ucfirst($lvl) }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ old("is_active", $subject->is_active) ? "checked" : "" }}>
                <span>Active</span>
            </label>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Update Subject</button>
    </form>
@endsection