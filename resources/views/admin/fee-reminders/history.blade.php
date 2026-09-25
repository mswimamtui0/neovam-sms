@extends("layouts.app")
@section("title", "Reminder History")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Reminder History</h1>
 <a href="{{ route("admin.fee-reminders.index") }}" class="text-blue-700 hover:underline">Back</a>
 </div>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <select name="type" class="border rounded px-3 py-2">
 <option value="">All Types</option>
 @foreach(["before_due","on_due","after_due","overdue_final"] as $t)
 <option value="{{ $t }}" {{ request("type") === $t ? "selected" : "" }}>{{ ucfirst(str_replace("_"," ",$t)) }}</option>
 @endforeach
 </select>
 <select name="status" class="border rounded px-3 py-2">
 <option value="">All Statuses</option>
 <option value="sent" {{ request("status") === "sent" ? "selected" : "" }}>Sent</option>
 <option value="failed" {{ request("status") === "failed" ? "selected" : "" }}>Failed</option>
 </select>
 <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
 <a href="{{ route("admin.fee-reminders.history") }}" class="text-gray-600 px-4 py-2">Reset</a>
 </form>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Student</th>
 <th class="p-3 text-right">Balance</th>
 <th class="p-3 text-left">Due Date</th>
 <th class="p-3 text-left">SMS Sent</th>
 </tr>
 </thead>
 <tbody>
 @forelse($reminders as $r)
 <tr class="border-b">
 <td class="p-3 whitespace-nowrap">{{ $r->created_at->format("d M Y H:i") }}</td>
 <td class="p-3">{{ $r->typeLabel() }}</td>
 <td class="p-3 font-semibold">{{ $r->student?->full_name }}</td>
 <td class="p-3 text-right font-bold text-red-700">{{ number_format($r->balance) }}</td>
 <td class="p-3">{{ $r->due_date?->format("d M Y") }}</td>
 <td class="p-3">
 @if($r->sms_sent)
 <span class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-800">Sent</span>
 @else
 <span class="text-xs px-2 py-0.5 rounded bg-red-100 text-red-800">Failed</span>
 @endif
 </td>
 </tr>
 @empty
 <tr><td colspan="6" class="p-6 text-center text-gray-500">No reminders yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $reminders->links() }}</div>
@endsection