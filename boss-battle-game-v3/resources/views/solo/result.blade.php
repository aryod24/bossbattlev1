<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-card overflow-hidden shadow-sm sm:rounded-lg border border-border p-8 text-center">
                
                <!-- Boss Status -->
                <div class="mb-8">
                    @if($session->boss_kalah)
                        <div class="text-6xl mb-4">🎉</div>
                        <h1 class="text-4xl font-black text-green-500 mb-2">Boss Kalah!</h1>
                        <p class="text-xl text-text-primary">Selamat! Anda berhasil mengalahkan <span class="font-bold">{{ $bossName }}</span>!</p>
                    @else
                        <div class="text-6xl mb-4">💪</div>
                        <h1 class="text-4xl font-black text-red-500 mb-2">Boss Bertahan!</h1>
                        <p class="text-xl text-text-primary"><span class="font-bold">{{ $bossName }}</span> masih terlalu kuat. Coba lagi!</p>
                    @endif
                </div>

                <!-- Score Summary -->
                <!-- Battle Statistics -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark p-6 mb-8 text-left">
                    <h3 class="text-lg font-bold text-text-primary mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">analytics</span>
                        Battle Statistics
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <!-- Attempt -->
                        <div>
                            <p class="text-sm text-text-muted mb-1">Attempt</p>
                            <p class="text-xl font-bold text-text-primary">#{{ $session->attempt_number }}</p>
                        </div>

                        <!-- Score -->
                        <div>
                            <p class="text-sm text-text-muted mb-1">Score</p>
                            <div class="flex items-baseline gap-1">
                                <p class="text-xl font-bold text-text-primary">{{ number_format($session->skor_akhir, 1) }}%</p>
                                <span class="text-xs text-text-muted">({{ $session->jumlah_benar }}/{{ $session->jumlah_soal }})</span>
                            </div>
                        </div>

                        <!-- XP Gained -->
                        <div>
                            <p class="text-sm text-text-muted mb-1">XP Gained</p>
                            <p class="text-xl font-bold text-primary">+{{ $session->xp_diperoleh }} XP</p>
                        </div>

                        <!-- Duration -->
                        <div>
                            <p class="text-sm text-text-muted mb-1">Duration</p>
                            <p class="text-xl font-bold text-text-primary">{{ gmdate("i:s", $session->durasi_detik) }}</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-border-light dark:border-border-dark grid grid-cols-1 md:grid-cols-2 gap-4">
                         <!-- Status -->
                         <div class="flex justify-between items-center p-3 rounded-lg bg-background-light dark:bg-background-dark">
                            <span class="text-sm text-text-muted">Status</span>
                            @if($session->boss_kalah)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-500 border border-green-500/20">
                                    VICTORY
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-500/10 text-red-500 border border-red-500/20">
                                    DEFEAT
                                </span>
                            @endif
                        </div>

                        <!-- Research Status -->
                        <div class="flex justify-between items-center p-3 rounded-lg bg-background-light dark:bg-background-dark">
                            <span class="text-sm text-text-muted">Research Data</span>
                            @if($session->is_counted_research)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-blue-500 border border-blue-500/20">
                                    RECORDED
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-500/10 text-gray-500 border border-gray-500/20">
                                    NOT COUNTED
                                </span>
                            @endif
                        </div>
                    </div>
                </div>



                <!-- Action Buttons -->
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('solo.map', $session->solo_raid_id) }}" 
                       class="bg-background-dark hover:bg-border border border-border text-text-primary font-bold py-3 px-8 rounded-lg transition-colors">
                        🗺️ Kembali ke Map
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
