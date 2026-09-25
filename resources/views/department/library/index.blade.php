@extends("layouts.app")
@section("title", "Library")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Library Department</h1>
 <a href="{{ route("department.library.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Book</a>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white"><tr><th class="p-3 text-left">Title</th><th class="p-3 text-left">Author</th><th class="p-3 text-right">Copies</th><th class="p-3 text-right">Available</th></tr></thead>
 <tbody>
 @forelse($books as $b)
 <tr class="border-b"><td class="p-3">{{ $b->title }}</td><td class="p-3">{{ $b->author ?? "-" }}</td><td class="p-3 text-right">{{ $b->total_copies }}</td><td class="p-3 text-right">{{ $b->available_copies }}</td></tr>
 @empty
 <tr><td colspan="4" class="p-6 text-center text-gray-500">No books yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $books->links() }}</div>
@endsection