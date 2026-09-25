@extends("layouts.app")
@section("title", "Edit Salary Structure")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Edit Salary Structure — {{ $salaryStructure->staff?->full_name }}</h1>

    <form method="POST" action="{{ route("admin.salary-structures.update", $salaryStructure) }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
        @csrf @method("PUT")

        <h3 class="font-bold text-blue-900 border-b pb-2">Earnings</h3>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Basic Salary</label>
                <input type="number" step="0.01" name="basic_salary" value="{{ $salaryStructure->basic_salary }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">House Allowance</label>
                <input type="number" step="0.01" name="allowance_house" value="{{ $salaryStructure->allowance_house }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Transport Allowance</label>
                <input type="number" step="0.01" name="allowance_transport" value="{{ $salaryStructure->allowance_transport }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Meal Allowance</label>
                <input type="number" step="0.01" name="allowance_meal" value="{{ $salaryStructure->allowance_meal }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Other Allowance</label>
                <input type="number" step="0.01" name="allowance_other" value="{{ $salaryStructure->allowance_other }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <h3 class="font-bold text-blue-900 border-b pb-2">Deductions</h3>
        <div class="grid grid-cols-4 gap-4">
            <div>
                <label class="block font-semibold mb-1">Tax (PAYE)</label>
                <input type="number" step="0.01" name="deduction_tax" value="{{ $salaryStructure->deduction_tax }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">NSSF / Pension</label>
                <input type="number" step="0.01" name="deduction_nssf" value="{{ $salaryStructure->deduction_nssf }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Loan</label>
                <input type="number" step="0.01" name="deduction_loan" value="{{ $salaryStructure->deduction_loan }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Other</label>
                <input type="number" step="0.01" name="deduction_other" value="{{ $salaryStructure->deduction_other }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <h3 class="font-bold text-blue-900 border-b pb-2">Payment</h3>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Payment Method</label>
                <select name="payment_method" class="w-full border rounded px-3 py-2" required>
                    @foreach(["bank","cash","mobile_money","cheque"] as $m)
                        <option value="{{ $m }}" {{ $salaryStructure->payment_method === $m ? "selected" : "" }}>{{ ucfirst(str_replace("_"," ",$m)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Bank Name</label>
                <input type="text" name="bank_name" value="{{ $salaryStructure->bank_name }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Bank Account</label>
                <input type="text" name="bank_account" value="{{ $salaryStructure->bank_account }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Effective From</label>
            <input type="date" name="effective_from" value="{{ $salaryStructure->effective_from?->format("Y-m-d") }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" rows="2" class="w-full border rounded px-3 py-2">{{ $salaryStructure->notes }}</textarea>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Update Structure</button>
    </form>
@endsection