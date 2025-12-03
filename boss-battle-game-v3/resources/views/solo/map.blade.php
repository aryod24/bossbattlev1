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
    </style>
</head>
<body x-data="dungeonMap({{ $soloRaid->id }})">
    <div class="flex flex-col md:flex-row min-h-screen">
        
        <!-- Left Section: Info Panel (Light Theme) -->
        <x-solo.map-info-panel :solo-raid="$soloRaid" />
        <x-solo.map-visual :solo-raid="$soloRaid" />
    </div>

    <!-- Info Modal -->
    <div x-show="showInfoModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showInfoModal" @click="showInfoModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showInfoModal" class="inline-block align-bottom bg-card rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-border">
                <div class="bg-card px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary/20 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="material-symbols-outlined text-primary">menu_book</span>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-text-primary" x-text="infoTitle"></h3>
                            <div class="mt-2">
                                <p class="text-sm text-text-muted whitespace-pre-line" x-text="infoContent"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-background-dark px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showInfoModal = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
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
                        window.location.href = `/solo/${this.raidId}/battle/init/${level}`;
                    } else {
                        alert('This level is currently locked or unavailable.');
                    }
                }
            }))
        })
    </script>
</body>
</html>
