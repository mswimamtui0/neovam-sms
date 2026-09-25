@extends("layouts.app")
@section("title", "SMS Analytics")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">SMS Analytics</h1>
 <a href="{{ route("admin.analytics.index") }}" class="text-blue-700 hover:underline">Back</a>
 </div>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <input type="date" name="from" value="{{ $from->format("Y-m-d") }}" class="border rounded px-3 py-2">
 <input type="date" name="to" value="{{ $to->format("Y-m-d") }}" class="border rounded px-3 py-2">
 <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
 </form>

 <div class="grid grid-cols-5 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">Total</div>
 <div class="text-2xl font-bold">{{ number_format($data["total"]) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Sent</div>
 <div class="text-2xl font-bold text-green-800">{{ number_format($data["sent"]) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
 <div class="text-xs text-gray-500">Failed</div>
 <div class="text-2xl font-bold text-red-800">{{ number_format($data["failed"]) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
 <div class="text-xs text-gray-500">Units</div>
 <div class="text-2xl font-bold">{{ number_format($data["units"]) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Cost (TZS)</div>
 <div class="text-2xl font-bold">{{ number_format($data["cost"]) }}</div>
 </div>
 </div>

 <div class="grid grid-cols-2 gap-6 mb-6">
 <div class="bg-white rounded shadow p-6">
 <h2 class="text-xl font-bold text-blue-900 mb-3">By Trigger</h2>
 <table class="w-full text-sm">
 <thead class="bg-gray-100">
 <tr>
 <th class="p-2 text-left">Trigger</th>
 <th class="p-2 text-right">Count</th>
 <th class="p-2 text-right">Units</th>
 </tr>
 </thead>
 <tbody>
 @forelse($data["by_trigger"] as $row)
 <tr class="border-b">
 <td class="p-2 font-mono text-xs">{{ $row["trigger"] }}</td>
 <td class="p-2 text-right">{{ number_format($row["count"]) }}</td>
 <td class="p-2 text-right">{{ number_format($row["units"]) }}</td>
 </tr>
 @empty
 <tr><td colspan="3" class="p-4 text-center text-gray-500">No data.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>

 <div class="bg-white rounded shadow p-6">
 <h2 class="text-xl font-bold text-blue-900 mb-3">By Delivery Status</h2>
 <table class="w-full text-sm">
 <thead class="bg-gray-100">
 <tr>
 <th class="p-2 text-left">Status</th>
 <th class="p-2 text-right">Count</th>
 </tr>
 </thead>
 <tbody>
 @forelse($data["by_delivery"] as $status => $count)
 <tr class="border-b">
 <td class="p-2">{{ ucfirst($status) }}</td>
 <td class="p-2 text-right font-bold">{{ number_format($count) }}</td>
 </tr>
 @empty
 <tr><td colspan="2" class="p-4 text-center text-gray-500">No data.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 </div>

 <div class="bg-white rounded shadow p-6">
 <h2 class="text-xl font-bold text-blue-900 mb-3">Daily SMS</h2>
 <table class="w-full text-sm">
 <thead class="bg-gray-100">
 <tr>
 <th class="p-2 text-left">Day</th>
 <th class="p-2 text-right">Count</th>
 <th class="p-2 text-right">Units</th>
 </tr>
 </thead>
 <tbody>
 @forelse($data["daily"] as $row)
 <tr class="border-b">
 <td class="p-2">{{ $row["day"] }}</td>
 <td class="p-2 text-right">{{ number_format($row["count"]) }}</td>
 <td class="p-2 text-right">{{ number_format($row["units"]) }}</td>
 </tr>
 @empty
 <tr><td colspan="3" class="p-4 text-center text-gray-500">No data.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
@endsection