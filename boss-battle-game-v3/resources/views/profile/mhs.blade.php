<x-app-layout>
    <div class="flex flex-col gap-8">
        <!-- User Stats & Badges Section -->
        <div class="bg-card shadow-xl sm:rounded-2xl border border-border p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 pb-6 border-b border-border">
                <div>
                    <h3 class="text-3xl font-black text-white tracking-tight flex items-center gap-3">
                        <span class="material-symbols-outlined text-4xl text-primary">military_tech</span>
                        Badge Collection
                    </h3>
                    <p class="text-text-muted mt-2 text-lg">Your achievements and milestones in the Boss Battle arena.</p>
                </div>
                <div class="text-right mt-4 sm:mt-0 bg-surface-dark px-6 py-3 rounded-xl border border-border">
                    <span class="text-4xl font-bold text-primary">{{ count($unlockedBadges) }}</span>
                    <span class="text-xl text-text-muted">/ {{ count($allBadges) }}</span>
                    <div class="text-xs font-bold uppercase tracking-wider text-text-muted mt-1">Unlocked</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-6">
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
                                    <span class="material-symbols-outlined text-[10px] mr-1">lock</span> Locked
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Profile Information & Stats Cards (2 Columns) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Card: Profile Information -->
            <div class="bg-card shadow-xl sm:rounded-2xl border border-border p-6 sm:p-8">
                <header class="mb-6">
                    <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Profile Information
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
                        Your Stats
                    </h3>
                    <p class="mt-1 text-sm text-text-muted">Progress and rank based on XP.</p>
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
                        $nextThresholdText = "Max Level";
                    } else {
                        $nextThreshold = $thresholds[$nextLevel];
                        $prevThreshold = $thresholds[$level];
                        $levelRange = $nextThreshold - $prevThreshold;
                        $xpInLevel = $xp - $prevThreshold;
                        $progress = min(100, max(0, ($xpInLevel / $levelRange) * 100));
                        $nextThresholdText = "{$xp} / {$nextThreshold} XP";
                    }

                    $rankName = match(true) {
                        $level >= 5 => 'Champion',
                        $level >= 3 => 'Gold',
                        $level >= 2 => 'Silver',
                        default => 'Novice'
                    };
                @endphp

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-text-muted">Level</span>
                        <span class="font-bold text-primary text-xl">{{ $level }}</span>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-text-muted">Progress to Lvl {{ $level < 5 ? $level + 1 : 'Max' }}</span>
                            <span class="text-text-primary font-bold">{{ $nextThresholdText }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                            <div class="bg-primary h-2.5 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-border">
                        <span class="text-text-muted text-sm">Rank</span>
                        <span class="font-bold text-text-primary">{{ $rankName }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
