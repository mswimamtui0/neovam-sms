@extends("layouts.app")
@section("title", "Add School")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Add New School</h1>

    <form method="POST" action="{{ route("super-admin.schools.store") }}" class="bg-white rounded shadow p-6 space-y-6 max-w-4xl">
        @csrf

        <h3 class="font-bold text-blue-900 border-b pb-2">School Details</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">School Name</label>
                <input type="text" name="name" value="{{ old("name") }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Group Name (optional)</label>
                <input type="text" name="group_name" value="{{ old("group_name") }}" class="w-full border rounded px-3 py-2" placeholder="e.g. NEOVAM Group">
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Code</label>
                <input type="text" name="code" value="{{ old("code") }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Branch Code (optional)</label>
                <input type="text" name="branch_code" value="{{ old("branch_code") }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Subdomain (optional)</label>
                <input type="text" name="subdomain" value="{{ old("subdomain") }}" class="w-full border rounded px-3 py-2" placeholder="e.g. arusha">
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old("phone") }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" value="{{ old("email") }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Address</label>
                <input type="text" name="address" value="{{ old("address") }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <h3 class="font-bold text-blue-900 border-b pb-2">Education Levels</h3>
        <div class="flex gap-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="has_primary" value="1" {{ old("has_primary") ? "checked" : "" }}>
                <span>Primary</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="has_secondary" value="1" {{ old("has_secondary") ? "checked" : "" }}>
                <span>Secondary</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="has_alevel" value="1" {{ old("has_alevel") ? "checked" : "" }}>
                <span>A-Level</span>
            </label>
        </div>

        <h3 class="font-bold text-blue-900 border-b pb-2">Subscription</h3>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Plan</label>
                <select name="subscription_plan" class="w-full border rounded px-3 py-2" required>
                    @foreach(["basic","standard","premium","enterprise"] as $p)
                        <option value="{{ $p }}" {{ old("subscription_plan") === $p ? "selected" : "" }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Expires At</label>
                <input type="date" name="subscription_expires_at" value="{{ old("subscription_expires_at") }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Initial SMS Units</label>
                <input type="number" name="sms_balance_units" value="{{ old("sms_balance_units", 1000) }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Parent School (optional — if this is a branch)</label>
            <select name="parent_school_id" class="w-full border rounded px-3 py-2">
                <option value="">None — This is a main school</option>
                @foreach($parents as $p)
                    <option value="{{ $p->id }}" {{ old("parent_school_id") == $p->id ? "selected" : "" }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <h3 class="font-bold text-blue-900 border-b pb-2">Initial School Admin</h3>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Admin Name</label>
                <input type="text" name="admin_name" value="{{ old("admin_name") }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Admin Email</label>
                <input type="email" name="admin_email" value="{{ old("admin_email") }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Admin Password</label>
                <input type="text" name="admin_password" value="{{ old("admin_password") }}" class="w-full border rounded px-3 py-2" required>
                <p class="text-xs text-gray-500 mt-1">Share this with the school admin.</p>
            </div>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Create School</button>
    </form>
@endsection