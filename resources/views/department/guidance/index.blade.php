@extends("layouts.app")
@section("title", "Guidance")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-indigo-900">Guidance &amp; Counseling</h1>
 <a href="{{ route("department.guidance.create") }}" class="bg-indigo-700 text-white px-4 py-2 rounded">New Session</a>
 </div>
 <div class="grid grid-cols-3 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4"><div class="text-xs text-gray-500">Total Sessions</div><div class="text-2xl font-bold">{{ $stats["total"] }}</div></div>
 <div class="bg-white rounded shadow p-4"><div class="text-xs text-gray-500">Today</div><div class="text-2xl font-bold">{{ $stats["today"] }}</div></div>
 <div class="bg-white rounded shadow p-4"><div class="text-xs text-gray-500">Follow-ups</div><div class="text-2xl font-bold">{{ $stats["follow_up"] }}</div></div>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-indigo-700 text-white"><tr><th class="p-3 text-left">Date</th><th class="p-3 text-left">Student</th><th class="p-3 text-left">Type</th><th class="p-3 text-left">Topic</th></tr></thead>
 <tbody>
 @forelse($sessions as $s)
 <tr class="border-b"><td class="p-3">{{ $s->session_date?->format("Y-m-d") }}</td><td class="p-3">{{ $s->student?->full_name }}</td><td class="p-3">{{ ucfirst($s->session_type) }}</td><td class="p-3">{{ $s->topic }}</td></tr>
 @empty
 <tr><td colspan="4" class="p-6 text-center text-gray-500">No sessions yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $sessions->links() }}</div>
@endsection