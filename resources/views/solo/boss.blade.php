{{-- Boss Info Page --}}
<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $bossName }} — Boss Battle</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

    <!-- Tailwind & Alpine via Vite (production build, no JIT in browser) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Hanken Grotesk', sans-serif;
            background-color: #0A0A0B;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.05'/%3E%3C/svg%3E");
            color: #e5e2e3;
        }

        /* === Cyber-noir typography & shared components === */
        .font-headline { font-family: 'Sora', sans-serif; }
        .font-body { font-family: 'Hanken Grotesk', sans-serif; }
        .font-mono-label { font-family: 'JetBrains Mono', monospace; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .text-cyan-glow { color: #00f2ff; }
        .text-magenta-glow { color: #ce5dff; }
        .text-soft { color: #b9cacb; }
        .text-faint { color: #849495; }
        .border-cyan-soft { border-color: rgba(0, 242, 255, 0.3); }
        .bg-cyan-soft { background-color: rgba(0, 242, 255, 0.15); }

        .glass-card {
            background: rgba(25, 25, 28, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 242, 255, 0.2);
            transition: all 0.3s ease;
        }
        .glass-card:hover { border-color: rgba(0, 242, 255, 0.4); }

        .boss-card {
            background: linear-gradient(135deg, rgba(206, 93, 255, 0.6), rgba(255, 99, 99, 0.4));
            border: 1px solid rgba(206, 93, 255, 0.5);
            box-shadow: 0 0 30px rgba(206, 93, 255, 0.15);
        }

        .btn-cyber-primary {
            background: linear-gradient(135deg, #00f2ff, #ce5dff);
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }
        .btn-cyber-primary:hover { box-shadow: 0 0 20px rgba(0, 242, 255, 0.6); }

        .btn-boss {
            background: linear-gradient(135deg, #ce5dff, #ff6b6b);
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }
        .btn-boss:hover { box-shadow: 0 0 24px rgba(206, 93, 255, 0.55); }

        .progress-bar-fill { background: linear-gradient(90deg, #00f2ff, #ce5dff); }
        .progress-glow-tip { box-shadow: 0 0 10px #ffffff; }
        .divider-soft { border-top: 1px solid rgba(58, 73, 75, 0.5); }

        .custom-scrollbar::-webkit-scrollbar { width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(25,25,28,0.4); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,242,255,0.2); border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(0,242,255,0.4); }

        /* Boss page animations */
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
        .animate-float { animation: float 3s ease-in-out infinite; }
        @keyframes pulse-magenta {
            0%, 100% { box-shadow: 0 0 0 0 rgba(206,93,255,0.4); }
            50% { box-shadow: 0 0 0 16px rgba(206,93,255,0); }
        }
        .animate-pulse-magenta { animation: pulse-magenta 2s ease-in-out infinite; }
    </style>
</head>
<body class="md:h-screen md:overflow-hidden flex flex-col md:flex-row" x-data="{ showConfirmModal: false }">

    {{-- Left: Info Panel (cyber-noir, scrolls internally on desktop) --}}
    <div class="w-full md:w-1/2 md:h-screen p-6 md:p-10 flex flex-col gap-6 overflow-y-auto custom-scrollbar"
         style="background-color: rgba(14, 14, 15, 0.4); border-right: 1px solid rgba(0, 242, 255, 0.15);">

        {{-- Header --}}
        <div class="glass-card rounded-xl p-4 flex justify-between items-center">
            <a href="{{ route('solo.index') }}"
               class="group inline-flex items-center gap-2 font-mono-label text-xs uppercase tracking-widest text-soft hover:text-cyan-glow transition-colors">
                <span class="material-symbols-outlined text-[18px] transition-transform group-hover:-translate-x-1">arrow_back</span>
                Keluar Misi
            </a>
            <div class="flex items-center gap-3">
                <h2 class="font-headline text-base font-bold hidden sm:block" style="color: #e5e2e3;">CodeBossArena</h2>
                <div class="w-8 h-8">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
            </div>
        </div>

        {{-- Mission Briefing --}}
        <div class="glass-card rounded-xl p-8">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="font-mono-label inline-flex items-center gap-1 text-[10px] font-medium px-2 py-0.5 rounded uppercase tracking-wider"
                      style="background-color: rgba(255,99,99,0.15); color: #ffb4ab; border: 1px solid rgba(255,99,99,0.3);">
                    <span class="material-symbols-outlined" style="font-size: 11px;">skull</span>
                    Boss Battle · Section {{ ucfirst(strtolower($section)) }}
                </span>
                <span class="font-mono-label text-[10px] font-medium uppercase tracking-widest text-faint">
                    ID · #BB-{{ str_pad($soloRaid->id, 3, '0', STR_PAD_LEFT) }}
                </span>
            </div>

            <h1 class="font-headline text-3xl md:text-[32px] font-extrabold text-cyan-glow leading-tight mb-4">
                {{ $soloRaid->nama }}
            </h1>

            <div class="relative pl-4 mb-4">
                <div class="absolute left-0 top-0 bottom-0 w-1 rounded-full"
                     style="background: linear-gradient(180deg, rgba(206,93,255,0.5), rgba(255,99,99,0.5));"></div>
                <p class="font-body text-sm text-soft leading-relaxed italic">
                    "{{ $soloRaid->deskripsi }}"
                </p>
            </div>
        </div>

        {{-- Tactical Stats --}}
        <div class="glass-card rounded-xl p-8">
            <div class="flex items-center gap-2 mb-6 pb-4" style="border-bottom: 1px solid rgba(58, 73, 75, 0.5);">
                <span class="material-symbols-outlined text-cyan-glow">analytics</span>
                <h3 class="font-headline text-xl font-semibold" style="color: #e5e2e3;">Statistik Misi</h3>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="rounded-lg p-4 text-center"
                     style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                    <p class="font-headline text-3xl font-extrabold text-cyan-glow leading-none">{{ $levelConfig['questions'] }}</p>
                    <p class="font-mono-label text-[10px] uppercase tracking-widest text-soft mt-2">Soal</p>
                </div>
                <div class="rounded-lg p-4 text-center"
                     style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                    <p class="font-headline text-3xl font-extrabold text-magenta-glow leading-none">{{ $levelConfig['timer_minutes'] }}'</p>
                    <p class="font-mono-label text-[10px] uppercase tracking-widest text-soft mt-2">Menit</p>
                </div>
                <div class="rounded-lg p-4 text-center"
                     style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                    <p class="font-headline text-3xl font-extrabold leading-none" style="color: #fde68a;">{{ $levelConfig['min_correct'] }}</p>
                    <p class="font-mono-label text-[10px] uppercase tracking-widest text-soft mt-2">Min. Benar</p>
                </div>
            </div>
        </div>

        {{-- Rewards Intel --}}
        <div class="glass-card rounded-xl p-8">
            <div class="flex items-center gap-2 mb-6 pb-4" style="border-bottom: 1px solid rgba(58, 73, 75, 0.5);">
                <span class="material-symbols-outlined" style="color: #fde68a;">stars</span>
                <h3 class="font-headline text-xl font-semibold" style="color: #e5e2e3;">Potensi Hadiah</h3>
            </div>

            @php
                $maxXP = ($levelConfig['questions'] * ($section === 'Easy' ? 10 : ($section === 'Medium' ? 15 : 20))) + ($section === 'Easy' ? 50 : ($section === 'Medium' ? 75 : 100));
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="flex items-center gap-3 p-3 rounded-lg"
                     style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0"
                         style="background-color: rgba(0,242,255,0.12); border: 1px solid rgba(0,242,255,0.3);">
                        <span class="font-mono-label text-xs font-bold text-cyan-glow">XP</span>
                    </div>
                    <div class="min-w-0">
                        <p class="font-headline text-sm font-bold text-cyan-glow leading-tight">+{{ $maxXP }} XP</p>
                        <p class="font-mono-label text-[10px] uppercase tracking-wider text-faint mt-0.5">Potensi Maks.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-lg"
                     style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0"
                         style="background-color: rgba(255,99,99,0.12); border: 1px solid rgba(255,99,99,0.3);">
                        <span class="material-symbols-outlined text-sm" style="color: #ffb4ab;">info</span>
                    </div>
                    <div class="min-w-0">
                        <p class="font-headline text-sm font-bold leading-tight" style="color: #ffb4ab;">XP 50%</p>
                        <p class="font-mono-label text-[10px] uppercase tracking-wider text-faint mt-0.5">Percobaan ≥ 2</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-lg"
                     style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0"
                         style="background-color: rgba(250,204,21,0.12); border: 1px solid rgba(250,204,21,0.3);">
                        <span class="material-symbols-outlined text-sm" style="color: #fde68a;">workspace_premium</span>
                    </div>
                    <div class="min-w-0">
                        <p class="font-headline text-sm font-bold leading-tight" style="color: #fde68a;">Badge Khusus</p>
                        <p class="font-mono-label text-[10px] uppercase tracking-wider text-faint mt-0.5">Reward Misi</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Percobaan --}}
        <div class="glass-card rounded-xl p-8">
            <div class="flex justify-between items-center mb-6 pb-4" style="border-bottom: 1px solid rgba(58, 73, 75, 0.5);">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined" style="color: #ebb2ff;">history</span>
                    <h3 class="font-headline text-xl font-semibold" style="color: #e5e2e3;">Riwayat Percobaan</h3>
                </div>
                <span class="font-mono-label text-xs uppercase tracking-wider text-faint">
                    {{ $sessionHistory->count() }} Entri
                </span>
            </div>

            @if($sessionHistory->count() > 0)
                <div class="space-y-3">
                    @foreach($sessionHistory->take(3) as $sess)
                        @php
                            $levelColor = match($section) {
                                'Hard' => '#ffb4ab',
                                'Medium' => '#fde68a',
                                'Easy' => '#86efac',
                                default => '#00f2ff',
                            };
                        @endphp
                        <div class="flex items-center justify-between p-4 rounded-lg"
                             style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col items-center justify-center p-2 rounded min-w-[60px]"
                                     style="background-color: #131314; border: 1px solid rgba(58, 73, 75, 0.5);">
                                    <span class="font-mono-label text-[10px] uppercase tracking-wider mb-1 text-faint">Level</span>
                                    <span class="font-headline font-bold text-sm" style="color: {{ $levelColor }};">{{ $section }}</span>
                                </div>
                                <div>
                                    <h4 class="font-headline text-sm font-semibold mb-1" style="color: #e5e2e3;">
                                        Percobaan #{{ $sess->attempt_number }}
                                    </h4>
                                    <span class="font-mono-label text-[10px] uppercase tracking-wider flex items-center gap-1 text-soft">
                                        <span class="material-symbols-outlined" style="font-size: 12px;">calendar_today</span>
                                        {{ \Carbon\Carbon::parse($sess->waktu_mulai)->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </div>

                            <div class="text-right">
                                @if(!$sess->waktu_selesai)
                                    <div class="font-mono-label inline-flex items-center gap-1.5 text-[10px] font-medium uppercase tracking-wider px-2 py-1 rounded-full"
                                         style="background-color: rgba(250,204,21,0.15); color: #fde68a; border: 1px solid rgba(250,204,21,0.3);">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 animate-pulse"></span>
                                        Aktif
                                    </div>
                                @else
                                    <div class="font-mono-label inline-block text-[10px] font-medium uppercase tracking-wider px-2 py-1 rounded-full mb-1"
                                         style="{{ $sess->boss_kalah
                                            ? 'background-color: rgba(34,197,94,0.15); color: #86efac; border: 1px solid rgba(34,197,94,0.3);'
                                            : 'background-color: rgba(255,99,99,0.15); color: #ffb4ab; border: 1px solid rgba(255,99,99,0.3);' }}">
                                        {{ $sess->boss_kalah ? '✓ Berhasil' : '✗ Gagal' }}
                                    </div>
                                    <div class="font-mono-label text-[10px] uppercase tracking-wider text-soft">
                                        Akurasi {{ number_format($sess->skor_akhir, 0) }}%
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center w-full py-8 text-center rounded-lg"
                     style="border: 2px dashed rgba(58, 73, 75, 0.5);">
                    <span class="material-symbols-outlined text-4xl mb-2 text-faint">folder_open</span>
                    <p class="font-body font-medium text-soft">Belum ada riwayat percobaan.</p>
                    <p class="font-body text-sm text-faint">Bersiaplah untuk pertarungan pertamamu!</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Right: Boss Visual + Start --}}
    <div class="w-full md:w-1/2 md:h-screen flex items-center justify-center p-8 md:p-12 relative overflow-hidden"
         style="background: radial-gradient(ellipse at center, rgba(206, 93, 255, 0.08) 0%, rgba(10, 10, 11, 0) 60%);">

        {{-- Background glows --}}
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div class="w-96 h-96 rounded-full blur-3xl"
                 style="background: radial-gradient(circle, rgba(206,93,255,0.15), rgba(255,99,99,0.05) 60%, transparent 80%);"></div>
        </div>

        <div class="relative z-10 flex flex-col items-center text-center gap-8 w-full max-w-sm">

            {{-- Section Tag --}}
            <span class="font-mono-label inline-flex items-center gap-1.5 text-[10px] font-medium px-3 py-1 rounded-full uppercase tracking-widest"
                  style="background-color: rgba(255,99,99,0.15); color: #ffb4ab; border: 1px solid rgba(255,99,99,0.3);">
                <span class="material-symbols-outlined" style="font-size: 12px;">warning</span>
                Final Boss · {{ $section }}
            </span>

            {{-- Boss Avatar --}}
            <div class="animate-float animate-pulse-magenta w-44 h-44 rounded-full flex items-center justify-center overflow-hidden"
                 style="background: linear-gradient(135deg, rgba(206,93,255,0.25), rgba(255,99,99,0.25));
                        border: 4px solid rgba(206, 93, 255, 0.5);
                        box-shadow: 0 0 40px rgba(206, 93, 255, 0.3);">
                <img src="https://api.dicebear.com/9.x/bottts-neutral/svg?seed={{ urlencode($bossName) }}&backgroundColor=1a1b1e"
                     alt="{{ $bossName }}" class="w-full h-full object-cover opacity-95">
            </div>

            <div>
                <h2 class="font-headline text-3xl md:text-4xl font-extrabold mb-2"
                    style="background: linear-gradient(135deg, #ce5dff, #ff6b6b); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">
                    {{ $bossName }}
                </h2>
                <p class="font-body text-sm text-soft">
                    HP: <span class="font-headline font-bold" style="color: #ffb4ab;">{{ $levelConfig['boss_hp'] }}</span>
                    <span class="text-faint">·</span>
                    Kalahkan dengan menjawab benar!
                </p>
            </div>

            @if($bestSession)
                <div class="rounded-xl px-4 py-3 w-full text-center"
                     style="background-color: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3);">
                    <p class="font-headline text-sm font-bold flex items-center justify-center gap-1.5" style="color: #86efac;">
                        <span class="material-symbols-outlined" style="font-size: 16px;">military_tech</span>
                        Kamu sudah pernah menang!
                    </p>
                    <p class="font-mono-label text-xs uppercase tracking-wider text-soft mt-1">
                        Skor terbaik: {{ number_format($bestSession->skor_akhir, 0) }}%
                    </p>
                </div>
            @endif

            <button @click="showConfirmModal = true"
                    class="btn-boss font-headline w-full flex items-center justify-center gap-3 rounded-xl py-4 text-base font-bold hover:scale-[1.02] active:scale-95 transition-all">
                <span class="material-symbols-outlined">swords</span>
                Mulai Boss Battle
            </button>

            <p class="font-mono-label text-[11px] uppercase tracking-wider text-faint">
                Timer mulai begitu kamu konfirmasi. Siap?
            </p>
        </div>
    </div>

    {{-- Modal Konfirmasi Boss Battle --}}
    <div x-show="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showConfirmModal" @click="showConfirmModal = false" class="fixed inset-0 bg-black opacity-75"></div>

            <div x-show="showConfirmModal"
                 class="relative inline-block rounded-xl text-left overflow-hidden shadow-2xl max-w-md w-full"
                 style="background: rgba(25, 25, 28, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(206, 93, 255, 0.5); box-shadow: 0 0 40px rgba(206, 93, 255, 0.2);">

                <div class="px-6 pt-6 pb-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                             style="background: linear-gradient(135deg, rgba(206,93,255,0.2), rgba(255,99,99,0.2)); border: 1px solid rgba(206,93,255,0.4);">
                            <span class="material-symbols-outlined text-magenta-glow">swords</span>
                        </div>
                        <h3 class="font-headline text-lg font-bold" style="color: #e5e2e3;">Mulai Boss Battle?</h3>
                    </div>

                    <div class="rounded-lg p-4 mb-4"
                         style="background-color: rgba(32, 31, 32, 0.6); border: 1px solid rgba(58, 73, 75, 0.5);">
                        <p class="font-mono-label text-[10px] uppercase tracking-wider text-soft mb-1">Boss</p>
                        <p class="font-headline text-base font-bold mb-3" style="color: #e5e2e3;">{{ $bossName }}</p>

                        <div class="grid grid-cols-2 gap-3 pt-3" style="border-top: 1px solid rgba(58, 73, 75, 0.5);">
                            <div>
                                <p class="font-mono-label text-[10px] uppercase tracking-wider text-soft mb-1">HP Boss</p>
                                <p class="font-headline text-sm font-bold" style="color: #ffb4ab;">{{ $levelConfig['boss_hp'] }} HP</p>
                            </div>
                            <div>
                                <p class="font-mono-label text-[10px] uppercase tracking-wider text-soft mb-1">Percobaan ke-</p>
                                <p class="font-headline text-sm font-bold text-cyan-glow">#{{ $sessionHistory->count() + 1 }}</p>
                            </div>
                            <div>
                                <p class="font-mono-label text-[10px] uppercase tracking-wider text-soft mb-1">Durasi</p>
                                <p class="font-headline text-sm font-bold" style="color: #e5e2e3;">{{ $levelConfig['timer_minutes'] }} menit</p>
                            </div>
                            <div>
                                <p class="font-mono-label text-[10px] uppercase tracking-wider text-soft mb-1">Min. Benar</p>
                                <p class="font-headline text-sm font-bold" style="color: #e5e2e3;">{{ $levelConfig['min_correct'] }} soal</p>
                            </div>
                        </div>
                    </div>

                    <p class="font-body text-xs text-soft">
                        ⚔️ Timer akan langsung mulai setelah konfirmasi.
                    </p>
                </div>

                <div class="px-6 py-4 flex flex-row-reverse gap-2"
                     style="background-color: rgba(14, 14, 15, 0.8); border-top: 1px solid rgba(58, 73, 75, 0.5);">
                    <a href="{{ route('solo.battle.init', ['soloRaid' => $soloRaid->id, 'level' => $section]) }}"
                       class="btn-boss font-headline inline-flex justify-center rounded-lg px-5 py-2 text-sm font-bold">
                        Mulai Sekarang!
                    </a>
                    <button type="button" @click="showConfirmModal = false"
                            class="font-headline inline-flex justify-center rounded-lg px-5 py-2 text-sm font-medium transition-colors"
                            style="background-color: transparent; color: #b9cacb; border: 1px solid rgba(58, 73, 75, 0.5);"
                            onmouseover="this.style.backgroundColor='rgba(0,242,255,0.05)'; this.style.borderColor='rgba(0,242,255,0.3)';"
                            onmouseout="this.style.backgroundColor='transparent'; this.style.borderColor='rgba(58, 73, 75, 0.5)';">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
