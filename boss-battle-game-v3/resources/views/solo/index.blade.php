<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Solo Raids') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($raids as $raid)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300 cursor-pointer" onclick="window.location='{{ route('solo.map', $raid) }}'">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-bold text-gray-900">{{ $raid->nama }}</h3>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $raid->deskripsi }}</p>
                            <div class="text-xs text-gray-500">
                                <p>Period: {{ $raid->tanggal_mulai }} - {{ $raid->tanggal_selesai }}</p>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <span class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Enter Dungeon &rarr;</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-500">
                        No active solo raids available at the moment.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
