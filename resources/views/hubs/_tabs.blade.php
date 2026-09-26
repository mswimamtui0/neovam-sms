<div class="bg-white rounded shadow overflow-hidden">
    {{-- Tab buttons --}}
    <div class="border-b border-gray-200 flex flex-wrap">
        @foreach($tabs as $key => $tab)
            <a href="?tab={{ $key }}"
               class="px-5 py-3 text-sm font-semibold {{ $activeTab === $key ? "text-blue-900 border-b-2 border-blue-900" : "text-gray-600 hover:text-blue-900" }}">
                {{ $tab["label"] }}
            </a>
        @endforeach
    </div>

    {{-- Tab content --}}
    @php $active = $tabs[$activeTab] ?? reset($tabs); @endphp
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-700">
            <tr>
                @foreach($active["columns"] as $col)
                    <th class="p-3 text-left">{{ ucwords(str_replace("_"," ",$col)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($active["records"] as $row)
                <tr class="border-b hover:bg-gray-50">
                    @foreach($active["columns"] as $col)
                        <td class="p-3">
                            @if(in_array($col, ["created_at","updated_at"]) && $row->$col)
                                {{ $row->$col->format("Y-m-d H:i") }}
                            @else
                                {{ $row->$col ?? "-" }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ count($active["columns"]) }}" class="p-6 text-center text-gray-500">No records in this tab yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>