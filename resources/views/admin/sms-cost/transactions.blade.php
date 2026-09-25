@extends("layouts.app")
@section("title", "SMS Balance Transactions")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">SMS Balance Transactions</h1>
 <a href="{{ route("admin.sms-cost.index") }}" class="text-blue-700 hover:underline">Back</a>
 </div>

 <div class="grid grid-cols-2 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-sm text-gray-500">Balance (Units)</div>
 <div class="text-3xl font-bold text-blue-900">{{ number_format($balanceUnits) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-sm text-gray-500">Balance (Money)</div>
 <div class="text-3xl font-bold text-green-900">TZS {{ number_format($balanceMoney) }}</div>
 </div>
 </div>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <select name="type" class="border rounded px-3 py-2">
 <option value="">All Types</option>
 @foreach(["topup","debit","refund","adjustment"] as $t)
 <option value="{{ $t }}" {{ request("type") === $t ? "selected" : "" }}>{{ ucfirst($t) }}</option>
 @endforeach
 </select>
 <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
 <a href="{{ route("admin.sms-cost.transactions") }}" class="text-gray-600 px-4 py-2">Reset</a>
 </form>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Units</th>
 <th class="p-3 text-left">Amount</th>
 <th class="p-3 text-left">Reference</th>
 <th class="p-3 text-left">Description</th>
 <th class="p-3 text-left">By</th>
 </tr>
 </thead>
 <tbody>
 @forelse($transactions as $t)
 <tr class="border-b">
 <td class="p-3 whitespace-nowrap">{{ $t->created_at->format("Y-m-d H:i") }}</td>
 <td class="p-3">
 <span class="text-xs px-2 py-0.5 rounded
 @if($t->type === "topup") bg-green-100 text-green-800
 @elseif($t->type === "debit") bg-red-100 text-red-800
 @else bg-gray-100 text-gray-800 @endif">
 {{ ucfirst($t->type) }}
 </span>
 </td>
 <td class="p-3 {{ $t->units >= 0 ? "text-green-700" : "text-red-700" }}">
 {{ $t->units >= 0 ? "+" : "" }}{{ number_format($t->units) }}
 </td>
 <td class="p-3">{{ $t->currency }} {{ number_format((float)$t->amount, 2) }}</td>
 <td class="p-3 text-xs font-mono">{{ $t->reference ?? "-" }}</td>
 <td class="p-3 text-xs">{{ $t->description }}</td>
 <td class="p-3 text-xs">{{ $t->creator?->name ?? "-" }}</td>
 </tr>
 @empty
 <tr><td colspan="7" class="p-6 text-center text-gray-500">No transactions yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $transactions->links() }}</div>
@endsection