@extends("layouts.app")
@section("title", "Student Class Movements")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Student Class Movements</h1>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Student</th>
 <th class="p-3 text-left">From Class</th>
 <th class="p-3 text-left">To Class</th>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Reason</th>
 <th class="p-3 text-left">By</th>
 </tr>
 </thead>
 <tbody>
 @forelse($movements as $m)
 <tr class="border-b">
 <td class="p-3">{{ $m->effective_date?->format("d M Y") }}</td>
 <td class="p-3 font-semibold">{{ $m->student?->full_name }}</td>
 <td class="p-3">{{ $m->fromClassroom?->name ?? "-" }}</td>
 <td class="p-3">{{ $m->toClassroom?->name ?? "-" }}</td>
 <td class="p-3">{{ ucfirst(str_replace("_"," ",$m->movement_type)) }}</td>
 <td class="p-3 text-xs">{{ $m->reason ?? "-" }}</td>
 <td class="p-3 text-xs">{{ $m->mover?->name ?? "-" }}</td>
 </tr>
 @empty
 <tr><td colspan="7" class="p-6 text-center text-gray-500">No movements yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $movements->links() }}</div>
@endsection