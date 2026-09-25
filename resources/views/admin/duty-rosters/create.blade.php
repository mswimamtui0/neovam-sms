@extends("layouts.app")
@section("title", "Add Duty")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Duty Roster Entry</h1>
 <form method="POST" action="{{ route("admin.duty-rosters.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 <div>
 <label class="block font-semibold mb-1">Teacher</label>
 <select name="staff_id" class="w-full border rounded px-3 py-2" required>
 <option value="">-- Select --</option>
 @foreach($teachers as $t)<option value="{{ $t->id }}">{{ $t->full_name }}</option>@endforeach
 </select>
 </div>
 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">Date</label>
 <input type="date" name="duty_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Shift</label>
 <select name="shift" class="w-full border rounded px-3 py-2" required>
 <option value="day">Day</option>
 <option value="evening">Evening</option>
 <option value="night">Night</option>
 </select>
 </div>
 </div>
 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">Duty Type</label>
 <select name="duty_type" class="w-full border rounded px-3 py-2" required>
 <option value="general">General</option>
 <option value="gate">Gate</option>
 <option value="assembly">Assembly</option>
 <option value="break">Break</option>
 <option value="lunch">Lunch</option>
 <option value="evening">Evening Study</option>
 <option value="night">Night</option>
 <option value="exam">Exam</option>
 </select>
 </div>
 <div>
 <label class="block font-semibold mb-1">Location</label>
 <input type="text" name="location" class="w-full border rounded px-3 py-2">
 </div>
 </div>
 <div>
 <label class="block font-semibold mb-1">Notes</label>
 <textarea name="notes" rows="2" class="w-full border rounded px-3 py-2"></textarea>
 </div>
 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save</button>
 </form>
@endsection