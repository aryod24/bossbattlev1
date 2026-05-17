<x-app-layout>
    <div class="flex flex-col gap-6">

        {{-- Page Heading --}}
        <div class="glass-card rounded-xl p-8">
            <div class="flex flex-wrap justify-between items-start gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center"
                         style="background: linear-gradient(135deg, rgba(0,242,255,0.15), rgba(206,93,255,0.15)); border: 1px solid rgba(0,242,255,0.3);">
                        <span class="material-symbols-outlined text-cyan-glow" style="font-size: 32px;">swords</span>
                    </div>
                    <div>
                        <h1 class="font-headline text-3xl md:text-[32px] font-extrabold text-cyan-glow leading-tight">
                            Solo Raid
                        </h1>
                        <p class="font-body text-soft text-sm mt-1">
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

                $sectionMeta = match($sectionName) {
                    'Easy'   => ['color' => '#86efac', 'bg' => 'rgba(34,197,94,0.15)',  'border' => 'rgba(34,197,94,0.3)'],
                    'Medium' => ['color' => '#fde68a', 'bg' => 'rgba(250,204,21,0.15)', 'border' => 'rgba(250,204,21,0.3)'],
                    'Hard'   => ['color' => '#ffb4ab', 'bg' => 'rgba(255,99,99,0.15)',  'border' => 'rgba(255,99,99,0.3)'],
                    default  => ['color' => '#00f2ff', 'bg' => 'rgba(0,242,255,0.15)',  'border' => 'rgba(0,242,255,0.3)'],
                };
            @endphp

            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <h2 class="font-headline text-2xl font-extrabold" style="color: {{ $sectionMeta['color'] }};">{{ $sectionName }}</h2>
                    <div class="h-px flex-1" style="background-color: rgba(58, 73, 75, 0.5);"></div>
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($events as $index => $event)
                            @php
                                $isExpired    = now()->greaterThan($event->tanggal_selesai);
                                $progress     = $event->progress;
                                $isCompleted  = $progress && $progress->status === 'completed';
                                $isInProgress = $progress && $progress->status === 'in_progress';
                                $isUnlocked   = $isSectionUnlocked && $event->is_unlocked && !$isExpired;
                                $isBoss       = $event->type === 'boss';

                                $cardBorder = $isCompleted
                                    ? 'rgba(34, 197, 94, 0.4)'
                                    : ($isBoss ? 'rgba(206, 93, 255, 0.5)' : 'rgba(0, 242, 255, 0.2)');

                                $cardShadow = $isBoss
                                    ? '0 0 30px rgba(206, 93, 255, 0.15)'
                                    : ($isCompleted ? '0 0 20px rgba(34, 197, 94, 0.1)' : 'none');
                            @endphp

                            <div class="flex flex-col rounded-xl overflow-hidden glow-card transition-all duration-300 {{ !$isUnlocked ? 'opacity-50' : 'hover:-translate-y-0.5' }}"
                                 style="background: rgba(25, 25, 28, 0.6);
                                        backdrop-filter: blur(20px);
                                        -webkit-backdrop-filter: blur(20px);
                                        border: 1px solid {{ $cardBorder }};
                                        box-shadow: {{ $cardShadow }};">

                                {{-- Coloured top stripe --}}
                                @if($isBoss)
                                    <div class="h-1.5" style="background: linear-gradient(90deg, #ce5dff, #ff6b6b);"></div>
                                @elseif($isCompleted)
                                    <div class="h-1.5" style="background: linear-gradient(90deg, #22c55e, #14b8a6);"></div>
                                @else
                                    <div class="h-1.5 progress-bar-fill"></div>
                                @endif

                                <div class="p-5 flex-1 flex flex-col gap-3">
                                    {{-- Top row: badges + icon --}}
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex flex-col gap-2 flex-1 min-w-0">
                                            <div class="flex flex-wrap gap-2 items-center">
                                                @if($isBoss)
                                                    <span class="font-mono-label inline-flex items-center gap-1 text-[10px] font-medium px-2 py-0.5 rounded uppercase tracking-wider"
                                                          style="background-color: rgba(206,93,255,0.15); color: #ebb2ff; border: 1px solid rgba(206,93,255,0.3);">
                                                        <span class="material-symbols-outlined" style="font-size:11px">skull</span> Boss Battle
                                                    </span>
                                                @else
                                                    <span class="font-mono-label inline-flex items-center gap-1 text-[10px] font-medium px-2 py-0.5 rounded uppercase tracking-wider"
                                                          style="background-color: rgba(0,242,255,0.12); color: #00f2ff; border: 1px solid rgba(0,242,255,0.3);">
                                                        <span class="material-symbols-outlined" style="font-size:11px">menu_book</span> Materi #{{ $index + 1 }}
                                                    </span>
                                                @endif

                                                @if($isCompleted)
                                                    <span class="font-mono-label text-[10px] font-medium px-2 py-0.5 rounded uppercase tracking-wider"
                                                          style="background-color: rgba(34,197,94,0.15); color: #86efac; border: 1px solid rgba(34,197,94,0.3);">
                                                        ✓ Selesai
                                                    </span>
                                                @elseif($isInProgress)
                                                    <span class="font-mono-label text-[10px] font-medium px-2 py-0.5 rounded uppercase tracking-wider"
                                                          style="background-color: rgba(250,204,21,0.15); color: #fde68a; border: 1px solid rgba(250,204,21,0.3);">
                                                        Sedang
                                                    </span>
                                                @elseif(!$isUnlocked)
                                                    <span class="font-mono-label text-[10px] font-medium px-2 py-0.5 rounded uppercase tracking-wider flex items-center gap-0.5"
                                                          style="background-color: rgba(132,148,149,0.15); color: #849495; border: 1px solid rgba(132,148,149,0.3);">
                                                        <span class="material-symbols-outlined" style="font-size:10px">lock</span> Terkunci
                                                    </span>
                                                @endif
                                            </div>
                                            <h3 class="font-headline text-base font-semibold leading-snug" style="color: #e5e2e3;">
                                                {{ $event->nama }}
                                            </h3>
                                        </div>
                                        {{-- Icon --}}
                                        @if($isBoss)
                                            <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                                                 style="background-color: rgba(206,93,255,0.15); border: 1px solid rgba(206,93,255,0.3);">
                                                <span class="material-symbols-outlined text-magenta-glow">skull</span>
                                            </div>
                                        @else
                                            <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                                                 style="background-color: rgba(0,242,255,0.12); border: 1px solid rgba(0,242,255,0.3);">
                                                <span class="font-headline text-sm font-extrabold text-cyan-glow">{{ $index + 1 }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <p class="font-body text-xs text-soft line-clamp-2 mt-auto">{{ $event->deskripsi }}</p>

                                    {{-- Date + CTA --}}
                                    <div class="mt-4 flex items-center justify-between pt-3" style="border-top: 1px solid rgba(58, 73, 75, 0.5);">
                                        <div class="font-mono-label flex items-center gap-1 text-xs uppercase tracking-wider text-soft">
                                            <span class="material-symbols-outlined" style="font-size: 14px;">calendar_today</span>
                                            <span>{{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d M Y') }}</span>
                                        </div>

                                        @if(!$isUnlocked || $isExpired)
                                            <div class="font-mono-label flex items-center gap-1 text-xs uppercase tracking-wider text-faint">
                                                <span class="material-symbols-outlined" style="font-size: 14px;">lock</span>
                                                <span>Terkunci</span>
                                            </div>
                                        @elseif($isBoss)
                                            <a href="{{ route('solo.boss', $event) }}"
                                               class="font-headline flex items-center justify-center rounded-lg h-8 text-xs font-bold px-4 transition-all"
                                               style="background: linear-gradient(135deg, #ce5dff, #ff6b6b); color: #ffffff; text-shadow: 0 1px 2px rgba(0,0,0,0.4);"
                                               onmouseover="this.style.boxShadow='0 0 18px rgba(206,93,255,0.5)';"
                                               onmouseout="this.style.boxShadow='none';">
                                                <span class="material-symbols-outlined text-sm mr-1">swords</span>
                                                {{ $isCompleted ? 'Ulangi' : 'Mulai Battle' }}
                                            </a>
                                        @elseif($isCompleted)
                                            <a href="{{ route('solo.map', $event) }}"
                                               class="font-headline flex items-center justify-center rounded-lg h-8 text-xs font-bold px-3 transition-colors"
                                               style="background-color: rgba(34,197,94,0.12); color: #86efac; border: 1px solid rgba(34,197,94,0.3);"
                                               onmouseover="this.style.backgroundColor='rgba(34,197,94,0.2)';"
                                               onmouseout="this.style.backgroundColor='rgba(34,197,94,0.12)';">
                                                <span class="material-symbols-outlined text-sm mr-1">replay</span>Ulangi
                                            </a>
                                        @else
                                            <a href="{{ route('solo.map', $event) }}"
                                               class="btn-cyber-primary font-headline flex items-center justify-center rounded-lg h-8 text-xs font-bold px-4">
                                                {{ $isInProgress ? 'Lanjutkan' : 'Mulai' }}
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
