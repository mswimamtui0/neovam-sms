@extends("layouts.app")
@section("title", "New Session")
@section("content")
 <h1 class="text-3xl font-bold text-indigo-900 mb-6">New Counseling Session</h1>
 <form method="POST" action="{{ route("department.guidance.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 <div><label class="block font-semibold mb-1">Student</label>
 <select name="student_id" class="w-full border rounded px-3 py-2" required>
 <option value="">-- Select --</option>
 @foreach($students as $s)<option value="{{ $s->id }}">{{ $s->full_name }}</option>@endforeach
 </select></div>
 <div><label class="block font-semibold mb-1">Session Type</label>
 <select name="session_type" class="w-full border rounded px-3 py-2" required>
 <option value="personal">Personal</option>
 <option value="academic">Academic</option>
 <option value="career">Career</option>
 <option value="family">Family</option>
 </select></div>
 <div><label class="block font-semibold mb-1">Topic</label><input type="text" name="topic" class="w-full border rounded px-3 py-2" required></div>
 <div><label class="block font-semibold mb-1">Notes</label><textarea name="notes" rows="3" class="w-full border rounded px-3 py-2" required></textarea></div>
 <div><label class="block font-semibold mb-1">Action Plan</label><textarea name="action_plan" rows="2" class="w-full border rounded px-3 py-2"></textarea></div>
 <div><label class="block font-semibold mb-1">Date</label><input type="date" name="session_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required></div>
 <label class="flex items-center gap-2"><input type="checkbox" name="follow_up_needed" value="1"><span>Follow-up needed</span></label>
 <button type="submit" class="bg-indigo-700 text-white px-6 py-2 rounded">Save Session</button>
 </form>
@endsection