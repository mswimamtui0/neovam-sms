<form method="POST"
      action="{{ $record ? route("dept." . $department->code . ".update", $record->id) : route("dept." . $department->code . ".store") }}"
      class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
    @csrf
    @if($record) @method("PUT") @endif

    @foreach($formFields as $name => $field)
        <div>
            <label class="block font-semibold mb-1">{{ $field["label"] ?? ucwords(str_replace("_"," ",$name)) }}</label>

            @if(($field["type"] ?? "text") === "textarea")
                <textarea name="{{ $name }}" rows="3"
                          class="w-full border rounded px-3 py-2">{{ old($name, $record->$name ?? "") }}</textarea>

            @elseif(($field["type"] ?? "text") === "select")
                <select name="{{ $name }}" class="w-full border rounded px-3 py-2">
                    <option value="">-- Select --</option>
                    @foreach($field["options"] ?? [] as $opt)
                        <option value="{{ $opt }}" {{ old($name, $record->$name ?? "") === $opt ? "selected" : "" }}>{{ ucwords(str_replace("_"," ",$opt)) }}</option>
                    @endforeach
                </select>

            @else
                <input type="{{ $field["type"] ?? "text" }}"
                       name="{{ $name }}"
                       value="{{ old($name, $record->$name ?? "") }}"
                       class="w-full border rounded px-3 py-2"
                       {{ ($field["required"] ?? false) ? "required" : "" }}>
            @endif

            @error($name)
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    @endforeach

    <div class="flex gap-3 pt-2">
        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-800">
            {{ $record ? "Update" : "Save" }}
        </button>
        <a href="{{ route("dept." . $department->code . ".index") }}"
           class="bg-gray-200 text-gray-800 px-6 py-2 rounded hover:bg-gray-300">Cancel</a>
    </div>
</form>