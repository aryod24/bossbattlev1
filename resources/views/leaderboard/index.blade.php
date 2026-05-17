<x-app-layout>
    <div class="flex flex-col gap-6">
        {{-- Header --}}
        <div class="glass-card rounded-xl p-8">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex flex-col gap-2">
                    <h1 class="font-headline text-3xl md:text-[40px] font-extrabold leading-tight tracking-tight text-cyan-glow">
                        Leaderboard Global
                    </h1>
                    <p class="font-body text-base md:text-lg text-soft">
                        Lihat posisi Anda di antara semua mahasiswa.
                    </p>
                </div>
            </div>
        </div>

        {{-- Top 3 Podium --}}
        @if($topUsers->count() > 0)
            <section class="relative flex items-end justify-center gap-4 px-4 h-96">
                {{-- Rank 2 --}}
                @if($topUsers->count() >= 2)
                    @php $user2 = $topUsers[1]; @endphp
                    <div class="relative flex w-1/4 flex-col items-center">
                        <span class="material-symbols-outlined text-4xl text-faint mb-1" style="filter: drop-shadow(0 0 10px rgba(192,192,192,0.6));">workspace_premium</span>
                        <div class="h-28 w-28 rounded-full bg-cover bg-center drop-shadow-lg ring-4 ring-offset-0"
                             style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode($user2->nama) }}&background=C0C0C0&color=fff&size=128'); --tw-ring-color: #C0C0C0;">
                        </div>
                        <div class="flex h-48 w-full flex-col items-center justify-start rounded-t-xl pt-6 mt-2"
                             style="background: linear-gradient(180deg, rgba(192,192,192,0.4), rgba(192,192,192,0.1)); border: 1px solid rgba(192,192,192,0.3); border-bottom: 0;">
                            <span class="font-headline text-4xl font-extrabold text-white drop-shadow-md">#2</span>
                            <p class="font-headline mt-2 text-lg font-bold text-white text-center px-1 truncate w-full">{{ $user2->nama }}</p>
                            <p class="font-mono-label text-sm font-medium text-soft uppercase tracking-wider mt-1">{{ number_format($user2->total_xp) }} XP</p>
                        </div>
                    </div>
                @endif

                {{-- Rank 1 --}}
                @if($topUsers->count() >= 1)
                    @php $user1 = $topUsers[0]; @endphp
                    <div class="relative flex w-1/3 flex-col items-center">
                        <span class="material-symbols-outlined text-5xl absolute -top-12 z-10 drop-shadow-xl"
                              style="color: #FFD700; filter: drop-shadow(0 0 15px #FFD700);">emoji_events</span>
                        <div class="h-36 w-36 rounded-full bg-cover bg-center drop-shadow-xl ring-4"
                             style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode($user1->nama) }}&background=FFD700&color=fff&size=128'); --tw-ring-color: #FFD700;">
                        </div>
                        <div class="flex h-64 w-full flex-col items-center justify-start rounded-t-xl pt-8 mt-2 relative overflow-hidden"
                             style="background: linear-gradient(180deg, rgba(255,215,0,0.5), rgba(255,215,0,0.1)); border: 1px solid rgba(255,215,0,0.5); border-bottom: 0; box-shadow: 0 0 30px rgba(255,215,0,0.2);">
                            <span class="font-headline text-6xl font-extrabold text-white drop-shadow-lg">#1</span>
                            <p class="font-headline mt-2 text-xl font-bold text-white text-center px-1 truncate w-full">{{ $user1->nama }}</p>
                            <p class="font-mono-label text-base font-medium uppercase tracking-wider mt-1" style="color: #fde68a;">{{ number_format($user1->total_xp) }} XP</p>
                        </div>
                    </div>
                @endif

                {{-- Rank 3 --}}
                @if($topUsers->count() >= 3)
                    @php $user3 = $topUsers[2]; @endphp
                    <div class="relative flex w-1/4 flex-col items-center">
                        <span class="material-symbols-outlined text-4xl mb-1" style="color: #CD7F32; filter: drop-shadow(0 0 10px #CD7F32);">workspace_premium</span>
                        <div class="h-28 w-28 rounded-full bg-cover bg-center drop-shadow-lg ring-4"
                             style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode($user3->nama) }}&background=CD7F32&color=fff&size=128'); --tw-ring-color: #CD7F32;">
                        </div>
                        <div class="flex h-40 w-full flex-col items-center justify-start rounded-t-xl pt-6 mt-2"
                             style="background: linear-gradient(180deg, rgba(205,127,50,0.4), rgba(205,127,50,0.1)); border: 1px solid rgba(205,127,50,0.3); border-bottom: 0;">
                            <span class="font-headline text-4xl font-extrabold text-white drop-shadow-md">#3</span>
                            <p class="font-headline mt-2 text-lg font-bold text-white text-center px-1 truncate w-full">{{ $user3->nama }}</p>
                            <p class="font-mono-label text-sm font-medium uppercase tracking-wider mt-1" style="color: #fed7aa;">{{ number_format($user3->total_xp) }} XP</p>
                        </div>
                    </div>
                @endif
            </section>
        @endif

        {{-- Table & Sidebar --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- TABLE SECTION --}}
            <main class="lg:col-span-2">
                <div class="glass-card rounded-xl overflow-hidden">
                    <div class="p-6 pb-4 divider-soft" style="border-bottom: 1px solid rgba(58, 73, 75, 0.5);">
                        <h2 class="font-headline text-xl font-semibold flex items-center gap-2" style="color: #e5e2e3;">
                            <span class="material-symbols-outlined text-cyan-glow">leaderboard</span>
                            Full Leaderboard
                        </h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr style="background-color: rgba(32, 31, 32, 0.6);">
                                    <th class="font-mono-label px-6 py-3 text-center text-xs uppercase tracking-wider text-soft" scope="col">Rank</th>
                                    <th class="font-mono-label px-6 py-3 text-xs uppercase tracking-wider text-soft" scope="col">Player</th>
                                    <th class="font-mono-label px-6 py-3 text-xs uppercase tracking-wider text-soft" scope="col">Level</th>
                                    <th class="font-mono-label px-6 py-3 text-right text-xs uppercase tracking-wider text-soft" scope="col">Total XP</th>
                                    <th class="font-mono-label px-6 py-3 text-right text-xs uppercase tracking-wider text-soft" scope="col">Win Rate</th>
                                    <th class="font-mono-label px-6 py-3 text-right text-xs uppercase tracking-wider text-soft" scope="col">Avg Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="transition-colors hover:bg-cyan-soft"
                                        style="border-bottom: 1px solid rgba(58, 73, 75, 0.3);
                                            {{ $user->id === auth()->id() ? 'background: linear-gradient(90deg, rgba(0,242,255,0.1), transparent); border-left: 3px solid #00f2ff;' : '' }}">
                                        <td class="px-6 py-4 text-center font-headline font-bold" style="color: #e5e2e3;">
                                            @if($user->rank == 1)
                                                <span class="material-symbols-outlined !text-2xl align-middle" style="color: #FFD700; filter: drop-shadow(0 0 6px #FFD700);">emoji_events</span>
                                            @elseif($user->rank == 2)
                                                <span class="material-symbols-outlined !text-2xl align-middle" style="color: #C0C0C0;">emoji_events</span>
                                            @elseif($user->rank == 3)
                                                <span class="material-symbols-outlined !text-2xl align-middle" style="color: #CD7F32;">emoji_events</span>
                                            @else
                                                {{ $user->rank }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-body whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <img class="h-8 w-8 rounded-full ring-1 ring-cyan-soft"
                                                     src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=random"
                                                     alt="{{ $user->nama }}"/>
                                                <span class="font-medium" style="color: #e5e2e3;">
                                                    {{ $user->nama }}
                                                    @if($user->id === auth()->id())
                                                        <span class="font-mono-label text-xs text-cyan-glow ml-1">(Anda)</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-mono-label text-xs font-medium px-2.5 py-0.5 rounded-full uppercase tracking-wider"
                                                style="border: 1px solid;
                                                    {{ $user->rank_label == 'Master' ? 'background-color: rgba(255,99,99,0.15); color:#ffb4ab; border-color: rgba(255,99,99,0.3);' : '' }}
                                                    {{ $user->rank_label == 'Advanced' ? 'background-color: rgba(206,93,255,0.15); color:#ebb2ff; border-color: rgba(206,93,255,0.3);' : '' }}
                                                    {{ $user->rank_label == 'Gold' ? 'background-color: rgba(250,204,21,0.15); color:#fde68a; border-color: rgba(250,204,21,0.3);' : '' }}
                                                    {{ $user->rank_label == 'Silver' ? 'background-color: rgba(148,163,184,0.15); color:#cbd5e1; border-color: rgba(148,163,184,0.3);' : '' }}
                                                    {{ $user->rank_label == 'Novice' ? 'background-color: rgba(0,242,255,0.15); color:#00f2ff; border-color: rgba(0,242,255,0.3);' : '' }}">
                                                {{ $user->rank_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-headline font-semibold text-cyan-glow">{{ number_format($user->total_xp) }}</td>
                                        <td class="px-6 py-4 text-right font-mono-label text-soft">{{ $user->win_rate }}%</td>
                                        <td class="px-6 py-4 text-right font-mono-label text-soft">{{ $user->avg_score_formatted }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4" style="border-top: 1px solid rgba(58, 73, 75, 0.5);">
                        {{ $users->links() }}
                    </div>
                </div>
            </main>

            {{-- SIDEBAR SECTION --}}
            <aside class="lg:col-span-1">
                <div class="sticky top-24">
                    <div class="glass-card rounded-xl p-8">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="material-symbols-outlined text-cyan-glow">person_pin</span>
                            <h3 class="font-headline text-xl font-semibold" style="color: #e5e2e3;">Posisi Anda</h3>
                        </div>

                        <div class="flex flex-col items-center text-center">
                            <div class="font-headline text-6xl font-extrabold text-cyan-glow drop-shadow-md">#{{ $currentUserRank }}</div>
                            <p class="font-headline mt-2 text-2xl font-bold text-magenta-glow">{{ number_format($currentUser->total_xp) }} XP</p>
                            <span class="font-mono-label mt-4 bg-cyan-soft text-cyan-glow text-xs font-medium px-4 py-1 rounded-full border border-cyan-soft uppercase tracking-wider">
                                Level {{ $currentUser->level }}
                            </span>
                        </div>

                        @if($targetUser)
                            <div class="mt-8">
                                <div class="flex justify-between font-mono-label text-xs uppercase tracking-wider text-soft mb-2">
                                    <span>Menuju #{{ $currentUserRank - 1 }} ({{ explode(' ', $targetUser->nama)[0] }})</span>
                                    <span>{{ number_format($targetUser->total_xp - $currentUser->total_xp) }} XP lagi</span>
                                </div>
                                <div class="w-full h-3 rounded-full overflow-hidden relative" style="background-color: #353436;">
                                    @php
                                        $diff = max(1, $targetUser->total_xp - $currentUser->total_xp);
                                        $base = max(100, $targetUser->total_xp);
                                        $progress = max(5, min(95, 100 - ($diff / $base * 100)));
                                    @endphp
                                    <div class="h-full progress-bar-fill rounded-full transition-all duration-500" style="width: {{ $progress }}%;">
                                        <div class="absolute right-0 top-0 bottom-0 w-2 bg-white rounded-full progress-glow-tip" style="left: calc({{ $progress }}% - 8px);"></div>
                                    </div>
                                </div>
                            </div>
                            <p class="font-body mt-6 text-center text-sm text-soft">Terus berjuang! Kamu bisa menyusul {{ $targetUser->nama }}.</p>
                        @else
                            <p class="font-headline mt-6 text-center text-sm font-bold text-cyan-glow">Kamu adalah Nomor #1! Pertahankan!</p>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
