@extends("layouts.app")
@section("title", "Payroll")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Payroll</h1>
 <div class="flex gap-2">
 <a href="{{ route("admin.salary-structures.index") }}" class="bg-gray-700 text-white px-4 py-2 rounded">Salary Structures</a>
 <a href="{{ route("admin.payroll-runs.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">New Payroll Run</a>
 </div>
 </div>

 <div class="grid grid-cols-5 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">Total Runs</div>
 <div class="text-2xl font-bold">{{ $stats["total_runs"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">This Year</div>
 <div class="text-2xl font-bold text-green-800">{{ $stats["runs_this_year"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
 <div class="text-xs text-gray-500">Active Structures</div>
 <div class="text-2xl font-bold text-purple-800">{{ $stats["active_structures"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Pending (TZS)</div>
 <div class="text-xl font-bold text-yellow-800">{{ number_format($stats["total_pending"]) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Paid YTD (TZS)</div>
 <div class="text-xl font-bold text-green-800">{{ number_format($stats["total_paid_ytd"]) }}</div>
 </div>
 </div>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Period</th>
 <th class="p-3 text-left">Label</th>
 <th class="p-3 text-right">Staff</th>
 <th class="p-3 text-right">Gross (TZS)</th>
 <th class="p-3 text-right">Net (TZS)</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($runs as $run)
 <tr class="border-b hover:bg-gray-50">
 <td class="p-3 font-mono">{{ $run->period }}</td>
 <td class="p-3 font-semibold">{{ $run->label }}</td>
 <td class="p-3 text-right">{{ $run->staff_count }}</td>
 <td class="p-3 text-right">{{ number_format($run->total_gross) }}</td>
 <td class="p-3 text-right font-bold">{{ number_format($run->total_net) }}</td>
 <td class="p-3">
 <span class="text-xs px-2 py-0.5 rounded
 @if($run->status === "paid") bg-green-100 text-green-800
 @elseif($run->status === "approved") bg-blue-100 text-blue-800
 @elseif($run->status === "draft") bg-gray-200 text-gray-800
 @else bg-red-100 text-red-800 @endif">
 {{ ucfirst($run->status) }}
 </span>
 </td>
 <td class="p-3">
 <a href="{{ route("admin.payroll-runs.show", $run) }}" class="text-blue-700 hover:underline">View</a>
 </td>
 </tr>
 @empty
 <tr><td colspan="7" class="p-6 text-center text-gray-500">No payroll runs yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $runs->links() }}</div>
@endsection