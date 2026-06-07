<x-app-layout>
    <style>
        @keyframes highlight-learning {
            0% { box-shadow: 0 0 0px rgba(99, 102, 241, 0.5); transform: scale(1); }
            50% { box-shadow: 0 0 40px 15px rgba(99, 102, 241, 0.8); transform: scale(1.02); border-color: rgba(99, 102, 241, 1); }
            100% { box-shadow: 0 0 0px rgba(99, 102, 241, 0.5); transform: scale(1); }
        }
        @keyframes highlight-boss {
            0% { box-shadow: 0 0 0px rgba(59, 130, 246, 0.5); transform: scale(1); }
            50% { box-shadow: 0 0 40px 15px rgba(59, 130, 246, 0.8); transform: scale(1.02); border-color: rgba(59, 130, 246, 1); }
            100% { box-shadow: 0 0 0px rgba(59, 130, 246, 0.5); transform: scale(1); }
        }
        .highlight-learning-card {
            animation: highlight-learning 1.5s ease-in-out 2;
            z-index: 20;
        }
        .highlight-boss-card {
            animation: highlight-boss 1.5s ease-in-out 2;
            z-index: 20;
        }
    </style>
    <div class="flex flex-col gap-6">

        {{-- Page Heading --}}
        <div class="glass-card rounded-xl p-6">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                         style="background: linear-gradient(135deg, rgba(0,242,255,0.15), rgba(206,93,255,0.15)); border: 1px solid rgba(0,242,255,0.3);">
                        <span class="material-symbols-outlined text-cyan-glow" style="font-size: 24px;">swords</span>
                    </div>
                    <div>
                        <h1 class="font-headline text-2xl md:text-3xl font-extrabold text-cyan-glow leading-tight">
                            Event
                        </h1>
                        <p class="font-body text-sm text-soft mt-1">
                            Selesaikan event secara bertahap untuk unlock Boss Battle.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-4 py-2 rounded-lg"
                         style="background-color: rgba(32, 31, 32, 0.6); border: 1px solid rgba(0, 242, 255, 0.3);">
                        <span class="material-symbols-outlined text-cyan-glow">psychology</span>
                        <div class="flex flex-col">
                            <span class="font-mono-label text-[10px] font-medium uppercase tracking-wider text-soft">Adaptive Level</span>
                            <span class="font-headline text-sm font-bold text-cyan-glow">{{ $currentSection }}</span>
                        </div>
                    </div>
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'dosen')
                        <a href="{{ route('admin.solo-raids.create') }}" class="btn-cyber-primary font-headline flex items-center gap-2 rounded-lg h-10 text-sm font-bold px-5">
                            <span class="material-symbols-outlined text-sm">add_circle</span> Buat Event
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg px-4 py-3 text-sm font-medium font-body"
                 style="background-color: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #86efac;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-lg px-4 py-3 text-sm font-medium font-body"
                 style="background-color: rgba(255, 99, 99, 0.1); border: 1px solid rgba(255, 99, 99, 0.3); color: #ffb4ab;">
                {{ session('error') }}
            </div>
        @endif

        @foreach($eventsBySection as $sectionName => $sectionData)
            @php
                $events = $sectionData['events'];
                $isSectionUnlocked = $sectionData['is_unlocked'];

                // All sections use cyan color
                $sectionMeta = ['color' => '#00f2ff', 'bg' => 'rgba(0,242,255,0.15)', 'border' => 'rgba(0,242,255,0.3)'];
            @endphp

            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <h2 class="font-headline text-2xl font-extrabold" style="color: {{ $sectionMeta['color'] }};">{{ $sectionName }}</h2>
                    <div class="h-px flex-1" style="background-color: {{ $sectionMeta['border'] }};"></div>
                    @if(!$isSectionUnlocked)
                        <span class="font-mono-label text-xs font-medium px-2 py-1 rounded-md uppercase tracking-wider flex items-center gap-1"
                              style="background-color: rgba(132,148,149,0.15); color: #849495; border: 1px solid rgba(132,148,149,0.3);">
                            <span class="material-symbols-outlined" style="font-size:12px">lock</span> Terkunci
                        </span>
                    @endif
                </div>

                @if($events->isEmpty())
                    <div class="glass-card rounded-xl p-8 md:p-12 text-center" style="opacity: 0.7;">
                        <span class="material-symbols-outlined text-4xl mb-2 block text-faint">hourglass_empty</span>
                        <p class="font-headline font-semibold mb-1" style="color: #e5e2e3;">Belum ada event aktif</p>
                        <p class="font-body text-sm text-soft">Event untuk section {{ $sectionName }} belum tersedia.</p>
                    </div>
                @else
                    {{-- Event Cards Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($events as $index => $event)
                            @php
                                $isExpired    = now()->greaterThan($event->tanggal_selesai);
                                $progress     = $event->progress;
                                $isCompleted  = $progress && $progress->status === 'completed';
                                $isInProgress = $progress && $progress->status === 'in_progress';
                                $isUnlocked   = $isSectionUnlocked && $event->is_unlocked && !$isExpired;
                                $isBoss       = $event->type === 'boss';

                                // Card styling with dark background
                                if ($isBoss) {
                                    // Boss Battle: Blue-Cyan border
                                    $cardBorder     = 'rgba(59, 130, 246, 0.5)';
                                    $cardShadow     = '0 0 30px rgba(6, 182, 212, 0.2)';
                                    $iconBgColor    = 'rgba(59, 130, 246, 0.3)';
                                    $iconBorder     = 'rgba(59, 130, 246, 0.6)';
                                    $iconColor      = '#00d4ff';
                                    $iconSymbol     = 'skull';
                                    $bgIconColor    = 'rgba(0, 212, 255, 0.6)';
                                    $bgIconSymbol   = 'swords';
                                    $titleColor     = '#ffffff';
                                    $descColor      = 'rgba(255, 255, 255, 0.9)';
                                    $dateColor      = 'rgba(255, 255, 255, 0.75)';
                                    $btnTextColor   = '#1d4ed8';
                                } else {
                                    // Materi: Indigo-Purple border
                                    $cardBorder     = 'rgba(99, 102, 241, 0.4)';
                                    $cardShadow     = '0 0 25px rgba(99, 102, 241, 0.15)';
                                    $iconBgColor    = 'rgba(99, 102, 241, 0.3)';
                                    $iconBorder     = 'rgba(99, 102, 241, 0.6)';
                                    $iconColor      = '#a78bfa';
                                    $iconSymbol     = 'menu_book';
                                    $bgIconColor    = 'rgba(167, 139, 250, 0.6)';
                                    $bgIconSymbol   = 'school';
                                    $titleColor     = '#ffffff';
                                    $descColor      = 'rgba(255, 255, 255, 0.9)';
                                    $dateColor      = 'rgba(255, 255, 255, 0.75)';
                                    $btnTextColor   = '#4f46e5';
                                }

                                // Badge styling
                                $badgeBg        = 'rgba(255,255,255,0.1)';
                                $badgeBorder    = 'rgba(255,255,255,0.2)';
                                $badgeColor     = 'rgba(255,255,255,0.85)';

                                // Completed status — keep cyan, just add extra glow
                                if ($isCompleted) {
                                    $cardShadow = $isBoss
                                        ? '0 0 30px rgba(0, 212, 255, 0.3), 0 0 15px rgba(0, 212, 255, 0.2)'
                                        : '0 0 25px rgba(0, 212, 255, 0.25), 0 0 15px rgba(0, 212, 255, 0.15)';
                                }

                                $highlightClass = '';
                                if (request('type') === 'boss' && $isBoss) {
                                    $highlightClass = 'highlight-boss-card';
                                } elseif (request('type') === 'learning' && !$isBoss) {
                                    $highlightClass = 'highlight-learning-card';
                                }
                            @endphp

                            <div class="rounded-xl p-8 relative overflow-hidden group transition-all duration-300 {{ !$isUnlocked ? 'opacity-50' : 'hover:-translate-y-1' }} {{ $highlightClass }}"
                                 style="background: rgba(19, 19, 20, 0.8);
                                        border: 1px solid {{ $cardBorder }};
                                        box-shadow: {{ $cardShadow }};">

                                {{-- Decorative background icon --}}
                                <div class="absolute top-0 right-0 p-8 opacity-10 transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform duration-500 pointer-events-none">
                                    <span class="material-symbols-outlined" style="font-size: 128px; color: {{ $bgIconColor }};">{{ $bgIconSymbol }}</span>
                                </div>

                                <div class="relative z-10 flex flex-col h-full gap-4">
                                    {{-- Icon Box (top-left) --}}
                                    <div class="flex items-start justify-between">
                                        <div class="w-14 h-14 rounded-xl flex items-center justify-center backdrop-blur-md border"
                                             style="background-color: {{ $iconBgColor }}; border-color: {{ $iconBorder }};">
                                            <span class="material-symbols-outlined" style="color: {{ $iconColor }}; font-size: 30px;">{{ $iconSymbol }}</span>
                                        </div>

                                        {{-- Status badges --}}
                                        <div class="flex flex-wrap gap-2 justify-end">
                                            @if($isBoss)
                                                <span class="font-mono-label inline-flex items-center gap-1 text-[10px] font-medium px-2 py-1 rounded uppercase tracking-wider"
                                                      style="background-color: {{ $badgeBg }}; color: {{ $badgeColor }}; border: 1px solid {{ $badgeBorder }};">
                                                    <span class="material-symbols-outlined" style="font-size:11px">skull</span> Boss Battle
                                                </span>
                                            @else
                                                <span class="font-mono-label inline-flex items-center gap-1 text-[10px] font-medium px-2 py-1 rounded uppercase tracking-wider"
                                                      style="background-color: {{ $badgeBg }}; color: {{ $badgeColor }}; border: 1px solid {{ $badgeBorder }};">
                                                    <span class="material-symbols-outlined" style="font-size:11px">menu_book</span> Materi
                                                </span>
                                            @endif

                                            @if($isCompleted)
                                                <span class="font-mono-label text-[10px] font-medium px-2 py-1 rounded uppercase tracking-wider inline-flex items-center gap-1"
                                                      style="background-color: rgba(34,197,94,0.2); color: #bbf7d0; border: 1px solid rgba(34,197,94,0.4);">
                                                    <span class="material-symbols-outlined" style="font-size:11px">check_circle</span> Selesai
                                                </span>
                                            @elseif($isInProgress)
                                                <span class="font-mono-label text-[10px] font-medium px-2 py-1 rounded uppercase tracking-wider"
                                                      style="background-color: rgba(250,204,21,0.2); color: #fef3c7; border: 1px solid rgba(250,204,21,0.4);">
                                                    Sedang
                                                </span>
                                            @elseif(!$isUnlocked)
                                                <span class="font-mono-label text-[10px] font-medium px-2 py-1 rounded uppercase tracking-wider inline-flex items-center gap-1"
                                                      style="background-color: rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.2);">
                                                    <span class="material-symbols-outlined" style="font-size:11px">lock</span> Terkunci
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Title --}}
                                    <h3 class="font-headline text-xl md:text-2xl font-extrabold leading-tight" style="color: {{ $titleColor }};">
                                        {{ $event->nama }}
                                    </h3>

                                    {{-- Description --}}
                                    <p class="font-body text-sm md:text-base line-clamp-3" style="color: {{ $descColor }};">{{ $event->deskripsi }}</p>

                                    {{-- Date + CTA --}}
                                    <div class="mt-auto flex items-center justify-between pt-4" style="border-top: 1px solid rgba(255,255,255,0.1);">
                                        <div class="font-mono-label flex items-center gap-1 text-xs uppercase tracking-wider" style="color: {{ $dateColor }};">
                                            <span class="material-symbols-outlined" style="font-size: 14px;">calendar_today</span>
                                            <span>{{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d M Y') }}</span>
                                        </div>

                                        @if(!$isUnlocked || $isExpired)
                                            <div class="font-mono-label flex items-center gap-1 text-xs uppercase tracking-wider" style="color: rgba(255,255,255,0.5);">
                                                <span class="material-symbols-outlined" style="font-size: 14px;">lock</span>
                                                <span>Terkunci</span>
                                            </div>
                                        @elseif($isBoss)
                                            @if($isCompleted)
                                                <a href="{{ route('solo.boss', $event) }}"
                                                   class="font-headline inline-flex items-center bg-blue-600 text-white px-5 py-2 rounded-lg font-bold text-xs transition-all duration-300 hover:bg-blue-500 hover:scale-105">
                                                    <span class="material-symbols-outlined text-sm mr-1">restart_alt</span>Ulangi
                                                </a>
                                            @else
                                                <a href="{{ route('solo.boss', $event) }}"
                                                   class="font-headline inline-flex items-center bg-white px-5 py-2 rounded-lg font-bold text-xs transition-all duration-300 hover:bg-gray-100 hover:scale-105"
                                                   style="color: {{ $btnTextColor }};">
                                                    <span class="material-symbols-outlined text-sm mr-1">swords</span>Mulai Battle
                                                </a>
                                            @endif
                                        @elseif($isCompleted)
                                            <a href="{{ route('solo.map', $event) }}"
                                               class="font-headline inline-flex items-center bg-blue-600 text-white px-5 py-2 rounded-lg font-bold text-xs transition-all duration-300 hover:bg-blue-500 hover:scale-105">
                                                <span class="material-symbols-outlined text-sm mr-1">replay</span>Ulangi
                                            </a>
                                        @else
                                            <a href="{{ route('solo.map', $event) }}"
                                               class="font-headline inline-flex items-center bg-white px-5 py-2 rounded-lg font-bold text-xs transition-all duration-300 hover:bg-gray-100 hover:scale-105"
                                               style="color: {{ $btnTextColor }};">
                                                {{ $isInProgress ? 'Lanjutkan' : 'Mulai' }}
                                                <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

    </div>
</x-app-layout>
