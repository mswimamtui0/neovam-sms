@extends("layouts.app")
@section("title", "Schedule Meeting")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Schedule Academic Meeting</h1>
 <form method="POST" action="{{ route("academic.meetings.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 <div>
 <label class="block font-semibold mb-1">Title</label>
 <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Meeting Type</label>
 <select name="meeting_type" class="w-full border rounded px-3 py-2" required>
 <option value="subject">Subject Meeting</option>
 <option value="department">Department Meeting</option>
 <option value="board">Academic Board</option>
 <option value="parent">Parent Meeting</option>
 </select>
 </div>
 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">Date</label>
 <input type="date" name="meeting_date" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Time</label>
 <input type="time" name="meeting_time" class="w-full border rounded px-3 py-2">
 </div>
 </div>
 <div>
 <label class="block font-semibold mb-1">Venue</label>
 <input type="text" name="venue" class="w-full border rounded px-3 py-2">
 </div>
 <div>
 <label class="block font-semibold mb-1">Agenda</label>
 <textarea name="agenda" rows="4" class="w-full border rounded px-3 py-2"></textarea>
 </div>
 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Schedule</button>
 </form>
@endsection