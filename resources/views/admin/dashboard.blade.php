@extends("layouts.app")
@section("title", "Admin Dashboard")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Admin Dashboard</h1>
 <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-sm text-gray-500">Students</div>
 <div class="text-3xl font-bold text-blue-900">{{ \App\Models\Student::count() }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-sm text-gray-500">Staff</div>
 <div class="text-3xl font-bold text-green-900">{{ \App\Models\Staff::count() }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-sm text-gray-500">SMS Sent</div>
 <div class="text-3xl font-bold text-yellow-900">{{ \App\Models\SmsLog::count() }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
 <div class="text-sm text-gray-500">Emergencies</div>
 <div class="text-3xl font-bold text-red-900">{{ \App\Models\Incident::count() }}</div>
 </div>
 </div>
 <div class="bg-white rounded shadow p-6">
 <h2 class="text-xl font-semibold text-blue-900 mb-4">Enabled Levels</h2>
 <div class="flex gap-4">
 @php $school = \App\Models\School::first(); @endphp
 <span class="px-3 py-1 rounded-full text-sm {{ $school?->has_primary ? "bg-blue-100 text-blue-800" : "bg-gray-200 text-gray-500" }}">Primary: {{ $school?->has_primary ? "ON" : "OFF" }}</span>
 <span class="px-3 py-1 rounded-full text-sm {{ $school?->has_secondary ? "bg-blue-100 text-blue-800" : "bg-gray-200 text-gray-500" }}">Secondary: {{ $school?->has_secondary ? "ON" : "OFF" }}</span>
 <span class="px-3 py-1 rounded-full text-sm {{ $school?->has_alevel ? "bg-blue-100 text-blue-800" : "bg-gray-200 text-gray-500" }}">A-Level: {{ $school?->has_alevel ? "ON" : "OFF" }}</span>
 </div>
 </div>
@endsection