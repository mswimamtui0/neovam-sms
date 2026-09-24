@extends("layouts.app")
@section("title", "Academic Reports")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Academic Reports</h1>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-sm text-gray-500">Subjects</div>
            <div class="text-2xl font-bold">{{ $totalSubjects }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-sm text-gray-500">Classes</div>
            <div class="text-2xl font-bold">{{ $totalClasses }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
            <div class="text-sm text-gray-500">Exams</div>
            <div class="text-2xl font-bold">{{ $totalExams }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
            <div class="text-sm text-gray-500">Avg Marks</div>
            <div class="text-2xl font-bold">{{ number_format($avgMarks ?? 0, 1) }}</div>
        </div>
    </div>

    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold text-blue-900 mb-4">Students per Class</h2>
        @foreach($classAvg as $c)
            <div class="border-b py-2 flex justify-between">
                <span>{{ $c->name }} ({{ ucfirst($c->level) }})</span>
                <span class="font-bold">{{ $c->students_count }} students</span>
            </div>
        @endforeach
    </div>
@endsection