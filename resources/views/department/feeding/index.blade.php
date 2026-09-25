@extends("layouts.app")
@section("title", "Feeding")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-orange-900">Feeding Department</h1>
 <a href="{{ route('department.feeding.create') }}" class="bg-orange-700 text-white px-4 py-2 rounded">Add New</a>
 </div>
 <div class="bg-white rounded shadow p-6 text-gray-500">Feeding records will appear here.
 </div>
@endsection