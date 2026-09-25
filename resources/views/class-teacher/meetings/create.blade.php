@extends("layouts.app")
@section("title", "Add Class Meeting")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Class Meeting</h1>
 <form method="POST" action="{{ route("class-teacher.meetings.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 <div>
 <label class="block font-semibold mb-1">Date</label>
 <input type="date" name="meeting_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Topic</label>
 <input type="text" name="topic" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Notes</label>
 <textarea name="notes" rows="4" class="w-full border rounded px-3 py-2" required></textarea>
 </div>
 <div>
 <label class="block font-semibold mb-1">Decisions</label>
 <textarea name="decisions" rows="3" class="w-full border rounded px-3 py-2"></textarea>
 </div>
 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Meeting</button>
 </form>
@endsection