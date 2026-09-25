@extends("layouts.app")
@section("title", "Skipped Messages")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Skipped SMS</h1>
 <div class="flex gap-2">
 <form method="POST" action="{{ route("admin.sms-control.clear-skipped") }}" onsubmit="return confirm(&quot;Clear entire log?&quot;);">
 @csrf @method("DELETE")
 <button class="bg-red-700 text-white px-4 py-2 rounded text-sm">Clear Log</button>
 </form>
 <a href="{{ route("admin.sms-control.index") }}" class="text-blue-700 hover:underline px-4 py-2">Back</a>
 </div>
 </div>

 <p class="text-gray-600 mb-4">Messages that would have been sent but were skipped by the system (master off, trigger off, schedule, rate limit).</p>

 <div class="grid grid-cols-4 gap-4 mb-6">
 @foreach(["master_off","trigger_off","schedule","rate_limit"] as $reason)
 <div class="bg-white rounded shadow p-4">
 <div class="text-xs text-gray-500">{{ ucfirst(str_replace("_"," ",$reason)) }}</div>
 <div class="text-2xl font-bold">{{ $byReason[$reason] ?? 0 }}</div>
 </div>
 @endforeach
 </div>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Time</th>
 <th class="p-3 text-left">Recipient</th>
 <th class="p-3 text-left">Trigger</th>
 <th class="p-3 text-left">Reason</th>
 <th class="p-3 text-left">Message</th>
 </tr>
 </thead>
 <tbody>
 @forelse($skipped as $s)
 <tr class="border-b">
 <td class="p-3 whitespace-nowrap">{{ $s->created_at->format("d M H:i") }}</td>
 <td class="p-3">{{ $s->recipient }}</td>
 <td class="p-3 font-mono text-xs">{{ $s->trigger }}</td>
 <td class="p-3">
 <span class="text-xs px-2 py-0.5 rounded
 @if($s->skip_reason === "master_off") bg-red-100 text-red-800
 @elseif($s->skip_reason === "trigger_off") bg-yellow-100 text-yellow-800
 @elseif($s->skip_reason === "schedule") bg-blue-100 text-blue-800
 @else bg-purple-100 text-purple-800 @endif">
 {{ ucfirst(str_replace("_"," ",$s->skip_reason)) }}
 </span>
 </td>
 <td class="p-3 text-xs">{{ \Illuminate\Support\Str::limit($s->message, 60) }}</td>
 </tr>
 @empty
 <tr><td colspan="5" class="p-6 text-center text-gray-500">No skipped messages.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $skipped->links() }}</div>
@endsection