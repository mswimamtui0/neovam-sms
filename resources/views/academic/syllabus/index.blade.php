@extends("layouts.app")
@section("title", "Syllabus Coverage")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Syllabus Coverage</h1>
 <a href="{{ route("academic.syllabus.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Coverage</a>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Class</th>
 <th class="p-3 text-left">Subject</th>
 <th class="p-3 text-left">Teacher</th>
 <th class="p-3 text-left">Term</th>
 <th class="p-3 text-left">Covered / Planned</th>
 <th class="p-3 text-left">%</th>
 </tr>
 </thead>
 <tbody>
 @forelse($coverage as $c)
 <tr class="border-b">
 <td class="p-3">{{ $c->classroom?->name }}</td>
 <td class="p-3">{{ $c->subject?->name }}</td>
 <td class="p-3">{{ $c->staff?->full_name ?? "-" }}</td>
 <td class="p-3">{{ $c->term }} {{ $c->year }}</td>
 <td class="p-3">{{ $c->covered_topics }} / {{ $c->planned_topics }}</td>
 <td class="p-3 font-bold">{{ $c->percent() }}%</td>
 </tr>
 @empty
 <tr><td colspan="6" class="p-6 text-center text-gray-500">No entries yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $coverage->links() }}</div>
@endsection