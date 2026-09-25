@extends("layouts.app")
@section("title", "Add Fee Structure")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Fee Structure</h1>
    <form method="POST" action="{{ route("admin.fee-structures.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Level</label>
                <select name="level" class="w-full border rounded px-3 py-2" required>
                    @foreach(["nursery","kg","pre_unit","primary","secondary","alevel"] as $l)
                        <option value="{{ $l }}">{{ ucfirst($l) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Term</label>
                <input type="text" name="term" value="Term 1" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Year</label>
                <input type="number" name="year" value="{{ date("Y") }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Tuition Fee</label>
                <input type="number" step="0.01" name="tuition_fee" value="0" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Transport Fee</label>
                <input type="number" step="0.01" name="transport_fee" value="0" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Meal Fee</label>
                <input type="number" step="0.01" name="meal_fee" value="0" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Development Fee</label>
                <input type="number" step="0.01" name="development_fee" value="0" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Exam Fee</label>
                <input type="number" step="0.01" name="exam_fee" value="0" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Other Fee</label>
                <input type="number" step="0.01" name="other_fee" value="0" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" rows="2" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Fee Structure</button>
    </form>
@endsection