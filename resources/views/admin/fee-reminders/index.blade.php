@extends("layouts.app")
@section("title", "Fee Reminders")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Fee Reminders</h1>
 <div class="flex gap-2">
 <a href="{{ route("admin.fee-reminders.history") }}" class="bg-gray-700 text-white px-4 py-2 rounded">History</a>
 <form method="POST" action="{{ route("admin.fee-reminders.send-all") }}" class="inline"
 onsubmit="return confirm(&quot;Send ALL pending reminders now?&quot;);">
 @csrf
 <button class="bg-green-700 text-white px-4 py-2 rounded">Send All Now</button>
 </form>
 </div>
 </div>

 <div class="grid grid-cols-5 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">Total Sent</div>
 <div class="text-2xl font-bold">{{ number_format($stats["total_reminders"]) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Sent Today</div>
 <div class="text-2xl font-bold text-green-800">{{ $stats["sent_today"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
 <div class="text-xs text-gray-500">This Month</div>
 <div class="text-2xl font-bold text-purple-800">{{ $stats["sent_this_month"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
 <div class="text-xs text-gray-500">Failed</div>
 <div class="text-2xl font-bold text-red-800">{{ $stats["failed"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Pending Now</div>
 <div class="text-2xl font-bold text-yellow-800">
 {{ $pending["before_due"]->count() + $pending["on_due"]->count() + $pending["after_due"]->count() + $pending["overdue_final"]->count() }}
 </div>
 </div>
 </div>

 @php
 $groups = [
 ["key" => "before_due", "label" => "3 Days Before Due", "color" => "blue", "rows" => $pending["before_due"]],
 ["key" => "on_due", "label" => "On Due Date", "color" => "yellow", "rows" => $pending["on_due"]],
 ["key" => "after_due", "label" => "Overdue Follow-up", "color" => "orange", "rows" => $pending["after_due"]],
 ["key" => "overdue_final", "label" => "Final Notice (7+ days)","color" => "red", "rows" => $pending["overdue_final"]],
 ];
 @endphp

 @foreach($groups as $group)
 <div class="bg-white rounded shadow p-6 mb-6 border-l-4 border-{{ $group["color"] }}-600">
 <div class="flex justify-between items-center mb-4">
 <h2 class="text-xl font-bold text-{{ $group["color"] }}-900">
 {{ $group["label"] }}
 <span class="text-sm text-gray-500 font-normal">({{ $group["rows"]->count() }} pending)</span>
 </h2>
 @if($group["rows"]->count() > 0)
 <form method="POST" action="{{ route("admin.fee-reminders.send") }}" class="inline"
 onsubmit="return confirm(&quot;Send {{ $group["rows"]->count() }} reminders?&quot;);">
 @csrf
 <input type="hidden" name="type" value="{{ $group["key"] }}">
 <button class="bg-{{ $group["color"] }}-700 text-white px-4 py-2 rounded text-sm">Send {{ $group["rows"]->count() }} Now
 </button>
 </form>
 @endif
 </div>

 @if($group["rows"]->isEmpty())
 <p class="text-gray-500 text-sm">No pending reminders in this group.</p>
 @else
 <table class="w-full text-sm">
 <thead class="bg-gray-100">
 <tr>
 <th class="p-2 text-left">Student</th>
 <th class="p-2 text-left">Class</th>
 <th class="p-2 text-right">Balance (TZS)</th>
 <th class="p-2 text-left">Due Date</th>
 <th class="p-2 text-left">Parent Phone</th>
 </tr>
 </thead>
 <tbody>
 @foreach($group["rows"] as $inv)
 <tr class="border-b">
 <td class="p-2 font-semibold">{{ $inv->student?->full_name }}</td>
 <td class="p-2 text-xs">{{ $inv->student?->classroom?->name ?? "-" }}</td>
 <td class="p-2 text-right font-bold text-red-700">{{ number_format($inv->balance) }}</td>
 <td class="p-2">{{ $inv->due_date?->format("d M Y") }}</td>
 <td class="p-2 text-xs">{{ $inv->student?->parent_phone }}</td>
 </tr>
 @endforeach
 </tbody>
 </table>
 @endif
 </div>
 @endforeach

 <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded">
 <strong>How it works:</strong>
 <ul class="list-disc pl-5 mt-2 text-sm">
 <li>System scans invoices automatically every day at 08:00</li>
 <li>Reminders are grouped: 3 days before, on due, after due, final notice (7+ days)</li>
 <li>No duplicate — each invoice gets only one reminder per group per day</li>
 <li>Manual "Send Now" button available per group</li>
 <li>See all sent reminders in History tab</li>
 </ul>
 </div>
@endsection