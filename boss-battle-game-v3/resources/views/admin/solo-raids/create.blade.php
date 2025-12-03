<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-primary leading-tight">
            {{ __('Create Solo Raid') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-card overflow-hidden shadow-sm sm:rounded-lg border border-border">
                <div class="p-6 text-text-primary">
                    <form action="{{ route('admin.solo-raids.store') }}" method="POST">
                        @csrf

                        <!-- Basic Info -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-text-primary mb-4">Basic Information</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-text-muted">Raid Name</label>
                                    <input type="text" name="nama" id="nama" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-text-muted">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="draft" selected>Draft</option>
                                        <option value="active">Active</option>
                                        <option value="selesai">Finished</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="deskripsi" class="block text-sm font-medium text-text-muted">Description</label>
                                    <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="tanggal_mulai" class="block text-sm font-medium text-text-muted">Start Date</label>
                                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>
                                    </div>
                                    <div>
                                        <label for="tanggal_selesai" class="block text-sm font-medium text-text-muted">End Date</label>
                                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Nodes -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-text-primary mb-4">Info Nodes (Study Material)</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="info_node_1" class="block text-sm font-medium text-text-muted">Info Node 1 (Before Easy)</label>
                                    <textarea name="info_node_1" id="info_node_1" rows="3" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary"></textarea>
                                </div>
                                <div>
                                    <label for="info_node_2" class="block text-sm font-medium text-text-muted">Info Node 2 (Before Medium)</label>
                                    <textarea name="info_node_2" id="info_node_2" rows="3" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary"></textarea>
                                </div>
                                <div>
                                    <label for="info_node_3" class="block text-sm font-medium text-text-muted">Info Node 3 (Before Hard)</label>
                                    <textarea name="info_node_3" id="info_node_3" rows="3" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Level Config -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-text-primary mb-4">Level Configuration</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Easy -->
                                <div class="border border-border p-4 rounded-md bg-background-dark">
                                    <div class="flex items-center mb-4">
                                        <input type="checkbox" name="easy_enabled" id="easy_enabled" value="1" checked class="h-4 w-4 text-primary focus:ring-primary border-border rounded bg-background-dark">
                                        <label for="easy_enabled" class="ml-2 block text-sm font-medium text-text-primary">Enable Easy</label>
                                    </div>
                                    <label for="boss_easy_name" class="block text-sm font-medium text-text-muted">Boss Name</label>
                                    <input type="text" name="boss_easy_name" id="boss_easy_name" value="Goblin King" class="mt-1 block w-full rounded-md bg-card border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                                </div>

                                <!-- Medium -->
                                <div class="border border-border p-4 rounded-md bg-background-dark">
                                    <div class="flex items-center mb-4">
                                        <input type="checkbox" name="medium_enabled" id="medium_enabled" value="1" class="h-4 w-4 text-primary focus:ring-primary border-border rounded bg-background-dark">
                                        <label for="medium_enabled" class="ml-2 block text-sm font-medium text-text-primary">Enable Medium</label>
                                    </div>
                                    <label for="boss_medium_name" class="block text-sm font-medium text-text-muted">Boss Name</label>
                                    <input type="text" name="boss_medium_name" id="boss_medium_name" value="Orc Warlord" class="mt-1 block w-full rounded-md bg-card border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                                </div>

                                <!-- Hard -->
                                <div class="border border-border p-4 rounded-md bg-background-dark">
                                    <div class="flex items-center mb-4">
                                        <input type="checkbox" name="hard_enabled" id="hard_enabled" value="1" class="h-4 w-4 text-primary focus:ring-primary border-border rounded bg-background-dark">
                                        <label for="hard_enabled" class="ml-2 block text-sm font-medium text-text-primary">Enable Hard</label>
                                    </div>
                                    <label for="boss_hard_name" class="block text-sm font-medium text-text-muted">Boss Name</label>
                                    <input type="text" name="boss_hard_name" id="boss_hard_name" value="Dragon Lord" class="mt-1 block w-full rounded-md bg-card border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('admin.solo-raids.index') }}" class="bg-border hover:bg-text-muted text-text-primary font-bold py-2 px-4 rounded mr-2">Cancel</a>
                            <button type="submit" class="bg-primary hover:bg-primary/80 text-white font-bold py-2 px-4 rounded">Create Raid</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
