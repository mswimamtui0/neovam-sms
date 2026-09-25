@extends("layouts.app")
@section("title", "Sports")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-green-900">Sports Department</h1>
 <a href="{{ route("department.sports.create") }}" class="bg-green-700 text-white px-4 py-2 rounded">Add Team</a>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-green-700 text-white"><tr><th class="p-3 text-left">Team</th><th class="p-3 text-left">Sport</th><th class="p-3 text-left">Coach</th><th class="p-3 text-left">Matches</th></tr></thead>
 <tbody>
 @forelse($teams as $t)
 <tr class="border-b"><td class="p-3">{{ $t->name }}</td><td class="p-3">{{ $t->sport }}</td><td class="p-3">{{ $t->coach?->full_name ?? "-" }}</td><td class="p-3">{{ $t->matches_count }}</td></tr>
 @empty
 <tr><td colspan="4" class="p-6 text-center text-gray-500">No teams yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $teams->links() }}</div>
@endsection