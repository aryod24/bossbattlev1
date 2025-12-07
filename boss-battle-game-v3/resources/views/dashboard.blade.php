<x-app-layout>
    @php
        // XP & Level Logic (Replicated from previous dashboard)
        $level = auth()->user()->level;
        $xp = auth()->user()->total_xp;
        $thresholds = \App\Services\XpService::LEVEL_THRESHOLDS;
        $nextLevel = $level + 1;
        $maxLevel = 5;
        
        if ($level >= $maxLevel) {
            $nextThreshold = $thresholds[$maxLevel];
            $prevThreshold = $thresholds[$maxLevel-1]; 
            $progress = 100;
            $nextThresholdText = "Max Level";
        } else {
            $nextThreshold = $thresholds[$nextLevel];
            $prevThreshold = $thresholds[$level];
            
            // Calculate progress relative to current level range
            $levelRange = $nextThreshold - $prevThreshold;
            $xpInLevel = $xp - $prevThreshold;
            $progress = min(100, max(0, ($xpInLevel / $levelRange) * 100));
            $nextThresholdText = "{$xp} / {$nextThreshold} XP";
        }

        // Rank Logic
        $rankName = match(true) {
            $level >= 5 => 'Champion',
            $level >= 3 => 'Gold',
            $level >= 2 => 'Silver',
            default => 'Novice'
        };
    @endphp

    <div class="flex h-full grow flex-col">
        <div class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-8">
                <h1 class="text-text-primary text-3xl font-bold tracking-tight">Selamat Datang, {{ auth()->user()->nama }}!</h1>
                
                <!-- Profile & Stats Card -->
                <div class="w-full rounded-xl bg-card shadow-lg p-6 @container border border-border">
                    <div class="flex flex-col gap-6 @[640px]:flex-row @[640px]:items-center @[640px]:justify-between">
                        <div class="flex items-center gap-6">
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-24 w-24 min-w-[96px] border-2 border-primary/20" 
                                 data-alt="User profile avatar" 
                                 style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}&background=random&size=128");'>
                            </div>
                            <div class="flex flex-col">
                                <p class="text-text-primary text-2xl font-bold leading-tight">{{ auth()->user()->nama }}</p>
                                <p class="text-text-secondary text-base font-normal mb-1">{{ auth()->user()->nim }}</p>
                                <p class="text-text-secondary text-sm font-normal bg-surface-light dark:bg-surface-dark px-2 py-1 rounded inline-block self-start border border-border">
                                    {{ auth()->user()->kelas ?? 'Belum ada kelas' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6 justify-between">
                            <div class="flex flex-col items-center gap-2">
                                <div class="relative flex h-20 w-20 items-center justify-center rounded-full bg-primary/10">
                                    <span class="material-symbols-outlined text-5xl text-primary">workspace_premium</span>
                                    <div class="absolute -bottom-2 rounded-full bg-primary px-3 py-0.5 text-xs font-bold text-black border border-yellow-400">LEVEL {{ $level }}</div>
                                </div>
                                <p class="text-sm font-medium text-text-primary">{{ $rankName }}</p>
                            </div>
                            <div class="flex flex-col gap-3 flex-1 min-w-[240px]">
                                <div class="flex justify-between items-baseline">
                                    <p class="text-text-primary text-base font-medium">XP Progress</p>
                                    <p class="text-text-primary text-sm font-normal">{{ number_format($progress, 0) }}%</p>
                                </div>
                                <div class="rounded-full bg-surface-dark h-3 border border-border overflow-hidden">
                                    <div class="h-3 rounded-full bg-gradient-to-r from-yellow-500 to-amber-500" style="width: {{ $progress }}%;"></div>
                                </div>
                                <p class="text-text-secondary text-sm font-normal text-right">{{ $nextThresholdText }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Action Cards Grid -->
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                    <!-- Solo Boss Battle Card -->
                    <div class="flex flex-col gap-4 rounded-xl p-8 text-white bg-gradient-to-br from-indigo-500 to-violet-600 transition-transform hover:-translate-y-1 hover:shadow-2xl shadow-lg relative overflow-hidden group">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all"></div>
                        <div class="flex items-center gap-4 relative z-10">
                            <span class="material-symbols-outlined text-5xl bg-white/20 p-3 rounded-lg backdrop-blur-sm">swords</span>
                            <p class="text-2xl font-bold">Solo Boss Battle</p>
                        </div>
                        <p class="text-base font-normal text-indigo-100 relative z-10">Tantang Boss & Kuasai Materi. Pilih Level Kesulitan dan raih XP maksimal!</p>
                        <a href="{{ route('solo.index') }}" class="mt-4 self-start rounded-lg bg-white/90 px-6 py-2.5 text-base font-bold text-indigo-700 shadow-lg hover:bg-white transition-colors">
                            Mulai Battle
                        </a>
                    </div>

                    <!-- Active Event Card -->
                    @if($activeEvent)
                        <div class="flex flex-col gap-4 rounded-xl p-8 text-black bg-gradient-to-br from-amber-400 to-yellow-500 transition-transform hover:-translate-y-1 hover:shadow-2xl shadow-lg relative overflow-hidden group">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/20 rounded-full blur-2xl group-hover:bg-white/30 transition-all"></div>
                            <div class="flex items-center gap-4 relative z-10">
                                <span class="material-symbols-outlined text-5xl bg-black/10 p-3 rounded-lg backdrop-blur-sm">emoji_events</span>
                                <div class="flex flex-col">
                                    <p class="text-2xl font-bold">Event Aktif</p>
                                    <span class="text-xs font-bold uppercase tracking-wider bg-black/20 text-black px-2 py-0.5 rounded w-fit">Live Now</span>
                                </div>
                            </div>
                            <p class="text-base font-medium text-yellow-900 relative z-10">{{ $activeEvent->nama_event }}</p>
                            <p class="text-sm text-yellow-900/80 relative z-10 line-clamp-2">Bergabunglah dalam event kompetitif ini dan buktikan kemampuanmu!</p>
                            <button disabled class="mt-4 self-start rounded-lg bg-white/90 px-6 py-2.5 text-base font-bold text-yellow-800 shadow-lg cursor-not-allowed opacity-80" title="Coming Soon">
                                Gabung Event (Segera)
                            </button>
                        </div>
                    @else
                        <div class="flex flex-col gap-4 rounded-xl p-8 bg-surface-light dark:bg-surface-dark border border-border transition-transform hover:-translate-y-1 hover:shadow-lg relative overflow-hidden">
                            <div class="flex items-center gap-4">
                                <span class="material-symbols-outlined text-5xl text-text-muted bg-surface-dark/5 dark:bg-white/5 p-3 rounded-lg">event_busy</span>
                                <p class="text-2xl font-bold text-text-primary">Tidak Ada Event</p>
                            </div>
                            <p class="text-base font-normal text-text-secondary">Saat ini belum ada event yang aktif. Fokus pada Solo Battle untuk meningkatkan levelmu!</p>
                            <div class="mt-4 self-start rounded-lg bg-surface-dark/10 dark:bg-white/10 px-6 py-2.5 text-base font-bold text-text-muted shadow-sm cursor-default">
                                Menunggu Event...
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Secondary Info Grid -->
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                    <!-- Latest Badge -->
                    <div class="flex flex-col bg-card rounded-xl border border-border shadow-sm">
                        <header class="px-6 py-4 border-b border-border flex justify-between items-center">
                            <h3 class="text-lg font-bold text-text-primary flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">military_tech</span>
                                Badge Terbaru
                            </h3>
                            <a href="#" class="text-sm text-primary hover:underline">Lihat Semua</a>
                        </header>
                        
                        <div class="p-6 flex items-center gap-6">
                            @if($latestBadge)
                                <div class="text-6xl animate-bounce-slow">
                                    {{ $latestBadge->badge->emoji ?? '🏅' }}
                                </div>
                                <div class="flex flex-col">
                                    <h4 class="text-xl font-bold text-text-primary">{{ $latestBadge->badge->name }}</h4>
                                    <p class="text-text-secondary text-sm">{{ $latestBadge->badge->description }}</p>
                                    <p class="text-xs text-text-muted mt-2">Didapatkan pada {{ $latestBadge->created_at->format('d M Y') }}</p>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center w-full py-4 text-center">
                                    <span class="material-symbols-outlined text-4xl text-text-muted mb-2">lock</span>
                                    <p class="text-text-secondary font-medium">Belum ada badge yang didapatkan.</p>
                                    <p class="text-text-muted text-sm">Mainkan game untuk membuka achievement!</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Last Raid Session -->
                    <div class="flex flex-col bg-card rounded-xl border border-border shadow-sm">
                        <header class="px-6 py-4 border-b border-border flex justify-between items-center">
                            <h3 class="text-lg font-bold text-text-primary flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-500">history</span>
                                Aktivitas Terakhir
                            </h3>
                        </header>
                        
                        <div class="p-6">
                            @if($lastRaidSession)
                                <div class="flex items-center gap-4">
                                    <div class="bg-surface-light dark:bg-surface-dark p-3 rounded-lg border border-border text-center min-w-[80px]">
                                        <div class="text-xs text-text-muted uppercase">Level</div>
                                        <div class="text-lg font-bold {{ $lastRaidSession->level == 'Hard' ? 'text-red-500' : ($lastRaidSession->level == 'Medium' ? 'text-yellow-500' : 'text-green-500') }}">
                                            {{ $lastRaidSession->level }}
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-base font-bold text-text-primary mb-1">{{ $lastRaidSession->soloRaid->nama }}</h4>
                                        <div class="flex items-center gap-3 text-xs text-text-secondary">
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                                {{ $lastRaidSession->created_at->diffForHumans() }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                                {{ $lastRaidSession->jumlah_benar }} Benar
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('solo.result', $lastRaidSession->id) }}" class="p-2 hover:bg-surface-light dark:hover:bg-surface-dark rounded-full transition-colors" title="Lihat Hasil">
                                        <span class="material-symbols-outlined text-text-primary">chevron_right</span>
                                    </a>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center w-full py-4 text-center">
                                    <span class="material-symbols-outlined text-4xl text-text-muted mb-2">sports_esports</span>
                                    <p class="text-text-secondary font-medium">Belum ada riwayat permainan.</p>
                                    <a href="{{ route('solo.index') }}" class="text-primary text-sm font-bold hover:underline mt-1">Mulai Bermain Sekarang!</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
