<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $soloRaid->nama }}</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Hanken+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Markdown & Code Highlighting -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/python.min.js"></script>
    
    <!-- Tailwind & Alpine via Vite (production build, no JIT in browser) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { 
            font-family: 'Hanken Grotesk', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #0A0A0B;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.05'/%3E%3C/svg%3E");
            color: #e5e2e3;
        }

        /* === Cyber-noir typography & shared components (matches dashboard/profile) === */
        .font-headline { font-family: 'Sora', sans-serif; }
        .font-body { font-family: 'Hanken Grotesk', sans-serif; }
        .font-mono-label { font-family: 'JetBrains Mono', monospace; }

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

        .btn-cyber-primary {
            background: linear-gradient(135deg, #00f2ff, #ce5dff);
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }
        .btn-cyber-primary:hover { box-shadow: 0 0 20px rgba(0, 242, 255, 0.6); }

        .progress-bar-fill { background: linear-gradient(90deg, #00f2ff, #ce5dff); }
        .progress-glow-tip { box-shadow: 0 0 10px #ffffff; }
        .divider-soft { border-top: 1px solid rgba(58, 73, 75, 0.5); }

        .custom-scrollbar::-webkit-scrollbar { width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(25,25,28,0.4); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,242,255,0.2); border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(0,242,255,0.4); }

        .glass-panel {
            background: rgba(25, 25, 28, 0.7);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 242, 255, 0.2);
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
        
        /* Prose styling for markdown content */
        .prose {
            color: #d4d4d4;
            max-width: none;
        }
        .prose h1 { font-size: 2em; font-weight: 800; color: #007acc; margin-top: 0; margin-bottom: 0.5em; }
        .prose h2 { font-size: 1.5em; font-weight: 700; color: #007acc; margin-top: 1.5em; margin-bottom: 0.5em; }
        .prose h3 { font-size: 1.25em; font-weight: 600; color: #4ec9b0; margin-top: 1.25em; margin-bottom: 0.5em; }
        .prose h4 { font-size: 1.1em; font-weight: 600; color: #9cdcfe; margin-top: 1em; margin-bottom: 0.5em; }
        
        .prose p { margin-top: 0.75em; margin-bottom: 0.75em; line-height: 1.7; }
        .prose strong { color: #dcdcaa; font-weight: 600; }
        .prose em { color: #ce9178; font-style: italic; }
        
        .prose code {
            background: #2d2d2d;
            color: #ce9178;
            padding: 0.2em 0.4em;
            border-radius: 3px;
            font-size: 0.9em;
            font-family: 'Consolas', 'Monaco', monospace;
        }
        
        .prose pre {
            background: #1e1e1e;
            border: 1px solid #333;
            border-radius: 6px;
            padding: 1em;
            overflow-x: auto;
            margin: 1em 0;
        }
        
        .prose pre code {
            background: transparent;
            padding: 0;
            color: #d4d4d4;
            font-size: 0.875em;
        }
        
        .prose blockquote {
            border-left: 4px solid #007acc;
            background: #252526;
            padding: 0.5em 1em;
            margin: 1em 0;
            font-style: italic;
            color: #858585;
        }
        
        .prose ul, .prose ol {
            margin: 0.75em 0;
            padding-left: 1.5em;
        }
        
        .prose li {
            margin: 0.5em 0;
        }
        
        .prose table {
            border-collapse: collapse;
            width: 100%;
            margin: 1em 0;
        }
        
        .prose th, .prose td {
            border: 1px solid #333;
            padding: 0.5em;
            text-align: left;
        }
        
        .prose th {
            background: #2d2d2d;
            color: #007acc;
            font-weight: 600;
        }
        
        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            margin: 1em 0;
        }
        
        .prose a {
            color: #007acc;
            text-decoration: none;
        }
        
        .prose a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body x-data="dungeonMap({{ $soloRaid->id }})" class="md:h-screen md:overflow-hidden">
    <div class="flex flex-col md:flex-row md:h-screen">
        
        <!-- Left Section: Info Panel (cyber-noir, scrolls internally on desktop) -->
        <x-solo.map-info-panel :solo-raid="$soloRaid" :stats="$userStats" :sessions="$sessionHistory" :active-session="$activeSession" :nodes="$nodes" :completed-node-ids="$completedNodeIds" />
        <x-solo.map-visual :solo-raid="$soloRaid" :nodes="$nodes" :completed-node-ids="$completedNodeIds" />
    </div>

    <!-- Materi Modal Component -->
    <x-solo.materi-modal />

    <!-- Modal Badge Unlocked -->
    <div x-show="showBadgeModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showBadgeModal" class="fixed inset-0 bg-black opacity-80"></div>

            <div x-show="showBadgeModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative inline-block rounded-2xl text-center overflow-hidden shadow-2xl max-w-sm w-full"
                 style="background: rgba(25, 25, 28, 0.97); backdrop-filter: blur(20px); border: 1px solid rgba(250, 204, 21, 0.55); box-shadow: 0 0 60px rgba(250, 204, 21, 0.35);">

                <template x-if="currentBadge">
                    <div class="p-8">
                        <p class="font-mono-label text-[11px] uppercase tracking-[0.2em] mb-4" style="color: #facc15;">Badge Unlocked</p>

                        <div class="flex items-center justify-center mb-5">
                            <div class="w-24 h-24 rounded-full flex items-center justify-center text-5xl animate-float"
                                 style="background: linear-gradient(135deg, rgba(250,204,21,0.25), rgba(206,93,255,0.18)); border: 2px solid rgba(250,204,21,0.6); box-shadow: 0 0 30px rgba(250,204,21,0.45);">
                                <span x-text="currentBadge.emoji || '🏆'"></span>
                            </div>
                        </div>

                        <h3 class="font-headline text-2xl font-bold mb-2" style="color: #e5e2e3;" x-text="currentBadge.name"></h3>
                        <p class="font-body text-sm text-soft mb-6" x-text="currentBadge.description"></p>

                        <div class="flex items-center justify-center gap-2 text-xs text-faint mb-5"
                             x-show="newBadgesQueue.length > 1">
                            <span x-text="(currentBadgeIndex + 1) + ' / ' + newBadgesQueue.length"></span>
                        </div>

                        <button type="button" @click="dismissBadge()"
                                class="btn-cyber-primary font-headline w-full inline-flex justify-center rounded-lg px-5 py-2.5 text-sm font-bold">
                            <span x-text="currentBadgeIndex < newBadgesQueue.length - 1 ? 'Selanjutnya' : 'Mantap!'"></span>
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Mulai Latihan -->
    <div x-show="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showConfirmModal" @click="showConfirmModal = false" class="fixed inset-0 bg-black opacity-75"></div>

            <div x-show="showConfirmModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="relative inline-block rounded-xl text-left overflow-hidden shadow-2xl max-w-md w-full"
                 style="background: rgba(25, 25, 28, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(0, 242, 255, 0.4); box-shadow: 0 0 40px rgba(0, 242, 255, 0.18);">
                <div class="px-6 pt-6 pb-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                             style="background: linear-gradient(135deg, rgba(0,242,255,0.2), rgba(206,93,255,0.15)); border: 1px solid rgba(0, 242, 255, 0.4);">
                            <span class="material-symbols-outlined text-cyan-glow">quiz</span>
                        </div>
                        <h3 class="font-headline text-lg font-bold" style="color: #e5e2e3;">Mulai Latihan Soal?</h3>
                    </div>

                    <div class="rounded-lg p-4 mb-4"
                         style="background-color: rgba(32, 31, 32, 0.6); border: 1px solid rgba(58, 73, 75, 0.5);">
                        <p class="font-mono-label text-[10px] uppercase tracking-wider text-soft mb-1">Event</p>
                        <p class="font-headline text-base font-bold mb-3" style="color: #e5e2e3;">{{ $soloRaid->nama }}</p>

                        <div class="pt-3 grid grid-cols-2 gap-3" style="border-top: 1px solid rgba(58, 73, 75, 0.5);">
                            <div>
                                <p class="font-mono-label text-[10px] uppercase tracking-wider text-soft mb-1">Jumlah Soal</p>
                                <p class="font-headline text-sm font-bold" style="color: #e5e2e3;">{{ $levelConfig['questions'] }} soal</p>
                            </div>
                            <div>
                                <p class="font-mono-label text-[10px] uppercase tracking-wider text-soft mb-1">Batas Lulus</p>
                                <p class="font-headline text-sm font-bold text-cyan-glow">≥ {{ $levelConfig['min_correct'] }} benar</p>
                            </div>
                            <div>
                                <p class="font-mono-label text-[10px] uppercase tracking-wider text-soft mb-1">Durasi</p>
                                <p class="font-headline text-sm font-bold" style="color: #e5e2e3;">{{ $levelConfig['timer_minutes'] }} menit</p>
                            </div>
                            <div>
                                <p class="font-mono-label text-[10px] uppercase tracking-wider text-soft mb-1">Percobaan ke-</p>
                                <p class="font-headline text-sm font-bold text-cyan-glow">#{{ $userStats['attempts'] + 1 }}</p>
                            </div>
                        </div>
                    </div>

                    <p class="font-body text-xs text-soft">
                        ⏱️ Timer akan langsung mulai setelah konfirmasi. Untuk lulus, jawab minimal
                        <strong class="text-cyan-glow">{{ $levelConfig['min_correct'] }} dari {{ $levelConfig['questions'] }}</strong> soal dengan benar.
                    </p>
                </div>

                <div class="px-6 py-4 flex flex-row-reverse gap-2"
                     style="background-color: rgba(14, 14, 15, 0.8); border-top: 1px solid rgba(58, 73, 75, 0.5);">
                    <button type="button" @click="doStartLatihan()"
                            class="btn-cyber-primary font-headline inline-flex justify-center rounded-lg px-5 py-2 text-sm font-bold">
                        Mulai Sekarang
                    </button>
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

    <!-- Modal Sesi Aktif -->
    <div x-show="showActiveSessionModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showActiveSessionModal" @click="showActiveSessionModal = false" class="fixed inset-0 bg-black opacity-75"></div>

            <div x-show="showActiveSessionModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="relative inline-block rounded-xl text-left overflow-hidden shadow-2xl max-w-md w-full"
                 style="background: rgba(25, 25, 28, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(250, 204, 21, 0.45); box-shadow: 0 0 40px rgba(250, 204, 21, 0.15);">
                <div class="px-6 pt-6 pb-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                             style="background-color: rgba(250, 204, 21, 0.15); border: 1px solid rgba(250, 204, 21, 0.4);">
                            <span class="material-symbols-outlined" style="color: #fde68a;">warning</span>
                        </div>
                        <h3 class="font-headline text-lg font-bold" style="color: #e5e2e3;">Sesi Aktif Terdeteksi</h3>
                    </div>

                    <div class="rounded-lg p-4 mb-4"
                         style="background-color: rgba(250, 204, 21, 0.08); border: 1px solid rgba(250, 204, 21, 0.3);">
                        <p class="font-body text-sm text-soft mb-1">Kamu masih punya sesi yang sedang berjalan di:</p>
                        <p class="font-headline text-sm font-bold" style="color: #fde68a;"
                           x-text="activeSession ? activeSession.solo_raid_id + ' — Level ' + (activeSession.level || '-') : ''"></p>
                    </div>

                    <p class="font-body text-xs text-soft">
                        Selesaikan sesi tersebut terlebih dahulu, atau lanjutkan dari sini.
                    </p>
                </div>

                <div class="px-6 py-4 flex flex-row-reverse gap-2"
                     style="background-color: rgba(14, 14, 15, 0.8); border-top: 1px solid rgba(58, 73, 75, 0.5);">
                    <button type="button" @click="continueActiveSession()"
                            class="font-headline inline-flex justify-center rounded-lg px-5 py-2 text-sm font-bold transition-all"
                            style="background: linear-gradient(135deg, #fde68a, #fbbf24); color: #1a1a1a; text-shadow: 0 1px 1px rgba(255,255,255,0.2);"
                            onmouseover="this.style.boxShadow='0 0 18px rgba(250,204,21,0.5)';"
                            onmouseout="this.style.boxShadow='none';">
                        Lanjutkan Sesi
                    </button>
                    <button type="button" @click="showActiveSessionModal = false"
                            class="font-headline inline-flex justify-center rounded-lg px-5 py-2 text-sm font-medium transition-colors"
                            style="background-color: transparent; color: #b9cacb; border: 1px solid rgba(58, 73, 75, 0.5);"
                            onmouseover="this.style.backgroundColor='rgba(0,242,255,0.05)'; this.style.borderColor='rgba(0,242,255,0.3)';"
                            onmouseout="this.style.backgroundColor='transparent'; this.style.borderColor='rgba(58, 73, 75, 0.5)';">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Boss Battle -->
    <div x-show="showBossConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showBossConfirmModal" @click="showBossConfirmModal = false" class="fixed inset-0 bg-black opacity-75"></div>

            <div x-show="showBossConfirmModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="relative inline-block rounded-xl text-left overflow-hidden shadow-2xl max-w-md w-full"
                 style="background: rgba(25, 25, 28, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(206, 93, 255, 0.5); box-shadow: 0 0 40px rgba(206, 93, 255, 0.2);">
                <div class="px-6 pt-6 pb-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                             style="background: linear-gradient(135deg, rgba(206, 93, 255, 0.2), rgba(255, 99, 99, 0.15)); border: 1px solid rgba(206, 93, 255, 0.4);">
                            <span class="material-symbols-outlined text-magenta-glow">swords</span>
                        </div>
                        <h3 class="font-headline text-lg font-bold" style="color: #e5e2e3;">Mulai Boss Battle?</h3>
                    </div>

                    <div class="rounded-lg p-4 mb-4"
                         style="background-color: rgba(32, 31, 32, 0.6); border: 1px solid rgba(58, 73, 75, 0.5);">
                        <p class="font-mono-label text-[10px] uppercase tracking-wider text-soft mb-1">Event</p>
                        <p class="font-headline text-base font-bold mb-3" style="color: #e5e2e3;">{{ $soloRaid->nama }}</p>
                        <div class="pt-3" style="border-top: 1px solid rgba(58, 73, 75, 0.5);">
                            <p class="font-mono-label text-[10px] uppercase tracking-wider text-soft mb-1">Percobaan ke-</p>
                            <p class="font-headline text-sm font-bold text-magenta-glow">#{{ $userStats['attempts'] + 1 }}</p>
                        </div>
                    </div>

                    <p class="font-body text-xs text-soft">
                        ⚔️ Timer akan langsung mulai setelah konfirmasi. Siap?
                    </p>
                </div>

                <div class="px-6 py-4 flex flex-row-reverse gap-2"
                     style="background-color: rgba(14, 14, 15, 0.8); border-top: 1px solid rgba(58, 73, 75, 0.5);">
                    <button type="button" @click="doStartBoss()"
                            class="font-headline inline-flex justify-center rounded-lg px-5 py-2 text-sm font-bold transition-all"
                            style="background: linear-gradient(135deg, #ce5dff, #ff6b6b); color: #ffffff; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);"
                            onmouseover="this.style.boxShadow='0 0 24px rgba(206, 93, 255, 0.55)';"
                            onmouseout="this.style.boxShadow='none';">
                        Mulai Sekarang!
                    </button>
                    <button type="button" @click="showBossConfirmModal = false"
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

    <script>
        // Configure marked.js with highlight.js before Alpine init
        marked.setOptions({
            highlight: function(code, lang) {
                if (lang && hljs.getLanguage(lang)) {
                    try {
                        return hljs.highlight(code, { language: lang }).value;
                    } catch (e) {}
                }
                return hljs.highlightAuto(code).value;
            },
            breaks: true,
            gfm: true
        });
        
        document.addEventListener('alpine:init', () => {
            Alpine.data('dungeonMap', (raidId) => ({
                raidId: raidId,
                // Node state
                completedNodeIds: @json($completedNodeIds),
                currentNodeId: null,
                isMarkingDone: false,
                isNodeAlreadyDone: false,
                // Modal state
                showInfoModal: false,
                infoTitle: '',
                infoContent: '',
                renderedContent: '',
                // Badge unlock modal state
                showBadgeModal: false,
                newBadgesQueue: [],
                currentBadgeIndex: 0,
                get currentBadge() {
                    return this.newBadgesQueue[this.currentBadgeIndex] ?? null;
                },
                // Modal & battle state
                showConfirmModal: false,
                showBossConfirmModal: false,
                showActiveSessionModal: false,
                activeSession: @json($activeSession),
                currentRaidId: {{ $soloRaid->id }},
                // Quiz level — always use the raid's section for learning events
                raidSection: '{{ $soloRaid->section ?? "Easy" }}',
                userSection: '{{ auth()->user()->current_section ?? "Easy" }}',

                init() {
                    window.addEventListener('pageshow', (event) => {
                        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                            window.location.reload();
                        }
                    });
                },

                openInfo(nodeOrder) {
                    fetch(`/solo/${this.raidId}/materi/${nodeOrder}`)
                        .then(res => res.json())
                        .then(data => {
                            this.currentNodeId = data.id;
                            this.infoTitle = data.title;
                            this.renderedContent = marked.parse(data.content || 'Belum ada materi.');
                            this.isNodeAlreadyDone = this.completedNodeIds.includes(data.id);
                            this.showInfoModal = true;
                            this.isMarkingDone = false;
                        })
                        .catch(err => {
                            console.error('Error loading materi:', err);
                            this.infoTitle = 'Error';
                            this.renderedContent = '<p class="text-error">Gagal memuat materi. Silakan coba lagi.</p>';
                            this.showInfoModal = true;
                        });
                },

                markNodeDone() {
                    if (!this.currentNodeId || this.isMarkingDone) return;
                    this.isMarkingDone = true;

                    fetch(`/solo/node/${this.currentNodeId}/complete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Update local state so next node unlocks without reload
                            if (!this.completedNodeIds.includes(this.currentNodeId)) {
                                this.completedNodeIds.push(this.currentNodeId);
                            }
                        }
                        this.showInfoModal = false;
                        this.isMarkingDone = false;

                        const newBadges = Array.isArray(data.new_badges) ? data.new_badges : [];
                        if (newBadges.length > 0) {
                            // Tunda reload sampai user selesai melihat badge popup
                            this.newBadgesQueue = newBadges;
                            this.currentBadgeIndex = 0;
                            this.showBadgeModal = true;
                            return;
                        }

                        window.location.reload();
                    })
                    .catch(() => {
                        this.isMarkingDone = false;
                    });
                },

                dismissBadge() {
                    if (this.currentBadgeIndex < this.newBadgesQueue.length - 1) {
                        this.currentBadgeIndex++;
                        return;
                    }
                    this.showBadgeModal = false;
                    window.location.reload();
                },

                startLatihan() {
                    if (this.activeSession && this.activeSession.solo_raid_id != this.currentRaidId) {
                        this.showActiveSessionModal = true;
                        return;
                    }
                    if (this.activeSession && this.activeSession.solo_raid_id == this.currentRaidId) {
                        this.continueActiveSession();
                        return;
                    }
                    this.showConfirmModal = true;
                },

                doStartLatihan() {
                    this.showConfirmModal = false;
                    // Use raid's section level — NOT the user's adaptive level
                    window.location.href = `/solo/${this.raidId}/battle/init/${this.raidSection}`;
                },

                startBoss() {
                    if (this.activeSession && this.activeSession.solo_raid_id != this.currentRaidId) {
                        this.showActiveSessionModal = true;
                        return;
                    }
                    if (this.activeSession && this.activeSession.solo_raid_id == this.currentRaidId) {
                        this.continueActiveSession();
                        return;
                    }
                    this.showBossConfirmModal = true;
                },

                doStartBoss() {
                    this.showBossConfirmModal = false;
                    window.location.href = `/solo/${this.raidId}/battle/init/${this.userSection}`;
                },

                continueActiveSession() {
                    if (this.activeSession) {
                        window.location.href = `/solo/${this.activeSession.solo_raid_id}/battle/${this.activeSession.id}`;
                    }
                }
            }))
        })
    </script>
</body>
</html>
