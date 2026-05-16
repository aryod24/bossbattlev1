<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4">
        <!-- Result Header (match pretest index style) -->
        <div class="mb-8">
            <div class="bg-card p-4 rounded-lg border border-border shadow-sm flex items-center gap-4 w-full">
                <span class="material-symbols-outlined text-4xl text-primary">emoji_events</span>
                <div class="text-left">
                    <h1 class="text-2xl font-black text-text-primary mb-0">Pre-test Selesai!</h1>
                    <p class="text-text-secondary text-sm">Berikut adalah hasil penempatan Anda</p>
                </div>
            </div>
        </div>

        <!-- Score Card -->
        <div class="bg-card rounded-lg border border-border p-8 mb-8 text-center shadow-sm">
            <div class="mb-6">
                <p class="text-text-secondary text-sm uppercase tracking-wider mb-2">Skor Anda</p>
                <p class="text-6xl font-black text-primary">{{ $pretestResult['score'] ?? $session->skor_akhir }}%</p>
            </div>

            <div class="grid grid-cols-2 gap-4 max-w-sm mx-auto mb-6">
                <div class="bg-background-dark rounded-lg p-4">
                    <p class="text-text-secondary text-xs uppercase">Benar</p>
                    <p class="text-2xl font-bold text-green-400">{{ $pretestResult['jumlah_benar'] ?? $session->jumlah_benar }}</p>
                </div>
                <div class="bg-background-dark rounded-lg p-4">
                    <p class="text-text-secondary text-xs uppercase">Total Soal</p>
                    <p class="text-2xl font-bold text-text-primary">{{ $pretestResult['jumlah_soal'] ?? $session->jumlah_soal }}</p>
                </div>
            </div>

            @php
                $section = $pretestResult['section'] ?? auth()->user()->current_section ?? 'Easy';
                $sectionColors = [
                    'Easy' => ['bg' => 'bg-green-500/20', 'text' => 'text-green-400', 'border' => 'border-green-500/30'],
                    'Medium' => ['bg' => 'bg-yellow-500/20', 'text' => 'text-yellow-400', 'border' => 'border-yellow-500/30'],
                    'Hard' => ['bg' => 'bg-red-500/20', 'text' => 'text-red-400', 'border' => 'border-red-500/30'],
                ];
                $colors = $sectionColors[$section] ?? $sectionColors['Easy'];
            @endphp

            <div class="border-t border-border pt-6">
                <p class="text-text-secondary text-sm mb-3">Anda ditempatkan di</p>
                <div class="inline-flex items-center gap-3 {{ $colors['bg'] }} {{ $colors['border'] }} border rounded-lg px-6 py-3">
                    <span class="material-symbols-outlined {{ $colors['text'] }} text-3xl">
                        @if($section === 'Easy') looks_one
                        @elseif($section === 'Medium') looks_two
                        @else looks_3
                        @endif
                    </span>
                    <div class="text-left">
                        <p class="{{ $colors['text'] }} font-black text-2xl">Section {{ $section }}</p>
                        <p class="text-text-secondary text-xs">
                            @if($section === 'Easy') Mulai dari dasar PHP
                            @elseif($section === 'Medium') Pemahaman menengah PHP
                            @else Mahir dalam PHP
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action -->
        <div class="text-center">
            <a href="{{ route('solo.index') }}" 
               class="inline-flex items-center gap-2 rounded-lg h-12 px-8 bg-primary text-black font-bold text-base hover:brightness-95 transition-all shadow-lg shadow-primary/30">
                <span class="material-symbols-outlined">arrow_forward</span>
                Mulai Belajar
            </a>
        </div>
    </div>
</x-app-layout>
