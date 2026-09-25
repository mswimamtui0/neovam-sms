@extends("layouts.app")
@section("title", "SMS Settings")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">SMS Gateway Settings</h1>

 <div class="grid grid-cols-2 gap-6 max-w-5xl">

 <!-- LEFT: Configuration -->
 <div>
 <form method="POST" action="{{ route("admin.settings.sms.update") }}" class="bg-white rounded shadow p-6 space-y-4">
 @csrf

 <h2 class="font-bold text-blue-900 border-b pb-2">Gateway Configuration</h2>

 <div>
 <label class="block font-semibold mb-1">API URL</label>
 <input type="text" name="url" value="{{ $url }}" placeholder="https://api.yourgateway.com/send"
 class="w-full border rounded px-3 py-2">
 <p class="text-xs text-gray-500 mt-1">Full endpoint URL for sending SMS.</p>
 </div>

 <div>
 <label class="block font-semibold mb-1">API Key</label>
 <input type="text" name="key" value="{{ $key }}" placeholder="Leave blank to keep current"
 class="w-full border rounded px-3 py-2">
 <p class="text-xs text-gray-500 mt-1">Current key is masked. Type a new value to replace it.</p>
 </div>

 <div>
 <label class="block font-semibold mb-1">Sender ID / Name</label>
 <input type="text" name="sender" value="{{ $sender }}" placeholder="NEOVAM"
 class="w-full border rounded px-3 py-2">
 </div>

 <div>
 <label class="block font-semibold mb-1">Environment</label>
 <select name="environment" class="w-full border rounded px-3 py-2">
 <option value="sandbox" {{ $environment === "sandbox" ? "selected" : "" }}>Sandbox (testing)</option>
 <option value="production" {{ $environment === "production" ? "selected" : "" }}>Production (live)</option>
 </select>
 </div>

 <div>
 <label class="flex items-center gap-2">
 <input type="checkbox" name="test_mode" value="1" {{ $testMode ? "checked" : "" }}>
 <span>Test mode — SMS are logged but NOT sent (safe for demos)</span>
 </label>
 </div>

 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Settings</button>
 </form>
 </div>

 <!-- RIGHT: Status + Test -->
 <div class="space-y-6">

 <div class="bg-white rounded shadow p-6">
 <h2 class="font-bold text-blue-900 border-b pb-2 mb-3">Status</h2>
 <div class="space-y-2 text-sm">
 <div>
 <strong>Environment:</strong>
 <span class="px-2 py-0.5 rounded text-xs {{ $environment === "production" ? "bg-red-100 text-red-800" : "bg-yellow-100 text-yellow-800" }}">
 {{ ucfirst($environment) }}
 </span>
 </div>
 <div>
 <strong>Test Mode:</strong>
 @if($testMode)
 <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded">ON (safe)</span>
 @else
 <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded">OFF (live)</span>
 @endif
 </div>
 <div>
 <strong>Gateway Balance:</strong>
 @if($balance["success"])
 <span class="font-bold text-blue-900">
 {{ number_format($balance["balance"]) }} {{ $balance["currency"] }}
 </span>
 @else
 <span class="text-gray-500">Not available</span>
 @endif
 </div>
 </div>
 </div>

 <div class="bg-white rounded shadow p-6">
 <h2 class="font-bold text-blue-900 border-b pb-2 mb-3">Send Test SMS</h2>
 <form method="POST" action="{{ route("admin.settings.sms.test") }}" class="space-y-3">
 @csrf
 <div>
 <label class="block font-semibold mb-1 text-sm">Phone Number</label>
 <input type="text" name="phone" placeholder="+255712345678"
 class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1 text-sm">Message (max 160)</label>
 <textarea name="message" rows="3" maxlength="160"
 class="w-full border rounded px-3 py-2" required>This is a test SMS from NEOVAM SMS.</textarea>
 </div>
 <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded w-full">Send Test
 </button>
 <p class="text-xs text-gray-500 text-center">Result appears at <a href="{{ route("admin.communication.index") }}" class="underline">/admin/communication</a>
 </p>
 </form>
 </div>

 <div class="bg-blue-50 rounded shadow p-4 text-sm">
 <strong class="text-blue-900">How to go live:</strong>
 <ol class="list-decimal pl-5 mt-2 space-y-1 text-gray-700">
 <li>Enter your real API URL and key</li>
 <li>Set environment to <strong>Production</strong></li>
 <li><strong>Uncheck</strong>Test Mode</li>
 <li>Send a test SMS to your own number</li>
 <li>Once it arrives, you're live</li>
 </ol>
 </div>

 </div>
 </div>
@endsection