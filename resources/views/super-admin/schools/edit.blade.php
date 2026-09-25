@extends("layouts.app")
@section("title", "Edit School")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Edit School — {{ $school->name }}</h1>

    <form method="POST" action="{{ route("super-admin.schools.update", $school) }}" class="bg-white rounded shadow p-6 space-y-4 max-w-4xl">
        @csrf @method("PUT")

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">School Name</label>
                <input type="text" name="name" value="{{ old("name", $school->name) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Group Name</label>
                <input type="text" name="group_name" value="{{ old("group_name", $school->group_name) }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Code</label>
                <input type="text" name="code" value="{{ old("code", $school->code) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Branch Code</label>
                <input type="text" name="branch_code" value="{{ old("branch_code", $school->branch_code) }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Subdomain</label>
                <input type="text" name="subdomain" value="{{ old("subdomain", $school->subdomain) }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old("phone", $school->phone) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" value="{{ old("email", $school->email) }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Address</label>
                <input type="text" name="address" value="{{ old("address", $school->address) }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="flex gap-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="has_primary" value="1" {{ $school->has_primary ? "checked" : "" }}>
                <span>Primary</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="has_secondary" value="1" {{ $school->has_secondary ? "checked" : "" }}>
                <span>Secondary</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="has_alevel" value="1" {{ $school->has_alevel ? "checked" : "" }}>
                <span>A-Level</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ $school->is_active ? "checked" : "" }}>
                <span>Active</span>
            </label>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Parent School (if branch)</label>
                <select name="parent_school_id" class="w-full border rounded px-3 py-2">
                    <option value="">None — Main School</option>
                    @foreach($parents as $p)
                        <option value="{{ $p->id }}" {{ $school->parent_school_id == $p->id ? "selected" : "" }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Subscription Plan</label>
                <select name="subscription_plan" class="w-full border rounded px-3 py-2" required>
                    @foreach(["basic","standard","premium","enterprise"] as $p)
                        <option value="{{ $p }}" {{ $school->subscription_plan === $p ? "selected" : "" }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Subscription Expires</label>
                <input type="date" name="subscription_expires_at" value="{{ $school->subscription_expires_at?->format("Y-m-d") }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">SMS Balance Units</label>
                <input type="number" name="sms_balance_units" value="{{ old("sms_balance_units", $school->sms_balance_units) }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Update</button>
    </form>
@endsection