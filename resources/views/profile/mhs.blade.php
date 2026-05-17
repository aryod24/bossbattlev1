<x-app-layout>
    @php
        $level = $user->level ?? 0;
        $xp = $user->total_xp ?? 0;
        $thresholds = \App\Services\XpService::LEVEL_THRESHOLDS;
        $nextLevel = $level + 1;
        $maxLevel = 5;

        if ($level >= $maxLevel) {
            $nextThreshold = $thresholds[$maxLevel];
            $prevThreshold = $thresholds[$maxLevel - 1];
            $progress = 100;
            $nextThresholdText = "Level Maksimal";
        } else {
            $nextThreshold = $thresholds[$nextLevel];
            $prevThreshold = $thresholds[$level];
            $levelRange = $nextThreshold - $prevThreshold;
            $xpInLevel = $xp - $prevThreshold;
            $progress = min(100, max(0, ($xpInLevel / $levelRange) * 100));
            $nextThresholdText = "{$xp} / {$nextThreshold} XP";
        }

        $rankName = $user->rank_label;
    @endphp

    <div class="flex flex-col gap-6">
        {{-- Hero Header: User identity + collection summary --}}
        <div class="glass-card rounded-xl p-8 relative overflow-hidden">
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="size-16 rounded-full bg-cover bg-center ring-2 ring-cyan-soft"
                         style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=random&size=128");'></div>
                    <div>
                        <h1 class="font-headline text-3xl md:text-[32px] font-extrabold text-cyan-glow leading-tight flex items-center gap-3">
                            <span class="material-symbols-outlined text-magenta-glow" style="font-size: 44px;">military_tech</span>
                            Koleksi Badge
                        </h1>
                        <p class="font-body text-soft mt-1">Pencapaian {{ explode(' ', trim($user->nama))[0] }} di arena Boss Battle.</p>
                    </div>
                </div>
                <div class="text-center px-6 py-3 rounded-xl"
                     style="background-color: rgba(0, 242, 255, 0.08); border: 1px solid rgba(0, 242, 255, 0.3);">
                    <div class="flex items-baseline gap-1 justify-center">
                        <span class="font-headline text-4xl font-extrabold text-cyan-glow">{{ count($unlockedBadges) }}</span>
                        <span class="font-headline text-xl text-soft">/ {{ count($allBadges) }}</span>
                    </div>
                    <div class="font-mono-label text-xs font-medium uppercase tracking-wider text-soft mt-1">Terbuka</div>
                </div>
            </div>
        </div>

        {{-- Badge Collection Grid --}}
        <div class="glass-card rounded-xl p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($allBadges as $badge)
                    @php
                        $isUnlocked = isset($unlockedBadges[$badge->id]);
                        $unlockDate = $isUnlocked ? $unlockedBadges[$badge->id]->unlock_date->format('d M Y') : null;
                    @endphp

                    <div class="group relative p-6 rounded-xl flex flex-col items-center text-center transition-all duration-300 hover:-translate-y-1"
                         @if($isUnlocked)
                            style="background: linear-gradient(135deg, rgba(0,242,255,0.08), rgba(206,93,255,0.05)); border: 1px solid rgba(0,242,255,0.3);"
                            onmouseover="this.style.borderColor='rgba(0,242,255,0.6)'; this.style.boxShadow='0 0 20px rgba(0,242,255,0.2)';"
                            onmouseout="this.style.borderColor='rgba(0,242,255,0.3)'; this.style.boxShadow='none';"
                         @else
                            style="background: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3); opacity: 0.6; filter: grayscale(1);"
                         @endif>

                        <div class="text-6xl mb-4 transform transition-transform group-hover:scale-110 {{ $isUnlocked ? 'drop-shadow-[0_0_15px_rgba(0,242,255,0.4)]' : '' }}">
                            {{ $badge->emoji }}
                        </div>

                        <h4 class="font-headline text-base font-semibold mb-2 leading-tight" style="color: {{ $isUnlocked ? '#e5e2e3' : '#849495' }};">
                            {{ $badge->name }}
                        </h4>

                        <p class="font-body text-xs text-soft mb-4 flex-grow leading-relaxed px-2">
                            {{ $badge->description }}
                        </p>

                        <div class="w-full pt-3" style="border-top: 1px solid rgba(58, 73, 75, 0.3);">
                            @if($isUnlocked)
                                <div class="font-mono-label inline-flex items-center px-2 py-1 rounded-md text-[10px] font-medium uppercase tracking-wider"
                                     style="background-color: rgba(34, 197, 94, 0.1); color: #86efac; border: 1px solid rgba(34, 197, 94, 0.25);">
                                    <span class="mr-1">✓</span> {{ $unlockDate }}
                                </div>
                            @else
                                <div class="font-mono-label inline-flex items-center px-2 py-1 rounded-md text-[10px] font-medium uppercase tracking-wider"
                                     style="background-color: #131314; color: #849495; border: 1px solid rgba(58, 73, 75, 0.4);">
                                    <span class="material-symbols-outlined text-[10px] mr-1">lock</span> Terkunci
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Profile Information & Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Left Card: Profile Information --}}
            <div class="glass-card rounded-xl p-8 flex flex-col">
                <header class="flex items-center gap-2 mb-6 pb-4" style="border-bottom: 1px solid rgba(58, 73, 75, 0.5);">
                    <span class="material-symbols-outlined text-cyan-glow">person</span>
                    <div>
                        <h3 class="font-headline text-xl font-semibold" style="color: #e5e2e3;">Informasi Profil</h3>
                        <p class="font-body mt-0.5 text-sm text-soft">{{ __('Perbarui informasi profil Anda. Email tidak dapat diubah.') }}</p>
                    </div>
                </header>

                <div>
                    <x-profile-information :user="$user" />
                </div>

                {{-- Akademik Info --}}
                @php
                    $section = $user->current_section ?? null;
                    $pretestScore = $user->pretest_score;
                    $sectionMeta = match($section) {
                        'Hard' => ['label' => 'Hard', 'color' => '#ffb4ab', 'bg' => 'rgba(255,99,99,0.15)', 'border' => 'rgba(255,99,99,0.3)'],
                        'Medium' => ['label' => 'Medium', 'color' => '#fde68a', 'bg' => 'rgba(250,204,21,0.15)', 'border' => 'rgba(250,204,21,0.3)'],
                        'Easy' => ['label' => 'Easy', 'color' => '#86efac', 'bg' => 'rgba(34,197,94,0.15)', 'border' => 'rgba(34,197,94,0.3)'],
                        default => ['label' => 'Belum Pre-test', 'color' => '#849495', 'bg' => 'rgba(132,148,149,0.15)', 'border' => 'rgba(132,148,149,0.3)'],
                    };
                @endphp

                <div class="grid grid-cols-2 gap-4 pt-4 mt-4" style="border-top: 1px solid rgba(58, 73, 75, 0.5);">
                    <div class="flex flex-col gap-1 p-3 rounded-lg" style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                        <span class="font-mono-label text-[10px] uppercase tracking-wider text-soft flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size: 12px;">school</span>
                            Kelas
                        </span>
                        <span class="font-headline text-lg font-bold text-cyan-glow">{{ $user->kelas ?? '-' }}</span>
                        <span class="font-mono-label text-[10px] text-faint truncate">NIM: {{ $user->nim ?? '—' }}</span>
                    </div>
                    <div class="flex flex-col gap-1 p-3 rounded-lg" style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                        <span class="font-mono-label text-[10px] uppercase tracking-wider text-soft flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size: 12px;">tune</span>
                            Adaptive Level
                        </span>
                        <span class="font-headline text-lg font-bold" style="color: {{ $sectionMeta['color'] }};">
                            {{ $sectionMeta['label'] }}
                        </span>
                        <span class="font-mono-label text-[10px] text-faint">
                            @if($pretestScore !== null)
                                Pre-test: {{ $pretestScore }}/100
                            @else
                                Selesaikan Pre-test
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- Right Card: Your Stats --}}
            <div class="glass-card rounded-xl p-8">
                <header class="flex items-center gap-2 mb-6 pb-4" style="border-bottom: 1px solid rgba(58, 73, 75, 0.5);">
                    <span class="material-symbols-outlined text-cyan-glow">bar_chart</span>
                    <div>
                        <h3 class="font-headline text-xl font-semibold" style="color: #e5e2e3;">Statistik Anda</h3>
                        <p class="font-body mt-0.5 text-sm text-soft">Kemajuan dan peringkat berdasarkan XP.</p>
                    </div>
                </header>

                <div class="space-y-6">
                    <div class="flex justify-between items-end">
                        <span class="font-body text-base text-soft">Level</span>
                        <span class="font-headline text-3xl font-bold text-cyan-glow leading-none">{{ $level }}</span>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-mono-label text-xs uppercase tracking-wider font-medium text-soft">
                                Kemajuan ke Lvl {{ $level < 5 ? $level + 1 : 'Maks' }}
                            </span>
                            <span class="font-mono-label text-xs uppercase tracking-wider font-medium text-soft">
                                {{ $nextThresholdText }}
                            </span>
                        </div>
                        <div class="w-full h-3 rounded-full overflow-hidden relative" style="background-color: #353436;">
                            <div class="h-full progress-bar-fill rounded-full relative transition-all duration-500" style="width: {{ $progress }}%;">
                                @if($progress > 0 && $progress < 100)
                                    <div class="absolute right-0 top-0 bottom-0 w-2 bg-white rounded-full progress-glow-tip"></div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4" style="border-top: 1px solid rgba(58, 73, 75, 0.5);">
                        <span class="font-body text-base text-soft">Peringkat</span>
                        <span class="font-mono-label text-xs uppercase tracking-wider font-medium px-3 py-1 rounded-full"
                              style="border: 1px solid;
                                    {{ $rankName == 'Master' ? 'background-color: rgba(255,99,99,0.15); color:#ffb4ab; border-color: rgba(255,99,99,0.3);' : '' }}
                                    {{ $rankName == 'Advanced' ? 'background-color: rgba(206,93,255,0.15); color:#ebb2ff; border-color: rgba(206,93,255,0.3);' : '' }}
                                    {{ $rankName == 'Gold' ? 'background-color: rgba(250,204,21,0.15); color:#fde68a; border-color: rgba(250,204,21,0.3);' : '' }}
                                    {{ $rankName == 'Silver' ? 'background-color: rgba(148,163,184,0.15); color:#cbd5e1; border-color: rgba(148,163,184,0.3);' : '' }}
                                    {{ $rankName == 'Novice' ? 'background-color: rgba(0,242,255,0.15); color:#00f2ff; border-color: rgba(0,242,255,0.3);' : '' }}">
                            {{ $rankName }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4" style="border-top: 1px solid rgba(58, 73, 75, 0.5);">
                        <div class="flex flex-col gap-1 p-3 rounded-lg" style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                            <span class="font-mono-label text-[10px] uppercase tracking-wider text-soft">Win Rate</span>
                            <span class="font-headline text-lg font-bold text-cyan-glow">{{ $winRate }}%</span>
                            <span class="font-mono-label text-[10px] text-faint">{{ $totalGames }} permainan</span>
                        </div>
                        <div class="flex flex-col gap-1 p-3 rounded-lg" style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                            <span class="font-mono-label text-[10px] uppercase tracking-wider text-soft">Skor Rata-rata</span>
                            <span class="font-headline text-lg font-bold text-magenta-glow">{{ $avgScoreFormatted }}</span>
                            <span class="font-mono-label text-[10px] text-faint">poin / sesi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
