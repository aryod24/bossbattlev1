<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Battle Result - {{ $bossName }}</title>
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
<body class="min-h-screen flex items-center justify-center p-4 text-vscode-text font-mono" x-data="{ showRetryModal: false }">

    <div class="w-full max-w-3xl terminal-window bg-vscode-card rounded-lg border border-vscode-border overflow-hidden relative">
        
        <!-- Terminal Header -->
        <div class="bg-[#333333] px-4 py-2 flex items-center justify-between border-b border-vscode-border">
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
                <div class="mb-2">
                    <span class="text-vscode-muted">[INFO]</span> Analyzing battle data...
                </div>
                <div class="mb-2">
                    <span class="text-vscode-muted">[INFO]</span> Target: <span class="text-[#ce9178]">'{{ $bossName }}'</span>
                </div>
                
                @if($session->boss_kalah)
                    <div class="mt-6 mb-6 p-4 border-l-4 border-success-green bg-[#1e1e1e]/50">
                        <h1 class="text-2xl md:text-3xl font-bold text-success-green mb-2">BUILD SUCCESSFUL</h1>
                        <p class="text-vscode-text">Target eliminated. Mission accomplished.</p>
                    </div>
                @else
                    <div class="mt-6 mb-6 p-4 border-l-4 border-boss-red bg-[#1e1e1e]/50">
                        <h1 class="text-2xl md:text-3xl font-bold text-boss-red mb-2">BUILD FAILED</h1>
                        <p class="text-vscode-text">Target remains active. Mission failed.</p>
                    </div>
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
                <div class="flex justify-between border-b border-vscode-border pb-1 border-dashed">
                    <span class="text-vscode-muted">Research Data</span>
                    <span>{{ $session->is_counted_research ? 'RECORDED' : 'IGNORED' }}</span>
                </div>
            </div>

            <!-- Footer / Prompt -->
            <div class="mt-10 flex flex-col md:flex-row gap-4">
                <a href="{{ route('solo.map', $session->solo_raid_id) }}" class="group flex items-center gap-2 px-5 py-2 bg-vscode-primary hover:bg-vscode-primary-dark text-white rounded-sm transition-all">
                    <i class="fa-solid fa-map"></i>
                    <span>cd ../map</span>
                </a>
                
                @if(!$session->boss_kalah)
                <button @click="showRetryModal = true" class="group flex items-center gap-2 px-5 py-2 bg-[#3c3c3c] hover:bg-[#4c4c4c] text-white rounded-sm transition-all border border-vscode-border">
                    <i class="fa-solid fa-rotate-right"></i>
                    <span>./retry_mission.sh</span>
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
            <div class="bg-[#333333] px-4 py-3 border-b border-vscode-border flex justify-between items-center">
                <h3 class="text-vscode-text font-bold flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-yellow-500"></i>
                    Confirm Retry
                </h3>
                <button @click="showRetryModal = false" class="text-vscode-muted hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-vscode-text mb-4 leading-relaxed">
                    Are you sure you want to retry? <br>
                    <span class="text-vscode-muted text-sm">Atau sebaiknya anda review materi terlebih dahulu?</span>
                </p>
                
                <div class="flex flex-col gap-3 mt-6">
                    <a href="{{ route('solo.battle.init', ['soloRaid' => $session->solo_raid_id, 'level' => $session->level]) }}" 
                       class="w-full text-center bg-vscode-primary hover:bg-vscode-primary-dark text-white font-bold py-2 px-4 rounded-sm transition-colors">
                        Yes, Retry Mission
                    </a>
                    <button @click="showRetryModal = false" 
                            class="w-full bg-[#3c3c3c] hover:bg-[#4c4c4c] text-white py-2 px-4 rounded-sm transition-colors border border-vscode-border">
                        Cancel (Review Material)
                    </button>
                </div>
            </div>
        </div>
    </div>

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
