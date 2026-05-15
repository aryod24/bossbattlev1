<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $soloRaid->nama }}</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Markdown & Code Highlighting -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/python.min.js"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Primary Colors (VS Code Dark Theme)
                        "primary": "#007acc",
                        
                        // Background Colors
                        "background": "#1e1e1e",
                        "background-light": "#252526",
                        "background-dark": "#1e1e1e",
                        
                        // Card/Surface Colors
                        "card": "#252526",
                        "surface": "#252526",
                        "surface-light": "#2d2d2d",
                        "surface-dark": "#252526",
                        
                        // Text Colors
                        "text-primary": "#d4d4d4",
                        "text-muted": "#858585",
                        
                        // Border Colors
                        "border": "#333333",
                        "border-light": "#404040",
                        
                        // Status Colors
                        "success": "#4ec9b0",
                        "warning": "#dcdcaa",
                        "error": "#f44747",
                        "info": "#9cdcfe",
                        
                        // Accent Colors
                        "accent": "#007acc",
                        "accent-hover": "#1a8ad4",
                        
                        // Game/Legacy Colors (untuk map visual)
                        "game": {
                            "darker": "#0d1117",
                            "panel": "#161b22",
                            "gold": "#fbbf24",
                            "green": "#22c55e",
                            "red": "#ef4444",
                            "teal": "#14b8a6",
                        },
                    },
                },
            },
        }
    </script>
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
<body x-data="dungeonMap({{ $soloRaid->id }})">
    <div class="flex flex-col md:flex-row min-h-screen">
        
        <!-- Left Section: Info Panel (Light Theme) -->
        <x-solo.map-info-panel :solo-raid="$soloRaid" :stats="$userStats" :sessions="$sessionHistory" :active-session="$activeSession" :nodes="$nodes" :completed-node-ids="$completedNodeIds" />
        <x-solo.map-visual :solo-raid="$soloRaid" :nodes="$nodes" :completed-node-ids="$completedNodeIds" />
    </div>

    <!-- Materi Modal Component -->
    <x-solo.materi-modal />

    <!-- Modal Konfirmasi Mulai Latihan -->
    <div x-show="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showConfirmModal" @click="showConfirmModal = false" class="fixed inset-0 bg-black opacity-75"></div>

            <div x-show="showConfirmModal" class="relative inline-block bg-card rounded-xl text-left overflow-hidden shadow-2xl border border-border max-w-md w-full">
                <div class="px-6 pt-6 pb-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">quiz</span>
                        </div>
                        <h3 class="text-lg font-bold text-text-primary">Mulai Latihan Soal?</h3>
                    </div>
                    <div class="bg-surface-light rounded-lg p-4 border border-border mb-4">
                        <p class="text-sm text-text-muted mb-1">Event</p>
                        <p class="font-bold text-text-primary mb-3">{{ $soloRaid->nama }}</p>
                        <div class="text-xs">
                            <p class="text-text-muted mb-1">Percobaan ke-</p>
                            <p class="font-bold text-text-primary">#{{ $userStats['attempts'] + 1 }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-text-muted">⏱️ Timer akan langsung mulai setelah konfirmasi.</p>
                </div>
                <div class="px-6 py-4 bg-background flex flex-row-reverse gap-2">
                    <button type="button" @click="doStartLatihan()" class="inline-flex justify-center rounded-lg px-5 py-2 bg-primary text-white text-sm font-bold hover:bg-primary/80">
                        Mulai Sekarang
                    </button>
                    <button type="button" @click="showConfirmModal = false" class="inline-flex justify-center rounded-lg px-5 py-2 border border-border text-text-primary text-sm font-medium hover:bg-surface-light">
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

            <div x-show="showActiveSessionModal" class="relative inline-block bg-card rounded-xl text-left overflow-hidden shadow-2xl border border-yellow-500/50 max-w-md w-full">
                <div class="px-6 pt-6 pb-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-yellow-500/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-yellow-500">warning</span>
                        </div>
                        <h3 class="text-lg font-bold text-text-primary">Sesi Aktif Terdeteksi</h3>
                    </div>
                    <div class="bg-yellow-500/10 rounded-lg p-4 border border-yellow-500/30 mb-4">
                        <p class="text-sm text-text-muted mb-1">Kamu masih punya sesi yang sedang berjalan di:</p>
                        <p class="font-bold text-yellow-400" x-text="activeSession ? activeSession.solo_raid_id + ' — Level ' + (activeSession.level || '-') : ''"></p>
                    </div>
                    <p class="text-xs text-text-muted">Selesaikan sesi tersebut terlebih dahulu, atau lanjutkan dari sini.</p>
                </div>
                <div class="px-6 py-4 bg-background flex flex-row-reverse gap-2">
                    <button type="button" @click="continueActiveSession()" class="inline-flex justify-center rounded-lg px-5 py-2 bg-yellow-500 text-black text-sm font-bold hover:bg-yellow-400">
                        Lanjutkan Sesi
                    </button>
                    <button type="button" @click="showActiveSessionModal = false" class="inline-flex justify-center rounded-lg px-5 py-2 border border-border text-text-primary text-sm font-medium hover:bg-surface-light">
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

            <div x-show="showBossConfirmModal" class="relative inline-block bg-card rounded-xl text-left overflow-hidden shadow-2xl border border-error/50 max-w-md w-full">
                <div class="px-6 pt-6 pb-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-error/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-error">swords</span>
                        </div>
                        <h3 class="text-lg font-bold text-text-primary">Mulai Boss Battle?</h3>
                    </div>
                    <div class="bg-surface-light rounded-lg p-4 border border-border mb-4">
                        <p class="text-sm text-text-muted mb-1">Event</p>
                        <p class="font-bold text-text-primary mb-3">{{ $soloRaid->nama }}</p>
                        <div class="text-xs">
                            <p class="text-text-muted mb-1">Percobaan ke-</p>
                            <p class="font-bold text-text-primary">#{{ $userStats['attempts'] + 1 }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-text-muted">⚔️ Timer akan langsung mulai setelah konfirmasi. Siap?</p>
                </div>
                <div class="px-6 py-4 bg-background flex flex-row-reverse gap-2">
                    <button type="button" @click="doStartBoss()" class="inline-flex justify-center rounded-lg px-5 py-2 bg-error text-white text-sm font-bold hover:bg-red-600">
                        Mulai Sekarang!
                    </button>
                    <button type="button" @click="showBossConfirmModal = false" class="inline-flex justify-center rounded-lg px-5 py-2 border border-border text-text-primary text-sm font-medium hover:bg-surface-light">
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
                        // Reload to re-render map-visual with updated state
                        window.location.reload();
                    })
                    .catch(() => {
                        this.isMarkingDone = false;
                    });
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
