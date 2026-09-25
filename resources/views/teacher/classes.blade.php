@extends("layouts.app")
@section("title", "My Classes")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">My Classes</h1>
 <div class="bg-white rounded shadow p-6">
 @forelse($classes as $class)
 <div class="border-b py-3">
 <strong>{{ $class->name }}</strong> - {{ ucfirst($class->level) }}
 @if($class->stream) ({{ $class->stream }}) @endif
 </div>
 @empty
 <p class="text-gray-500">No classes assigned.</p>
 @endforelse
 </div>
@endsection