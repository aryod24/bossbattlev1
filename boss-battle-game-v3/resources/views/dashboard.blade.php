<x-app-layout>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Welcome Card -->
        <div class="lg:col-span-2 bg-card p-6 rounded-lg shadow-sm border border-border">
            <h1 class="text-2xl font-bold text-text-primary mb-2">Welcome back, {{ auth()->user()->nama }}!</h1>
            <p class="text-text-muted">Ready to conquer the code? Check out the latest events and boost your rank.</p>
            
            <div class="mt-6 flex gap-4">
                <a href="{{ route('solo.index') }}" class="flex items-center justify-center rounded-lg h-10 bg-primary text-white gap-2 text-sm font-bold px-5 shadow-sm hover:bg-accent-hover transition-transform duration-200 hover:-translate-y-0.5">
                    Browse Events
                </a>
                <a href="#" class="flex items-center justify-center rounded-lg h-10 bg-surface-light text-text-primary gap-2 text-sm font-bold px-5 border border-border shadow-sm hover:bg-border transition-transform duration-200 hover:-translate-y-0.5">
                    View Leaderboard
                </a>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="bg-card p-6 rounded-lg shadow-sm border border-border">
            <h2 class="text-lg font-bold text-text-primary mb-4">Your Stats</h2>
            <div class="space-y-4">
                @php
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
                @endphp

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
                    <span class="text-text-muted text-sm">Global Rank</span>
                    <span class="font-bold text-text-primary">#--</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity / Active Events -->
        <div class="lg:col-span-3">
            <h2 class="text-xl font-bold text-text-primary mb-4">Active Events</h2>
            <!-- This could be dynamic later -->
            <div class="bg-card p-8 rounded-lg text-center border border-dashed border-border">
                <p class="text-text-muted">No active events at the moment. Check back later!</p>
            </div>
        </div>
    </div>
</x-app-layout>
