@extends("layouts.app")
@section("title", "Create Exam")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Create Exam</h1>
 <form method="POST" action="{{ route("academic.exams.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 <div>
 <label class="block font-semibold mb-1">Exam Name</label>
 <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
 </div>
 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">Term</label>
 <input type="text" name="term" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Level</label>
 <select name="level" class="w-full border rounded px-3 py-2" required>
 @foreach(["nursery","kg","pre_unit","primary","secondary","alevel"] as $l)
 <option value="{{ $l }}">{{ ucfirst($l) }}</option>
 @endforeach
 </select>
 </div>
 </div>
 <div>
 <label class="block font-semibold mb-1">Class</label>
 <select name="classroom_id" class="w-full border rounded px-3 py-2">
 <option value="">-- All Classes --</option>
 @foreach($classes as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
 </select>
 </div>
 <div>
 <label class="block font-semibold mb-1">Start Date</label>
 <input type="date" name="start_date" class="w-full border rounded px-3 py-2">
 </div>
 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Create Exam</button>
 </form>
@endsection