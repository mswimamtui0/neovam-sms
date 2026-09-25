@extends("layouts.app")
@section("title", "Add Subject")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Subject</h1>

 <form method="POST" action="{{ route("admin.subjects.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">Subject Name</label>
 <input type="text" name="name" value="{{ old("name") }}" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Code</label>
 <input type="text" name="code" value="{{ old("code") }}" class="w-full border rounded px-3 py-2" required>
 </div>
 </div>

 <div>
 <label class="block font-semibold mb-1">Category</label>
 <select name="category" class="w-full border rounded px-3 py-2" required>
 <option value="">-- Select --</option>
 @foreach(["Core","Science","Arts","Commerce","Technical","Religious"] as $cat)
 <option value="{{ $cat }}" {{ old("category") === $cat ? "selected" : "" }}>{{ $cat }}</option>
 @endforeach
 </select>
 </div>

 <div>
 <label class="block font-semibold mb-1">Levels (select all that apply)</label>
 <div class="grid grid-cols-3 gap-2">
 @foreach(["nursery","kg","pre_unit","primary","secondary","alevel"] as $lvl)
 <label class="flex items-center gap-2 border rounded px-3 py-2">
 <input type="checkbox" name="levels[]" value="{{ $lvl }}"
 {{ in_array($lvl, old("levels", [])) ? "checked" : "" }}>
 <span>{{ ucfirst($lvl) }}</span>
 </label>
 @endforeach
 </div>
 </div>

 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Subject</button>
 </form>
@endsection