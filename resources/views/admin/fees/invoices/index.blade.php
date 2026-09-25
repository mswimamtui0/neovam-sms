@extends("layouts.app")
@section("title", "Invoices")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Invoices</h1>
 <div class="flex gap-2">
 <a href="{{ route("admin.invoices.create") }}" class="bg-gray-700 text-white px-4 py-2 rounded">Add Invoice</a>
 </div>
 </div>

 <div class="grid grid-cols-3 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-sm text-gray-500">Total Billed</div>
 <div class="text-2xl font-bold">TZS {{ number_format($totals["total"]) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-sm text-gray-500">Total Paid</div>
 <div class="text-2xl font-bold">TZS {{ number_format($totals["paid"]) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
 <div class="text-sm text-gray-500">Outstanding</div>
 <div class="text-2xl font-bold">TZS {{ number_format($totals["unpaid"]) }}</div>
 </div>
 </div>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <select name="status" class="border rounded px-3 py-2">
 <option value="">All Statuses</option>
 @foreach(["pending","partial","paid","overdue"] as $s)
 <option value="{{ $s }}" {{ request("status") === $s ? "selected" : "" }}>{{ ucfirst($s) }}</option>
 @endforeach
 </select>
 <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
 <a href="{{ route("admin.invoices.index") }}" class="text-gray-600 px-4 py-2">Reset</a>
 </form>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Invoice #</th>
 <th class="p-3 text-left">Student</th>
 <th class="p-3 text-left">Term / Year</th>
 <th class="p-3 text-left">Amount</th>
 <th class="p-3 text-left">Paid</th>
 <th class="p-3 text-left">Balance</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($invoices as $inv)
 <tr class="border-b">
 <td class="p-3">{{ $inv->invoice_no }}</td>
 <td class="p-3">{{ $inv->student?->full_name }}</td>
 <td class="p-3">{{ $inv->term }} {{ $inv->year }}</td>
 <td class="p-3">{{ number_format($inv->amount) }}</td>
 <td class="p-3">{{ number_format($inv->amount_paid) }}</td>
 <td class="p-3 font-bold">{{ number_format($inv->balance) }}</td>
 <td class="p-3">
 <span class="text-xs px-2 py-1 rounded
 @if($inv->status === "paid") bg-green-100 text-green-800
 @elseif($inv->status === "overdue") bg-red-100 text-red-800
 @elseif($inv->status === "partial") bg-yellow-100 text-yellow-800
 @else bg-gray-100 text-gray-800 @endif">
 {{ ucfirst($inv->status) }}
 </span>
 </td>
 <td class="p-3">
 <a href="{{ route("admin.invoices.show", $inv) }}" class="text-blue-700 hover:underline">View</a>
 <a href="{{ route("admin.payments.create", ["invoice_id" => $inv->id]) }}" class="text-green-700 hover:underline ml-2">Pay</a>
 </td>
 </tr>
 @empty
 <tr><td colspan="8" class="p-6 text-center text-gray-500">No invoices.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $invoices->links() }}</div>
@endsection