<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-blue-900">{{ $department->name }} Department</h1>
        <p class="text-gray-600 mt-1">{{ $department->description }}</p>
    </div>
    <a href="{{ route("dept." . $department->code . ".create") }}"
       class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">
        Add Record
    </a>
</div>