@extends("layouts.app")
@section("title", "Add Sports Team")
@section("content")
 <h1 class="text-3xl font-bold text-green-900 mb-6">Add Sports Team</h1>
 <form method="POST" action="{{ route("department.sports.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 <div><label class="block font-semibold mb-1">Team Name</label><input type="text" name="name" class="w-full border rounded px-3 py-2" required></div>
 <div><label class="block font-semibold mb-1">Sport</label><input type="text" name="sport" class="w-full border rounded px-3 py-2" required></div>
 <div><label class="block font-semibold mb-1">Age Group</label><input type="text" name="age_group" class="w-full border rounded px-3 py-2"></div>
 <div><label class="block font-semibold mb-1">Notes</label><textarea name="notes" rows="2" class="w-full border rounded px-3 py-2"></textarea></div>
 <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded">Create Team</button>
 </form>
@endsection