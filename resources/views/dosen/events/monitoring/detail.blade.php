<x-dosen-layout>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6 bg-card p-6 rounded-2xl shadow-sm border border-border">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('dosen.events.monitoring', $soloRaid->id) }}" class="text-text-muted hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-4xl font-black text-text-primary tracking-tight">Timeline Aktivitas</h1>
            </div>
            <p class="text-text-muted mt-2 font-medium">Event: <span class="text-primary font-bold">{{ $soloRaid->nama }}</span></p>
            <p class="text-text-muted mt-1 font-medium">
                Mahasiswa:
                <span class="text-text-primary font-bold">{{ $user->nama ?? '-' }}</span>
                @if($user->nim)
                    <span class="text-text-muted text-sm">({{ $user->nim }})</span>
                @endif
                <span class="text-text-muted text-sm">— {{ $user->email }}</span>
                @if($user->kelas)
                    <span class="text-primary text-sm">· {{ $user->kelas }}</span>
                @endif
            </p>
        </div>
    </div>

    <div class="bg-card rounded-2xl border border-border p-6 shadow-sm">
        <h2 class="text-xl font-bold mb-6">Riwayat Aktivitas</h2>
        
        @if(count($timeline) > 0)
            <div class="relative pl-6 border-l-2 border-border ml-3 space-y-8">
                @foreach($timeline as $event)
                    <div class="relative items-start">
                        <!-- Timeline Dot -->
                        <div class="absolute -left-[31px] top-1.5 h-4 w-4 rounded-full bg-card border-2 
                            {{ $event['type'] === 'materi' ? 'border-primary' : ($event['type'] === 'kuis_selesai' ? 'border-status-green-text' : 'border-status-red-text') }}">
                        </div>
                        
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-semibold text-text-primary">{{ $event['title'] }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-border text-text-muted font-medium">{{ \Carbon\Carbon::parse($event['time'])->format('d M Y, H:i:s') }}</span>
                            </div>
                            <p class="text-sm text-text-muted">{{ $event['status'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-text-muted">
                <span class="material-symbols-outlined text-4xl mb-2 opacity-50">history</span>
                <p>Belum ada riwayat aktivitas yang tercatat untuk mahasiswa ini pada event tersebut.</p>
            </div>
        @endif
    </div>
</x-dosen-layout>
