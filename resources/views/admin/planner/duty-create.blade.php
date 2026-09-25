@extends("layouts.app")
@section("title", "New Yearly Duty Calendar")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">New Yearly Duty Calendar</h1>

 <form method="POST" action="{{ route("admin.planner.duty.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 <div>
 <label class="block font-semibold mb-1">Year</label>
 <input type="number" name="year" value="{{ old("year", $year) }}" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Title</label>
 <input type="text" name="title" value="{{ old("title", "Duty Calendar " . $year) }}" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Notes</label>
 <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2"></textarea>
 </div>
 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Create</button>
 </form>
@endsection