<x-dosen-layout>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6 bg-card p-6 rounded-2xl shadow-sm border border-border">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('dosen.events.index') }}" class="text-text-muted hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-4xl font-black text-text-primary tracking-tight">Monitoring Event</h1>
            </div>
            <p class="text-text-muted mt-2 font-medium">Memantau aktivitas mahasiswa pada event: <span class="text-primary font-bold">{{ $soloRaid->nama }}</span></p>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg border border-border bg-card shadow-sm">
        <table class="w-full min-w-[1000px] text-left">
            <thead class="border-b border-border">
                <tr>
                    <th class="px-4 py-3 text-sm font-medium">Mahasiswa</th>
                    <th class="px-4 py-3 text-sm font-medium">Status Event</th>
                    @if($soloRaid->type !== 'boss')
                        <th class="px-4 py-3 text-sm font-medium text-center">Progress Materi</th>
                    @endif
                    <th class="px-4 py-3 text-sm font-medium text-center">Total Percobaan Kuis</th>
                    @if($soloRaid->type === 'boss')
                        <th class="px-4 py-3 text-sm font-medium text-center">Status Boss</th>
                    @endif
                    <th class="px-4 py-3 text-sm font-medium">Terakhir Aktif</th>
                    <th class="px-4 py-3 text-sm font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monitoringData as $data)
                    <tr class="border-b border-border hover:bg-primary/10">
                        <td class="px-4 py-3">
                            <div class="font-bold text-sm text-text-primary">
                                {{ $data['user']->nama ?? '-' }}
                            </div>
                            <div class="text-xs text-text-muted flex flex-wrap gap-x-2 gap-y-0.5">
                                @if($data['user']->nim)
                                    <span class="font-semibold text-text-light-secondary">{{ $data['user']->nim }}</span>
                                    <span class="text-border">·</span>
                                @endif
                                <span>{{ $data['user']->email }}</span>
                                @if($data['user']->kelas)
                                    <span class="text-border">·</span>
                                    <span class="text-primary/80 font-medium">{{ $data['user']->kelas }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm capitalize">
                            @if($data['progress'] === 'completed')
                                <span class="inline-flex items-center rounded-md bg-status-green-bg px-2 py-1 text-xs font-medium text-status-green-text ring-1 ring-inset ring-green-600/20">Selesai</span>
                            @elseif($data['progress'] === 'in_progress')
                                <span class="inline-flex items-center rounded-md bg-primary/20 px-2 py-1 text-xs font-medium text-primary ring-1 ring-inset ring-primary/20">Sedang Mengerjakan</span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-status-gray-bg px-2 py-1 text-xs font-medium text-status-gray-text ring-1 ring-inset ring-gray-500/20">Belum Mulai</span>
                            @endif
                        </td>
                        @if($soloRaid->type !== 'boss')
                            <td class="px-4 py-3 text-sm text-center font-bold">
                                {{ $data['completed_nodes_count'] }} / {{ $data['total_nodes'] }}
                            </td>
                        @endif
                        <td class="px-4 py-3 text-sm text-center font-bold">
                            {{ $data['attempts'] }}x
                        </td>
                        @if($soloRaid->type === 'boss')
                            <td class="px-4 py-3 text-sm text-center">
                                @if($data['boss_defeated'])
                                    <span class="inline-flex items-center gap-1 rounded-md bg-status-green-bg px-2 py-1 text-xs font-bold text-status-green-text">
                                        <span class="material-symbols-outlined text-[14px]">skull</span> Kalah
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-md bg-red-500/10 px-2 py-1 text-xs font-bold text-red-500">
                                        <span class="material-symbols-outlined text-[14px]">skull</span> Hidup
                                    </span>
                                @endif
                            </td>
                        @endif
                        <td class="px-4 py-3 text-sm text-text-muted">
                            {{ $data['last_active'] ? \Carbon\Carbon::parse($data['last_active'])->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right">
                            <a href="{{ route('dosen.events.monitoring.detail', [$soloRaid->id, $data['user']->id]) }}" 
                               class="inline-flex items-center justify-center gap-1.5 h-8 px-3 rounded-md bg-border hover:bg-primary/20 text-xs font-semibold text-text-primary hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-text-muted"> Belum ada data mahasiswa. </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
        <div class="mt-6">
            {{ $students->links() }}
        </div>
    @endif
</x-dosen-layout>
