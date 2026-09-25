@extends("layouts.app")
@section("title", "Add Salary Structure")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Salary Structure</h1>

    <form method="POST" action="{{ route("admin.salary-structures.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Staff Member</label>
            <select name="staff_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Select Staff --</option>
                @foreach($staffList as $s)
                    <option value="{{ $s->id }}" {{ $preselect == $s->id ? "selected" : "" }}>{{ $s->full_name }} — {{ $s->staff_type }}</option>
                @endforeach
            </select>
        </div>

        <h3 class="font-bold text-blue-900 border-b pb-2">Earnings</h3>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Basic Salary</label>
                <input type="number" step="0.01" name="basic_salary" value="0" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">House Allowance</label>
                <input type="number" step="0.01" name="allowance_house" value="0" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Transport Allowance</label>
                <input type="number" step="0.01" name="allowance_transport" value="0" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Meal Allowance</label>
                <input type="number" step="0.01" name="allowance_meal" value="0" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Other Allowance</label>
                <input type="number" step="0.01" name="allowance_other" value="0" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <h3 class="font-bold text-blue-900 border-b pb-2">Deductions</h3>
        <div class="grid grid-cols-4 gap-4">
            <div>
                <label class="block font-semibold mb-1">Tax (PAYE)</label>
                <input type="number" step="0.01" name="deduction_tax" value="0" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">NSSF / Pension</label>
                <input type="number" step="0.01" name="deduction_nssf" value="0" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Loan / Advance</label>
                <input type="number" step="0.01" name="deduction_loan" value="0" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Other Deduction</label>
                <input type="number" step="0.01" name="deduction_other" value="0" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <h3 class="font-bold text-blue-900 border-b pb-2">Payment</h3>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Payment Method</label>
                <select name="payment_method" class="w-full border rounded px-3 py-2" required>
                    <option value="bank">Bank Transfer</option>
                    <option value="cash">Cash</option>
                    <option value="mobile_money">Mobile Money</option>
                    <option value="cheque">Cheque</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Bank Name</label>
                <input type="text" name="bank_name" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Bank Account</label>
                <input type="text" name="bank_account" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Effective From</label>
            <input type="date" name="effective_from" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" rows="2" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Structure</button>
    </form>
@endsection