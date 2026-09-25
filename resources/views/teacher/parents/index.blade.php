@extends("layouts.app")
@section("title", "Parent Contacts")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Parent Contacts</h1>
 <a href="{{ route("teacher.parents.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Log Contact</a>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Student</th>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Reason</th>
 </tr>
 </thead>
 <tbody>
 @forelse($contacts as $c)
 <tr class="border-b">
 <td class="p-3">{{ $c->created_at->format("Y-m-d H:i") }}</td>
 <td class="p-3">{{ $c->student?->full_name }}</td>
 <td class="p-3">{{ ucfirst($c->contact_type) }}</td>
 <td class="p-3">{{ $c->reason }}</td>
 </tr>
 @empty
 <tr><td colspan="4" class="p-6 text-center text-gray-500">No contacts logged.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $contacts->links() }}</div>
@endsection