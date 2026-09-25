@extends("layouts.app")
@section("title", "Missing Staff")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Staff Not Checked In — {{ $date }}</h1>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <input type="date" name="date" value="{{ $date }}" class="border rounded px-3 py-2">
 <button class="bg-blue-900 text-white px-4 py-2 rounded">View</button>
 </form>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Staff No</th>
 <th class="p-3 text-left">Name</th>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Department</th>
 <th class="p-3 text-left">Phone</th>
 </tr>
 </thead>
 <tbody>
 @forelse($missing as $s)
 <tr class="border-b">
 <td class="p-3">{{ $s->staff_no }}</td>
 <td class="p-3 font-semibold">{{ $s->full_name }}</td>
 <td class="p-3">{{ $s->staff_type ?? "-" }}</td>
 <td class="p-3">{{ $s->department }}</td>
 <td class="p-3">{{ $s->phone }}</td>
 </tr>
 @empty
 <tr><td colspan="5" class="p-6 text-center text-green-600">All staff have checked in. </td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $missing->links() }}</div>
@endsection