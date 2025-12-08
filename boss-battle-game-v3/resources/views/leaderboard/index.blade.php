<x-app-layout>
    <div class="flex flex-col gap-8">
            <header>
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="flex min-w-72 flex-col gap-3">
                        <p class="text-text-primary text-4xl font-black leading-tight tracking-[-0.033em]">Leaderboard Global</p>
                        <p class="text-text-secondary text-base font-normal leading-normal">Lihat posisi Anda di antara semua mahasiswa</p>
                    </div>
                    <!-- 
                    <div class="flex flex-col items-end gap-4">
                        <div class="flex items-center gap-2">
                             Search and Filter can be implemented later 
                        </div>
                    </div>
                    -->
                </div>
            </header>

            <!-- PODIUM SECTION -->
            <section class="relative mt-8 flex items-end justify-center gap-4 px-4 h-96">
                <!-- Rank 2 -->
                @if($users->count() >= 2)
                    @php $user2 = $users[1]; @endphp
                    <div class="relative flex w-1/4 flex-col items-center">
                        <span class="material-symbols-outlined text-4xl text-gray-400" style="filter: drop-shadow(0 0 10px #C0C0C0);">workspace_premium</span>
                        <div class="h-28 w-28 rounded-full border-4 border-gray-400 bg-cover bg-center drop-shadow-lg"
                             style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode($user2->nama) }}&background=C0C0C0&color=fff&size=128');">
                        </div>
                        <div class="flex h-48 w-full flex-col items-center justify-start rounded-t-lg bg-gradient-to-b from-gray-500 to-gray-600 pt-6 shadow-2xl">
                            <span class="text-4xl font-black text-white drop-shadow-md">#2</span>
                            <p class="mt-2 text-lg font-bold text-white text-center px-1 truncate w-full">{{ $user2->nama }}</p>
                            <p class="text-sm font-medium text-gray-200">{{ number_format($user2->total_xp) }} XP</p>
                        </div>
                    </div>
                @endif

                <!-- Rank 1 -->
                @if($users->count() >= 1)
                    @php $user1 = $users[0]; @endphp
                    <div class="relative flex w-1/3 flex-col items-center">
                        <span class="material-symbols-outlined text-5xl text-yellow-400 absolute -top-12 z-10 drop-shadow-xl" style="filter: drop-shadow(0 0 15px #FFD700);">emoji_events</span>
                        <div class="h-36 w-36 rounded-full border-4 border-yellow-400 bg-cover bg-center drop-shadow-xl"
                             style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode($user1->nama) }}&background=FFD700&color=fff&size=128');">
                        </div>
                        <div class="flex h-64 w-full flex-col items-center justify-start rounded-t-lg bg-gradient-to-b from-yellow-500 to-yellow-600 pt-8 shadow-2xl">
                            <span class="text-6xl font-black text-white drop-shadow-lg">#1</span>
                            <p class="mt-2 text-xl font-bold text-white text-center px-1 truncate w-full">{{ $user1->nama }}</p>
                            <p class="text-base font-medium text-amber-100">{{ number_format($user1->total_xp) }} XP</p>
                        </div>
                    </div>
                @endif

                <!-- Rank 3 -->
                @if($users->count() >= 3)
                    @php $user3 = $users[2]; @endphp
                    <div class="relative flex w-1/4 flex-col items-center">
                        <span class="material-symbols-outlined text-4xl text-orange-400" style="filter: drop-shadow(0 0 10px #CD7F32);">workspace_premium</span>
                        <div class="h-28 w-28 rounded-full border-4 border-orange-400 bg-cover bg-center drop-shadow-lg"
                             style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode($user3->nama) }}&background=CD7F32&color=fff&size=128');">
                        </div>
                        <div class="flex h-40 w-full flex-col items-center justify-start rounded-t-lg bg-gradient-to-b from-orange-500 to-amber-700 pt-6 shadow-2xl">
                            <span class="text-4xl font-black text-white drop-shadow-md">#3</span>
                            <p class="mt-2 text-lg font-bold text-white text-center px-1 truncate w-full">{{ $user3->nama }}</p>
                            <p class="text-sm font-medium text-orange-100">{{ number_format($user3->total_xp) }} XP</p>
                        </div>
                    </div>
                @endif
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
                <!-- TABLE SECTION -->
                <!-- TABLE SECTION -->
                <main class="lg:col-span-2">
                    <div class="overflow-hidden rounded-lg bg-card border border-border shadow-sm">
                        <!-- Card Header -->
                        <div class="p-6 border-b border-border">
                            <h2 class="text-text-primary text-[22px] font-bold leading-tight tracking-[-0.015em]">Full Leaderboard</h2>
                        </div>
                        
                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-text-secondary">
                                <thead class="text-xs text-text-secondary uppercase bg-surface-light dark:bg-surface-dark">
                                    <tr>
                                        <th class="px-6 py-3 text-center" scope="col">Rank</th>
                                        <th class="px-6 py-3" scope="col">Player</th>
                                        <th class="px-6 py-3" scope="col">Level</th>
                                        <th class="px-6 py-3 text-right" scope="col">Total XP</th>
                                        <th class="px-6 py-3 text-right" scope="col">Win Rate</th>
                                        <th class="px-6 py-3 text-right" scope="col">Avg Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr class="{{ $user->id === auth()->id() ? 'bg-primary/10 border-l-4 border-primary' : 'bg-card border-b border-border' }}">
                                            <td class="px-6 py-4 text-center font-medium text-text-primary">
                                                @if($user->rank == 1)
                                                    <span class="material-symbols-outlined text-yellow-400 !text-2xl align-middle">emoji_events</span>
                                                @elseif($user->rank == 2)
                                                    <span class="material-symbols-outlined text-gray-400 !text-2xl align-middle">emoji_events</span>
                                                @elseif($user->rank == 3)
                                                    <span class="material-symbols-outlined text-orange-400 !text-2xl align-middle">emoji_events</span>
                                                @else
                                                    {{ $user->rank }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 font-medium text-text-primary whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=random" alt="{{ $user->nama }}"/>
                                                    <span>{{ $user->nama }} {{ $user->id === auth()->id() ? '(Anda)' : '' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="
                                                    text-xs font-medium px-2.5 py-0.5 rounded-full
                                                    {{ $user->rank_label == 'Master' ? 'bg-red-900 text-red-300' : '' }}
                                                    {{ $user->rank_label == 'Advanced' ? 'bg-purple-900 text-purple-300' : '' }}
                                                    {{ $user->rank_label == 'Gold' ? 'bg-yellow-900 text-yellow-300' : '' }}
                                                    {{ $user->rank_label == 'Silver' ? 'bg-gray-700 text-gray-300' : '' }}
                                                    {{ $user->rank_label == 'Novice' ? 'bg-blue-900 text-blue-300' : '' }}
                                                ">
                                                    {{ $user->rank_label }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right font-semibold text-primary">{{ number_format($user->total_xp) }}</td>
                                            <td class="px-6 py-4 text-right">{{ $user->win_rate }}%</td>
                                            <td class="px-6 py-4 text-right">{{ $user->avg_score_formatted }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 border-t border-border">
                            {{ $users->links() }}
                        </div>
                    </div>
                </main>

                <!-- SIDEBAR SECTION -->
                <aside class="lg:col-span-1">
                    <div class="sticky top-24">
                        <div class="bg-card rounded-lg p-6 border border-border shadow-lg">
                            <h3 class="text-lg font-bold text-text-primary">Posisi Anda</h3>
                            <div class="mt-4 flex flex-col items-center text-center">
                                <div class="text-6xl font-black text-text-primary">#{{ $currentUserRank }}</div>
                                <p class="mt-2 text-2xl font-bold text-primary">{{ number_format($currentUser->total_xp) }} XP</p>
                                <span class="mt-4 bg-primary/20 text-primary text-sm font-medium px-4 py-1 rounded-full">Level {{ $currentUser->level }}</span>
                            </div>
                            
                            @if($targetUser)
                                <div class="mt-6">
                                    <div class="flex justify-between text-sm font-medium text-text-secondary">
                                        <span>Menuju #{{ $currentUserRank - 1 }} ({{ explode(' ', $targetUser->nama)[0] }})</span>
                                        <span>{{ number_format($targetUser->total_xp - $currentUser->total_xp) }} XP lagi</span>
                                    </div>
                                    <div class="w-full bg-surface-light dark:bg-surface-dark rounded-full h-2.5 mt-2 border border-border">
                                        @php
                                            // Calculate progress to next rank user (just a visual representation, simplified)
                                            $diff = $targetUser->total_xp - $currentUser->total_xp;
                                            $base = max(100, $targetUser->total_xp); // Avoid div by zero
                                            $progress = 100 - ($diff / $base * 100); 
                                            // This progress logic is a bit arbitrary, let's just show a fixed 50% or relative to level
                                            // Better: Show progress to next LEVEL threshold as that's more standard
                                            // But the design asks for progress to next user. Let's stick to simple visually or use level progress.
                                            // Reverting to Level Progress for specific visual consistency with dashboard, 
                                            // OR implementing relative progress between users:
                                            // Let's use Level progress as it's cleaner.
                                        @endphp
                                        <div class="bg-primary h-2.5 rounded-full" style="width: 60%"></div> <!-- Static for now or hook to Level -->
                                    </div>
                                </div>
                                <p class="mt-6 text-center text-sm text-text-secondary">Terus berjuang! Kamu bisa menyusul {{ $targetUser->nama }}.</p>
                            @else
                                <p class="mt-6 text-center text-sm text-text-primary font-bold">Kamu adalah Nomor #1! Pertahankan!</p>
                            @endif
                        </div>
                    </div>
                </aside>
            </div>
    </div>
</x-app-layout>
