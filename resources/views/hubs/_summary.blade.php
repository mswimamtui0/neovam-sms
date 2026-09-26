<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    @foreach($summary as $label => $value)
        <div class="bg-white rounded shadow p-4">
            <div class="text-2xl font-bold text-blue-900">{{ $value }}</div>
            <div class="text-xs text-gray-500 mt-1">{{ $label }}</div>
        </div>
    @endforeach
</div>