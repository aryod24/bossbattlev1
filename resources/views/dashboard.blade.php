<x-app-layout>
    @php
        // XP & Level Logic
        $level = auth()->user()->level;
        $xp = auth()->user()->total_xp;
        $thresholds = \App\Services\XpService::LEVEL_THRESHOLDS;
        $nextLevel = $level + 1;
        $maxLevel = 5;

        if ($level >= $maxLevel) {
            $nextThreshold = $thresholds[$maxLevel];
            $prevThreshold = $thresholds[$maxLevel - 1];
            $progress = 100;
            $nextThresholdText = "Max Level";
        } else {
            $nextThreshold = $thresholds[$nextLevel];
            $prevThreshold = $thresholds[$level];

            $levelRange = $nextThreshold - $prevThreshold;
            $xpInLevel = $xp - $prevThreshold;
            $progress = min(100, max(0, ($xpInLevel / $levelRange) * 100));
            $nextThresholdText = "{$xp} / {$nextThreshold} XP";
        }

        $rankName = auth()->user()->rank_label;
    @endphp

    <div class="flex flex-col gap-6">
        {{-- Top Section: Welcome & Stats --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Welcome Card --}}
            <div class="glass-card rounded-xl p-8 lg:col-span-2 flex flex-col justify-center">
                <h1 class="font-headline text-3xl md:text-[32px] font-bold text-cyan-glow mb-2 leading-tight">
                    Selamat datang kembali, {{ explode(' ', trim(auth()->user()->nama))[0] }}!
                </h1>
                <p class="font-body text-base md:text-lg text-soft mb-8">
                    Siap menaklukkan kode? Lihat event terbaru dan tingkatkan peringkatmu.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('solo.index') }}" class="btn-cyber-primary px-6 py-3 rounded-lg font-bold inline-flex items-center justify-center font-headline">
                        Jelajahi Event
                    </a>
                    <a href="{{ route('leaderboard.index') }}" class="btn-cyber-secondary px-6 py-3 rounded-lg font-bold inline-flex items-center justify-center font-headline">
                        Lihat Leaderboard
                    </a>
                </div>
            </div>

            {{-- Stats Card --}}
            <div class="glass-card rounded-xl p-8">
                <h2 class="font-headline text-2xl font-semibold mb-8 text-on-surface" style="color: #e5e2e3;">Statistik Kamu</h2>

                <div class="mb-8">
                    <div class="flex justify-between items-end mb-2">
                        <span class="font-body text-base text-soft">Level</span>
                        <span class="font-headline text-3xl font-bold text-cyan-glow leading-none">{{ $level }}</span>
                    </div>

                    <div class="flex justify-between items-center mb-2">
                        <span class="font-mono-label text-xs uppercase tracking-wider font-medium text-soft">
                            Progress ke Lvl {{ $level < $maxLevel ? $nextLevel : 'Max' }}
                        </span>
                        <span class="font-mono-label text-xs uppercase tracking-wider font-medium text-soft">
                            {{ $nextThresholdText }}
                        </span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="w-full h-3 rounded-full overflow-hidden relative" style="background-color: #353436;">
                        <div class="h-full progress-bar-fill rounded-full relative transition-all duration-500" style="width: {{ $progress }}%;">
                            @if($progress > 0 && $progress < 100)
                                <div class="absolute right-0 top-0 bottom-0 w-2 bg-white rounded-full progress-glow-tip"></div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 divider-soft">
                    <span class="font-body text-base text-soft">Peringkat</span>
                    <span class="font-mono-label bg-cyan-soft text-cyan-glow font-medium text-xs uppercase tracking-wider px-3 py-1 rounded-full border border-cyan-soft"
                          style="{{ $rankName == 'Master' ? 'background-color: rgba(255,99,99,0.15); color:#ffb4ab; border-color: rgba(255,99,99,0.3);' : '' }}
                                {{ $rankName == 'Advanced' ? 'background-color: rgba(206,93,255,0.15); color:#ebb2ff; border-color: rgba(206,93,255,0.3);' : '' }}
                                {{ $rankName == 'Gold' ? 'background-color: rgba(250,204,21,0.15); color:#fde68a; border-color: rgba(250,204,21,0.3);' : '' }}
                                {{ $rankName == 'Silver' ? 'background-color: rgba(148,163,184,0.15); color:#cbd5e1; border-color: rgba(148,163,184,0.3);' : '' }}">
                        {{ $rankName }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Middle Section: Main Activities (Split Left/Right) --}}
        <div class="grid grid-cols-2 gap-6">
            {{-- Left: Learning Mode --}}
            <div class="rounded-xl p-8 relative overflow-hidden group"
                 style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.6), rgba(79, 70, 229, 0.5), rgba(67, 56, 202, 0.4));
                        border: 1px solid rgba(99, 102, 241, 0.4);
                        box-shadow: 0 0 25px rgba(99, 102, 241, 0.2);">
                <div class="absolute top-0 right-0 p-8 opacity-10 transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform duration-500 pointer-events-none">
                    <span class="material-symbols-outlined" style="font-size: 128px;">school</span>
                </div>
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-xl flex items-center justify-center backdrop-blur-md mb-4 border"
                         style="background-color: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.3);">
                        <span class="material-symbols-outlined text-white" style="font-size: 36px;">school</span>
                    </div>
                    <h2 class="font-headline text-3xl md:text-[40px] font-extrabold text-white mb-2 leading-tight drop-shadow-md">
                        Learning Mode
                    </h2>
                    <p class="font-body text-base md:text-lg mb-8 max-w-md" style="color: rgba(255,255,255,0.95);">
                        Pelajari materi programming dan asah kemampuan dengan latihan soal terstruktur.
                    </p>
                    <a href="{{ route('solo.index', ['type' => 'learning']) }}" class="font-headline inline-flex items-center bg-white px-8 py-3 rounded-lg font-bold transition-all duration-300 hover:bg-gray-100 hover:scale-105" style="color: #4f46e5;">
                        Mulai Belajar
                    </a>
                </div>
            </div>

            {{-- Right: Solo Boss Battle --}}
            <div class="rounded-xl p-8 relative overflow-hidden group"
                 style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.8), rgba(37, 99, 235, 0.7), rgba(6, 182, 212, 0.6));
                        border: 1px solid rgba(59, 130, 246, 0.5);
                        box-shadow: 0 0 30px rgba(6, 182, 212, 0.3);">
                <div class="absolute top-0 right-0 p-8 opacity-10 transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform duration-500 pointer-events-none">
                    <span class="material-symbols-outlined" style="font-size: 128px;">swords</span>
                </div>
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-xl flex items-center justify-center backdrop-blur-md mb-4 border"
                         style="background-color: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.3);">
                        <span class="material-symbols-outlined text-white" style="font-size: 36px;">swords</span>
                    </div>
                    <h2 class="font-headline text-3xl md:text-[40px] font-extrabold text-white mb-2 leading-tight drop-shadow-md">
                        Boss Battle
                    </h2>
                    <p class="font-body text-base md:text-lg mb-8 max-w-md" style="color: rgba(255,255,255,0.95);">
                        Tantang Boss & Kuasai Materi. Pilih Level Kesulitan dan raih XP maksimal!
                    </p>
                    <a href="{{ route('solo.index', ['type' => 'boss']) }}" class="font-headline inline-flex items-center bg-white px-8 py-3 rounded-lg font-bold transition-all duration-300 hover:bg-gray-100 hover:scale-105" style="color: #1d4ed8;">
                        Mulai Battle
                    </a>
                </div>
            </div>
        </div>

        {{-- Bottom Section: Badges & Activity (Split Left/Right) --}}
        <div class="grid grid-cols-2 gap-6">
            {{-- Left: Recent Badges --}}
            <div class="glass-card rounded-xl p-8 flex flex-col">
                <div class="flex justify-between items-center mb-8 pb-2" style="border-bottom: 1px solid rgba(58, 73, 75, 0.5);">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-cyan-glow">military_tech</span>
                        <h3 class="font-headline text-xl font-semibold" style="color: #e5e2e3;">Badge Terbaru</h3>
                    </div>
                    <a class="font-headline text-cyan-glow text-sm font-semibold transition-colors hover:opacity-80" href="{{ route('profile.edit') }}">
                        Lihat Semua
                    </a>
                </div>

                @if($latestBadge)
                    <div class="flex items-center gap-4 p-2 rounded-lg transition-colors">
                        <div class="w-16 h-16 rounded-lg flex items-center justify-center text-5xl flex-shrink-0"
                             style="background: linear-gradient(135deg, rgba(0,242,255,0.15), rgba(206,93,255,0.15)); border: 1px solid rgba(0,242,255,0.3);">
                            {{ $latestBadge->badge->emoji ?? '🏅' }}
                        </div>
                        <div>
                            <h4 class="font-headline text-base font-semibold mb-1" style="color: #e5e2e3;">{{ $latestBadge->badge->name }}</h4>
                            <p class="font-body text-sm mb-1 text-soft">{{ $latestBadge->badge->description }}</p>
                            <p class="font-mono-label text-xs uppercase tracking-wider text-faint">
                                Didapatkan pada {{ $latestBadge->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center w-full py-4 text-center">
                        <span class="material-symbols-outlined text-4xl mb-2 text-faint">lock</span>
                        <p class="font-body font-medium text-soft">Belum ada badge yang didapatkan.</p>
                        <p class="font-body text-sm text-faint">Mainkan game untuk membuka achievement!</p>
                    </div>
                @endif
            </div>

            {{-- Right: Recent Activity --}}
            <div class="glass-card rounded-xl p-8 flex flex-col">
                <div class="flex justify-between items-center mb-8 pb-2" style="border-bottom: 1px solid rgba(58, 73, 75, 0.5);">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined" style="color: #ebb2ff;">history</span>
                        <h3 class="font-headline text-xl font-semibold" style="color: #e5e2e3;">Aktivitas Terakhir</h3>
                    </div>
                </div>

                @if($lastRaidSession)
                    @php
                        $resultRoute = $lastRaidSession->is_pretest
                            ? route('pretest.result', $lastRaidSession->id)
                            : route('solo.result', $lastRaidSession->id);

                        $levelColor = match($lastRaidSession->level) {
                            'Hard' => '#ffb4ab',
                            'Medium' => '#fde68a',
                            default => '#86efac',
                        };

                        if ($lastRaidSession->soloRaid) {
                            $activityTitle = '' . $lastRaidSession->soloRaid->nama;
                        } elseif ($lastRaidSession->is_pretest) {
                            $activityTitle = 'Pre-test';
                        } else {
                            $activityTitle = 'Sesi Permainan';
                        }
                    @endphp

                    <a href="{{ $resultRoute }}" class="flex items-center justify-between p-4 rounded-lg transition-colors group cursor-pointer"
                       style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col items-center justify-center p-2 rounded min-w-[60px]"
                                 style="background-color: #131314; border: 1px solid rgba(58, 73, 75, 0.5);">
                                <span class="font-mono-label text-xs uppercase tracking-wider mb-1 text-faint">Level</span>
                                <span class="font-headline font-bold text-sm" style="color: {{ $levelColor }};">{{ $lastRaidSession->level }}</span>
                            </div>
                            <div>
                                <h4 class="font-headline text-base font-semibold mb-1 transition-colors group-hover:text-cyan-glow" style="color: #e5e2e3;">
                                    {{ $activityTitle }}
                                </h4>
                                <div class="flex flex-wrap gap-4">
                                    <span class="font-mono-label text-xs uppercase tracking-wider flex items-center gap-1 text-soft">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">calendar_today</span>
                                        {{ $lastRaidSession->created_at->diffForHumans() }}
                                    </span>
                                    <span class="font-mono-label text-xs uppercase tracking-wider flex items-center gap-1 text-soft">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">check_circle</span>
                                        {{ $lastRaidSession->jumlah_benar }} Benar
                                    </span>
                                </div>
                            </div>
                        </div>
                        <span class="material-symbols-outlined transition-all transform group-hover:translate-x-1 group-hover:text-cyan-glow text-soft">chevron_right</span>
                    </a>
                @else
                    <div class="flex flex-col items-center justify-center w-full py-4 text-center">
                        <span class="material-symbols-outlined text-4xl mb-2 text-faint">sports_esports</span>
                        <p class="font-body font-medium text-soft">Belum ada riwayat permainan.</p>
                        <a href="{{ route('solo.index') }}" class="font-headline text-cyan-glow text-sm font-bold hover:underline mt-1">
                            Mulai Bermain Sekarang!
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
