@extends("layouts.app")
@section("title", "Add Syllabus Coverage")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Syllabus Coverage</h1>
 <form method="POST" action="{{ route("academic.syllabus.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
 @csrf
 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">Class</label>
 <select name="classroom_id" class="w-full border rounded px-3 py-2" required>
 <option value="">-- Select --</option>
 @foreach($classes as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
 </select>
 </div>
 <div>
 <label class="block font-semibold mb-1">Subject</label>
 <select name="subject_id" class="w-full border rounded px-3 py-2" required>
 <option value="">-- Select --</option>
 @foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
 </select>
 </div>
 </div>
 <div class="grid grid-cols-3 gap-4">
 <div>
 <label class="block font-semibold mb-1">Teacher</label>
 <select name="staff_id" class="w-full border rounded px-3 py-2">
 <option value="">-- Optional --</option>
 @foreach($teachers as $t)<option value="{{ $t->id }}">{{ $t->full_name }}</option>@endforeach
 </select>
 </div>
 <div>
 <label class="block font-semibold mb-1">Term</label>
 <input type="text" name="term" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Year</label>
 <input type="number" name="year" value="{{ date("Y") }}" class="w-full border rounded px-3 py-2" required>
 </div>
 </div>
 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">Planned Topics</label>
 <input type="number" name="planned_topics" min="0" value="0" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Covered Topics</label>
 <input type="number" name="covered_topics" min="0" value="0" class="w-full border rounded px-3 py-2" required>
 </div>
 </div>
 <div>
 <label class="block font-semibold mb-1">Notes</label>
 <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2"></textarea>
 </div>
 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save</button>
 </form>
@endsection