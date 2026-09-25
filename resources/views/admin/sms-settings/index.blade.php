@extends("layouts.app")
@section("title", "SMS Settings")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">SMS Settings</h1>

    <style>
        .sms-section {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .sms-header {
            width: 100%;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            background: white;
            border: none;
            text-align: left;
            transition: background 0.15s;
        }
        .sms-header:hover { background: #f5f7fa; }
        .sms-header .title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e3a8a;
        }
        .sms-header .subtitle {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 2px;
        }
        .sms-header .chev {
            font-size: 1rem;
            transition: transform 0.2s;
            color: #1e3a8a;
        }
        .sms-header .chev.open { transform: rotate(90deg); }
        .sms-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            border-top: 1px solid #e5e7eb;
        }
        .sms-content.open {
            max-height: 10000px;
            transition: max-height 0.5s ease-in;
        }
        .sms-body { padding: 1.5rem; }
    </style>

    {{-- SECTION 1: CONTROL CENTER --}}
    <div class="sms-section">
        <button type="button" class="sms-header" onclick="toggleSms('control')">
            <div>
                <div class="title">Control Center</div>
                <div class="subtitle">Master switch, presets, schedule, rate limits, language</div>
            </div>
            <span class="chev" id="chev-control">></span>
        </button>
        <div class="sms-content" id="sec-control">
            <div class="sms-body">

                <div class="rounded-lg p-4 mb-4 {{ $controlStats["master"] ? "bg-green-50 border-l-4 border-green-600" : "bg-red-50 border-l-4 border-red-600" }}">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-xs text-gray-500">Master Switch</div>
                            <div class="text-2xl font-bold {{ $controlStats["master"] ? "text-green-800" : "text-red-800" }}">
                                AUTOMATIC SMS - ALL: {{ $controlStats["master"] ? "ON" : "OFF" }}
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route("admin.sms-settings.master-toggle") }}" class="inline">
                                @csrf
                                <input type="hidden" name="enabled" value="1">
                                <button class="bg-green-700 text-white px-4 py-2 rounded">Turn ON</button>
                            </form>
                            <form method="POST" action="{{ route("admin.sms-settings.master-toggle") }}" class="inline">
                                @csrf
                                <input type="hidden" name="enabled" value="0">
                                <button class="bg-red-700 text-white px-4 py-2 rounded">Turn OFF</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-3 mb-4">
                    <div class="bg-gray-50 rounded p-3">
                        <div class="text-xs text-gray-500">SMS Today</div>
                        <div class="text-xl font-bold">{{ number_format($controlStats["sms_today"]) }}</div>
                    </div>
                    <div class="bg-gray-50 rounded p-3">
                        <div class="text-xs text-gray-500">Last Hour</div>
                        <div class="text-xl font-bold">{{ number_format($controlStats["sms_hour"]) }}</div>
                    </div>
                    <div class="bg-gray-50 rounded p-3">
                        <div class="text-xs text-gray-500">Skipped Today</div>
                        <div class="text-xl font-bold text-yellow-800">{{ number_format($controlStats["skipped_today"]) }}</div>
                    </div>
                    <div class="bg-gray-50 rounded p-3">
                        <div class="text-xs text-gray-500">Triggers ON</div>
                        <div class="text-xl font-bold text-green-800">{{ $controlStats["enabled"] }}</div>
                    </div>
                </div>

                <h3 class="font-bold text-blue-900 mb-2 text-sm">Quick Presets</h3>
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach([
                        "all_on"          => "All ON",
                        "all_off"         => "All OFF",
                        "essentials_only" => "Essentials Only",
                        "parents_only"    => "Parents Only",
                        "staff_only"      => "Staff Only",
                        "silent"          => "Silent Mode",
                        "test"            => "Test Mode",
                    ] as $key => $label)
                        <form method="POST" action="{{ route("admin.sms-settings.preset") }}" class="inline"
                              onsubmit="return confirm(&quot;Apply {{ $label }}?&quot;);">
                            @csrf
                            <input type="hidden" name="preset" value="{{ $key }}">
                            <button class="bg-blue-900 text-white px-3 py-1.5 rounded text-xs">{{ $label }}</button>
                        </form>
                    @endforeach
                </div>

                <form method="POST" action="{{ route("admin.sms-settings.update-control") }}" class="space-y-4">
                    @csrf
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="schedule_enabled" value="1" {{ $controlStats["schedule"] ? "checked" : "" }}>
                        <span class="text-sm">Enable time schedule</span>
                    </label>
                    <div class="grid grid-cols-4 gap-3">
                        <div>
                            <label class="block font-semibold mb-1 text-xs">Start Time</label>
                            <input type="time" name="schedule_start" value="{{ $controlSettings["schedule_start"]->value ?? "07:00" }}" class="w-full border rounded px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-xs">End Time</label>
                            <input type="time" name="schedule_end" value="{{ $controlSettings["schedule_end"]->value ?? "20:00" }}" class="w-full border rounded px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-xs">Limit / Hour</label>
                            <input type="number" name="rate_limit_hour" value="{{ \App\Models\SmsControlSetting::rateLimitPerHour() }}" class="w-full border rounded px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-xs">Limit / Day</label>
                            <input type="number" name="rate_limit_day" value="{{ \App\Models\SmsControlSetting::rateLimitPerDay() }}" class="w-full border rounded px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="flex gap-3">
                        @php $activeDays = \App\Models\SmsControlSetting::get("schedule_days", ["1","2","3","4","5"]); @endphp
                        @foreach(["1"=>"Mon","2"=>"Tue","3"=>"Wed","4"=>"Thu","5"=>"Fri","6"=>"Sat","7"=>"Sun"] as $val => $label)
                            <label class="flex items-center gap-1 text-sm">
                                <input type="checkbox" name="schedule_days[]" value="{{ $val }}" {{ in_array($val, $activeDays) ? "checked" : "" }}>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div>
                        <label class="block font-semibold mb-1 text-xs">Default Language</label>
                        @php $lang = \App\Models\SmsControlSetting::get("default_language", "en"); @endphp
                        <select name="default_language" class="border rounded px-3 py-2 text-sm">
                            <option value="en"   {{ $lang === "en"   ? "selected" : "" }}>English only</option>
                            <option value="sw"   {{ $lang === "sw"   ? "selected" : "" }}>Kiswahili only</option>
                            <option value="both" {{ $lang === "both" ? "selected" : "" }}>Both</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded text-sm">Save Settings</button>
                </form>
            </div>
        </div>
    </div>

    {{-- SECTION 2: GATEWAY --}}
    <div class="sms-section">
        <button type="button" class="sms-header" onclick="toggleSms('gateway')">
            <div>
                <div class="title">Gateway Configuration</div>
                <div class="subtitle">API URL, key, sender, environment, balance, test SMS</div>
            </div>
            <span class="chev" id="chev-gateway">></span>
        </button>
        <div class="sms-content" id="sec-gateway">
            <div class="sms-body">
                <div class="grid grid-cols-2 gap-4">
                    <form method="POST" action="{{ route("admin.sms-settings.gateway") }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block font-semibold mb-1 text-xs">API URL</label>
                            <input type="text" name="url" value="{{ $gatewayData["url"] }}" class="w-full border rounded px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-xs">API Key (leave blank to keep)</label>
                            <input type="text" name="key" value="{{ $gatewayData["key"] }}" class="w-full border rounded px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-xs">Sender Name</label>
                            <input type="text" name="sender" value="{{ $gatewayData["sender"] }}" class="w-full border rounded px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-xs">Environment</label>
                            <select name="environment" class="w-full border rounded px-3 py-2 text-sm">
                                <option value="sandbox"    {{ $gatewayData["environment"] === "sandbox"    ? "selected" : "" }}>Sandbox</option>
                                <option value="production" {{ $gatewayData["environment"] === "production" ? "selected" : "" }}>Production</option>
                            </select>
                        </div>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="test_mode" value="1" {{ $gatewayData["testMode"] ? "checked" : "" }}>
                            <span>Test Mode (log only, don't send)</span>
                        </label>
                        <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded text-sm">Save Gateway</button>
                    </form>

                    <div class="space-y-3">
                        <div class="bg-gray-50 rounded p-3">
                            <div class="text-xs text-gray-500">Gateway Balance</div>
                            @if($gatewayData["balance"]["success"])
                                <div class="text-xl font-bold">{{ number_format($gatewayData["balance"]["balance"]) }} {{ $gatewayData["balance"]["currency"] }}</div>
                            @else
                                <div class="text-sm text-gray-500">Not available</div>
                            @endif
                        </div>

                        <form method="POST" action="{{ route("admin.sms-settings.send-test") }}" class="bg-gray-50 rounded p-3 space-y-2">
                            @csrf
                            <h4 class="font-semibold text-sm">Send Test SMS</h4>
                            <input type="text" name="phone" placeholder="+255712345678" class="w-full border rounded px-3 py-2 text-sm" required>
                            <textarea name="message" rows="2" maxlength="160" class="w-full border rounded px-3 py-2 text-sm" required>This is a test SMS from NEOVAM SMS.</textarea>
                            <button class="bg-green-700 text-white px-3 py-1.5 rounded text-xs w-full">Send Test</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 3: TEMPLATES --}}
    <div class="sms-section">
        <button type="button" class="sms-header" onclick="toggleSms('templates')">
            <div>
                <div class="title">Templates ({{ $templates->total() }})</div>
                <div class="subtitle">Edit SMS wording in English + Kiswahili</div>
            </div>
            <span class="chev" id="chev-templates">></span>
        </button>
        <div class="sms-content" id="sec-templates">
            <div class="sms-body">
                <div class="flex justify-end mb-3">
                    <a href="{{ route("admin.sms-templates.create") }}" class="bg-blue-900 text-white px-3 py-1.5 rounded text-xs">Add Template</a>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 text-left">Key</th>
                            <th class="p-2 text-left">Name</th>
                            <th class="p-2 text-left">Lang</th>
                            <th class="p-2 text-left">Body</th>
                            <th class="p-2 text-left">Active</th>
                            <th class="p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $t)
                            <tr class="border-b">
                                <td class="p-2 font-mono text-xs">{{ $t->key }}</td>
                                <td class="p-2">{{ $t->name }}</td>
                                <td class="p-2">
                                    <span class="text-xs px-2 py-0.5 rounded {{ $t->language === "sw" ? "bg-green-100 text-green-800" : "bg-blue-100 text-blue-800" }}">
                                        {{ strtoupper($t->language) }}
                                    </span>
                                </td>
                                <td class="p-2 text-xs">{{ \Illuminate\Support\Str::limit($t->body, 60) }}</td>
                                <td class="p-2">{{ $t->is_active ? "Yes" : "No" }}</td>
                                <td class="p-2">
                                    <a href="{{ route("admin.sms-templates.edit", $t) }}" class="text-yellow-700 hover:underline text-xs">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-4 text-center text-gray-500">No templates.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">{{ $templates->appends(["open" => "templates"])->links() }}</div>
            </div>
        </div>
    </div>

    {{-- SECTION 4: TRIGGERS --}}
    <div class="sms-section">
        <button type="button" class="sms-header" onclick="toggleSms('triggers')">
            <div>
                <div class="title">Triggers ({{ $controlStats["enabled"] }} on / {{ $controlStats["enabled"] + $controlStats["disabled"] }})</div>
                <div class="subtitle">Turn individual triggers or whole categories on/off</div>
            </div>
            <span class="chev" id="chev-triggers">></span>
        </button>
        <div class="sms-content" id="sec-triggers">
            <div class="sms-body">
                @forelse($triggersByCategory as $category => $triggers)
                    @php
                        $on = $triggers->where("is_enabled", true)->count();
                        $total = $triggers->count();
                    @endphp
                    <div class="border rounded p-3 mb-3 bg-gray-50">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-bold text-blue-900 text-sm">{{ $category }} ({{ $on }}/{{ $total }})</h3>
                            <div class="flex gap-2">
                                <form method="POST" action="{{ route("admin.sms-settings.trigger.bulk") }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="category" value="{{ $category }}">
                                    <input type="hidden" name="enable" value="1">
                                    <button class="text-xs bg-green-700 text-white px-2 py-0.5 rounded">All ON</button>
                                </form>
                                <form method="POST" action="{{ route("admin.sms-settings.trigger.bulk") }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="category" value="{{ $category }}">
                                    <input type="hidden" name="enable" value="0">
                                    <button class="text-xs bg-red-700 text-white px-2 py-0.5 rounded">All OFF</button>
                                </form>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($triggers as $t)
                                <div class="flex justify-between items-center bg-white rounded px-2 py-1 border text-xs">
                                    <span class="truncate">{{ $t->trigger_key }}</span>
                                    @if($t->is_critical)
                                        <span class="text-red-600 font-bold text-[10px]">LOCKED</span>
                                    @else
                                        <form method="POST" action="{{ route("admin.sms-settings.trigger.toggle", $t) }}" class="inline">
                                            @csrf @method("PUT")
                                            <input type="hidden" name="is_enabled" value="{{ $t->is_enabled ? 0 : 1 }}">
                                            <button class="{{ $t->is_enabled ? "text-red-700" : "text-green-700" }} hover:underline">
                                                {{ $t->is_enabled ? "Disable" : "Enable" }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No triggers.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- SECTION 5: DELIVERY --}}
    <div class="sms-section">
        <button type="button" class="sms-header" onclick="toggleSms('delivery')">
            <div>
                <div class="title">Delivery Reports</div>
                <div class="subtitle">Status of every SMS sent - delivered, undelivered, failed</div>
            </div>
            <span class="chev" id="chev-delivery">></span>
        </button>
        <div class="sms-content" id="sec-delivery">
            <div class="sms-body">
                <div class="grid grid-cols-4 gap-3 mb-4">
                    <div class="bg-blue-50 rounded p-3"><div class="text-xs text-gray-500">Total</div><div class="text-xl font-bold">{{ number_format($deliveryStats["total"]) }}</div></div>
                    <div class="bg-green-50 rounded p-3"><div class="text-xs text-gray-500">Delivered</div><div class="text-xl font-bold text-green-800">{{ number_format($deliveryStats["delivered"]) }}</div></div>
                    <div class="bg-yellow-50 rounded p-3"><div class="text-xs text-gray-500">Undelivered</div><div class="text-xl font-bold text-yellow-800">{{ number_format($deliveryStats["undelivered"]) }}</div></div>
                    <div class="bg-red-50 rounded p-3"><div class="text-xs text-gray-500">Failed</div><div class="text-xl font-bold text-red-800">{{ number_format($deliveryStats["failed"]) }}</div></div>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 text-left">Time</th>
                            <th class="p-2 text-left">Recipient</th>
                            <th class="p-2 text-left">Trigger</th>
                            <th class="p-2 text-left">Units</th>
                            <th class="p-2 text-left">Status</th>
                            <th class="p-2 text-left">Delivery</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveryLogs as $l)
                            <tr class="border-b">
                                <td class="p-2 text-xs">{{ $l->created_at->format("d M H:i") }}</td>
                                <td class="p-2 text-xs">{{ $l->recipient }}</td>
                                <td class="p-2 text-xs font-mono">{{ $l->trigger }}</td>
                                <td class="p-2 text-xs">{{ $l->units }}</td>
                                <td class="p-2">
                                    <span class="text-xs px-2 py-0.5 rounded
                                        @if($l->status === "sent") bg-green-100 text-green-800
                                        @elseif($l->status === "failed") bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($l->status) }}
                                    </span>
                                </td>
                                <td class="p-2 text-xs">{{ ucfirst($l->delivery_status) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-4 text-center text-gray-500">No SMS sent yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">{{ $deliveryLogs->appends(["open" => "delivery"])->links() }}</div>
            </div>
        </div>
    </div>

    {{-- SECTION 6: COST --}}
    <div class="sms-section">
        <button type="button" class="sms-header" onclick="toggleSms('cost')">
            <div>
                <div class="title">Cost Tracking</div>
                <div class="subtitle">Balance, unit price, usage this month, top-up</div>
            </div>
            <span class="chev" id="chev-cost">></span>
        </button>
        <div class="sms-content" id="sec-cost">
            <div class="sms-body">
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div class="bg-blue-50 rounded p-3">
                        <div class="text-xs text-gray-500">Balance (Units)</div>
                        <div class="text-2xl font-bold">{{ number_format($costData["balanceUnits"]) }}</div>
                    </div>
                    <div class="bg-green-50 rounded p-3">
                        <div class="text-xs text-gray-500">Balance (Money)</div>
                        <div class="text-2xl font-bold text-green-800">TZS {{ number_format($costData["balanceMoney"]) }}</div>
                    </div>
                    <div class="bg-yellow-50 rounded p-3">
                        <div class="text-xs text-gray-500">Unit Price</div>
                        <div class="text-2xl font-bold text-yellow-800">
                            @if($costData["pricing"])
                                {{ $costData["pricing"]->currency }} {{ number_format((float)$costData["pricing"]->unit_price, 2) }}
                            @else - @endif
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-3 mb-4">
                    <div class="bg-gray-50 rounded p-3"><div class="text-xs text-gray-500">SMS This Month</div><div class="text-lg font-bold">{{ number_format($costData["summary"]["count"]) }}</div></div>
                    <div class="bg-gray-50 rounded p-3"><div class="text-xs text-gray-500">Units Used</div><div class="text-lg font-bold">{{ number_format($costData["summary"]["units"]) }}</div></div>
                    <div class="bg-gray-50 rounded p-3"><div class="text-xs text-gray-500">Cost</div><div class="text-lg font-bold">TZS {{ number_format($costData["summary"]["cost"]) }}</div></div>
                    <div class="bg-gray-50 rounded p-3"><div class="text-xs text-gray-500">Failed</div><div class="text-lg font-bold text-red-800">{{ number_format($costData["summary"]["failed"]) }}</div></div>
                </div>
                <form method="POST" action="{{ route("admin.sms-settings.topup") }}" class="grid grid-cols-5 gap-2">
                    @csrf
                    <input type="number" name="units" placeholder="Units" class="border rounded px-3 py-2 text-sm" required>
                    <input type="number" step="0.01" name="amount" placeholder="Amount" class="border rounded px-3 py-2 text-sm" required>
                    <input type="text" name="reference" placeholder="Reference" class="border rounded px-3 py-2 text-sm">
                    <input type="text" name="description" placeholder="Description" class="border rounded px-3 py-2 text-sm">
                    <button class="bg-green-700 text-white rounded px-3 py-2 text-sm">Top Up</button>
                </form>
            </div>
        </div>
    </div>

    {{-- SECTION 7: SKIPPED --}}
    <div class="sms-section">
        <button type="button" class="sms-header" onclick="toggleSms('skipped')">
            <div>
                <div class="title">Skipped Messages</div>
                <div class="subtitle">What would have sent but was blocked by a gate</div>
            </div>
            <span class="chev" id="chev-skipped">></span>
        </button>
        <div class="sms-content" id="sec-skipped">
            <div class="sms-body">
                <div class="grid grid-cols-4 gap-3 mb-4">
                    @foreach(["master_off","trigger_off","schedule","rate_limit"] as $reason)
                        <div class="bg-gray-50 rounded p-3">
                            <div class="text-xs text-gray-500">{{ ucfirst(str_replace("_"," ",$reason)) }}</div>
                            <div class="text-xl font-bold">{{ $skippedReasons[$reason] ?? 0 }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-end mb-3">
                    <form method="POST" action="{{ route("admin.sms-settings.clear-skipped") }}" onsubmit="return confirm(&quot;Clear log?&quot;);">
                        @csrf @method("DELETE")
                        <button class="bg-red-700 text-white px-3 py-1.5 rounded text-xs">Clear Log</button>
                    </form>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 text-left">Time</th>
                            <th class="p-2 text-left">Recipient</th>
                            <th class="p-2 text-left">Trigger</th>
                            <th class="p-2 text-left">Reason</th>
                            <th class="p-2 text-left">Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($skipped as $s)
                            <tr class="border-b">
                                <td class="p-2 text-xs">{{ $s->created_at->format("d M H:i") }}</td>
                                <td class="p-2 text-xs">{{ $s->recipient }}</td>
                                <td class="p-2 text-xs font-mono">{{ $s->trigger }}</td>
                                <td class="p-2 text-xs">{{ ucfirst(str_replace("_"," ",$s->skip_reason)) }}</td>
                                <td class="p-2 text-xs">{{ \Illuminate\Support\Str::limit($s->message, 60) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-4 text-center text-gray-500">No skipped messages.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">{{ $skipped->appends(["open" => "skipped"])->links() }}</div>
            </div>
        </div>
    </div>

<script>
    function toggleSms(key) {
        const all = ["control","gateway","templates","triggers","delivery","cost","skipped"];
        const current = document.getElementById("sec-" + key);
        const isOpen = current.classList.contains("open");

        all.forEach(k => {
            const el = document.getElementById("sec-" + k);
            const ch = document.getElementById("chev-" + k);
            if (el) el.classList.remove("open");
            if (ch) ch.classList.remove("open");
        });

        if (!isOpen) {
            current.classList.add("open");
            document.getElementById("chev-" + key).classList.add("open");
            localStorage.setItem("neovam-sms-open", key);
        } else {
            localStorage.removeItem("neovam-sms-open");
        }
    }

    (function() {
        const urlOpen = new URLSearchParams(window.location.search).get("open");
        const saved = urlOpen || localStorage.getItem("neovam-sms-open");

        if (saved) {
            const el = document.getElementById("sec-" + saved);
            const ch = document.getElementById("chev-" + saved);
            if (el) {
                el.classList.add("open");
                if (ch) ch.classList.add("open");
            }
        } else {
            const el = document.getElementById("sec-control");
            const ch = document.getElementById("chev-control");
            if (el) { el.classList.add("open"); if (ch) ch.classList.add("open"); }
        }
    })();
</script>
@endsection