@extends("layouts.app")
@section("title", "Staff Directory")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Staff Directory</h1>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Name</th>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Department</th>
 <th class="p-3 text-left">Phone</th>
 <th class="p-3 text-left">Email</th>
 </tr>
 </thead>
 <tbody>
 @forelse($staff as $s)
 <tr class="border-b">
 <td class="p-3 font-semibold">{{ $s->full_name }}</td>
 <td class="p-3">{{ $s->staff_type ?? "-" }}</td>
 <td class="p-3">{{ $s->department }}</td>
 <td class="p-3">{{ $s->phone }}</td>
 <td class="p-3">{{ $s->email ?? "-" }}</td>
 </tr>
 @empty
 <tr><td colspan="5" class="p-6 text-center text-gray-500">No staff.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $staff->links() }}</div>
@endsection