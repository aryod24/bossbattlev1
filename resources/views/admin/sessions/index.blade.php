<x-admin-layout>
    <div x-data="{ activeTab: 'overview' }">
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-card p-6 rounded-2xl shadow-sm border border-border">
            <div>
                <h1 class="text-4xl font-black text-text-primary tracking-tight">Session Monitor</h1>
                <p class="text-text-muted mt-2 font-medium">Monitor and manage active game sessions.</p>
            </div>
            
            <!-- Tabs Navigation -->
            <div class="flex space-x-1 bg-surface-light dark:bg-surface-dark p-1 rounded-lg border border-border-light dark:border-border-dark">
                <button @click="activeTab = 'overview'" 
                        :class="activeTab === 'overview' ? 'bg-primary text-black shadow' : 'text-text-muted hover:text-text-primary'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-all">
                    Overview
                </button>
                <button @click="activeTab = 'solo'" 
                        :class="activeTab === 'solo' ? 'bg-primary text-black shadow' : 'text-text-muted hover:text-text-primary'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-all">
                    Solo Sessions
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- TAB: OVERVIEW -->
        <div x-show="activeTab === 'overview'" x-transition>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Session Solo -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg border border-border-light dark:border-border-dark p-6">
                    <h3 class="text-lg font-bold text-text-primary mb-2">Solo Sessions</h3>
                    <p class="text-3xl font-black text-primary mb-4">{{ $stats['session_solo'] }}</p>
                    <p class="text-sm text-text-muted mb-4">Total records in session_solo table</p>
                    
                    <form action="{{ route('admin.sessions.clear') }}" method="POST" onsubmit="return confirm('Are you sure? This will delete ALL solo session records.');">
                        @csrf
                        <input type="hidden" name="table" value="session_solo">
                        <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/50 font-bold py-2 px-4 rounded transition-colors">
                            Clear Solo Sessions
                        </button>
                    </form>

                    <form action="{{ route('admin.sessions.check-expired') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full bg-primary/10 hover:bg-primary/20 text-primary border border-primary/50 font-bold py-2 px-4 rounded transition-colors">
                            Check Expired Sessions
                        </button>
                    </form>
                </div>

                <!-- Session Answers -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg border border-border-light dark:border-border-dark p-6">
                    <h3 class="text-lg font-bold text-text-primary mb-2">Answer Records</h3>
                    <p class="text-3xl font-black text-primary mb-4">{{ $stats['session_answer'] }}</p>
                    <p class="text-sm text-text-muted mb-4">Total records in session_answer table</p>
                    
                    <form action="{{ route('admin.sessions.clear') }}" method="POST" onsubmit="return confirm('Are you sure? This will delete ALL answer records.');">
                        @csrf
                        <input type="hidden" name="table" value="session_answer">
                        <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/50 font-bold py-2 px-4 rounded transition-colors">
                            Clear Answers
                        </button>
                    </form>
                </div>
            </div>

            <!-- Global Actions -->
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg border border-border-light dark:border-border-dark p-8 text-center">
                <h2 class="text-2xl font-bold text-text-primary mb-4">Danger Zone</h2>
                <p class="text-text-muted mb-6">This action will wipe all session data from all tables. Use with caution.</p>
                
                <form action="{{ route('admin.sessions.clear') }}" method="POST" onsubmit="return confirm('WARNING: This will delete EVERYTHING (Sessions, Answers). Are you absolutely sure?');">
                    @csrf
                    <input type="hidden" name="table" value="all">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                        ⚠️ CLEAR ALL SESSION DATA
                    </button>
                </form>
            </div>
        </div>

        <!-- TAB: SOLO SESSIONS -->
        <div x-show="activeTab === 'solo'" x-transition>
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg border border-border-light dark:border-border-dark overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-background-light dark:bg-background-dark text-text-muted uppercase font-bold border-b border-border-light dark:border-border-dark">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4">Raid / Level</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Score / XP</th>
                                <th class="px-6 py-4">Started At</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light dark:divide-border-dark">
                            @forelse($soloSessions as $session)
                            <tr class="hover:bg-background-light/50 dark:hover:bg-background-dark/50 transition-colors">
                                <td class="px-6 py-4 font-mono text-text-muted">#{{ $session->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-text-primary">{{ $session->user->nama ?? 'Unknown' }}</div>
                                    <div class="text-xs text-text-muted">
                                        @if($session->user?->nim)
                                            <span class="font-semibold">{{ $session->user->nim }}</span>
                                            <span class="opacity-60">·</span>
                                        @endif
                                        {{ $session->user->email ?? '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-text-primary">{{ $session->soloRaid->name ?? 'Unknown Raid' }}</div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                                        {{ $session->level }}
                                    </span>
                                    @if($session->is_pretest || (int) ($session->jumlah_soal ?? 0) === 30)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-700 border border-indigo-200 ml-2">
                                            Pre-Test
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($session->waktu_selesai)
                                        @if($session->boss_kalah)
                                            <span class="text-green-500 font-bold flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm">trophy</span> Victory
                                            </span>
                                        @else
                                            <span class="text-red-500 font-bold flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm">close</span> Defeat
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-yellow-500 font-bold flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">play_circle</span> Playing
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-text-primary">{{ number_format($session->skor_akhir, 1) }}%</div>
                                    <div class="text-xs text-text-muted">+{{ $session->xp_diperoleh }} XP</div>
                                </td>
                                <td class="px-6 py-4 text-text-muted">
                                    {{ $session->waktu_mulai->format('d M Y H:i') }}
                                    <div class="text-xs">{{ $session->waktu_mulai->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.sessions.show', $session->id) }}" class="text-primary hover:text-primary/80 font-medium text-sm">View</a>
                                    
                                    <form action="{{ route('admin.sessions.destroy') }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this session?');">
                                        @csrf
                                        <input type="hidden" name="type" value="solo">
                                        <input type="hidden" name="id" value="{{ $session->id }}">
                                        <button type="submit" class="text-red-500 hover:text-red-400 font-medium text-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-text-muted">No solo sessions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-border-light dark:border-border-dark">
                    {{ $soloSessions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
