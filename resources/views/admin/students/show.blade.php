@extends("layouts.app")
@section("title", "Student Profile")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">{{ $student->full_name }}</h1>
 <div class="flex gap-2">
 @if($student->can_login)
 <form method="POST" action="{{ route("admin.students.disable-login", $student) }}" onsubmit="return confirm(&quot;Disable?&quot;);">
 @csrf
 <button class="bg-red-700 text-white px-4 py-2 rounded">Disable Login</button>
 </form>
 @else
 <form method="POST" action="{{ route("admin.students.enable-login", $student) }}" onsubmit="return confirm(&quot;Enable?&quot;);">
 @csrf
 <button class="bg-green-700 text-white px-4 py-2 rounded">Enable Login</button>
 </form>
 @endif
 <a href="{{ route("admin.students.edit", $student) }}" class="bg-blue-900 text-white px-4 py-2 rounded">Edit</a>
 </div>
 </div>

 <div class="grid grid-cols-3 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4">
 <div class="text-sm text-gray-500">Admission No</div>
 <div class="text-xl font-bold">{{ $student->admission_no }}</div>
 </div>
 <div class="bg-white rounded shadow p-4">
 <div class="text-sm text-gray-500">Class</div>
 <div class="text-xl font-bold">{{ $student->classroom?->name ?? "-" }}</div>
 </div>
 <div class="bg-white rounded shadow p-4">
 <div class="text-sm text-gray-500">Level</div>
 <div class="text-xl font-bold">{{ ucfirst($student->level) }}</div>
 </div>
 </div>

 <div class="bg-white rounded shadow p-4 mb-6">
 <h3 class="font-bold text-blue-900 mb-2">Login Access</h3>
 @if($student->can_login && $student->user)
 <p><strong>Status:</strong> <span class="text-green-700 font-semibold">Enabled</span></p>
 <p><strong>Email:</strong> {{ $student->user->email }}</p>
 <p><strong>Enabled on:</strong> {{ $student->login_enabled_at?->format("Y-m-d H:i") }}</p>
 <p><strong>Enabled by:</strong> {{ $student->login_enabled_by }}</p>
 @else
 <p><strong>Status:</strong> <span class="text-gray-600 font-semibold">Disabled</span></p>
 <p class="text-sm text-gray-500 mt-1">Student login is not active. Only staff and parents can access this student's records.</p>
 @endif
 </div>

 <div class="bg-white rounded shadow p-4">
 <h3 class="font-bold text-blue-900 mb-2">Parent / Guardian</h3>
 <p><strong>Name:</strong> {{ $student->parent_name }}</p>
 <p><strong>Phone:</strong> {{ $student->parent_phone }}</p>
 <p><strong>Email:</strong> {{ $student->parent_email ?? "-" }}</p>
 </div>
@endsection