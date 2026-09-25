@extends("layouts.app")
@section("title", "Fee Structures")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Fee Structures</h1>
 <a href="{{ route("admin.fee-structures.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Fee Structure</a>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Level</th>
 <th class="p-3 text-left">Term</th>
 <th class="p-3 text-left">Year</th>
 <th class="p-3 text-left">Total</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($fees as $f)
 <tr class="border-b">
 <td class="p-3">{{ ucfirst($f->level) }}</td>
 <td class="p-3">{{ $f->term }}</td>
 <td class="p-3">{{ $f->year }}</td>
 <td class="p-3 font-bold">TZS {{ number_format($f->total()) }}</td>
 <td class="p-3">
 <a href="{{ route("admin.fee-structures.edit", $f) }}" class="text-yellow-700 hover:underline">Edit</a>
 <form method="POST" action="{{ route("admin.fee-structures.destroy", $f) }}" class="inline ml-2" onsubmit="return confirm(&quot;Delete?&quot;);">
 @csrf @method("DELETE")
 <button class="text-red-700 hover:underline">Delete</button>
 </form>
 </td>
 </tr>
 @empty
 <tr><td colspan="5" class="p-6 text-center text-gray-500">No fee structures yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $fees->links() }}</div>
@endsection