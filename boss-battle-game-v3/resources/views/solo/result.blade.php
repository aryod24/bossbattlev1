<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result - {{ $session->soloRaid->type === 'learning' ? 'Latihan Soal' : $bossName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600;700&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Nunito', 'sans-serif'],
                        mono: ['"Fira Code"', 'monospace'],
                    },
                    colors: {
                        'vscode-bg': '#1e1e1e',
                        'vscode-card': '#252526',
                        'vscode-primary': '#007acc',
                        'vscode-text': '#d4d4d4',
                        'vscode-muted': '#858585',
                        'vscode-border': '#333333',
                        'vscode-string': '#ce9178',
                        'vscode-button': '#3c3c3c',
                        'vscode-button-hover': '#4c4c4c',
                        'boss-red': '#FF5252',
                        'success-green': '#4EC9B0',
                        'terminal-bg': '#1e1e1e',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #1e1e1e;
            background-image: linear-gradient(#333 1px, transparent 1px), linear-gradient(90deg, #333 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .terminal-window {
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }
        .typing-effect {
            border-right: 2px solid #d4d4d4;
            white-space: nowrap;
            overflow: hidden;
            animation: typing 3.5s steps(40, end), blink-caret .75s step-end infinite;
        }
        @keyframes typing { from { width: 0 } to { width: 100% } }
        @keyframes blink-caret { from, to { border-color: transparent } 50% { border-color: #d4d4d4; } }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 text-vscode-text font-mono" 
      x-data="{ 
          showRetryModal: false, 
          showClosedModal: false,
          isEventClosed: {{ ($session->soloRaid->status !== 'active' || ($session->soloRaid->tanggal_selesai && now()->gt($session->soloRaid->tanggal_selesai))) ? 'true' : 'false' }},
          showRewardModal: {{ (isset($battleResult) && (($battleResult['level_up']['leveled_up'] ?? false) || !empty($battleResult['new_badges'] ?? []))) ? 'true' : 'false' }} 
      }">

    <div class="w-full max-w-3xl terminal-window bg-vscode-card rounded-lg border border-vscode-border overflow-hidden relative">
        
        <!-- Terminal Header -->
        <div class="bg-vscode-border px-4 py-2 flex items-center justify-between border-b border-vscode-border">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-[#ff5f56]"></div>
                <div class="w-3 h-3 rounded-full bg-[#ffbd2e]"></div>
                <div class="w-3 h-3 rounded-full bg-[#27c93f]"></div>
            </div>
            <div class="text-xs text-vscode-muted">bash — 80x24</div>
            <div class="w-10"></div>
        </div>

        <!-- Terminal Body -->
        <div class="p-6 md:p-10 font-mono text-sm md:text-base leading-relaxed">
            
            <!-- Command Input -->
            <div class="flex gap-2 mb-6 text-vscode-muted">
                <span class="text-success-green">user@boss-battle</span>:<span class="text-vscode-primary">~/missions</span>$ <span class="text-vscode-text">./check_status.sh --session={{ $session->id }}</span>
            </div>

            <!-- Result Output -->
            <div class="mb-8">
                @if($session->soloRaid->type === 'learning')
                    <div class="mb-2">
                        <span class="text-vscode-muted">[INFO]</span> Evaluasi Latihan Soal selesai...
                    </div>
                    
                    @if($session->boss_kalah)
                        <div class="mt-6 mb-6 p-4 border-l-4 border-success-green bg-vscode-bg/50">
                            <h1 class="text-2xl md:text-3xl font-bold text-success-green mb-2">LULUS</h1>
                            <p class="text-vscode-text">Selamat! Anda telah memahami materi dengan baik.</p>
                        </div>
                    @else
                        <div class="mt-6 mb-6 p-4 border-l-4 border-boss-red bg-vscode-bg/50">
                            <h1 class="text-2xl md:text-3xl font-bold text-boss-red mb-2">TIDAK LULUS</h1>
                            <p class="text-vscode-text">Skor Anda belum memenuhi batas kelulusan. Silakan pelajari kembali materi.</p>
                        </div>
                    @endif
                @else
                    <div class="mb-2">
                        <span class="text-vscode-muted">[INFO]</span> Analyzing battle data...
                    </div>
                    <div class="mb-2">
                        <span class="text-vscode-muted">[INFO]</span> Target: <span class="text-vscode-string">'{{ $bossName }}'</span>
                    </div>
                    
                    @if($session->boss_kalah)
                        <div class="mt-6 mb-6 p-4 border-l-4 border-success-green bg-vscode-bg/50">
                            <h1 class="text-2xl md:text-3xl font-bold text-success-green mb-2">BUILD SUCCESSFUL</h1>
                            <p class="text-vscode-text">Target eliminated. Mission accomplished.</p>
                        </div>
                    @else
                        <div class="mt-6 mb-6 p-4 border-l-4 border-boss-red bg-vscode-bg/50">
                            <h1 class="text-2xl md:text-3xl font-bold text-boss-red mb-2">BUILD FAILED</h1>
                            <p class="text-vscode-text">Target remains active. Mission failed.</p>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 mb-8 text-vscode-text">
                <div class="flex justify-between border-b border-vscode-border pb-1 border-dashed">
                    <span class="text-vscode-muted">Attempt ID</span>
                    <span>#{{ $session->attempt_number }}</span>
                </div>
                <div class="flex justify-between border-b border-vscode-border pb-1 border-dashed">
                    <span class="text-vscode-muted">Duration</span>
                    <span>{{ gmdate("i:s", $session->durasi_detik) }}s</span>
                </div>
                <div class="flex justify-between border-b border-vscode-border pb-1 border-dashed">
                    <span class="text-vscode-muted">Accuracy</span>
                    <span class="{{ $session->skor_akhir >= 60 ? 'text-success-green' : 'text-boss-red' }}">
                        {{ number_format($session->skor_akhir, 1) }}%
                    </span>
                </div>
                <div class="flex justify-between border-b border-vscode-border pb-1 border-dashed">
                    <span class="text-vscode-muted">XP Gained</span>
                    <span class="text-vscode-primary">+{{ $session->xp_diperoleh }} XP</span>
                </div>
                 <div class="flex justify-between border-b border-vscode-border pb-1 border-dashed">
                    <span class="text-vscode-muted">Correct/Total</span>
                    <span>{{ $session->jumlah_benar }} / {{ $session->jumlah_soal }}</span>
                </div>
            </div>

            <!-- Inline Notifications Removed (Moved to Modal) -->

            <!-- Footer / Prompt -->
            <div class="mt-8 flex flex-col md:flex-row gap-4">
                <a href="{{ route('solo.map', $session->solo_raid_id) }}" class="group flex items-center justify-center gap-2 px-6 py-3 bg-vscode-primary hover:bg-vscode-primary-dark text-white rounded font-bold transition-all w-full md:w-auto">
                    <span>Kembali</span>
                </a>
                
                @if(!$session->boss_kalah)
                <button @click="isEventClosed ? showClosedModal = true : showRetryModal = true" class="group flex items-center justify-center gap-2 px-6 py-3 bg-vscode-button hover:bg-vscode-button-hover text-white rounded font-bold transition-all border border-vscode-border w-full md:w-auto">
                    <span>Ulangi</span>
                </button>
                @endif
            </div>

        </div>
    </div>

    <!-- Retry Confirmation Modal -->
    <div x-show="showRetryModal" 
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-vscode-card border border-vscode-border rounded-lg shadow-2xl w-full max-w-md overflow-hidden transform transition-all"
             @click.away="showRetryModal = false">
            
            <!-- Modal Header -->
            <div class="bg-vscode-border px-4 py-3 border-b border-vscode-border flex justify-between items-center">
                <h3 class="text-vscode-text font-bold flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-yellow-500"></i>
                    Konfirmasi Ulangi
                </h3>
                <button @click="showRetryModal = false" class="text-vscode-muted hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-vscode-text mb-4 leading-relaxed">
                    Apakah Anda yakin ingin mengulangi misi ini? <br>
                    <span class="text-vscode-muted text-sm">Pastikan Anda sudah siap!</span>
                </p>
                
                <div class="flex flex-col gap-3 mt-6">
                    <a href="{{ route('solo.battle.init', ['soloRaid' => $session->solo_raid_id, 'level' => $session->level]) }}" 
                       class="w-full text-center bg-vscode-primary hover:bg-vscode-primary-dark text-white font-bold py-2 px-4 rounded transition-colors">
                        Ya, Ulangi
                    </a>
                    <button @click="showRetryModal = false" 
                            class="w-full bg-vscode-button hover:bg-vscode-button-hover text-white py-2 px-4 rounded transition-colors border border-vscode-border">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Closed Modal -->
    <div x-show="showClosedModal" 
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-vscode-card border border-vscode-border rounded-lg shadow-2xl w-full max-w-md overflow-hidden transform transition-all"
             @click.away="showClosedModal = false">
            
            <!-- Modal Header -->
            <div class="bg-vscode-border px-4 py-3 border-b border-vscode-border flex justify-between items-center">
                <h3 class="text-vscode-text font-bold flex items-center gap-2">
                    <i class="fa-solid fa-lock text-boss-red"></i>
                    Event Berakhir
                </h3>
                <button @click="showClosedModal = false" class="text-vscode-muted hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 rounded-full bg-boss-red/10 flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-calendar-xmark text-3xl text-boss-red"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-2">Misi Ditutup</h4>
                    <p class="text-vscode-text text-sm leading-relaxed">
                        Maaf, periode event <span class="text-yellow-500 font-bold">{{ $session->soloRaid->nama }}</span> sudah berakhir. Anda tidak dapat mengulangi misi ini lagi.
                    </p>
                </div>
                
                <div class="flex flex-col gap-3">
                    <a href="{{ route('solo.index') }}" 
                       class="w-full text-center bg-vscode-primary hover:bg-vscode-primary-dark text-white font-bold py-2 px-4 rounded transition-colors">
                        Kembali ke Daftar Event
                    </a>
                    <button @click="showClosedModal = false" 
                            class="w-full bg-vscode-button hover:bg-vscode-button-hover text-white py-2 px-4 rounded transition-colors border border-vscode-border">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rewards/Level Up Modal -->
    @if(isset($battleResult) && ( ($battleResult['level_up']['leveled_up'] ?? false) || !empty($battleResult['new_badges'] ?? []) ))
    <div x-show="showRewardModal" 
         style="display: none;"
         class="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 scale-90 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-90 translate-y-4">
        
        <!-- VS Code Notification/Command Palette Style -->
        <div class="bg-vscode-card border border-vscode-border shadow-2xl w-full max-w-lg overflow-hidden relative" style="box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);">
            <!-- Accent Line (Top) -->
            <div class="h-1 w-full bg-yellow-500"></div>

            <!-- Header -->
            <div class="bg-vscode-bg px-6 py-4 border-b border-vscode-border flex justify-between items-center">
                <h3 class="text-white font-bold text-lg flex items-center gap-3 tracking-wide">
                    <span class="text-yellow-500"><i class="fa-solid fa-star"></i></span>
                    MISSION REWARDS
                </h3>
                <button @click="showRewardModal = false" class="text-vscode-muted hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-8 space-y-8">
                <!-- Level Up Section -->
                @if($battleResult['level_up']['leveled_up'] ?? false)
                <div class="text-center relative">
                    <div class="absolute inset-0 bg-yellow-500/5 blur-xl rounded-full"></div>
                    <div class="relative">
                        <div class="text-vscode-muted text-xs font-bold uppercase tracking-[0.3em] mb-2">Field Promotion</div>
                        <div class="text-5xl font-black text-white mb-3 drop-shadow-md font-mono">
                            LEVEL <span class="text-yellow-500">{{ $battleResult['level_up']['new_level'] }}</span>
                        </div>
                        <p class="text-vscode-text text-sm font-mono">Clearance level upgraded.</p>
                    </div>
                </div>
                @endif

                <!-- Badges Section -->
                @if(!empty($battleResult['new_badges']))
                <div class="{{ ($battleResult['level_up']['leveled_up'] ?? false) ? 'border-t border-dashed border-vscode-border pt-8' : '' }}">
                    <div class="text-center mb-6">
                        <span class="text-vscode-muted text-xs uppercase tracking-widest bg-vscode-bg border border-vscode-border inline-block px-3 py-1 rounded-sm">New Achievements</span>
                    </div>
                    
                    <div class="grid gap-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($battleResult['new_badges'] as $badge)
                        <div class="flex items-center gap-4 bg-vscode-bg p-3 rounded-sm border border-vscode-border hover:border-vscode-muted transition-colors group">
                            <div class="w-10 h-10 rounded bg-vscode-border flex items-center justify-center shrink-0 group-hover:bg-vscode-button-hover transition-colors">
                                <i class="{{ $badge->icon ?? 'fa-solid fa-medal' }} text-xl text-yellow-500"></i>
                            </div>
                            <div class="text-left flex-1">
                                <div class="font-bold text-vscode-text text-base group-hover:text-white transition-colors">{{ $badge->name }}</div>
                                <div class="text-xs text-vscode-muted leading-tight font-mono">{{ $badge->description }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <button @click="showRewardModal = false" class="w-full py-3 bg-vscode-primary hover:bg-vscode-primary/90 text-white font-bold text-base rounded-sm shadow-lg transition-all focus:ring-2 focus:ring-offset-2 focus:ring-vscode-primary focus:ring-offset-vscode-bg">
                    CLAIM REWARDS
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Alpine.js (Already included in head, but ensuring x-data works) -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('resultPage', () => ({
                showRetryModal: false
            }))
        })
    </script>
</body>
</html>
