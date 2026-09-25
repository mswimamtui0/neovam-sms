@extends("layouts.app")
@section("title", "Meetings")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Academic Meetings</h1>
 <a href="{{ route("academic.meetings.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Schedule Meeting</a>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Title</th>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Venue</th>
 <th class="p-3 text-left">Status</th>
 </tr>
 </thead>
 <tbody>
 @forelse($meetings as $m)
 <tr class="border-b">
 <td class="p-3">{{ $m->title }}</td>
 <td class="p-3">{{ ucfirst($m->meeting_type) }}</td>
 <td class="p-3">{{ $m->meeting_date?->format("Y-m-d") }}</td>
 <td class="p-3">{{ $m->venue ?? "-" }}</td>
 <td class="p-3">{{ ucfirst($m->status) }}</td>
 </tr>
 @empty
 <tr><td colspan="5" class="p-6 text-center text-gray-500">No meetings.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $meetings->links() }}</div>
@endsection