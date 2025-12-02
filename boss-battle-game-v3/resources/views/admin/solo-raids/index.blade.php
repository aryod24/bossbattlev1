<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Solo Raids') }}
            </h2>
            <a href="{{ route('admin.solo-raids.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create New Raid
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Levels</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($raids as $raid)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $raid->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $raid->tanggal_mulai }} - {{ $raid->tanggal_selesai }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'draft' => 'bg-gray-100 text-gray-800',
                                                'active' => 'bg-green-100 text-green-800',
                                                'selesai' => 'bg-blue-100 text-blue-800',
                                            ];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$raid->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($raid->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <form action="{{ route('admin.solo-raids.toggle-level', $raid) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="level" value="easy">
                                            <button type="submit" class="{{ $raid->easy_enabled ? 'text-green-600' : 'text-gray-400' }} hover:text-green-900 mr-2" title="Toggle Easy">E</button>
                                        </form>
                                        <form action="{{ route('admin.solo-raids.toggle-level', $raid) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="level" value="medium">
                                            <button type="submit" class="{{ $raid->medium_enabled ? 'text-yellow-600' : 'text-gray-400' }} hover:text-yellow-900 mr-2" title="Toggle Medium">M</button>
                                        </form>
                                        <form action="{{ route('admin.solo-raids.toggle-level', $raid) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="level" value="hard">
                                            <button type="submit" class="{{ $raid->hard_enabled ? 'text-red-600' : 'text-gray-400' }} hover:text-red-900" title="Toggle Hard">H</button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.solo-raids.edit', $raid) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        
                                        <form action="{{ route('admin.solo-raids.duplicate', $raid) }}" method="POST" class="inline mr-3">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:text-blue-900" onclick="return confirm('Duplicate this raid?')">Copy</button>
                                        </form>

                                        @if($raid->status !== 'active')
                                            <form action="{{ route('admin.solo-raids.destroy', $raid) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
