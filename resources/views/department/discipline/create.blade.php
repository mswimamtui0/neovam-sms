@extends("layouts.app")
@section("title", "Add to Discipline")
@section("content")
 <h1 class="text-3xl font-bold text-yellow-900 mb-6">Add Discipline Record</h1>
 <form method="POST" action="{{ route('department.discipline.store') }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 @include('department.discipline._form')
 <button type="submit" class="bg-yellow-700 text-white px-6 py-2 rounded">Save</button>
 </form>
@endsection