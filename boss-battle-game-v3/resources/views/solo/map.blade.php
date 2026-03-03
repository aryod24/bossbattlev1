<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $soloRaid->nama }} - Dungeon Map</title>
    
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
        <x-solo.map-info-panel :solo-raid="$soloRaid" :stats="$userStats" :sessions="$sessionHistory" :active-session="$activeSession" />
        <x-solo.map-visual :solo-raid="$soloRaid" :nodes="$nodes" :completed-node-ids="$completedNodeIds" />
    </div>

    <!-- Materi Modal Component -->
    <x-solo.materi-modal />

    <!-- Start Battle Confirmation Modal -->
    <div x-show="showStartModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showStartModal" @click="showStartModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showStartModal" class="inline-block align-bottom bg-card rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-border">
                <div class="bg-card px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-500/20 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="material-symbols-outlined text-yellow-500">swords</span>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-text-primary">Start Battle?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-text-muted">
                                    Are you sure you want to start the <span class="font-bold text-primary" x-text="selectedLevel.toUpperCase()"></span> level?
                                </p>
                                
                                <!-- Battle Stats -->
                                <div class="mt-4 p-3 bg-surface-light rounded-lg border border-border">
                                    <div class="grid grid-cols-2 gap-3 text-xs">
                                        <div>
                                            <p class="text-text-muted mb-1">This will be</p>
                                            <p class="font-bold text-text-primary" x-text="`Attempt #${(levelStats[selectedLevel]?.attempts || 0) + 1}`"></p>
                                        </div>
                                        <div>
                                            <p class="text-text-muted mb-1">Max XP Possible</p>
                                            <p class="font-bold text-primary" x-text="(() => {
                                                const attempt = (levelStats[selectedLevel]?.attempts || 0) + 1;
                                                const maxXP = selectedLevel === 'easy' ? 150 : selectedLevel === 'medium' ? 200 : 220;
                                                const penalty = attempt === 1 ? 1.0 : attempt === 2 ? 0.5 : 0.0;
                                                return Math.floor(maxXP * penalty) + ' XP';
                                            })()"></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <p class="text-xs text-text-muted mt-3">
                                    ⏱️ Timer starts immediately after confirmation
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-background-dark px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="button" @click="startBattle()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:w-auto sm:text-sm">
                        Start Battle
                    </button>
                    <button type="button" @click="showStartModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-border shadow-sm px-4 py-2 bg-card text-base font-medium text-text-primary hover:bg-background-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Session Warning Modal -->
    <div x-show="showActiveSessionModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showActiveSessionModal" @click="showActiveSessionModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showActiveSessionModal" class="inline-block align-bottom bg-card rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-border">
                <div class="bg-card px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-500/20 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="material-symbols-outlined text-yellow-500">warning</span>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-text-primary">Active Session Detected</h3>
                            <div class="mt-2">
                                <p class="text-sm text-text-muted">
                                    You have an active session on <span class="font-bold text-primary" x-text="activeSession?.level"></span> level.
                                </p>
                                <p class="text-sm text-text-muted mt-2">
                                    Please complete your current session before starting a new level.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-background-dark px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="button" @click="continueActiveSession()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:w-auto sm:text-sm">
                        Continue Session
                    </button>
                    <button type="button" @click="showActiveSessionModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-border shadow-sm px-4 py-2 bg-card text-base font-medium text-text-primary hover:bg-background-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
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
                // Battle/session state
                showStartModal: false,
                showActiveSessionModal: false,
                selectedLevel: '',
                activeSession: @json($activeSession),
                currentRaidId: {{ $soloRaid->id }},
                // Quiz auto-level from user section
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
                    // Latihan soal uses user's section as level (Easy/Medium/Hard)
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
