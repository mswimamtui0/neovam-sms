@extends("layouts.app")
@section("title", "Compare Schools")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Compare Schools</h1>
        <a href="{{ route("super-admin.schools.index") }}" class="text-blue-700 hover:underline">← Back</a>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">School</th>
                    <th class="p-3 text-right">Students</th>
                    <th class="p-3 text-right">Staff</th>
                    <th class="p-3 text-right">Users</th>
                    <th class="p-3 text-right">Classes</th>
                    <th class="p-3 text-right">SMS Units</th>
                    <th class="p-3 text-left">Plan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schools as $s)
                    <tr class="border-b">
                        <td class="p-3 font-semibold">{{ $s->name }}</td>
                        <td class="p-3 text-right">{{ $s->students_count }}</td>
                        <td class="p-3 text-right">{{ $s->staff_count }}</td>
                        <td class="p-3 text-right">{{ $s->users_count }}</td>
                        <td class="p-3 text-right">{{ $s->classrooms_count }}</td>
                        <td class="p-3 text-right">{{ number_format($s->sms_balance_units) }}</td>
                        <td class="p-3 text-xs">{{ ucfirst($s->subscription_plan) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection