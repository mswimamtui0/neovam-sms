@extends("layouts.app")
@section("title", "Add Lesson Plan")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Lesson Plan</h1>
 <form method="POST" action="{{ route("teacher.lesson-plans.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
 @csrf
 <div class="grid grid-cols-3 gap-4">
 <div>
 <label class="block font-semibold mb-1">Date</label>
 <input type="date" name="date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Class</label>
 <select name="classroom_id" class="w-full border rounded px-3 py-2">
 <option value="">-- Select --</option>
 @foreach($classes as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
 </select>
 </div>
 <div>
 <label class="block font-semibold mb-1">Subject</label>
 <select name="subject_id" class="w-full border rounded px-3 py-2">
 <option value="">-- Select --</option>
 @foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
 </select>
 </div>
 </div>
 <div>
 <label class="block font-semibold mb-1">Topic</label>
 <input type="text" name="topic" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Objectives</label>
 <textarea name="objectives" rows="3" class="w-full border rounded px-3 py-2"></textarea>
 </div>
 <div>
 <label class="block font-semibold mb-1">Activities</label>
 <textarea name="activities" rows="3" class="w-full border rounded px-3 py-2"></textarea>
 </div>
 <div>
 <label class="block font-semibold mb-1">Materials</label>
 <textarea name="materials" rows="2" class="w-full border rounded px-3 py-2"></textarea>
 </div>
 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Lesson Plan</button>
 </form>
@endsection