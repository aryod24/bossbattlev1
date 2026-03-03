<x-app-layout>
    <div class="flex flex-col gap-6">

        {{-- Page Heading --}}
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <p class="text-4xl font-black leading-tight tracking-tight text-text-primary">Solo Raid</p>
                <p class="text-text-muted text-sm mt-1">Section <span class="font-bold text-primary">{{ $currentSection }}</span> — Selesaikan semua event untuk unlock Boss Battle!</p>
            </div>
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'dosen')
            <a href="{{ route('admin.solo-raids.create') }}" class="flex items-center gap-2 rounded-lg h-10 bg-primary text-black text-sm font-bold px-5 shadow-sm hover:brightness-95 transition-all">
                <span class="material-symbols-outlined text-sm">add_circle</span> Buat Event
            </a>
            @endif
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-green-500/10 border border-green-500/30 text-green-600 dark:text-green-400 px-4 py-3 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-lg bg-red-500/10 border border-red-500/30 text-red-600 dark:text-red-400 px-4 py-3 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        @if($events->isEmpty())
            <div class="rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-16 text-center">
                <span class="material-symbols-outlined text-5xl text-text-muted mb-3 block">hourglass_empty</span>
                <p class="font-bold text-text-primary mb-1">Belum ada event aktif</p>
                <p class="text-sm text-text-muted">Event untuk section {{ $currentSection }} belum tersedia.</p>
            </div>
        @else
            {{-- Event Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($events as $index => $event)
                @php
                    $isExpired   = now()->greaterThan($event->tanggal_selesai);
                    $progress    = $event->progress;
                    $isCompleted = $progress && $progress->status === 'completed';
                    $isInProgress= $progress && $progress->status === 'in_progress';
                    $isUnlocked  = $event->is_unlocked && !$isExpired;
                    $isBoss      = $event->type === 'boss';
                @endphp

                <div class="flex flex-col bg-surface-light dark:bg-surface-dark rounded-xl border
                    {{ $isCompleted ? 'border-green-500/40' : ($isBoss ? 'border-red-500/30' : 'border-border-light dark:border-border-dark') }}
                    shadow-sm hover:shadow-md transition-all duration-200 {{ !$isUnlocked ? 'opacity-50' : 'hover:-translate-y-0.5' }} overflow-hidden">

                    {{-- Coloured top stripe --}}
                    @if($isBoss)
                        <div class="h-1.5 bg-gradient-to-r from-red-600 to-orange-500"></div>
                    @elseif($isCompleted)
                        <div class="h-1.5 bg-gradient-to-r from-green-500 to-teal-400"></div>
                    @else
                        <div class="h-1.5 bg-gradient-to-r from-primary to-yellow-300"></div>
                    @endif

                    <div class="p-5 flex-1 flex flex-col gap-3">
                        {{-- Top row: badges + icon --}}
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex flex-col gap-1.5 flex-1 min-w-0">
                                <div class="flex flex-wrap gap-1.5 items-center">
                                    @if($isBoss)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 bg-red-500/10 text-red-500 border border-red-500/20 rounded uppercase tracking-wider">
                                            <span class="material-symbols-outlined" style="font-size:11px">skull</span> Boss Battle
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 bg-primary/10 text-text-light-secondary dark:text-text-dark-secondary border border-primary/20 rounded uppercase tracking-wider">
                                            <span class="material-symbols-outlined" style="font-size:11px">menu_book</span> Materi #{{ $index + 1 }}
                                        </span>
                                    @endif

                                    @if($isCompleted)
                                        <span class="text-[10px] font-bold px-2 py-0.5 bg-green-500/10 text-green-500 border border-green-500/20 rounded uppercase">✓ Selesai</span>
                                    @elseif($isInProgress)
                                        <span class="text-[10px] font-bold px-2 py-0.5 bg-blue-500/10 text-blue-500 border border-blue-500/20 rounded uppercase">Sedang</span>
                                    @elseif(!$isUnlocked)
                                        <span class="text-[10px] font-bold px-2 py-0.5 bg-text-muted/10 text-text-muted border border-text-muted/20 rounded uppercase flex items-center gap-0.5">
                                            <span class="material-symbols-outlined" style="font-size:10px">lock</span> Terkunci
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-sm font-bold text-text-primary leading-snug">{{ $event->nama }}</h3>
                            </div>
                            {{-- Icon --}}
                            @if($isBoss)
                                <div class="shrink-0 w-10 h-10 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-red-500">skull</span>
                                </div>
                            @else
                                <div class="shrink-0 w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center">
                                    <span class="text-sm font-black text-text-light-secondary dark:text-text-dark-secondary">{{ $index + 1 }}</span>
                                </div>
                            @endif
                        </div>

                        <p class="text-xs text-text-muted line-clamp-2">{{ $event->deskripsi }}</p>

                        {{-- Date + CTA --}}
                        <div class="mt-auto flex items-center justify-between pt-3 border-t border-border-light dark:border-border-dark">
                            <div class="flex items-center gap-1 text-xs text-text-muted">
                                <span class="material-symbols-outlined text-sm">calendar_today</span>
                                <span>{{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d M Y') }}</span>
                            </div>

                            @if(!$isUnlocked || $isExpired)
                                <div class="flex items-center gap-1 text-xs text-text-muted">
                                    <span class="material-symbols-outlined text-sm">lock</span>
                                    <span>Terkunci</span>
                                </div>
                            @elseif($isBoss)
                                <a href="{{ route('solo.boss', $event) }}"
                                   class="flex items-center justify-center rounded-lg h-8 bg-red-500 text-white text-xs font-bold px-4 shadow-sm hover:bg-red-600 transition-all">
                                    <span class="material-symbols-outlined text-sm mr-1">swords</span>
                                    {{ $isCompleted ? 'Ulangi' : 'Mulai Battle' }}
                                </a>
                            @elseif($isCompleted)
                                <a href="{{ route('solo.map', $event) }}"
                                   class="flex items-center justify-center rounded-lg h-8 bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20 text-xs font-bold px-3 hover:bg-green-500/20 transition-colors">
                                    <span class="material-symbols-outlined text-sm mr-1">replay</span>Ulangi
                                </a>
                            @else
                                <a href="{{ route('solo.map', $event) }}"
                                   class="flex items-center justify-center rounded-lg h-8 bg-primary text-black text-xs font-bold px-4 shadow-sm hover:brightness-95 transition-all">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route("solo.check-expired") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(r => r.json()).then(d => { if (d.processed > 0) window.location.reload(); })
              .catch(() => {});
        });
    </script>
</x-app-layout>
