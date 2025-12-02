<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Solo Raid') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.solo-raids.update', $soloRaid) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <!-- Basic Info -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-gray-700">Raid Name</label>
                                    <input type="text" name="nama" id="nama" value="{{ $soloRaid->nama }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="draft" {{ $soloRaid->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="active" {{ $soloRaid->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="selesai" {{ $soloRaid->status === 'selesai' ? 'selected' : '' }}>Finished</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ $soloRaid->deskripsi }}</textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Start Date</label>
                                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $soloRaid->tanggal_mulai ? $soloRaid->tanggal_mulai->format('Y-m-d') : '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    </div>
                                    <div>
                                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">End Date</label>
                                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $soloRaid->tanggal_selesai ? $soloRaid->tanggal_selesai->format('Y-m-d') : '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Nodes -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Info Nodes (Study Material)</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="info_node_1" class="block text-sm font-medium text-gray-700">Info Node 1 (Before Easy)</label>
                                    <textarea name="info_node_1" id="info_node_1" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $soloRaid->info_node_1 }}</textarea>
                                </div>
                                <div>
                                    <label for="info_node_2" class="block text-sm font-medium text-gray-700">Info Node 2 (Before Medium)</label>
                                    <textarea name="info_node_2" id="info_node_2" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $soloRaid->info_node_2 }}</textarea>
                                </div>
                                <div>
                                    <label for="info_node_3" class="block text-sm font-medium text-gray-700">Info Node 3 (Before Hard)</label>
                                    <textarea name="info_node_3" id="info_node_3" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $soloRaid->info_node_3 }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Level Config -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Level Configuration</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Easy -->
                                <div class="border p-4 rounded-md">
                                    <div class="flex items-center mb-4">
                                        <input type="checkbox" name="easy_enabled" id="easy_enabled" value="1" {{ $soloRaid->easy_enabled ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="easy_enabled" class="ml-2 block text-sm font-medium text-gray-900">Enable Easy</label>
                                    </div>
                                    <label for="boss_easy_name" class="block text-sm font-medium text-gray-700">Boss Name</label>
                                    <input type="text" name="boss_easy_name" id="boss_easy_name" value="{{ $soloRaid->boss_easy_name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <!-- Medium -->
                                <div class="border p-4 rounded-md">
                                    <div class="flex items-center mb-4">
                                        <input type="checkbox" name="medium_enabled" id="medium_enabled" value="1" {{ $soloRaid->medium_enabled ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="medium_enabled" class="ml-2 block text-sm font-medium text-gray-900">Enable Medium</label>
                                    </div>
                                    <label for="boss_medium_name" class="block text-sm font-medium text-gray-700">Boss Name</label>
                                    <input type="text" name="boss_medium_name" id="boss_medium_name" value="{{ $soloRaid->boss_medium_name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <!-- Hard -->
                                <div class="border p-4 rounded-md">
                                    <div class="flex items-center mb-4">
                                        <input type="checkbox" name="hard_enabled" id="hard_enabled" value="1" {{ $soloRaid->hard_enabled ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="hard_enabled" class="ml-2 block text-sm font-medium text-gray-900">Enable Hard</label>
                                    </div>
                                    <label for="boss_hard_name" class="block text-sm font-medium text-gray-700">Boss Name</label>
                                    <input type="text" name="boss_hard_name" id="boss_hard_name" value="{{ $soloRaid->boss_hard_name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('admin.solo-raids.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Raid</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
