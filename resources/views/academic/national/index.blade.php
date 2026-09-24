@extends("layouts.app")
@section("title", "National Exams")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">National Exams</h1>

    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded shadow p-6 border-l-4 border-blue-600">
            <h3 class="font-bold text-blue-900 mb-2">PSLE</h3>
            <p class="text-sm text-gray-600">Primary School Leaving Examination</p>
            <p class="text-xs text-gray-500 mt-2">Standard 7</p>
        </div>
        <div class="bg-white rounded shadow p-6 border-l-4 border-green-600">
            <h3 class="font-bold text-green-900 mb-2">CSEE</h3>
            <p class="text-sm text-gray-600">Certificate of Secondary Education Examination</p>
            <p class="text-xs text-gray-500 mt-2">Form 4</p>
        </div>
        <div class="bg-white rounded shadow p-6 border-l-4 border-purple-600">
            <h3 class="font-bold text-purple-900 mb-2">ACSEE</h3>
            <p class="text-sm text-gray-600">Advanced Certificate of Secondary Education Examination</p>
            <p class="text-xs text-gray-500 mt-2">Form 6</p>
        </div>
    </div>

    <div class="bg-white rounded shadow p-6 mt-6">
        <p class="text-gray-600">National exam registration and tracking will be added here.</p>
    </div>
@endsection