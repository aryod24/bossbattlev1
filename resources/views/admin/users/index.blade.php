<x-admin-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-center bg-card p-6 rounded-2xl shadow-sm border border-border">
            <div>
                <h1 class="text-4xl font-black text-text-primary tracking-tight">User Management</h1>
                <p class="text-text-muted mt-2 font-medium">Manage student, dosen, and admin accounts.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-6 py-3 bg-primary hover:bg-accent-hover text-white font-bold rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-black/20">
                <span class="material-symbols-outlined mr-2">person_add</span>
                Create User
            </a>
        </div>

        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3" id="user-filter-form">
            <label class="flex flex-col h-12 flex-1 min-w-[260px]">
                <div class="flex w-full flex-1 items-stretch rounded-lg bg-card border border-border focus-within:ring-2 focus-within:ring-primary">
                    <div class="flex items-center justify-center pl-4">
                        <span class="material-symbols-outlined text-text-muted">search</span>
                    </div>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari berdasarkan nama, email, NIM, atau kelas..."
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-base font-normal h-full placeholder:text-text-muted px-4 pl-2 focus:outline-none focus:ring-0 border-none bg-transparent text-text-primary"/>
                </div>
            </label>
            <select name="role" onchange="document.getElementById('user-filter-form').submit()"
                    class="h-12 px-4 rounded-lg bg-card border border-border text-text-primary text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="" {{ $roleFilter === '' ? 'selected' : '' }}>Semua Role</option>
                <option value="student" {{ $roleFilter === 'student' ? 'selected' : '' }}>Student</option>
                <option value="dosen" {{ $roleFilter === 'dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <button type="submit" class="h-12 px-5 rounded-lg bg-primary hover:bg-accent-hover text-white font-bold text-sm">Cari</button>
            @if($search !== '' || $roleFilter !== '')
                <a href="{{ route('admin.users.index') }}" class="h-12 px-5 rounded-lg border border-border text-text-muted hover:bg-card flex items-center font-medium text-sm">Reset</a>
            @endif
        </form>

        @if($search !== '' || $roleFilter !== '')
            <p class="text-sm text-text-muted -mt-3">
                Menampilkan {{ $users->total() }} hasil
                @if($search !== '') untuk "<strong>{{ $search }}</strong>" @endif
                @if($roleFilter !== '') (role: <strong>{{ ucfirst($roleFilter) }}</strong>) @endif
            </p>
        @endif

        <div class="bg-card rounded-2xl shadow-sm border border-border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-dark border-b border-border">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-muted uppercase tracking-wider">
                                Name / ID
                                <span class="material-symbols-outlined align-middle text-[14px] text-primary" title="Diurutkan A-Z">arrow_upward</span>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-muted uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-muted uppercase tracking-wider">Level & XP</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-muted uppercase tracking-wider">Class</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-text-muted uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($users as $user)
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error border border-error/20">Admin</span>
                                @elseif($user->role === 'dosen')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning border border-warning/20">Dosen</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-info/10 text-info border border-info/20">Student</span>
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
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-text-muted">
                                <span class="material-symbols-outlined text-4xl block mb-2">search_off</span>
                                Tidak ada user yang cocok dengan pencarian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-border">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
