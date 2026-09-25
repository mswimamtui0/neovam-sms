@extends("layouts.app")
@section("title", "Edit Fee Structure")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Edit Fee Structure</h1>
 <form method="POST" action="{{ route("admin.fee-structures.update", $feeStructure) }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf @method("PUT")
 <div class="grid grid-cols-3 gap-4">
 <div>
 <label class="block font-semibold mb-1">Level</label>
 <select name="level" class="w-full border rounded px-3 py-2" required>
 @foreach(["nursery","kg","pre_unit","primary","secondary","alevel"] as $l)
 <option value="{{ $l }}" {{ $feeStructure->level === $l ? "selected" : "" }}>{{ ucfirst($l) }}</option>
 @endforeach
 </select>
 </div>
 <div>
 <label class="block font-semibold mb-1">Term</label>
 <input type="text" name="term" value="{{ $feeStructure->term }}" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Year</label>
 <input type="number" name="year" value="{{ $feeStructure->year }}" class="w-full border rounded px-3 py-2" required>
 </div>
 </div>

 <div class="grid grid-cols-3 gap-4">
 @foreach(["tuition_fee","transport_fee","meal_fee","development_fee","exam_fee","other_fee"] as $field)
 <div>
 <label class="block font-semibold mb-1">{{ ucwords(str_replace("_"," ",$field)) }}</label>
 <input type="number" step="0.01" name="{{ $field }}" value="{{ $feeStructure->$field }}" class="w-full border rounded px-3 py-2">
 </div>
 @endforeach
 </div>

 <div>
 <label class="block font-semibold mb-1">Notes</label>
 <textarea name="notes" rows="2" class="w-full border rounded px-3 py-2">{{ $feeStructure->notes }}</textarea>
 </div>

 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Update</button>
 </form>
@endsection