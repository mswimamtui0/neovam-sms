@extends("layouts.app")
@section("title", "SMS Pricing Rules")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">SMS Pricing Rules</h1>
 <a href="{{ route("admin.sms-cost.index") }}" class="text-blue-700 hover:underline">Back to Cost Dashboard</a>
 </div>

 <div class="bg-white rounded shadow p-4 mb-6">
 <h3 class="font-bold text-blue-900 mb-3">Add Pricing Rule</h3>
 <form method="POST" action="{{ route("admin.sms-cost.pricing.store") }}" class="grid grid-cols-5 gap-3">
 @csrf
 <input type="text" name="name" placeholder="Rule name" class="border rounded px-3 py-2" required>
 <input type="number" step="0.0001" name="unit_price" placeholder="Price per unit" class="border rounded px-3 py-2" required>
 <input type="text" name="currency" value="TZS" class="border rounded px-3 py-2" required>
 <input type="date" name="effective_from" value="{{ now()->toDateString() }}" class="border rounded px-3 py-2" required>
 <button class="bg-blue-900 text-white rounded px-4 py-2">Save</button>
 </form>
 </div>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Name</th>
 <th class="p-3 text-left">Unit Price</th>
 <th class="p-3 text-left">Currency</th>
 <th class="p-3 text-left">From</th>
 <th class="p-3 text-left">To</th>
 <th class="p-3 text-left">Active</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($rules as $rule)
 <tr class="border-b">
 <td class="p-3 font-semibold">{{ $rule->name }}</td>
 <td class="p-3">{{ number_format((float)$rule->unit_price, 4) }}</td>
 <td class="p-3">{{ $rule->currency }}</td>
 <td class="p-3">{{ $rule->effective_from?->format("Y-m-d") }}</td>
 <td class="p-3">{{ $rule->effective_to?->format("Y-m-d") ?? "-" }}</td>
 <td class="p-3">{{ $rule->is_active ? "Yes" : "No" }}</td>
 <td class="p-3">
 <form method="POST" action="{{ route("admin.sms-cost.pricing.destroy", $rule) }}" class="inline" onsubmit="return confirm(&quot;Delete?&quot;);">
 @csrf @method("DELETE")
 <button class="text-red-700 hover:underline">Delete</button>
 </form>
 </td>
 </tr>
 @empty
 <tr><td colspan="7" class="p-6 text-center text-gray-500">No pricing rules yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $rules->links() }}</div>
@endsection