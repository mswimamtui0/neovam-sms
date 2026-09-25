@extends("layouts.app")
@section("title", "Payroll — " . $run->label)
@section("content")
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-blue-900">Payroll Run — {{ $run->label }}</h1>
            <p class="text-gray-600">Period: {{ $run->period_start?->format("d M Y") }} → {{ $run->period_end?->format("d M Y") }}</p>
        </div>
        <div class="flex gap-2">
            @if($run->status === "draft")
                <form method="POST" action="{{ route("admin.payroll-runs.approve", $run) }}" class="inline">
                    @csrf
                    <button class="bg-blue-700 text-white px-4 py-2 rounded">Approve</button>
                </form>
            @endif

            @if(in_array($run->status, ["draft","approved"]))
                <form method="POST" action="{{ route("admin.payroll-runs.mark-paid", $run) }}" class="inline"
                      onsubmit="return confirm(&quot;Mark as paid and send SMS to all staff?&quot;);">
                    @csrf
                    <input type="hidden" name="send_sms" value="1">
                    <button class="bg-green-700 text-white px-4 py-2 rounded">Mark Paid + SMS</button>
                </form>
            @endif

            <a href="{{ route("admin.payroll-runs.index") }}" class="text-blue-700 hover:underline px-3 py-2">← Back</a>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-xs text-gray-500">Staff</div>
            <div class="text-2xl font-bold">{{ $run->staff_count }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
            <div class="text-xs text-gray-500">Total Gross</div>
            <div class="text-xl font-bold">TZS {{ number_format($run->total_gross) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
            <div class="text-xs text-gray-500">Total Deductions</div>
            <div class="text-xl font-bold text-red-700">TZS {{ number_format($run->total_deductions) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-xs text-gray-500">Total Net</div>
            <div class="text-xl font-bold text-green-700">TZS {{ number_format($run->total_net) }}</div>
        </div>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Staff</th>
                    <th class="p-3 text-right">Basic</th>
                    <th class="p-3 text-right">Allowances</th>
                    <th class="p-3 text-right">Deductions</th>
                    <th class="p-3 text-right">Gross</th>
                    <th class="p-3 text-right">Net Pay</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($run->items as $item)
                    <tr class="border-b">
                        <td class="p-3 font-semibold">{{ $item->staff?->full_name }}</td>
                        <td class="p-3 text-right">{{ number_format($item->basic_salary) }}</td>
                        <td class="p-3 text-right">{{ number_format($item->allowances) }}</td>
                        <td class="p-3 text-right text-red-700">{{ number_format($item->deductions) }}</td>
                        <td class="p-3 text-right">{{ number_format($item->gross_pay) }}</td>
                        <td class="p-3 text-right font-bold text-green-700">{{ number_format($item->net_pay) }}</td>
                        <td class="p-3">
                            @if($item->status === "paid")
                                <span class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-800">Paid</span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                        </td>
                        <td class="p-3">
                            @if($item->status !== "paid")
                                <form method="POST" action="{{ route("admin.payroll-items.pay", $item) }}" class="inline">
                                    @csrf
                                    <button class="text-green-700 hover:underline">Mark Paid</button>
                                </form>
                            @else
                                <span class="text-xs text-gray-500">{{ $item->paid_at?->format("d M Y") }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="p-6 text-center text-gray-500">No items.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection