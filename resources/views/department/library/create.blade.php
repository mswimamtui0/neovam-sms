@extends("layouts.app")
@section("title", "Add Book")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Library Book</h1>
 <form method="POST" action="{{ route("department.library.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 <div><label class="block font-semibold mb-1">Title</label><input type="text" name="title" class="w-full border rounded px-3 py-2" required></div>
 <div><label class="block font-semibold mb-1">Author</label><input type="text" name="author" class="w-full border rounded px-3 py-2"></div>
 <div><label class="block font-semibold mb-1">ISBN</label><input type="text" name="isbn" class="w-full border rounded px-3 py-2"></div>
 <div><label class="block font-semibold mb-1">Category</label><input type="text" name="category" class="w-full border rounded px-3 py-2"></div>
 <div><label class="block font-semibold mb-1">Total Copies</label><input type="number" name="total_copies" value="1" min="1" class="w-full border rounded px-3 py-2" required></div>
 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Add Book</button>
 </form>
@endsection