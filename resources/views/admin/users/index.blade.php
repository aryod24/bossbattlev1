<x-admin-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-center bg-card p-6 rounded-2xl shadow-sm border border-border">
            <div>
                <h1 class="text-4xl font-black text-text-primary tracking-tight">User Management</h1>
                <p class="text-text-muted mt-2 font-medium">Manage student and admin accounts.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-6 py-3 bg-primary hover:bg-accent-hover text-white font-bold rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-black/20">
                <span class="material-symbols-outlined mr-2">person_add</span>
                Create User
            </a>
        </div>

        <div class="bg-card rounded-2xl shadow-sm border border-border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-dark border-b border-border">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-muted uppercase tracking-wider">Name / ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-muted uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-muted uppercase tracking-wider">Level & XP</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-muted uppercase tracking-wider">Class</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-text-muted uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($users as $user)
                        <tr class="hover:bg-primary/5 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full bg-surface-light border border-border flex items-center justify-center text-lg">
                                        {{ substr($user->nama, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-text-primary">{{ $user->nama }}</div>
                                        <div class="text-xs text-text-muted">{{ $user->email }}</div>
                                        @if($user->nim)
                                            <div class="text-xs text-text-muted font-mono mt-0.5">{{ $user->nim }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error border border-error/20">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-info/10 text-info border border-info/20">
                                        Student
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <span class="text-sm font-bold text-primary">Lvl {{ $user->level }}</span>
                                    <span class="text-xs text-text-muted">{{ number_format($user->total_xp) }} XP</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-muted">
                                {{ $user->kelas ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-info hover:bg-info/10 rounded-lg transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-error hover:bg-error/10 rounded-lg transition-colors" title="Delete">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-border">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
