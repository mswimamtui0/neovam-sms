@extends("layouts.app")
@section("title", "Add Health Record")
@section("content")
 <h1 class="text-3xl font-bold text-red-900 mb-6">Add Health Record</h1>
 <form method="POST" action="{{ route("department.health.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 <div><label class="block font-semibold mb-1">Student</label>
 <select name="student_id" class="w-full border rounded px-3 py-2" required>
 <option value="">-- Select --</option>
 @foreach($students as $s)<option value="{{ $s->id }}">{{ $s->full_name }} ({{ $s->classroom?->name }})</option>@endforeach
 </select></div>
 <div class="grid grid-cols-2 gap-4">
 <div><label class="block font-semibold mb-1">Type</label>
 <select name="record_type" class="w-full border rounded px-3 py-2" required>
 <option value="illness">Illness</option>
 <option value="injury">Injury</option>
 <option value="first_aid">First Aid</option>
 <option value="hygiene">Hygiene</option>
 <option value="referral">Referral</option>
 <option value="checkup">Checkup</option>
 </select></div>
 <div><label class="block font-semibold mb-1">Severity</label>
 <select name="severity" class="w-full border rounded px-3 py-2" required>
 <option value="mild">Mild</option>
 <option value="moderate">Moderate</option>
 <option value="severe">Severe</option>
 </select></div>
 </div>
 <div><label class="block font-semibold mb-1">Title</label><input type="text" name="title" class="w-full border rounded px-3 py-2" required></div>
 <div><label class="block font-semibold mb-1">Description</label><textarea name="description" rows="3" class="w-full border rounded px-3 py-2" required></textarea></div>
 <div><label class="block font-semibold mb-1">Treatment</label><textarea name="treatment" rows="2" class="w-full border rounded px-3 py-2"></textarea></div>
 <div><label class="block font-semibold mb-1">Date</label><input type="date" name="record_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required></div>
 <div class="flex gap-4">
 <label class="flex items-center gap-2"><input type="checkbox" name="referred_hospital" value="1"><span>Referred to hospital</span></label>
 <label class="flex items-center gap-2"><input type="checkbox" name="parent_notified" value="1"><span>Parent notified</span></label>
 </div>
 <button type="submit" class="bg-red-700 text-white px-6 py-2 rounded">Save Record</button>
 </form>
@endsection