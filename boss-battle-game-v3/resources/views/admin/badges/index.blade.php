<x-admin-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-center bg-card p-6 rounded-2xl shadow-sm border border-border">
            <div>
                <h1 class="text-4xl font-black text-text-primary tracking-tight">System Badges</h1>
                <p class="text-text-muted mt-2 font-medium">Manage achievement badges for players.</p>
            </div>
            <a href="{{ route('admin.badges.create') }}" class="inline-flex items-center px-6 py-3 bg-primary hover:bg-accent-hover text-white font-bold rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-black/20">
                <span class="material-symbols-outlined mr-2">add_circle</span>
                Create Badge
            </a>
        </div>

        <div class="bg-card rounded-2xl shadow-sm border border-border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-dark border-b border-border">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-muted uppercase tracking-wider">Emoji</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-muted uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-muted uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-muted uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-muted uppercase tracking-wider">System</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-text-muted uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($badges as $badge)
                        <tr class="hover:bg-primary/5 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap text-2xl">
                                {{ $badge->emoji }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-text-primary">{{ $badge->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-muted font-mono">
                                {{ $badge->slug }}
                            </td>
                            <td class="px-6 py-4 text-sm text-text-dark-secondary max-w-xs truncate">
                                {{ $badge->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($badge->is_system)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-info/10 text-info border border-info/20">
                                        System
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary/10 text-text-muted border border-border">
                                        Custom
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.badges.edit', $badge) }}" class="p-2 text-info hover:bg-info/10 rounded-lg transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    
                                    @if(!$badge->is_system)
                                        <form action="{{ route('admin.badges.destroy', $badge) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this badge?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-error hover:bg-error/10 rounded-lg transition-colors" title="Delete">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    @else
                                        <div class="p-2 text-text-muted/30 cursor-not-allowed">
                                             <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($badges->isEmpty())
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-dark mb-4 border border-border">
                        <span class="material-symbols-outlined text-3xl text-text-muted">emoji_events</span>
                    </div>
                    <h3 class="text-lg font-bold text-text-primary">No Badges Found</h3>
                    <p class="text-text-muted mt-2">Start by creating your first achievement badge!</p>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
