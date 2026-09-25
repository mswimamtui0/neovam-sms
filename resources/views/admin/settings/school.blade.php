@extends("layouts.app")
@section("title", "School Settings")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">School Settings</h1>

 <form method="POST" action="{{ route("admin.settings.school.update") }}" class="bg-white rounded shadow p-6 space-y-6 max-w-3xl">
 @csrf

 <div>
 <label class="block font-semibold mb-1">School Name</label>
 <input type="text" name="name" value="{{ old("name", $school->name ?? "") }}" class="w-full border rounded px-3 py-2" required>
 </div>

 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">School Code</label>
 <input type="text" name="code" value="{{ old("code", $school->code ?? "") }}" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Phone</label>
 <input type="text" name="phone" value="{{ old("phone", $school->phone ?? "") }}" class="w-full border rounded px-3 py-2" required>
 </div>
 </div>

 <div>
 <label class="block font-semibold mb-1">Email</label>
 <input type="email" name="email" value="{{ old("email", $school->email ?? "") }}" class="w-full border rounded px-3 py-2">
 </div>

 <div>
 <label class="block font-semibold mb-1">Address</label>
 <input type="text" name="address" value="{{ old("address", $school->address ?? "") }}" class="w-full border rounded px-3 py-2">
 </div>

 <hr>

 <div>
 <h2 class="text-xl font-bold text-blue-900 mb-3">Education Levels</h2>
 <p class="text-sm text-gray-600 mb-4">Enable only the levels your school offers. Disabled levels will be hidden across the system.</p>

 <div class="space-y-4">

 <!-- PRIMARY (includes pre-primary) -->
 <div class="border rounded p-4">
 <label class="flex items-center gap-3 cursor-pointer mb-3">
 <input type="checkbox" name="has_primary" value="1"
 {{ old("has_primary", $school->has_primary ?? false) ? "checked" : "" }}
 class="w-5 h-5">
 <span class="font-bold text-blue-900">Primary (includes pre-primary levels)</span>
 </label>

 <div class="ml-8 space-y-2 border-l-2 border-blue-100 pl-4">
 <label class="flex items-center gap-3 cursor-pointer">
 <input type="checkbox" name="has_nursery" value="1"
 {{ old("has_nursery", $school->has_nursery ?? false) ? "checked" : "" }}
 class="w-4 h-4">
 <span>Nursery (ages 3-4)</span>
 </label>
 <label class="flex items-center gap-3 cursor-pointer">
 <input type="checkbox" name="has_kg" value="1"
 {{ old("has_kg", $school->has_kg ?? false) ? "checked" : "" }}
 class="w-4 h-4">
 <span>Kindergarten (KG) (ages 4-5)</span>
 </label>
 <label class="flex items-center gap-3 cursor-pointer">
 <input type="checkbox" name="has_pre_unit" value="1"
 {{ old("has_pre_unit", $school->has_pre_unit ?? false) ? "checked" : "" }}
 class="w-4 h-4">
 <span>Pre-Unit (ages 5-6, before Standard 1)</span>
 </label>
 </div>
 </div>

 <!-- SECONDARY -->
 <div class="border rounded p-4">
 <label class="flex items-center gap-3 cursor-pointer">
 <input type="checkbox" name="has_secondary" value="1"
 {{ old("has_secondary", $school->has_secondary ?? false) ? "checked" : "" }}
 class="w-5 h-5">
 <span class="font-bold text-blue-900">Secondary (Form 1 - 4)</span>
 </label>
 </div>

 <!-- A-LEVEL -->
 <div class="border rounded p-4">
 <label class="flex items-center gap-3 cursor-pointer">
 <input type="checkbox" name="has_alevel" value="1"
 {{ old("has_alevel", $school->has_alevel ?? false) ? "checked" : "" }}
 class="w-5 h-5">
 <span class="font-bold text-blue-900">A-Level (Form 5 - 6)</span>
 </label>
 </div>

 </div>
 </div>

 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-800">Save Settings</button>
 </form>
@endsection