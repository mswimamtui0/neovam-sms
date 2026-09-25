@extends("layouts.app")
@section("title", "Handover Notes")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Handover Notes</h1>
 <a href="{{ route("duty.handovers.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Create Handover</a>
 </div>

 <h2 class="text-xl font-bold text-blue-900 mb-3">Sent to Others</h2>
 <div class="bg-white rounded shadow overflow-hidden mb-6">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">To</th>
 <th class="p-3 text-left">Notes</th>
 <th class="p-3 text-left">Acknowledged</th>
 </tr>
 </thead>
 <tbody>
 @forelse($from as $h)
 <tr class="border-b">
 <td class="p-3">{{ $h->handover_date->format("Y-m-d") }}</td>
 <td class="p-3">{{ $h->toStaff?->full_name ?? "-" }}</td>
 <td class="p-3">{{ \Illuminate\Support\Str::limit($h->notes, 50) }}</td>
 <td class="p-3">{{ $h->acknowledged ? "Yes" : "No" }}</td>
 </tr>
 @empty
 <tr><td colspan="4" class="p-6 text-center text-gray-500">No handovers sent.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>

 <h2 class="text-xl font-bold text-blue-900 mb-3">Received</h2>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">From</th>
 <th class="p-3 text-left">Notes</th>
 </tr>
 </thead>
 <tbody>
 @forelse($to as $h)
 <tr class="border-b">
 <td class="p-3">{{ $h->handover_date->format("Y-m-d") }}</td>
 <td class="p-3">{{ $h->fromStaff?->full_name ?? "-" }}</td>
 <td class="p-3">{{ \Illuminate\Support\Str::limit($h->notes, 50) }}</td>
 </tr>
 @empty
 <tr><td colspan="3" class="p-6 text-center text-gray-500">No handovers received.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
@endsection