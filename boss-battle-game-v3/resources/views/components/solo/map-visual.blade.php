@props(['soloRaid'])

<div class="w-full md:w-1/2 bg-background text-text-primary p-6 md:p-12 flex items-center justify-center relative overflow-hidden min-h-screen">
    <div class="w-full max-w-md relative z-10 flex flex-col items-center">
        
        <!-- Header -->
        <div class="text-center mb-6 w-full">
            <h1 class="text-xl font-extrabold uppercase tracking-wider text-text-primary mb-2">
                Solo Raid Event
            </h1>
            <div class="flex justify-center items-center gap-2">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-success/20 text-success border border-success/30">ACTIVE</span>
            </div>
        </div>

        <!-- Map Container -->
        <div class="relative w-[360px] h-[580px]">
            
            <!-- Background Path (SVG) -->
            <svg class="absolute top-0 left-0 w-full h-full z-0 pointer-events-none" viewBox="0 0 360 580" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="gradientPath" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#9cdcfe" /> <!-- Info -->
                        <stop offset="33%" stop-color="#4ec9b0" /> <!-- Success -->
                        <stop offset="66%" stop-color="#dcdcaa" /> <!-- Warning -->
                        <stop offset="100%" stop-color="#f44747" /> <!-- Error -->
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
                    <div class="relative z-10 w-16 h-16 rounded-full bg-surface border-2 border-info flex items-center justify-center shadow-[0_0_15px_rgba(156,220,254,0.4)]">
                        <span class="material-symbols-outlined text-info text-2xl">menu_book</span>
                    </div>
                    <div class="glass-panel p-2 rounded-lg border-l-4 border-l-info mt-1 max-w-[180px]">
                        <p class="text-[10px] text-info font-bold uppercase tracking-wide">Info Node</p>
                        <p class="text-sm font-bold text-white leading-tight">Study Material 1</p>
                    </div>
                </div>
            </div>

            <!-- NODE 2: Easy Level (Top-Right) -->
            <div class="absolute w-full flex justify-end" style="top: 130px; right: 13px;">
                <div class="flex flex-row-reverse items-center gap-3 node" @click="checkLevel('easy')">
                    <div class="relative z-10 w-16 h-16 rounded-full bg-surface border-2 flex items-center justify-center shadow-[0_0_15px_rgba(78,201,176,0.4)] overflow-hidden"
                            :class="levels.easy.available ? 'border-success' : 'border-border grayscale opacity-70'">
                        <i class="fa-solid fa-dragon text-2xl" :class="levels.easy.available ? 'text-success' : 'text-text-muted'"></i>
                        <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center" x-show="!levels.easy.available">
                            <i class="fa-solid fa-lock text-white/50 text-lg"></i>
                        </div>
                    </div>
                    <div class="glass-panel p-2 rounded-lg border-r-4 text-right max-w-[150px]"
                            :class="levels.easy.available ? 'border-r-success' : 'border-r-border'">
                        <p class="text-[10px] font-bold uppercase" :class="levels.easy.available ? 'text-success' : 'text-text-muted'">Easy</p>
                        <p class="text-sm font-bold text-white">{{ $soloRaid->boss_easy_name }}</p>
                    </div>
                </div>
            </div>

            <!-- NODE 3: Info 2 (Mid-Left) -->
            <div class="absolute w-full" style="top: 240px; left: 13px;">
                <div class="flex items-center gap-4 node" @click="openInfo(2)">
                    <div class="relative z-10 w-16 h-16 rounded-full bg-surface border-2 border-info flex items-center justify-center shadow-[0_0_15px_rgba(156,220,254,0.4)]">
                        <span class="material-symbols-outlined text-info text-2xl">menu_book</span>
                    </div>
                    <div class="glass-panel p-2 rounded-lg border-l-4 border-l-info max-w-[180px]">
                        <p class="text-[10px] text-info font-bold uppercase">Info Node</p>
                        <p class="text-sm font-bold text-white">Study Material 2</p>
                    </div>
                </div>
            </div>

            <!-- NODE 4: Medium Level (Mid-Right) -->
            <div class="absolute w-full flex justify-end" style="top: 350px; right: 13px;">
                <div class="flex flex-row-reverse items-center gap-3 node" @click="checkLevel('medium')">
                    <div class="relative z-10 w-16 h-16 rounded-full bg-surface border-2 flex items-center justify-center shadow-[0_0_15px_rgba(220,220,170,0.4)]"
                            :class="levels.medium.available ? 'border-warning' : 'border-border grayscale opacity-70'">
                        <i class="fa-solid fa-fire-flame-curved text-2xl" :class="levels.medium.available ? 'text-warning' : 'text-text-muted'"></i>
                        <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center" x-show="!levels.medium.available">
                            <i class="fa-solid fa-lock text-white/50 text-lg"></i>
                        </div>
                    </div>
                    <div class="glass-panel p-2 rounded-lg border-r-4 text-right max-w-[150px]"
                            :class="levels.medium.available ? 'border-r-warning' : 'border-r-border'">
                        <p class="text-[10px] font-bold uppercase" :class="levels.medium.available ? 'text-warning' : 'text-text-muted'">Medium</p>
                        <p class="text-sm font-bold text-white">{{ $soloRaid->boss_medium_name }}</p>
                    </div>
                </div>
            </div>

            <!-- NODE 5: Info 3 (Lower-Left) -->
            <div class="absolute w-full" style="top: 460px; left: 13px;">
                <div class="flex items-center gap-3 node" @click="openInfo(3)">
                    <div class="relative z-10 w-16 h-16 rounded-full bg-surface border-2 border-info flex items-center justify-center shadow-[0_0_15px_rgba(156,220,254,0.4)]">
                        <span class="material-symbols-outlined text-info text-2xl">menu_book</span>
                    </div>
                    <div class="glass-panel p-2 rounded-lg border-l-4 border-l-info max-w-[180px]">
                        <p class="text-[10px] text-info font-bold uppercase">Info Node</p>
                        <p class="text-sm font-bold text-white">Study Material 3</p>
                    </div>
                </div>
            </div>

            <!-- NODE 6: Hard Level (Bottom-Right) -->
            <div class="absolute w-full flex justify-end" style="top: 540px; right: 13px;">
                <div class="flex flex-row-reverse items-center gap-4 node" @click="checkLevel('hard')"
                        :class="levels.hard.available ? '' : 'opacity-60'">
                    <div class="relative z-10 w-20 h-20 rounded-full bg-surface border-4 flex items-center justify-center"
                            :class="levels.hard.available ? 'border-error shadow-[0_0_20px_rgba(244,71,71,0.6)]' : 'border-border'">
                        <i class="fa-solid fa-skull text-3xl" :class="levels.hard.available ? 'text-error' : 'text-text-muted'"></i>
                        <div class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center" x-show="!levels.hard.available">
                            <i class="fa-solid fa-lock text-white/50 text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold uppercase tracking-widest" :class="levels.hard.available ? 'text-error' : 'text-text-muted'">FINAL BOSS</p>
                        <p class="text-lg font-bold" :class="levels.hard.available ? 'text-white' : 'text-text-muted'">{{ $soloRaid->boss_hard_name }}</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Legend -->
        <div class="mt-20 w-full bg-surface/50 backdrop-blur-lg border border-border rounded-xl p-4">
            <div class="flex justify-center gap-8 text-[10px] uppercase font-bold tracking-wide text-text-muted">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-success rounded-full shadow-[0_0_8px_#4ec9b0]"></span> Available
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-border rounded-full"></span> Locked
                </div>
            </div>
        </div>

    </div>
</div>
