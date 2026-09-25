@extends("layouts.app")
@section("title", "Staff Profile")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">{{ $staff->full_name }}</h1>
 <a href="{{ route("admin.staff.edit", $staff) }}" class="bg-blue-900 text-white px-4 py-2 rounded">Edit</a>
 </div>

 <div class="grid grid-cols-2 gap-4">
 <div class="bg-white rounded shadow p-4">
 <h3 class="font-bold text-blue-900 mb-2">Personal</h3>
 <p><strong>Staff No:</strong> {{ $staff->staff_no }}</p>
 <p><strong>Gender:</strong> {{ ucfirst($staff->gender) }}</p>
 <p><strong>DOB:</strong> {{ $staff->dob?->format("Y-m-d") ?? "-" }}</p>
 <p><strong>NIDA:</strong> {{ $staff->nida ?? "-" }}</p>
 <p><strong>Phone:</strong> {{ $staff->phone }}</p>
 <p><strong>Email:</strong> {{ $staff->email ?? "-" }}</p>
 <p><strong>Address:</strong> {{ $staff->address ?? "-" }}</p>
 </div>

 <div class="bg-white rounded shadow p-4">
 <h3 class="font-bold text-blue-900 mb-2">Employment</h3>
 <p><strong>Type:</strong> {{ $staff->staff_type ?? "-" }}</p>
 <p><strong>Department:</strong> {{ $staff->department }}</p>
 <p><strong>Role:</strong> {{ $staff->role_title ?? "-" }}</p>
 <p><strong>Employment Date:</strong> {{ $staff->employment_date?->format("Y-m-d") ?? "-" }}</p>
 <p><strong>Contract:</strong> {{ $staff->employment_type ?? "-" }}</p>
 <p><strong>TIN:</strong> {{ $staff->tin_number ?? "-" }}</p>
 </div>

 @if($staff->isTeacher() && $staff->subjects->count())
 <div class="bg-white rounded shadow p-4 col-span-2">
 <h3 class="font-bold text-blue-900 mb-2">Subjects Taught</h3>
 <div class="flex flex-wrap gap-2">
 @foreach($staff->subjects as $subject)
 <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded">{{ $subject->name }} ({{ $subject->code }})</span>
 @endforeach
 </div>
 </div>
 @endif

 <div class="bg-white rounded shadow p-4">
 <h3 class="font-bold text-blue-900 mb-2">Education</h3>
 <p><strong>Qualification:</strong> {{ $staff->qualification ?? "-" }}</p>
 <p><strong>Field:</strong> {{ $staff->field_of_study ?? "-" }}</p>
 <p><strong>Institution:</strong> {{ $staff->institution ?? "-" }}</p>
 <p><strong>Year:</strong> {{ $staff->year_graduated ?? "-" }}</p>
 </div>

 <div class="bg-white rounded shadow p-4">
 <h3 class="font-bold text-blue-900 mb-2">Emergency & Bank</h3>
 <p><strong>Emergency:</strong> {{ $staff->emergency_contact_name ?? "-" }} ({{ $staff->emergency_contact_phone ?? "-" }})</p>
 <p><strong>Bank:</strong> {{ $staff->bank_name ?? "-" }}</p>
 <p><strong>Account:</strong> {{ $staff->bank_account ?? "-" }}</p>
 </div>
 </div>
@endsection