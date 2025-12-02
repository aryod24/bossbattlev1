<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $soloRaid->nama }} - Dungeon Map</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { 
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .glass-panel {
            background: rgba(30, 41, 59, 0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }

        .path-dash {
            stroke-dasharray: 12;
            animation: dash 40s linear infinite;
        }
        @keyframes dash {
            to { stroke-dashoffset: -1000; }
        }

        .node {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .node:hover {
            transform: scale(1.05);
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        game: {
                            dark: '#0f172a',
                            darker: '#020617',
                            panel: '#1e293b',
                            gold: '#f59e0b',
                            green: '#10b981',
                            red: '#ef4444',
                            teal: '#14b8a6',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body x-data="dungeonMap({{ $soloRaid->id }})">
    <div class="flex flex-col md:flex-row min-h-screen">
        
        <!-- Left Section: Info Panel (Light Theme) -->
        <div class="w-full md:w-1/2 bg-white p-6 md:p-12 flex flex-col justify-center">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold">▲</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">CodeBossArena</h2>
                </div>
                <a href="{{ route('solo.index') }}" class="inline-block bg-yellow-400 hover:bg-yellow-500 px-6 py-2 rounded-full font-semibold transition-colors text-gray-900">
                    ← Back to List
                </a>
            </div>

            <!-- Raid Title -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ $soloRaid->nama }}
                </h1>
                <p class="text-gray-600 text-base md:text-lg leading-relaxed">
                    {{ $soloRaid->deskripsi }}
                </p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-700 font-semibold">Raid Progress</span>
                    <span class="text-gray-600 font-medium">0/6 Completed</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 h-full rounded-full" style="width: 0%"></div>
                </div>
            </div>

            <!-- Next Challenge Card -->
            <div class="bg-gray-50 rounded-2xl p-6 border-2 border-gray-200">
                <h3 class="text-gray-700 font-semibold mb-4">Your Next Challenge</h3>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-teal-400 to-teal-500 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-3xl">menu_book</span>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-900">Info Node 1</h4>
                        <p class="text-gray-600 text-sm">Click to read study material</p>
                    </div>
                </div>
                <button @click="openInfo(1)" class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-3 rounded-xl transition-colors">
                    Read Material
                </button>
            </div>

            <!-- Raid Info -->
            <div class="mt-6 text-sm text-gray-500">
                <p><strong>Period:</strong> {{ $soloRaid->tanggal_mulai }} - {{ $soloRaid->tanggal_selesai }}</p>
                <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-800">{{ strtoupper($soloRaid->status) }}</span></p>
            </div>
        </div>

        <!-- Right Section: Vertical Map (Dark Theme) -->
        <div class="w-full md:w-1/2 bg-game-darker text-white p-6 md:p-12 flex items-center justify-center relative overflow-hidden min-h-screen">
            <div class="w-full max-w-md relative z-10 flex flex-col items-center">
                
                <!-- Header -->
                <div class="text-center mb-6 w-full">
                    <h1 class="text-xl font-extrabold uppercase tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-game-gold to-yellow-200 mb-2">
                        Solo Raid Event
                    </h1>
                    <div class="flex justify-center items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-game-green/20 text-game-green border border-game-green/30">ACTIVE</span>
                    </div>
                </div>

                <!-- Map Container -->
                <div class="relative w-[360px] h-[580px]">
                    
                    <!-- Background Path (SVG) -->
                    <svg class="absolute top-0 left-0 w-full h-full z-0 pointer-events-none" viewBox="0 0 360 580" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="gradientPath" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#14b8a6" />
                                <stop offset="33%" stop-color="#10b981" />
                                <stop offset="66%" stop-color="#f59e0b" />
                                <stop offset="100%" stop-color="#ef4444" />
                            </linearGradient>
                            <filter id="glow">
                                <feGaussianBlur stdDeviation="2.5" result="coloredBlur"/>
                                <feMerge>
                                    <feMergeNode in="coloredBlur"/>
                                    <feMergeNode in="SourceGraphic"/>
                                </feMerge>
                            </filter>
                        </defs>
                        
                        <path d="
                            M 45,40 
                            C 150,40 180,85 240,120 
                            C 315,165 315,115 315,155
                            C 315,200 240,225 180,250
                            C 45,295 45,245 45,270
                            C 45,315 120,340 180,365
                            C 315,410 315,360 315,390
                            C 315,430 240,455 180,480
                            C 45,525 45,475 45,500
                            C 45,540 120,560 180,580
                            C 315,625 315,575 315,600
                        " 
                        stroke="url(#gradientPath)" 
                        stroke-width="4" 
                        fill="none" 
                        stroke-linecap="round"
                        class="path-dash opacity-50"
                        filter="url(#glow)" />
                    </svg>

                    <!-- NODE 1: Info 1 (Top-Left) -->
                    <div class="absolute w-full" style="top: 20px; left: 13px;">
                        <div class="flex items-start gap-3 group node" @click="openInfo(1)">
                            <div class="relative z-10 w-16 h-16 rounded-full bg-game-panel border-2 border-game-teal flex items-center justify-center shadow-[0_0_15px_rgba(20,184,166,0.4)]">
                                <span class="material-symbols-outlined text-game-teal text-2xl">menu_book</span>
                            </div>
                            <div class="glass-panel p-2 rounded-lg border-l-4 border-l-game-teal mt-1 max-w-[180px]">
                                <p class="text-[10px] text-game-teal font-bold uppercase tracking-wide">Info Node</p>
                                <p class="text-sm font-bold text-white leading-tight">Study Material 1</p>
                            </div>
                        </div>
                    </div>

                    <!-- NODE 2: Easy Level (Top-Right) -->
                    <div class="absolute w-full flex justify-end" style="top: 130px; right: 13px;">
                        <div class="flex flex-row-reverse items-center gap-3 node" @click="checkLevel('easy')">
                            <div class="relative z-10 w-16 h-16 rounded-full bg-game-panel border-2 flex items-center justify-center shadow-[0_0_15px_rgba(16,185,129,0.4)] overflow-hidden"
                                 :class="levels.easy.available ? 'border-game-green' : 'border-slate-600 grayscale opacity-70'">
                                <i class="fa-solid fa-dragon text-2xl" :class="levels.easy.available ? 'text-game-green' : 'text-slate-500'"></i>
                                <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center" x-show="!levels.easy.available">
                                    <i class="fa-solid fa-lock text-white/50 text-lg"></i>
                                </div>
                            </div>
                            <div class="glass-panel p-2 rounded-lg border-r-4 text-right max-w-[150px]"
                                 :class="levels.easy.available ? 'border-r-game-green' : 'border-r-slate-600'">
                                <p class="text-[10px] font-bold uppercase" :class="levels.easy.available ? 'text-game-green' : 'text-slate-500'">Easy</p>
                                <p class="text-sm font-bold text-white">{{ $soloRaid->boss_easy_name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- NODE 3: Info 2 (Mid-Left) -->
                    <div class="absolute w-full" style="top: 240px; left: 13px;">
                        <div class="flex items-center gap-4 node" @click="openInfo(2)">
                            <div class="relative z-10 w-16 h-16 rounded-full bg-game-panel border-2 border-game-teal flex items-center justify-center shadow-[0_0_15px_rgba(20,184,166,0.4)]">
                                <span class="material-symbols-outlined text-game-teal text-2xl">menu_book</span>
                            </div>
                            <div class="glass-panel p-2 rounded-lg border-l-4 border-l-game-teal max-w-[180px]">
                                <p class="text-[10px] text-game-teal font-bold uppercase">Info Node</p>
                                <p class="text-sm font-bold text-white">Study Material 2</p>
                            </div>
                        </div>
                    </div>

                    <!-- NODE 4: Medium Level (Mid-Right) -->
                    <div class="absolute w-full flex justify-end" style="top: 350px; right: 13px;">
                        <div class="flex flex-row-reverse items-center gap-3 node" @click="checkLevel('medium')">
                            <div class="relative z-10 w-16 h-16 rounded-full bg-game-panel border-2 flex items-center justify-center shadow-[0_0_15px_rgba(245,158,11,0.4)]"
                                 :class="levels.medium.available ? 'border-game-gold' : 'border-slate-600 grayscale opacity-70'">
                                <i class="fa-solid fa-fire-flame-curved text-2xl" :class="levels.medium.available ? 'text-game-gold' : 'text-slate-500'"></i>
                                <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center" x-show="!levels.medium.available">
                                    <i class="fa-solid fa-lock text-white/50 text-lg"></i>
                                </div>
                            </div>
                            <div class="glass-panel p-2 rounded-lg border-r-4 text-right max-w-[150px]"
                                 :class="levels.medium.available ? 'border-r-game-gold' : 'border-r-slate-600'">
                                <p class="text-[10px] font-bold uppercase" :class="levels.medium.available ? 'text-game-gold' : 'text-slate-500'">Medium</p>
                                <p class="text-sm font-bold text-white">{{ $soloRaid->boss_medium_name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- NODE 5: Info 3 (Lower-Left) -->
                    <div class="absolute w-full" style="top: 460px; left: 13px;">
                        <div class="flex items-center gap-3 node" @click="openInfo(3)">
                            <div class="relative z-10 w-16 h-16 rounded-full bg-game-panel border-2 border-game-teal flex items-center justify-center shadow-[0_0_15px_rgba(20,184,166,0.4)]">
                                <span class="material-symbols-outlined text-game-teal text-2xl">menu_book</span>
                            </div>
                            <div class="glass-panel p-2 rounded-lg border-l-4 border-l-game-teal max-w-[180px]">
                                <p class="text-[10px] text-game-teal font-bold uppercase">Info Node</p>
                                <p class="text-sm font-bold text-white">Study Material 3</p>
                            </div>
                        </div>
                    </div>

                    <!-- NODE 6: Hard Level (Bottom-Right) -->
                    <div class="absolute w-full flex justify-end" style="top: 540px; right: 13px;">
                        <div class="flex flex-row-reverse items-center gap-4 node" @click="checkLevel('hard')"
                             :class="levels.hard.available ? '' : 'opacity-60'">
                            <div class="relative z-10 w-20 h-20 rounded-full bg-game-panel border-4 flex items-center justify-center"
                                 :class="levels.hard.available ? 'border-game-red shadow-[0_0_20px_rgba(239,68,68,0.6)]' : 'border-red-900/30'">
                                <i class="fa-solid fa-skull text-3xl" :class="levels.hard.available ? 'text-game-red' : 'text-slate-600'"></i>
                                <div class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center" x-show="!levels.hard.available">
                                    <i class="fa-solid fa-lock text-red-900/50 text-2xl"></i>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold uppercase tracking-widest" :class="levels.hard.available ? 'text-game-red' : 'text-red-900'">FINAL BOSS</p>
                                <p class="text-lg font-bold" :class="levels.hard.available ? 'text-white' : 'text-slate-500'">{{ $soloRaid->boss_hard_name }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Legend -->
                <div class="mt-20 w-full bg-game-panel/50 backdrop-blur-lg border border-white/5 rounded-xl p-4">
                    <div class="flex justify-center gap-8 text-[10px] uppercase font-bold tracking-wide text-slate-400">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 bg-game-green rounded-full shadow-[0_0_8px_#10b981]"></span> Available
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 bg-slate-600 rounded-full"></span> Locked
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Info Modal -->
    <div x-show="showInfoModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showInfoModal" @click="showInfoModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showInfoModal" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-teal-100 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="material-symbols-outlined text-teal-600">menu_book</span>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" x-text="infoTitle"></h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 whitespace-pre-line" x-text="infoContent"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showInfoModal = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Got it
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dungeonMap', (raidId) => ({
                raidId: raidId,
                levels: {
                    easy: { available: false },
                    medium: { available: false },
                    hard: { available: false }
                },
                showInfoModal: false,
                infoTitle: '',
                infoContent: '',

                init() {
                    this.fetchLevels();
                },

                fetchLevels() {
                    fetch(`/solo/${this.raidId}/level-select`)
                        .then(res => res.json())
                        .then(data => {
                            this.levels = data;
                        });
                },

                openInfo(nodeId) {
                    fetch(`/solo/${this.raidId}/info/${nodeId}`)
                        .then(res => res.json())
                        .then(data => {
                            this.infoTitle = data.title;
                            this.infoContent = data.content;
                            this.showInfoModal = true;
                        });
                },

                checkLevel(level) {
                    if (this.levels[level].available) {
                        alert(`Starting ${level} level... (Gameplay implementation in next prompt)`);
                    } else {
                        alert('This level is currently locked or unavailable.');
                    }
                }
            }))
        })
    </script>
</body>
</html>
