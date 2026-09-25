@extends("layouts.app")
@section("title", "My Profile")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">My Profile</h1>
 @if($staff)
 <div class="grid grid-cols-2 gap-4">
 <div class="bg-white rounded shadow p-4">
 <h3 class="font-bold text-blue-900 mb-2">Personal</h3>
 <p><strong>Staff No:</strong> {{ $staff->staff_no }}</p>
 <p><strong>Name:</strong> {{ $staff->full_name }}</p>
 <p><strong>Gender:</strong> {{ ucfirst($staff->gender) }}</p>
 <p><strong>Phone:</strong> {{ $staff->phone }}</p>
 <p><strong>Email:</strong> {{ $staff->email ?? "-" }}</p>
 </div>
 <div class="bg-white rounded shadow p-4">
 <h3 class="font-bold text-blue-900 mb-2">Employment</h3>
 <p><strong>Type:</strong> {{ $staff->staff_type ?? "-" }}</p>
 <p><strong>Department:</strong> {{ $staff->department }}</p>
 <p><strong>Role:</strong> {{ $staff->role_title ?? "-" }}</p>
 </div>
 </div>
 @else
 <div class="bg-yellow-100 border-l-4 border-yellow-600 p-4 rounded">No staff record linked to your account. Contact admin.
 </div>
 @endif
@endsection