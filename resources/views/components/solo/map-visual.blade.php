@props(['soloRaid', 'nodes', 'completedNodeIds'])

{{-- 
    Map Visual: Shows 5 content nodes + 1 quiz node in a winding dungeon path.
    Design stays the same, only content nodes change (no boss level buttons anymore).
    Positions: odd = left, even = right, quiz = final bottom-right (large)
--}}

<div class="w-full md:w-1/2 bg-background text-text-primary p-6 md:p-12 flex items-center justify-center relative overflow-hidden min-h-screen">
    <div class="w-full max-w-md relative z-10 flex flex-col items-center">

        {{-- Header --}}
        <div class="text-center mb-6 w-full">
            <h1 class="text-xl font-extrabold uppercase tracking-wider text-text-primary mb-2">
                {{ $soloRaid->nama }}
            </h1>
            <div class="flex justify-center items-center gap-2">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-success/20 text-success border border-success/30">ACTIVE</span>
            </div>
        </div>

        {{-- Map Container --}}
        <div class="relative w-[360px] h-[580px]">

            {{-- Background Path (SVG) — same as original --}}
            <svg class="absolute top-0 left-0 w-full h-full z-0 pointer-events-none" viewBox="0 0 360 580" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="gradientPath" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%"   stop-color="#9cdcfe" />
                        <stop offset="25%"  stop-color="#4ec9b0" />
                        <stop offset="50%"  stop-color="#007acc" />
                        <stop offset="75%"  stop-color="#dcdcaa" />
                        <stop offset="100%" stop-color="#f44747" />
                    </linearGradient>
                    <filter id="glow">
                        <feGaussianBlur stdDeviation="2.5" result="coloredBlur"/>
                        <feMerge><feMergeNode in="coloredBlur"/><feMergeNode in="SourceGraphic"/></feMerge>
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

            @php
                $contentNodes = $nodes->where('type', 'content')->sortBy('order')->values();
                $quizNode     = $nodes->where('type', 'quiz')->first();
                
                // Positions: 5 content nodes along the path
                $positions = [
                    ['top' =>  20, 'side' => 'left',  'left' => 13],
                    ['top' => 130, 'side' => 'right', 'right' => 13],
                    ['top' => 240, 'side' => 'left',  'left' => 13],
                    ['top' => 350, 'side' => 'right', 'right' => 13],
                    ['top' => 455, 'side' => 'left',  'left' => 13],
                ];
                $nodeColors = ['#9cdcfe','#4ec9b0','#007acc','#dcdcaa','#9cdcfe'];
            @endphp

            {{-- Content Nodes 1-5 --}}
            @foreach($contentNodes->take(5) as $i => $node)
            @php
                $pos       = $positions[$i] ?? $positions[0];
                $isDone    = in_array($node->id, $completedNodeIds);
                $isOpen    = ($i === 0) || in_array($contentNodes[$i - 1]->id ?? null, $completedNodeIds);
                $color     = $isDone ? '#4ec9b0' : ($isOpen ? ($nodeColors[$i] ?? '#007acc') : '#555');
                $shadow    = $isDone ? 'rgba(78,201,176,0.4)' : ($isOpen ? 'rgba(0,122,204,0.4)' : 'rgba(0,0,0,0)');
                $isRight   = $pos['side'] === 'right';
            @endphp

            <div class="absolute w-full @if($isRight) flex justify-end @endif"
                 style="top: {{ $pos['top'] }}px; @if($isRight) right: {{ $pos['right'] }}px; @else left: {{ $pos['left'] }}px; @endif">

                <div class="flex @if($isRight) flex-row-reverse @endif items-center gap-3 node {{ $isOpen ? 'cursor-pointer' : 'cursor-not-allowed opacity-60' }}"
                     @if($isOpen) @click="openInfo({{ $node->order }})" @endif>

                    <div class="relative z-10 w-16 h-16 rounded-full bg-surface border-2 flex items-center justify-center overflow-hidden"
                         style="border-color: {{ $color }}; box-shadow: 0 0 15px {{ $shadow }};">
                        @if($isDone)
                            <span class="material-symbols-outlined text-[#4ec9b0] text-2xl">check_circle</span>
                        @elseif($isOpen)
                            <span class="material-symbols-outlined text-2xl" style="color: {{ $color }};">menu_book</span>
                        @else
                            <i class="fa-solid fa-lock text-lg text-text-muted"></i>
                        @endif
                    </div>

                    <div class="glass-panel p-2 rounded-lg max-w-[180px]"
                         style="border-@if(!$isRight) left @else right @endif: 4px solid {{ $color }}; @if($isRight) text-align: right @endif">
                        <p class="text-[10px] font-bold uppercase tracking-wide" style="color: {{ $color }};">
                            {{ $isDone ? '✓ SELESAI' : ($isOpen ? 'RESOURCE' : 'TERKUNCI') }}
                        </p>
                        <p class="text-sm font-bold text-white leading-tight">{{ $node->title }}</p>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Quiz Node (Latihan Soal) — bottom right, larger --}}
            @if($quizNode)
            @php
                $allContentDone = count(array_intersect($contentNodes->pluck('id')->toArray(), $completedNodeIds)) >= $contentNodes->count();
                $quizColor  = $allContentDone ? '#f44747' : '#555';
                $quizShadow = $allContentDone ? 'rgba(244,71,71,0.6)' : 'rgba(0,0,0,0)';
            @endphp
            <div class="absolute w-full flex justify-end" style="top: 510px; right: 13px;">
                <div class="flex flex-row-reverse items-center gap-4 node {{ $allContentDone ? 'cursor-pointer' : 'cursor-not-allowed opacity-60' }}"
                     @if($allContentDone) @click="startLatihan()" @endif>

                    <div class="relative z-10 w-20 h-20 rounded-full bg-surface border-4 flex items-center justify-center"
                         style="border-color: {{ $quizColor }}; @if($allContentDone) box-shadow: 0 0 20px {{ $quizShadow }}; @endif">
                        @if($allContentDone)
                            <span class="material-symbols-outlined text-3xl" style="color: {{ $quizColor }};">quiz</span>
                        @else
                            <i class="fa-solid fa-lock text-2xl text-text-muted"></i>
                        @endif
                    </div>

                    <div class="text-right">
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color: {{ $quizColor }};">
                            LATIHAN SOAL
                        </p>
                        <p class="text-lg font-bold" style="color: {{ $allContentDone ? '#d4d4d4' : '#555' }};">
                            {{ $quizNode->title }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Legend --}}
        <div class="mt-20 w-full bg-surface/50 backdrop-blur-lg border border-border rounded-xl p-4">
            <div class="flex justify-center gap-8 text-[10px] uppercase font-bold tracking-wide text-text-muted">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-success rounded-full shadow-[0_0_8px_#4ec9b0]"></span> Selesai
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-primary rounded-full shadow-[0_0_8px_#007acc]"></span> Terbuka
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-border rounded-full"></span> Terkunci
                </div>
            </div>
        </div>

    </div>
</div>
