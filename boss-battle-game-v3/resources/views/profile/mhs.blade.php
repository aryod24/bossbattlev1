<x-app-layout>
    <div class="flex flex-col gap-8">
        <!-- User Stats & Badges Section -->
        <div class="bg-card shadow-xl sm:rounded-2xl border border-border p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 pb-6 border-b border-border">
                <div>
                    <h3 class="text-3xl font-black text-white tracking-tight flex items-center gap-3">
                        <span class="material-symbols-outlined text-4xl text-primary">military_tech</span>
                        Koleksi Badge
                    </h3>
                    <p class="text-text-muted mt-2 text-lg">Pencapaian dan tonggak sejarah Anda di arena Boss Battle.</p>
                </div>
                <div class="text-right mt-4 sm:mt-0 bg-surface-dark px-6 py-3 rounded-xl border border-border">
                    <span class="text-4xl font-bold text-primary">{{ count($unlockedBadges) }}</span>
                    <span class="text-xl text-text-muted">/ {{ count($allBadges) }}</span>
                    <div class="text-xs font-bold uppercase tracking-wider text-text-muted mt-1">Terbuka</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($allBadges as $badge)
                    @php
                        $isUnlocked = isset($unlockedBadges[$badge->id]);
                        $unlockDate = $isUnlocked ? $unlockedBadges[$badge->id]->unlock_date->format('d M Y') : null;
                    @endphp

                    <div class="group relative p-6 rounded-xl border-2 flex flex-col items-center text-center transition-all duration-300 hover:shadow-2xl hover:-translate-y-1
                        {{ $isUnlocked
                            ? 'border-primary/50 bg-primary/5 shadow-primary/10'
                            : 'border-border bg-surface-dark/50 opacity-60 grayscale'
                        }}">

                        <div class="text-6xl mb-4 transform transition-transform group-hover:scale-110 {{ $isUnlocked ? 'drop-shadow-[0_0_15px_rgba(255,215,0,0.3)]' : '' }}">
                            {{ $badge->emoji }}
                        </div>

                        <h4 class="text-base font-bold mb-2 leading-tight {{ $isUnlocked ? 'text-white' : 'text-text-muted' }}">
                            {{ $badge->name }}
                        </h4>

                        <p class="text-xs text-text-muted mb-4 flex-grow leading-relaxed px-2">
                            {{ $badge->description }}
                        </p>

                        <div class="w-full pt-3 border-t border-border/50">
                            @if($isUnlocked)
                                <div class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">
                                    <span class="mr-1">✓</span> {{ $unlockDate }}
                                </div>
                            @else
                                <div class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold bg-surface-dark text-text-muted border border-border">
                                    <span class="material-symbols-outlined text-[10px] mr-1">lock</span> Terkunci
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Profile Information & Stats Cards (2 Columns) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Card: Profile Information -->
            <div class="bg-card shadow-xl sm:rounded-2xl border border-border p-6 sm:p-8">
                <header class="mb-6">
                    <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Informasi Profil
                    </h3>
                    <p class="mt-1 text-sm text-text-muted">{{ __('Perbarui informasi profil Anda. Email tidak dapat diubah.') }}</p>
                </header>

                <div>
                    <x-profile-information :user="$user" />
                </div>
            </div>

            <!-- Right Card: Your Stats -->
            <div class="bg-card shadow-xl sm:rounded-2xl border border-border p-6 sm:p-8">
                <header class="mb-6">
                    <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">bar_chart</span>
                        Statistik Anda
                    </h3>
                    <p class="mt-1 text-sm text-text-muted">Kemajuan dan peringkat berdasarkan XP.</p>
                </header>

                @php
                    $level = $user->level ?? 0;
                    $xp = $user->total_xp ?? 0;
                    $thresholds = \App\Services\XpService::LEVEL_THRESHOLDS;
                    $nextLevel = $level + 1;
                    $maxLevel = 5;

                    if ($level >= $maxLevel) {
                        $nextThreshold = $thresholds[$maxLevel];
                        $prevThreshold = $thresholds[$maxLevel-1];
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

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-text-muted">Level</span>
                        <span class="font-bold text-primary text-xl">{{ $level }}</span>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-text-muted">Kemajuan ke Lvl {{ $level < 5 ? $level + 1 : 'Maks' }}</span>
                            <span class="text-text-primary font-bold">{{ $nextThresholdText }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                            <div class="bg-primary h-2.5 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-border">
                        <span class="text-text-muted text-sm">Peringkat</span>
                        <span class="
                            px-3 py-1 rounded-full text-xs font-bold
                            {{ $user->rank_label == 'Master' ? 'bg-red-900 text-red-300' : '' }}
                            {{ $user->rank_label == 'Advanced' ? 'bg-purple-900 text-purple-300' : '' }}
                            {{ $user->rank_label == 'Gold' ? 'bg-yellow-900 text-yellow-300' : '' }}
                            {{ $user->rank_label == 'Silver' ? 'bg-gray-700 text-gray-300' : '' }}
                            {{ $user->rank_label == 'Novice' ? 'bg-blue-900 text-blue-300' : '' }}
                        ">{{ $user->rank_label }}</span>
                    </div>
                    <div class="pt-2 border-t border-border">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex justify-between items-center">
                                <span class="text-text-muted text-sm">Persentase Kemenangan</span>
                                <span class="font-bold text-text-primary">{{ $winRate }}% <span class="text-xs text-text-muted">({{ $totalGames }} permainan)</span></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-text-muted text-sm">Skor Rata-rata</span>
                                <span class="font-bold text-text-primary">{{ $avgScoreFormatted }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
