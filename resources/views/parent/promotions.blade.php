@extends("layouts.app")
@section("title", "Promotions")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Promotion History</h1>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Child</th>
 <th class="p-3 text-left">From</th>
 <th class="p-3 text-left">To</th>
 <th class="p-3 text-left">Year</th>
 <th class="p-3 text-left">Status</th>
 </tr>
 </thead>
 <tbody>
 @forelse($promotions as $p)
 <tr class="border-b">
 <td class="p-3">{{ $p->promoted_at?->format("Y-m-d") ?? "-" }}</td>
 <td class="p-3">{{ $p->student?->full_name }}</td>
 <td class="p-3">{{ $p->fromClassroom?->name ?? $p->from_level ?? "-" }}</td>
 <td class="p-3 font-semibold">{{ $p->toClassroom?->name ?? $p->to_level ?? "-" }}</td>
 <td class="p-3">{{ $p->academic_year }}</td>
 <td class="p-3">{{ ucfirst($p->status) }}</td>
 </tr>
 @empty
 <tr><td colspan="6" class="p-6 text-center text-gray-500">No promotions yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $promotions->links() }}</div>
@endsection