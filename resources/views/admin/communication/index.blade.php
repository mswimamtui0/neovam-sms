@extends("layouts.app")
@section("title", "Communication")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">SMS Communication</h1>
 <a href="{{ route("admin.communication.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">Send SMS</a>
 </div>

 <div class="grid grid-cols-2 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-sm text-gray-500">Total SMS Units Used</div>
 <div class="text-3xl font-bold text-blue-900">{{ number_format($totalUnits) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-sm text-gray-500">This Month</div>
 <div class="text-3xl font-bold text-green-900">{{ number_format($monthUnits) }}</div>
 </div>
 </div>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Time</th>
 <th class="p-3 text-left">Recipient</th>
 <th class="p-3 text-left">Trigger</th>
 <th class="p-3 text-left">Message</th>
 <th class="p-3 text-left">Chars</th>
 <th class="p-3 text-left">Units</th>
 <th class="p-3 text-left">Status</th>
 </tr>
 </thead>
 <tbody>
 @forelse($logs as $log)
 <tr class="border-b hover:bg-gray-50">
 <td class="p-3">{{ $log->created_at->format("Y-m-d H:i") }}</td>
 <td class="p-3">{{ $log->recipient }}</td>
 <td class="p-3">{{ $log->trigger }}</td>
 <td class="p-3">{{ \Illuminate\Support\Str::limit($log->message, 50) }}</td>
 <td class="p-3">{{ $log->char_count ?? "-" }}</td>
 <td class="p-3 font-bold">{{ $log->units ?? 1 }}</td>
 <td class="p-3">
 <span class="px-2 py-1 rounded text-xs
 @if($log->status === "sent") bg-green-100 text-green-800
 @elseif($log->status === "failed") bg-red-100 text-red-800
 @else bg-yellow-100 text-yellow-800 @endif">
 {{ ucfirst($log->status) }}
 </span>
 </td>
 </tr>
 @empty
 <tr><td colspan="7" class="p-6 text-center text-gray-500">No SMS sent yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $logs->links() }}</div>
@endsection