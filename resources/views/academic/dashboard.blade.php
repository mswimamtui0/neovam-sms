@extends("layouts.app")
@section("title", "Academic Dashboard")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Academic Dashboard</h1>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-sm text-gray-500">Students</div>
            <div class="text-3xl font-bold text-blue-900">{{ $totalStudents }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-sm text-gray-500">Teachers</div>
            <div class="text-3xl font-bold text-green-900">{{ $totalTeachers }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
            <div class="text-sm text-gray-500">Subjects</div>
            <div class="text-3xl font-bold text-purple-900">{{ $totalSubjects }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
            <div class="text-sm text-gray-500">Avg Marks</div>
            <div class="text-3xl font-bold text-yellow-900">{{ number_format($avgMarks ?? 0, 1) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded shadow p-4 border-l-4 border-indigo-600">
            <div class="text-sm text-gray-500">Published Exams</div>
            <div class="text-2xl font-bold">{{ $publishedExams }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-orange-600">
            <div class="text-sm text-gray-500">Pending Exams</div>
            <div class="text-2xl font-bold">{{ $pendingExams }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-teal-600">
            <div class="text-sm text-gray-500">Avg Syllabus Coverage</div>
            <div class="text-2xl font-bold">{{ $avgCoverage }}%</div>
        </div>
    </div>
@endsection