<x-dosen-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-card rounded-2xl shadow-sm border border-border p-6">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 border-2 border-primary">
                    <span class="material-symbols-outlined text-3xl text-primary">school</span>
                </div>
                <div>
                    <h1 class="text-4xl font-black text-text-primary tracking-tight">Dashboard Dosen</h1>
                    <p class="text-text-muted mt-1 font-medium">Selamat datang kembali, {{ auth()->user()->nama }}</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Events Card -->
            <div class="bg-card rounded-2xl shadow-sm border border-border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-text-muted text-sm font-medium">Total Event</p>
                        <h3 class="text-4xl font-black text-text-primary mt-2">{{ $totalEvents }}</h3>
                        <p class="text-xs text-text-muted mt-1">
                            <span class="font-semibold text-primary">{{ $myEvents }}</span> dibuat oleh Anda
                        </p>
                    </div>
                    <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-primary/10">
                        <span class="material-symbols-outlined text-3xl text-primary">calendar_month</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-border">
                    <a href="{{ route('dosen.events.index') }}" class="text-sm text-info hover:text-info/80 font-medium flex items-center gap-1">
                        Lihat Semua Event
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Question Banks Card -->
            <div class="bg-card rounded-2xl shadow-sm border border-border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-text-muted text-sm font-medium">Bank Soal</p>
                        <h3 class="text-4xl font-black text-text-primary mt-2">{{ $totalBanks }}</h3>
                        <p class="text-xs text-text-muted mt-1">
                            <span class="font-semibold text-success">{{ $totalQuestions }}</span> total soal
                            @if($myBanks > 0)
                                · <span class="font-semibold text-primary">{{ $myBanks }}</span> bank Anda
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-success/10">
                        <span class="material-symbols-outlined text-3xl text-success">quiz</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-border">
                    <a href="{{ route('dosen.questions.index') }}" class="text-sm text-info hover:text-info/80 font-medium flex items-center gap-1">
                        Lihat Bank Soal
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Events -->
        <div class="bg-card rounded-2xl shadow-sm border border-border overflow-hidden">
            <div class="bg-surface-dark border-b border-border px-6 py-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-info">history</span>
                    Event Terbaru
                </h2>
                <span class="text-xs text-text-muted font-medium">5 event terakhir di sistem</span>
            </div>
            <div class="p-6">
                @if($recentEvents->isEmpty())
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-dark mb-4 border border-border">
                            <span class="material-symbols-outlined text-3xl text-text-muted">calendar_month</span>
                        </div>
                        <h3 class="text-lg font-bold text-text-primary">Belum Ada Event</h3>
                        <p class="text-text-muted mt-2">Mulai buat event pertama!</p>
                        <a href="{{ route('dosen.events.create') }}" class="inline-flex items-center px-6 py-3 mt-4 bg-primary hover:bg-accent-hover text-white font-bold rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined mr-2">add_circle</span>
                            Buat Event
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recentEvents as $event)
                            <div class="flex items-center justify-between p-4 rounded-lg bg-surface-light/50 border border-border hover:border-primary/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-lg
                                                {{ $event->type === 'boss' ? 'bg-red-500/10' : 'bg-primary/10' }}">
                                        <span class="material-symbols-outlined {{ $event->type === 'boss' ? 'text-red-400' : 'text-primary' }}">
                                            {{ $event->type === 'boss' ? 'skull' : 'menu_book' }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-text-primary">{{ $event->nama }}</h4>
                                        <p class="text-xs text-text-muted">
                                            Section {{ $event->section }} · {{ $event->type === 'boss' ? 'Boss' : 'Materi' }}
                                            · {{ $event->tanggal_mulai ? \Carbon\Carbon::parse($event->tanggal_mulai)->format('d M Y') : '-' }}
                                            @if($event->creator)
                                                · oleh {{ $event->creator->nama }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($event->status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-status-green-bg text-status-green-text border border-status-green-text/20">Active</span>
                                    @elseif($event->status === 'draft')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-status-gray-bg text-status-gray-text border border-status-gray-text/20">Draft</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-status-red-bg text-status-red-text border border-status-red-text/20">Selesai</span>
                                    @endif
                                    <a href="{{ route('dosen.events.edit', $event) }}" class="p-2 text-info hover:bg-info/10 rounded-lg transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dosen-layout>
