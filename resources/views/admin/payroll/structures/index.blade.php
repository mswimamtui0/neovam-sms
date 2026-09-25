@extends("layouts.app")
@section("title", "Salary Structures")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Salary Structures</h1>
 <a href="{{ route("admin.salary-structures.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Structure</a>
 </div>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Staff</th>
 <th class="p-3 text-right">Basic</th>
 <th class="p-3 text-right">Allowances</th>
 <th class="p-3 text-right">Deductions</th>
 <th class="p-3 text-right">Net Pay</th>
 <th class="p-3 text-left">Effective</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($structures as $s)
 <tr class="border-b">
 <td class="p-3 font-semibold">{{ $s->staff?->full_name }}</td>
 <td class="p-3 text-right">{{ number_format($s->basic_salary) }}</td>
 <td class="p-3 text-right">{{ number_format($s->totalAllowances()) }}</td>
 <td class="p-3 text-right text-red-700">{{ number_format($s->totalDeductions()) }}</td>
 <td class="p-3 text-right font-bold text-green-700">{{ number_format($s->netPay()) }}</td>
 <td class="p-3">{{ $s->effective_from?->format("d M Y") }}</td>
 <td class="p-3">
 @if($s->is_active)
 <span class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-800">Active</span>
 @else
 <span class="text-xs px-2 py-0.5 rounded bg-gray-200 text-gray-600">Inactive</span>
 @endif
 </td>
 <td class="p-3">
 <a href="{{ route("admin.salary-structures.edit", $s) }}" class="text-yellow-700 hover:underline">Edit</a>
 </td>
 </tr>
 @empty
 <tr><td colspan="8" class="p-6 text-center text-gray-500">No structures yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $structures->links() }}</div>
@endsection