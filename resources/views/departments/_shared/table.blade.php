<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-blue-900 text-white">
            <tr>
                @foreach($tableColumns as $col)
                    <th class="p-3 text-left">{{ ucwords(str_replace("_"," ",$col)) }}</th>
                @endforeach
                <th class="p-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr class="border-b hover:bg-gray-50">
                    @foreach($tableColumns as $col)
                        <td class="p-3">{{ $record->$col ?? "-" }}</td>
                    @endforeach
                    <td class="p-3">
                        <a href="{{ route("dept." . $department->code . ".show", $record->id) }}" class="text-blue-700 hover:underline">View</a>
                        <a href="{{ route("dept." . $department->code . ".edit", $record->id) }}" class="text-yellow-700 hover:underline ml-2">Edit</a>
                        <form method="POST" action="{{ route("dept." . $department->code . ".destroy", $record->id) }}" class="inline ml-2"
                              onsubmit="return confirm('Delete this record?');">
                            @csrf @method("DELETE")
                            <button class="text-red-700 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="{{ count($tableColumns) + 1 }}" class="p-6 text-center text-gray-500">No records yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $records->links() }}</div>