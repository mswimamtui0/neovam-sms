@extends("layouts.app")
@section("title", "Environment")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-teal-900">Environment Department</h1>
 <a href="{{ route('department.environment.create') }}" class="bg-teal-700 text-white px-4 py-2 rounded">Add New</a>
 </div>
 <div class="bg-white rounded shadow p-6 text-gray-500">Environment records will appear here.
 </div>
@endsection