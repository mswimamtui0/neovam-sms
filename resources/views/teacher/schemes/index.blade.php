@extends("layouts.app")
@section("title", "Schemes of Work")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Schemes of Work</h1>
 <a href="{{ route("teacher.schemes.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Scheme</a>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Term</th>
 <th class="p-3 text-left">Year</th>
 <th class="p-3 text-left">Class</th>
 <th class="p-3 text-left">Subject</th>
 <th class="p-3 text-left">Status</th>
 </tr>
 </thead>
 <tbody>
 @forelse($schemes as $s)
 <tr class="border-b">
 <td class="p-3">{{ $s->term }}</td>
 <td class="p-3">{{ $s->year }}</td>
 <td class="p-3">{{ $s->classroom?->name ?? "-" }}</td>
 <td class="p-3">{{ $s->subject?->name ?? "-" }}</td>
 <td class="p-3">{{ ucfirst($s->status) }}</td>
 </tr>
 @empty
 <tr><td colspan="5" class="p-6 text-center text-gray-500">No schemes yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $schemes->links() }}</div>
@endsection