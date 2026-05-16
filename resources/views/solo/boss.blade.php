{{-- Boss Info Page --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $bossName }} — Boss Battle</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: {
                "primary": "#007acc",
                "background": "#1e1e1e", "card": "#252526", "surface": "#252526", "surface-light": "#2d2d2d",
                "text-primary": "#d4d4d4", "text-muted": "#858585",
                "border": "#333333", "border-light": "#404040",
                "success": "#4ec9b0", "warning": "#dcdcaa", "error": "#f44747",
            }}}
        }
    </script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
        .animate-float { animation: float 3s ease-in-out infinite; }
        @keyframes pulse-red { 0%, 100% { box-shadow: 0 0 0 0 rgba(244,71,71,0.4); } 50% { box-shadow: 0 0 0 16px rgba(244,71,71,0); } }
        .animate-pulse-red { animation: pulse-red 2s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen bg-background flex flex-col md:flex-row" x-data="{ showConfirmModal: false }">

    {{-- Left: Info Panel --}}
    <div class="w-full md:w-1/2 bg-background p-6 md:p-10 flex flex-col justify-start overflow-y-auto custom-scrollbar border-r border-border">
        
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex justify-between items-center bg-surface p-4 rounded-xl border border-border shadow-lg">
                <a href="{{ route('solo.index') }}" class="group inline-flex items-center gap-2 text-text-muted hover:text-primary transition-colors text-xs font-bold uppercase tracking-widest">
                    <span class="material-symbols-outlined text-[18px] transition-transform group-hover:-translate-x-1">arrow_back</span>
                    Keluar Misi
                </a>

                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-bold text-text-primary hidden sm:block">CodeBossArena</h2>
                    <div class="w-8 h-8">
                        <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                </div>
            </div>
        </div>

        {{-- Mission Briefing --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <span class="px-3 py-1 rounded text-[10px] font-black bg-error/20 text-error border border-error/30 uppercase tracking-[0.2em] flex items-center gap-1.5">
                    <span class="material-symbols-outlined" style="font-size:14px">skull</span>
                    BOSS BATTLE — SECTION {{ strtoupper($section) }}
                </span>
                <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">ID: #BB-{{ str_pad($soloRaid->id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
            <h1 class="text-4xl font-black text-white mb-4 tracking-tight">{{ $soloRaid->nama }}</h1>
            
            <div class="relative group mb-6">
                <div class="absolute -left-4 top-0 bottom-0 w-1 bg-error/30 rounded-full group-hover:bg-error transition-colors"></div>
                <p class="text-text-muted text-sm leading-relaxed pl-2 italic">
                    "{{ $soloRaid->deskripsi }}"
                </p>
            </div>
        </div>

        {{-- Tactical Stats --}}
        <div class="mb-8 grid grid-cols-3 gap-4">
            <div class="bg-surface border border-border rounded-xl p-4 text-center group hover:border-primary/50 transition-all shadow-inner">
                <p class="text-2xl font-black text-white group-hover:text-primary transition-colors">{{ $levelConfig['questions'] }}</p>
                <p class="text-[9px] font-black text-text-muted mt-1 uppercase tracking-widest">Soal</p>
            </div>
            <div class="bg-surface border border-border rounded-xl p-4 text-center group hover:border-primary/50 transition-all shadow-inner">
                <p class="text-2xl font-black text-white group-hover:text-primary transition-colors">{{ $levelConfig['timer_minutes'] }}'</p>
                <p class="text-[9px] font-black text-text-muted mt-1 uppercase tracking-widest">Menit</p>
            </div>
            <div class="bg-surface border border-border rounded-xl p-4 text-center group hover:border-primary/50 transition-all shadow-inner">
                <p class="text-2xl font-black text-white group-hover:text-primary transition-colors">{{ $levelConfig['min_correct'] }}</p>
                <p class="text-[9px] font-black text-text-muted mt-1 uppercase tracking-widest">Min. Benar</p>
            </div>
        </div>

        {{-- Rewards Intel --}}
        <div class="mb-10 bg-surface/50 border border-border rounded-xl p-5 border-dashed">
            <h3 class="text-[10px] font-black text-text-muted uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-warning text-sm">stars</span>
                Potensi Hadiah Misi
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center border border-primary/20 shrink-0">
                        <span class="text-xs font-black text-primary">XP</span>
                    </div>
                    <div>
                        @php
                            $maxXP = ($levelConfig['questions'] * ($section === 'Easy' ? 10 : ($section === 'Medium' ? 15 : 20))) + ($section === 'Easy' ? 50 : ($section === 'Medium' ? 75 : 100));
                        @endphp
                        <p class="text-xs font-black text-text-primary uppercase tracking-tight">+{{ $maxXP }} XP</p>
                        <p class="text-[9px] text-text-muted font-bold uppercase">Potensi Maks.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 border-l border-border pl-4">
                    <div class="w-8 h-8 rounded bg-error/10 flex items-center justify-center border border-error/20 shrink-0">
                        <span class="material-symbols-outlined text-error text-sm">info</span>
                    </div>
                    <div>
                        <p class="text-xs font-black text-error uppercase tracking-tight">XP 50%</p>
                        <p class="text-[9px] text-text-muted font-bold uppercase leading-tight">Percobaan ≥ 2</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 border-l border-border pl-4">
                    <div class="w-8 h-8 rounded bg-warning/10 flex items-center justify-center border border-warning/20 shrink-0">
                        <span class="material-symbols-outlined text-warning text-lg">workspace_premium</span>
                    </div>
                    <div>
                        <p class="text-xs font-black text-text-primary uppercase tracking-tight">Badge Khusus</p>
                        <p class="text-[9px] text-text-muted font-bold uppercase">Reward Misi</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Percobaan --}}
        <div class="mb-6">
            <h3 class="text-text-muted font-black mb-4 flex items-center gap-2 text-[10px] uppercase tracking-[0.2em]">
                <span class="material-symbols-outlined text-primary text-sm">history</span>
                Log Aktivitas ({{ $sessionHistory->count() }} Entri)
            </h3>
            
            @if($sessionHistory->count() > 0)
                <div class="space-y-3">
                    @foreach($sessionHistory->take(3) as $sess)
                        <div class="group bg-surface/30 rounded-lg p-3 border border-border hover:border-primary/50 transition-all flex justify-between items-center">
                            <div class="flex flex-col">
                                <span class="text-[9px] text-text-muted font-bold uppercase tracking-tight">{{ \Carbon\Carbon::parse($sess->waktu_mulai)->format('d M Y, H:i') }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-text-primary uppercase tracking-widest">Percobaan #{{ $sess->attempt_number }}</span>
                                    <span class="w-1 h-1 rounded-full bg-border"></span>
                                <span class="text-[10px] font-black text-error uppercase">{{ $section }}</span>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                @if(!$sess->waktu_selesai)
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></div>
                                        <span class="text-[10px] font-black text-yellow-500 uppercase tracking-widest">Aktif</span>
                                    </div>
                                @else
                                    <div class="text-xs font-black {{ $sess->boss_kalah ? 'text-success' : 'text-error' }} uppercase">
                                        {{ $sess->boss_kalah ? 'Berhasil' : 'Gagal' }}
                                    </div>
                                    <div class="text-[10px] font-black text-text-primary">Akurasi {{ number_format($sess->skor_akhir, 0) }}%</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    @if($sessionHistory->count() > 3)
                        <div class="text-center">
                            <span class="text-[9px] font-bold text-text-muted uppercase tracking-widest">... {{ $sessionHistory->count() - 3 }} entri lainnya disembunyikan ...</span>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-10 border-2 border-dashed border-border rounded-xl">
                    <span class="material-symbols-outlined text-3xl text-border mb-2 block">folder_open</span>
                    <p class="text-[10px] font-black text-text-muted uppercase tracking-[0.2em]">Belum Ada Rekaman Aktivitas</p>
                </div>
            @endif
        </div>
    </div>
    </div>

    {{-- Right: Boss Visual + Start --}}
    <div class="w-full md:w-1/2 bg-background flex items-center justify-center p-8 md:p-12 relative overflow-hidden">

        {{-- BG glow --}}
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div class="w-80 h-80 bg-error/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 flex flex-col items-center text-center gap-8 w-full max-w-xs">

            {{-- Boss Avatar --}}
            <div class="animate-float animate-pulse-red w-44 h-44 rounded-full bg-surface border-4 border-error/50 flex items-center justify-center shadow-2xl overflow-hidden">
                <img src="https://api.dicebear.com/9.x/bottts-neutral/svg?seed={{ urlencode($bossName) }}&backgroundColor=1e1e1e"
                     alt="{{ $bossName }}" class="w-full h-full object-cover opacity-90">
            </div>

            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-error mb-1">⚠ FINAL BOSS</p>
                <h2 class="text-3xl font-black text-text-primary mb-2">{{ $bossName }}</h2>
                <p class="text-text-muted text-sm">HP: {{ $levelConfig['boss_hp'] }} — Kalahkan dengan menjawab soal dengan benar!</p>
            </div>

            @if($bestSession)
            <div class="bg-success/10 border border-success/30 rounded-xl px-4 py-3 w-full text-center">
                <p class="text-xs text-success font-bold">✓ Kamu sudah pernah menang!</p>
                <p class="text-xs text-text-muted mt-0.5">Skor terbaik: {{ number_format($bestSession->skor_akhir, 0) }}%</p>
            </div>
            @endif

            <button @click="showConfirmModal = true"
               class="w-full flex items-center justify-center gap-3 rounded-xl py-4 bg-error text-white text-base font-black shadow-lg hover:bg-red-600 transition-all hover:scale-105 active:scale-95">
                <span class="material-symbols-outlined">swords</span>
                Mulai Boss Battle!
            </button>

            <p class="text-xs text-text-muted">Timer mulai begitu kamu konfirmasi. Siap?</p>
        </div>
    </div>

    <!-- Modal Konfirmasi Boss Battle -->
    <div x-show="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showConfirmModal" @click="showConfirmModal = false" class="fixed inset-0 bg-black opacity-75"></div>

            <div x-show="showConfirmModal" class="relative inline-block bg-card rounded-xl text-left overflow-hidden shadow-2xl border border-error/50 max-w-md w-full">
                <div class="px-6 pt-6 pb-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-error/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-error">swords</span>
                        </div>
                        <h3 class="text-lg font-bold text-text-primary">Mulai Boss Battle?</h3>
                    </div>
                    <div class="bg-surface-light rounded-lg p-4 border border-border mb-4">
                        <p class="text-sm text-text-muted mb-1">Boss</p>
                        <p class="font-bold text-text-primary mb-3">{{ $bossName }}</p>
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <p class="text-text-muted mb-1">HP Boss</p>
                                <p class="font-bold text-error">{{ $levelConfig['boss_hp'] }} HP</p>
                            </div>
                            <div>
                                <p class="text-text-muted mb-1">Percobaan ke-</p>
                                <p class="font-bold text-text-primary">#{{ $sessionHistory->count() + 1 }}</p>
                            </div>
                            <div>
                                <p class="text-text-muted mb-1">Durasi</p>
                                <p class="font-bold text-text-primary">{{ $levelConfig['timer_minutes'] }} menit</p>
                            </div>
                            <div>
                                <p class="text-text-muted mb-1">Min. Benar</p>
                                <p class="font-bold text-text-primary">{{ $levelConfig['min_correct'] }} soal</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-text-muted">⚔️ Timer akan langsung mulai setelah konfirmasi.</p>
                </div>
                <div class="px-6 py-4 bg-background flex flex-row-reverse gap-2">
                    <a href="{{ route('solo.battle.init', ['soloRaid' => $soloRaid->id, 'level' => $section]) }}"
                       class="inline-flex justify-center rounded-lg px-5 py-2 bg-error text-white text-sm font-bold hover:bg-red-600">
                        Mulai Sekarang!
                    </a>
                    <button type="button" @click="showConfirmModal = false"
                            class="inline-flex justify-center rounded-lg px-5 py-2 border border-border text-text-primary text-sm font-medium hover:bg-surface-light">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
