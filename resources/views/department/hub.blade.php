@extends("layouts.app")
@section("title", "My Departments")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-2">My Departments</h1>
 <p class="text-gray-600 mb-6">Welcome, {{ $staff?->full_name }} — here are your assigned department tools.</p>

 @if(!$staff || !$staff->hasAnyDepartmentRole())
 <div class="bg-yellow-100 border-l-4 border-yellow-600 p-4 rounded">
 <strong>No department assigned.</strong>Contact admin to be assigned to a department.
 </div>
 @else
 <div class="grid grid-cols-3 gap-4">
 @foreach($staff->roleList() as $role)
 @php
 $map = [
 "health" => ["title" => "Health", "route" => "department.health", "color" => "red"],
 "discipline" => ["title" => "Discipline", "route" => "department.discipline", "color" => "yellow"],
 "sports" => ["title" => "Sports", "route" => "department.sports", "color" => "green"],
 "boarding" => ["title" => "Boarding", "route" => "department.boarding", "color" => "purple"],
 "feeding" => ["title" => "Feeding", "route" => "department.feeding", "color" => "orange"],
 "library" => ["title" => "Library", "route" => "department.library", "color" => "blue"],
 "guidance" => ["title" => "Guidance", "route" => "department.guidance", "color" => "indigo"],
 "environment" => ["title" => "Environment", "route" => "department.environment", "color" => "teal"],
 "security" => ["title" => "Security", "route" => "department.security", "color" => "gray"],
 ];
 @endphp
 @if(isset($map[$role]))
 <a href="{{ route($map[$role]["route"]) }}" class="bg-white rounded shadow p-6 hover:shadow-lg border-l-4 border-{{ $map[$role]["color"] }}-600">
 <div class="text-xl font-bold text-blue-900">{{ $map[$role]["title"] }}</div>
 <div class="text-sm text-gray-600 mt-2">Open {{ $map[$role]["title"] }} dashboard</div>
 </a>
 @endif
 @endforeach
 </div>
 @endif
@endsection