@extends("layouts.app")
@section("title", "Edit Template")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Edit Template — {{ $smsTemplate->key }} ({{ strtoupper($smsTemplate->language) }})</h1>

    <form method="POST" action="{{ route("admin.sms-templates.update", $smsTemplate) }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
        @csrf @method("PUT")

        <div>
            <label class="block font-semibold mb-1">Name</label>
            <input type="text" name="name" value="{{ old("name", $smsTemplate->name) }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Body (max 600 chars)</label>
            <textarea name="body" rows="5" maxlength="600" class="w-full border rounded px-3 py-2" required>{{ old("body", $smsTemplate->body) }}</textarea>
            <p class="text-xs text-gray-500 mt-1">
                Use <code class="bg-gray-100 px-1">{placeholder}</code> for dynamic values:
                @if($smsTemplate->key === "absence")
                    <code>{student_name}</code>, <code>{date}</code>
                @elseif($smsTemplate->key === "result")
                    <code>{student_name}</code>, <code>{exam_name}</code>, <code>{summary}</code>
                @elseif($smsTemplate->key === "payment")
                    <code>{student_name}</code>, <code>{amount}</code>, <code>{receipt}</code>, <code>{balance}</code>
                @elseif($smsTemplate->key === "welcome")
                    <code>{school_name}</code>, <code>{student_name}</code>, <code>{admission_no}</code>, <code>{class_name}</code>, <code>{level}</code>, <code>{fee}</code>
                @elseif($smsTemplate->key === "promotion")
                    <code>{student_name}</code>, <code>{old_class}</code>, <code>{new_class}</code>
                @elseif($smsTemplate->key === "graduation")
                    <code>{student_name}</code>, <code>{old_class}</code>
                @elseif($smsTemplate->key === "emergency")
                    <code>{student_name}</code>, <code>{incident_type}</code>, <code>{description}</code>
                @elseif($smsTemplate->key === "fee_reminder")
                    <code>{student_name}</code>, <code>{balance}</code>, <code>{due_date}</code>
                @endif
            </p>
        </div>

        <div>
            <label class="block font-semibold mb-1">Description</label>
            <input type="text" name="description" value="{{ old("description", $smsTemplate->description) }}" class="w-full border rounded px-3 py-2">
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" {{ $smsTemplate->is_active ? "checked" : "" }}>
            <span>Active</span>
        </label>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Update Template</button>
    </form>
@endsection