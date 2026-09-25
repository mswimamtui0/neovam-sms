@extends("layouts.app")
@section("title", "Analytics Dashboard")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Analytics Dashboard</h1>
        <div class="flex gap-2">
            <a href="{{ route("admin.analytics.academic") }}" class="bg-gray-700 text-white px-3 py-2 rounded text-sm">Academic</a>
            <a href="{{ route("admin.analytics.attendance") }}" class="bg-gray-700 text-white px-3 py-2 rounded text-sm">Attendance</a>
            <a href="{{ route("admin.analytics.financial") }}" class="bg-gray-700 text-white px-3 py-2 rounded text-sm">Financial</a>
            <a href="{{ route("admin.analytics.sms") }}" class="bg-gray-700 text-white px-3 py-2 rounded text-sm">SMS</a>
            <a href="{{ route("admin.analytics.staff") }}" class="bg-gray-700 text-white px-3 py-2 rounded text-sm">Staff</a>
        </div>
    </div>

    <form method="GET" class="bg-white rounded shadow p-4 mb-6 flex gap-3">
        <input type="date" name="from" value="{{ $from->format("Y-m-d") }}" class="border rounded px-3 py-2">
        <input type="date" name="to"   value="{{ $to->format("Y-m-d") }}"   class="border rounded px-3 py-2">
        <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route("admin.analytics.index") }}" class="text-gray-600 px-4 py-2">Reset</a>
    </form>

    {{-- Big KPI cards --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-xs text-gray-500">Active Students</div>
            <div class="text-3xl font-bold text-blue-900">{{ number_format($overview["students"]) }}</div>
            <div class="text-xs text-gray-400 mt-1">+{{ $overview["students_new"] }} this period</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-xs text-gray-500">Active Staff</div>
            <div class="text-3xl font-bold text-green-900">{{ number_format($overview["staff"]) }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ $overview["classes"] }} classes</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
            <div class="text-xs text-gray-500">Collected (TZS)</div>
            <div class="text-3xl font-bold text-yellow-900">{{ number_format($overview["collected"]) }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ number_format($overview["invoiced_total"]) }} invoiced</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
            <div class="text-xs text-gray-500">Outstanding (TZS)</div>
            <div class="text-3xl font-bold text-red-900">{{ number_format($overview["outstanding"]) }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ $overview["invoices"] }} invoices</div>
        </div>
    </div>

    {{-- Small KPI cards --}}
    <div class="grid grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">Exams</div>
            <div class="text-2xl font-bold">{{ $overview["exams"] }}</div>
            <div class="text-xs text-green-700">{{ $overview["exams_published"] }} published</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">Results</div>
            <div class="text-2xl font-bold">{{ number_format($overview["results"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">Incidents</div>
            <div class="text-2xl font-bold text-red-700">{{ $overview["incidents"] }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">SMS Sent</div>
            <div class="text-2xl font-bold">{{ number_format($overview["sms_sent"]) }}</div>
            <div class="text-xs text-gray-400">{{ number_format($overview["sms_units"]) }} units</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">SMS Cost (TZS)</div>
            <div class="text-2xl font-bold">{{ number_format($overview["sms_cost"]) }}</div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-3 gap-4">
        <a href="{{ route("admin.analytics.academic") }}" class="bg-white rounded shadow p-6 hover:shadow-lg">
            <div class="font-bold text-blue-900 text-lg mb-1">Academic Analytics</div>
            <div class="text-sm text-gray-600">Grade distribution, subject averages, top students</div>
        </a>
        <a href="{{ route("admin.analytics.attendance") }}" class="bg-white rounded shadow p-6 hover:shadow-lg">
            <div class="font-bold text-blue-900 text-lg mb-1">Attendance Analytics</div>
            <div class="text-sm text-gray-600">Daily attendance rates, per-class breakdown</div>
        </a>
        <a href="{{ route("admin.analytics.financial") }}" class="bg-white rounded shadow p-6 hover:shadow-lg">
            <div class="font-bold text-blue-900 text-lg mb-1">Financial Analytics</div>
            <div class="text-sm text-gray-600">Monthly income, payment methods</div>
        </a>
        <a href="{{ route("admin.analytics.sms") }}" class="bg-white rounded shadow p-6 hover:shadow-lg">
            <div class="font-bold text-blue-900 text-lg mb-1">SMS Analytics</div>
            <div class="text-sm text-gray-600">SMS by trigger, delivery status</div>
        </a>
        <a href="{{ route("admin.analytics.staff") }}" class="bg-white rounded shadow p-6 hover:shadow-lg">
            <div class="font-bold text-blue-900 text-lg mb-1">Staff Analytics</div>
            <div class="text-sm text-gray-600">By department, by role type</div>
        </a>
        <a href="{{ route("admin.income-tracking.index") }}" class="bg-white rounded shadow p-6 hover:shadow-lg">
            <div class="font-bold text-blue-900 text-lg mb-1">Income Tracking</div>
            <div class="text-sm text-gray-600">Per level, class, term, student</div>
        </a>
    </div>
@endsection