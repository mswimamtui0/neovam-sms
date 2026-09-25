@extends("layouts.app")
@section("title", "SMS Cost Tracking")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">SMS Cost Tracking</h1>
 <div class="flex gap-2">
 <a href="{{ route("admin.sms-cost.pricing") }}" class="bg-gray-700 text-white px-4 py-2 rounded">Pricing Rules</a>
 <a href="{{ route("admin.sms-cost.transactions") }}" class="bg-gray-700 text-white px-4 py-2 rounded">Transactions</a>
 </div>
 </div>

 {{-- Balance + Current Price --}}
 <div class="grid grid-cols-3 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-sm text-gray-500">Balance (Units)</div>
 <div class="text-3xl font-bold text-blue-900">{{ number_format($balanceUnits) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-sm text-gray-500">Balance (Money)</div>
 <div class="text-3xl font-bold text-green-900">TZS {{ number_format($balanceMoney) }}
 </div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-sm text-gray-500">Current Unit Price</div>
 <div class="text-3xl font-bold text-yellow-900">
 @if($pricing)
 {{ $pricing->currency }} {{ number_format((float)$pricing->unit_price, 2) }}
 @else
 Not set
 @endif
 </div>
 </div>
 </div>

 {{-- Top-up form --}}
 <div class="bg-white rounded shadow p-4 mb-6">
 <h3 class="font-bold text-blue-900 mb-3">Add Balance</h3>
 <form method="POST" action="{{ route("admin.sms-cost.topup") }}" class="grid grid-cols-5 gap-3">
 @csrf
 <input type="number" name="units" placeholder="Units" class="border rounded px-3 py-2" required>
 <input type="number" step="0.01" name="amount" placeholder="Amount" class="border rounded px-3 py-2" required>
 <input type="text" name="reference" placeholder="Reference (e.g. INV-001)" class="border rounded px-3 py-2">
 <input type="text" name="description" placeholder="Description" class="border rounded px-3 py-2">
 <button class="bg-green-700 text-white rounded px-4 py-2">Top Up</button>
 </form>
 </div>

 {{-- Period filter --}}
 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <input type="date" name="from" value="{{ $from->format("Y-m-d") }}" class="border rounded px-3 py-2">
 <input type="date" name="to" value="{{ $to->format("Y-m-d") }}" class="border rounded px-3 py-2">
 <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
 <a href="{{ route("admin.sms-cost.index") }}" class="text-gray-600 px-4 py-2">Reset</a>
 </form>

 {{-- Summary cards for the period --}}
 <div class="grid grid-cols-5 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-sm text-gray-500">Total SMS</div>
 <div class="text-2xl font-bold">{{ number_format($summary["count"]) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-sm text-gray-500">Sent</div>
 <div class="text-2xl font-bold text-green-900">{{ number_format($summary["sent"]) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
 <div class="text-sm text-gray-500">Failed</div>
 <div class="text-2xl font-bold text-red-900">{{ number_format($summary["failed"]) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
 <div class="text-sm text-gray-500">Units Used</div>
 <div class="text-2xl font-bold">{{ number_format($summary["units"]) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-sm text-gray-500">Cost</div>
 <div class="text-2xl font-bold">TZS {{ number_format($summary["cost"]) }}</div>
 </div>
 </div>

 {{-- Cost by trigger --}}
 <div class="grid grid-cols-2 gap-4">
 <div class="bg-white rounded shadow p-6">
 <h3 class="font-bold text-blue-900 mb-3">Cost by Trigger</h3>
 <table class="w-full text-sm">
 <thead class="bg-gray-100">
 <tr>
 <th class="p-2 text-left">Trigger</th>
 <th class="p-2 text-right">Count</th>
 <th class="p-2 text-right">Units</th>
 <th class="p-2 text-right">Cost (TZS)</th>
 </tr>
 </thead>
 <tbody>
 @forelse($summary["byTrigger"] as $row)
 <tr class="border-b">
 <td class="p-2 font-mono text-xs">{{ $row["trigger"] }}</td>
 <td class="p-2 text-right">{{ number_format($row["count"]) }}</td>
 <td class="p-2 text-right">{{ number_format($row["units"]) }}</td>
 <td class="p-2 text-right">{{ number_format($row["cost"]) }}</td>
 </tr>
 @empty
 <tr><td colspan="4" class="p-4 text-center text-gray-500">No data.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>

 <div class="bg-white rounded shadow p-6">
 <h3 class="font-bold text-blue-900 mb-3">Cost by Day</h3>
 <table class="w-full text-sm">
 <thead class="bg-gray-100">
 <tr>
 <th class="p-2 text-left">Day</th>
 <th class="p-2 text-right">Units</th>
 <th class="p-2 text-right">Cost (TZS)</th>
 </tr>
 </thead>
 <tbody>
 @forelse($summary["byDay"] as $row)
 <tr class="border-b">
 <td class="p-2">{{ $row["day"] }}</td>
 <td class="p-2 text-right">{{ number_format($row["units"]) }}</td>
 <td class="p-2 text-right">{{ number_format($row["cost"]) }}</td>
 </tr>
 @empty
 <tr><td colspan="3" class="p-4 text-center text-gray-500">No data.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 </div>
@endsection