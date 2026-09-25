@extends("layouts.app")
@section("title", "Department Tasks")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">My Department Tasks</h1>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Title</th>
 <th class="p-3 text-left">Department</th>
 <th class="p-3 text-left">Due Date</th>
 <th class="p-3 text-left">Priority</th>
 <th class="p-3 text-left">Status</th>
 </tr>
 </thead>
 <tbody>
 @forelse($tasks as $t)
 <tr class="border-b">
 <td class="p-3">{{ $t->title }}</td>
 <td class="p-3">{{ $t->department }}</td>
 <td class="p-3">{{ $t->due_date?->format("Y-m-d") ?? "-" }}</td>
 <td class="p-3">{{ ucfirst($t->priority) }}</td>
 <td class="p-3">{{ ucfirst($t->status) }}</td>
 </tr>
 @empty
 <tr><td colspan="5" class="p-6 text-center text-gray-500">No tasks.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $tasks->links() }}</div>
@endsection