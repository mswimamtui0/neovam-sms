@extends("layouts.app")
@section("title", "SMS Trigger Settings")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">SMS Trigger Settings</h1>
 <a href="{{ route("admin.sms-templates.index") }}" class="bg-gray-700 text-white px-4 py-2 rounded">SMS Templates</a>
 </div>

 <div class="grid grid-cols-4 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">Total Triggers</div>
 <div class="text-2xl font-bold">{{ $stats["total"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Enabled</div>
 <div class="text-2xl font-bold text-green-800">{{ $stats["enabled"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Disabled</div>
 <div class="text-2xl font-bold text-yellow-800">{{ $stats["disabled"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
 <div class="text-xs text-gray-500">Critical (always on)</div>
 <div class="text-2xl font-bold text-red-800">{{ $stats["critical"] }}</div>
 </div>
 </div>

 @foreach($byCategory as $category => $triggers)
 <div class="bg-white rounded shadow p-6 mb-4">
 <div class="flex justify-between items-center mb-4">
 <h2 class="text-xl font-bold text-blue-900">{{ $category }}</h2>
 <div class="flex gap-2">
 <form method="POST" action="{{ route("admin.sms-triggers.bulk-toggle") }}" class="inline">
 @csrf
 <input type="hidden" name="category" value="{{ $category }}">
 <input type="hidden" name="enable" value="1">
 <button class="text-xs bg-green-700 text-white px-3 py-1 rounded">Enable All</button>
 </form>
 <form method="POST" action="{{ route("admin.sms-triggers.bulk-toggle") }}" class="inline"
 onsubmit="return confirm(&quot;Disable all non-critical triggers in this category?&quot;);">
 @csrf
 <input type="hidden" name="category" value="{{ $category }}">
 <input type="hidden" name="enable" value="0">
 <button class="text-xs bg-red-700 text-white px-3 py-1 rounded">Disable All</button>
 </form>
 </div>
 </div>

 <table class="w-full text-sm">
 <thead class="bg-gray-100">
 <tr>
 <th class="p-2 text-left">Trigger</th>
 <th class="p-2 text-left">Description</th>
 <th class="p-2 text-center">Status</th>
 <th class="p-2 text-center">Action</th>
 </tr>
 </thead>
 <tbody>
 @foreach($triggers as $t)
 <tr class="border-b">
 <td class="p-2 font-mono text-xs">{{ $t->trigger_key }}</td>
 <td class="p-2">{{ $t->description }}</td>
 <td class="p-2 text-center">
 @if($t->is_critical)
 <span class="text-xs px-2 py-0.5 rounded bg-red-100 text-red-800">CRITICAL</span>
 @elseif($t->is_enabled)
 <span class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-800">Enabled</span>
 @else
 <span class="text-xs px-2 py-0.5 rounded bg-gray-200 text-gray-600">Disabled</span>
 @endif
 </td>
 <td class="p-2 text-center">
 @if(!$t->is_critical)
 <form method="POST" action="{{ route("admin.sms-triggers.update", $t) }}" class="inline">
 @csrf @method("PUT")
 <input type="hidden" name="is_enabled" value="{{ $t->is_enabled ? 0 : 1 }}">
 <button class="text-xs {{ $t->is_enabled ? "text-red-700" : "text-green-700" }} hover:underline">
 {{ $t->is_enabled ? "Disable" : "Enable" }}
 </button>
 </form>
 @else
 <span class="text-xs text-gray-400">Locked</span>
 @endif
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>
 @endforeach

 <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded">
 <strong>Note:</strong>Critical triggers (emergency, school closure, password reset) can never be disabled.
 </div>
@endsection