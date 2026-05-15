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
    <div class="w-full md:w-1/2 bg-card p-8 md:p-12 flex flex-col justify-start border-b md:border-b-0 md:border-r border-border">

        {{-- Back + Logo --}}
        <div class="flex justify-between items-center mb-10 bg-surface rounded-xl p-4 border border-border">
            <a href="{{ route('solo.index') }}" class="inline-flex items-center gap-2 text-text-muted hover:text-primary transition-colors text-sm font-bold group">
                <span class="material-symbols-outlined text-[18px] transition-transform group-hover:-translate-x-1">arrow_back</span>
                Kembali ke List
            </a>
            <div class="flex items-center gap-2">
                <h2 class="text-sm font-bold text-text-primary hidden sm:block">CodeBossArena</h2>
                <div class="w-7 h-7"><img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-full h-full object-contain"></div>
            </div>
        </div>

        {{-- Event Info --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-3">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-error/20 text-error border border-error/30 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="material-symbols-outlined" style="font-size:14px">skull</span>
                    Boss Battle — Section {{ $section }}
                </span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-text-primary mb-3">{{ $soloRaid->nama }}</h1>
            <p class="text-text-muted text-base leading-relaxed">{{ $soloRaid->deskripsi }}</p>
        </div>

        {{-- Level Config Stats --}}
        <div class="mb-8 grid grid-cols-3 gap-4">
            <div class="bg-surface-light rounded-xl p-4 border border-border text-center">
                <p class="text-2xl font-black text-text-primary">{{ $levelConfig['questions'] }}</p>
                <p class="text-xs text-text-muted mt-1">Soal</p>
            </div>
            <div class="bg-surface-light rounded-xl p-4 border border-border text-center">
                <p class="text-2xl font-black text-text-primary">{{ $levelConfig['timer_minutes'] }}'</p>
                <p class="text-xs text-text-muted mt-1">Menit</p>
            </div>
            <div class="bg-surface-light rounded-xl p-4 border border-border text-center">
                <p class="text-2xl font-black text-text-primary">{{ $levelConfig['min_correct'] }}</p>
                <p class="text-xs text-text-muted mt-1">Min. Benar</p>
            </div>
        </div>

        {{-- Previous Attempts --}}
        @if($sessionHistory->count() > 0)
        <div>
            <h3 class="text-text-muted font-semibold mb-3 flex items-center gap-2 text-sm">
                <span class="material-symbols-outlined text-primary">history</span>
                Riwayat Percobaan ({{ $sessionHistory->count() }}x)
            </h3>
            <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                @foreach($sessionHistory->take(5) as $sess)
                <div class="bg-surface-light rounded-lg p-3 border border-border flex justify-between items-center text-xs">
                    <span class="text-text-muted">{{ \Carbon\Carbon::parse($sess->waktu_mulai)->format('d M, H:i') }}</span>
                    @if(!$sess->waktu_selesai)
                        <span class="font-bold text-yellow-500 animate-pulse">● Berjalan</span>
                    @else
                        <span class="font-bold {{ $sess->boss_kalah ? 'text-success' : 'text-error' }}">
                            {{ $sess->boss_kalah ? '✓ Menang' : '✗ Kalah' }}
                            — {{ number_format($sess->skor_akhir, 0) }}%
                        </span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
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
