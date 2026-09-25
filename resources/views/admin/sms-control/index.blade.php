@extends("layouts.app")
@section("title", "SMS Control Center")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">SMS Control Center</h1>
 <div class="flex gap-2">
 <a href="{{ route("admin.sms-triggers.index") }}" class="bg-gray-700 text-white px-4 py-2 rounded text-sm">Advanced Triggers</a>
 <a href="{{ route("admin.sms-control.skipped") }}" class="bg-yellow-700 text-white px-4 py-2 rounded text-sm">Skipped Messages</a>
 </div>
 </div>

 {{-- MASTER SWITCH --}}
 <div class="rounded-lg shadow-lg p-6 mb-6 {{ $stats["master"] ? "bg-green-50 border-l-4 border-green-600" : "bg-red-50 border-l-4 border-red-600" }}">
 <form method="POST" action="{{ route("admin.sms-control.update") }}">
 @csrf
 <input type="hidden" name="_only_master" value="1">

 <div class="flex justify-between items-center">
 <div>
 <div class="text-sm text-gray-500">Master Switch</div>
 <div class="text-3xl font-bold {{ $stats["master"] ? "text-green-800" : "text-red-800" }}">AUTOMATIC SMS — ALL: {{ $stats["master"] ? "ON" : "OFF" }}
 </div>
 <div class="text-sm text-gray-600 mt-1">
 @if($stats["master"])
 All enabled triggers are sending automatically.
 @else
 Only critical triggers (emergency, security) are sending.
 @endif
 </div>
 </div>

 <div class="flex gap-2">
 <button type="button" onclick="setMaster(true)" class="bg-green-700 text-white px-6 py-3 rounded text-lg">Turn ON</button>
 <button type="button" onclick="setMaster(false)" class="bg-red-700 text-white px-6 py-3 rounded text-lg">Turn OFF</button>
 </div>
 </div>

 <input type="hidden" name="master_switch" id="master_switch_field" value="{{ $stats["master"] ? 1 : 0 }}">
 </form>
 </div>

 {{-- PRESETS --}}
 <div class="bg-white rounded shadow p-6 mb-6">
 <h2 class="text-xl font-bold text-blue-900 mb-3">Quick Presets</h2>
 <div class="flex flex-wrap gap-2">
 @foreach([
 "all_on" => "All ON",
 "all_off" => "All OFF",
 "essentials_only"=> "Essentials Only",
 "parents_only" => "Parents Only",
 "staff_only" => "Staff Only",
 "silent" => "Silent Mode",
 "test" => "Test Mode",
 ] as $key => $label)
 <form method="POST" action="{{ route("admin.sms-control.preset") }}" class="inline"
 onsubmit="return confirm(&quot;Apply preset: {{ $label }}?&quot;);">
 @csrf
 <input type="hidden" name="preset" value="{{ $key }}">
 <button class="bg-blue-900 text-white px-4 py-2 rounded text-sm">{{ $label }}</button>
 </form>
 @endforeach
 </div>
 </div>

 {{-- KPI CARDS --}}
 <div class="grid grid-cols-4 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">SMS Today</div>
 <div class="text-2xl font-bold">{{ number_format($stats["sms_today"]) }}</div>
 <div class="text-xs text-gray-400">Limit: {{ \App\Models\SmsControlSetting::rateLimitPerDay() }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Last Hour</div>
 <div class="text-2xl font-bold text-green-800">{{ number_format($stats["sms_hour"]) }}</div>
 <div class="text-xs text-gray-400">Limit: {{ \App\Models\SmsControlSetting::rateLimitPerHour() }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Skipped Today</div>
 <div class="text-2xl font-bold text-yellow-800">{{ number_format($stats["skipped_today"]) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
 <div class="text-xs text-gray-500">Trigger Status</div>
 <div class="text-2xl font-bold text-purple-800">
 {{ $stats["enabled"] }} / {{ $stats["enabled"] + $stats["disabled"] }}
 </div>
 <div class="text-xs text-gray-400">enabled</div>
 </div>
 </div>

 {{-- SETTINGS FORM --}}
 <form method="POST" action="{{ route("admin.sms-control.update") }}" class="bg-white rounded shadow p-6 space-y-6 mb-6">
 @csrf

 <h2 class="text-xl font-bold text-blue-900 border-b pb-2">Global Settings</h2>

 <div class="grid grid-cols-2 gap-6">

 {{-- Test mode --}}
 <div>
 <label class="flex items-center gap-3">
 <input type="checkbox" name="test_mode" value="1" {{ $stats["test_mode"] ? "checked" : "" }} class="w-5 h-5">
 <div>
 <div class="font-semibold">Test Mode</div>
 <div class="text-xs text-gray-500">Log SMS but DON'T send them. Perfect for demos & training.</div>
 </div>
 </label>
 </div>

 {{-- Schedule --}}
 <div>
 <label class="flex items-center gap-3">
 <input type="checkbox" name="schedule_enabled" value="1" {{ $stats["schedule"] ? "checked" : "" }} class="w-5 h-5">
 <div>
 <div class="font-semibold">Time Schedule</div>
 <div class="text-xs text-gray-500">Only send during certain hours/days.</div>
 </div>
 </label>
 </div>
 </div>

 <div class="grid grid-cols-4 gap-4">
 <div>
 <label class="block font-semibold mb-1 text-sm">Start Time</label>
 <input type="time" name="schedule_start" value="{{ $settings["schedule_start"]->value ?? "07:00" }}" class="w-full border rounded px-3 py-2">
 </div>
 <div>
 <label class="block font-semibold mb-1 text-sm">End Time</label>
 <input type="time" name="schedule_end" value="{{ $settings["schedule_end"]->value ?? "20:00" }}" class="w-full border rounded px-3 py-2">
 </div>
 <div>
 <label class="block font-semibold mb-1 text-sm">Rate Limit / Hour</label>
 <input type="number" name="rate_limit_hour" value="{{ \App\Models\SmsControlSetting::rateLimitPerHour() }}" class="w-full border rounded px-3 py-2">
 </div>
 <div>
 <label class="block font-semibold mb-1 text-sm">Rate Limit / Day</label>
 <input type="number" name="rate_limit_day" value="{{ \App\Models\SmsControlSetting::rateLimitPerDay() }}" class="w-full border rounded px-3 py-2">
 </div>
 </div>

 <div>
 <label class="block font-semibold mb-2 text-sm">Active Days</label>
 <div class="flex gap-3">
 @foreach(["1"=>"Mon","2"=>"Tue","3"=>"Wed","4"=>"Thu","5"=>"Fri","6"=>"Sat","7"=>"Sun"] as $val => $label)
 @php $activeDays = \App\Models\SmsControlSetting::get("schedule_days", ["1","2","3","4","5"]); @endphp
 <label class="flex items-center gap-1">
 <input type="checkbox" name="schedule_days[]" value="{{ $val }}" {{ in_array($val, $activeDays) ? "checked" : "" }}>
 <span class="text-sm">{{ $label }}</span>
 </label>
 @endforeach
 </div>
 </div>

 <div>
 <label class="block font-semibold mb-1 text-sm">Default Language</label>
 <select name="default_language" class="border rounded px-3 py-2">
 @php $lang = \App\Models\SmsControlSetting::get("default_language", "en"); @endphp
 <option value="en" {{ $lang === "en" ? "selected" : "" }}>English only</option>
 <option value="sw" {{ $lang === "sw" ? "selected" : "" }}>Kiswahili only</option>
 <option value="both" {{ $lang === "both" ? "selected" : "" }}>Both (bilingual)</option>
 </select>
 </div>

 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Global Settings</button>
 </form>

 {{-- CATEGORY TOGGLES --}}
 <h2 class="text-xl font-bold text-blue-900 mb-3">Trigger Categories</h2>
 <div class="grid grid-cols-2 gap-4">
 @foreach($triggersByCategory as $category => $triggers)
 @php
 $enabledCount = $triggers->where("is_enabled", true)->count();
 $totalCount = $triggers->count();
 $hasCritical = $triggers->where("is_critical", true)->count() > 0;
 @endphp
 <div class="bg-white rounded shadow p-4 border-l-4 {{ $enabledCount === $totalCount ? "border-green-600" : ($enabledCount === 0 ? "border-red-600" : "border-yellow-600") }}">
 <div class="flex justify-between items-center mb-2">
 <h3 class="font-bold text-blue-900">{{ $category }}</h3>
 <span class="text-xs">{{ $enabledCount }}/{{ $totalCount }} on</span>
 </div>
 <div class="flex gap-2">
 <form method="POST" action="{{ route("admin.sms-triggers.bulk-toggle") }}" class="inline">
 @csrf
 <input type="hidden" name="category" value="{{ $category }}">
 <input type="hidden" name="enable" value="1">
 <button class="text-xs bg-green-700 text-white px-3 py-1 rounded">Enable All</button>
 </form>
 <form method="POST" action="{{ route("admin.sms-triggers.bulk-toggle") }}" class="inline">
 @csrf
 <input type="hidden" name="category" value="{{ $category }}">
 <input type="hidden" name="enable" value="0">
 <button class="text-xs bg-red-700 text-white px-3 py-1 rounded">Disable All</button>
 </form>
 </div>
 </div>
 @endforeach
 </div>

<script>function setMaster(value) {
 document.getElementById("master_switch_field").value = value ? "1" : "0";
 document.querySelector("form").submit();
 }
</script>
@endsection