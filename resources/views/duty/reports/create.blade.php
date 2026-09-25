@extends("layouts.app")
@section("title", "New Duty Report")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">New Duty Report</h1>
 <form method="POST" action="{{ route("duty.reports.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
 @csrf
 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">Report Type</label>
 <select name="report_type" class="w-full border rounded px-3 py-2" required>
 <option value="daily">Daily</option>
 <option value="weekly">Weekly</option>
 </select>
 </div>
 <div>
 <label class="block font-semibold mb-1">Date</label>
 <input type="date" name="report_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
 </div>
 </div>

 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">Morning Notes</label>
 <textarea name="morning_notes" rows="2" class="w-full border rounded px-3 py-2"></textarea>
 </div>
 <div>
 <label class="block font-semibold mb-1">Break Notes</label>
 <textarea name="break_notes" rows="2" class="w-full border rounded px-3 py-2"></textarea>
 </div>
 <div>
 <label class="block font-semibold mb-1">Lunch Notes</label>
 <textarea name="lunch_notes" rows="2" class="w-full border rounded px-3 py-2"></textarea>
 </div>
 <div>
 <label class="block font-semibold mb-1">Evening Notes</label>
 <textarea name="evening_notes" rows="2" class="w-full border rounded px-3 py-2"></textarea>
 </div>
 <div>
 <label class="block font-semibold mb-1">Night Notes</label>
 <textarea name="night_notes" rows="2" class="w-full border rounded px-3 py-2"></textarea>
 </div>
 <div>
 <label class="block font-semibold mb-1">Incidents Count</label>
 <input type="number" name="incidents_count" min="0" value="0" class="w-full border rounded px-3 py-2">
 </div>
 </div>

 <div>
 <label class="block font-semibold mb-1">Summary</label>
 <textarea name="summary" rows="4" class="w-full border rounded px-3 py-2" required></textarea>
 </div>

 <div>
 <label class="block font-semibold mb-1">Handover Notes</label>
 <textarea name="handover" rows="3" class="w-full border rounded px-3 py-2"></textarea>
 </div>

 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Submit Report</button>
 </form>
@endsection