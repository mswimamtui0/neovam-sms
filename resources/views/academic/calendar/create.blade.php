@extends("layouts.app")
@section("title", "Add Academic Term")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Academic Term</h1>
 <form method="POST" action="{{ route("academic.calendar.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 <div class="grid grid-cols-2 gap-4">
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
 <label class="block font-semibold mb-1">Term Start</label>
 <input type="date" name="term_start" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Term End</label>
 <input type="date" name="term_end" class="w-full border rounded px-3 py-2" required>
 </div>
 </div>
 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">Exam Start</label>
 <input type="date" name="exam_start" class="w-full border rounded px-3 py-2">
 </div>
 <div>
 <label class="block font-semibold mb-1">Exam End</label>
 <input type="date" name="exam_end" class="w-full border rounded px-3 py-2">
 </div>
 </div>
 <div>
 <label class="block font-semibold mb-1">Notes</label>
 <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2"></textarea>
 </div>
 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save</button>
 </form>
@endsection