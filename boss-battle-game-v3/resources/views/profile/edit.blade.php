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

        <!-- Profile Information -->
        <div class="bg-card shadow-xl sm:rounded-2xl border border-border p-6 sm:p-8">
            <div class="max-w-xl">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">person</span>
                    Profile Information
                </h3>
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>
        
        <!-- Update Password (Optional if needed, else just Profile Info as per existing code) -->
        <!-- Logic for delete account is often in profile.edit too, if I removed it I should check if it was there. 
             Wait, looking at the code I read in Step 183: 
             It only had 'Profile Information'. 
             Wait, verify line 76: @include('profile.partials.update-profile-information-form')
             Did I miss other includes?
             Let's re-read Step 183 carefully.
             It shows only update-profile-information-form.
             However, typically Laravel Breeze has update-password-form and delete-user-form.
             Maybe they were removed previously or I just didn't see them in the truncated view?
             Step 183 says "Showing lines 1 to 82" and "The above content shows the entire, complete file contents".
             So it seems it only has `update-profile-information-form`.
             Okay, I will stick to what was there.
        -->
    </div>
</x-app-layout>
