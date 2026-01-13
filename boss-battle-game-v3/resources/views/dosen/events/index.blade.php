<x-dosen-layout>
    <!-- PageHeading -->
    <!-- PageHeading -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6 bg-card p-6 rounded-2xl shadow-sm border border-border">
        <div>
            <h1 class="text-4xl font-black text-text-primary tracking-tight">Manajemen Event</h1>
            <p class="text-text-muted mt-2 font-medium">Manage solo raid events and schedules.</p>
        </div>
        <a href="{{ route('dosen.events.create') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-11 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-wide shadow-sm hover:bg-accent-hover">
            <span class="material-symbols-outlined">add_circle</span>
            <span class="truncate">Buat Event Baru</span>
        </a>
    </div>

    <!-- SearchBar -->
    <div class="mb-4">
        <label class="flex flex-col h-12 w-full">
            <div class="flex w-full flex-1 items-stretch rounded-lg bg-card border border-border focus-within:ring-2 focus-within:ring-primary">
                <div class="flex items-center justify-center pl-4">
                    <span class="material-symbols-outlined text-text-muted">search</span>
                </div>
                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-base font-normal h-full placeholder:text-text-muted px-4 pl-2 focus:outline-none focus:ring-0 border-none bg-transparent text-text-primary" placeholder="Cari event..." value=""/>
            </div>
        </label>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg border border-border bg-card">
        <table class="w-full min-w-[1000px] text-left">
            <thead class="border-b border-border">
                <tr>
                    <th class="px-4 py-3 text-sm font-medium">Nama Event</th>
                    <th class="px-4 py-3 text-sm font-medium">Level</th>
                    <th class="px-4 py-3 text-sm font-medium">Tanggal & Waktu</th>
                    <th class="px-4 py-3 text-sm font-medium">Status</th>
                    <th class="px-4 py-3 text-sm font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($raids as $raid)
                    <tr class="border-b border-border hover:bg-primary/10">
                        <td class="px-4 py-3 text-sm font-medium">{{ $raid->nama }}</td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex gap-1">
                                <form action="{{ route('dosen.events.toggle-level', $raid) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="level" value="easy">
                                    <button type="submit" class="w-6 h-6 rounded flex items-center justify-center text-xs font-bold {{ $raid->easy_enabled ? 'bg-success/20 text-success' : 'bg-border text-text-muted' }}" title="Toggle Easy">E</button>
                                </form>
                                <form action="{{ route('dosen.events.toggle-level', $raid) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="level" value="medium">
                                    <button type="submit" class="w-6 h-6 rounded flex items-center justify-center text-xs font-bold {{ $raid->medium_enabled ? 'bg-warning/20 text-warning' : 'bg-border text-text-muted' }}" title="Toggle Medium">M</button>
                                </form>
                                <form action="{{ route('dosen.events.toggle-level', $raid) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="level" value="hard">
                                    <button type="submit" class="w-6 h-6 rounded flex items-center justify-center text-xs font-bold {{ $raid->hard_enabled ? 'bg-error/20 text-error' : 'bg-border text-text-muted' }}" title="Toggle Hard">H</button>
                                </form>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-text-muted">
                            {{ \Carbon\Carbon::parse($raid->tanggal_mulai)->format('d M Y, H:i') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($raid->status === 'active')
                                <span class="relative inline-flex items-center rounded-md bg-status-red-bg px-2 py-1 text-xs font-medium text-status-red-text ring-1 ring-inset ring-red-500/20">
                                    <span class="absolute -top-0.5 -right-0.5 flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                    </span>
                                    Active
                                </span>
                            @elseif($raid->status === 'selesai')
                                <span class="inline-flex items-center rounded-md bg-status-green-bg px-2 py-1 text-xs font-medium text-status-green-text ring-1 ring-inset ring-green-600/20">Selesai</span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-status-gray-bg px-2 py-1 text-xs font-medium text-status-gray-text ring-1 ring-inset ring-gray-500/20">Draft</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('dosen.events.edit', $raid) }}" class="flex items-center justify-center size-8 rounded-md hover:bg-primary/20" title="Edit">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </a>
                                <form action="{{ route('dosen.events.duplicate', $raid) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="flex items-center justify-center size-8 rounded-md hover:bg-primary/20" title="Duplicate" onclick="return confirm('Duplicate this raid?')">
                                        <span class="material-symbols-outlined text-base">content_copy</span>
                                    </button>
                                </form>
                                @if($raid->status !== 'active')
                                    <form action="{{ route('dosen.events.destroy', $raid) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center justify-center size-8 rounded-md hover:bg-error/20 text-error" title="Delete" onclick="return confirm('Are you sure?')">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav class="flex items-center justify-center p-4 mt-4">
        <a class="flex size-10 items-center justify-center" href="#"><span class="material-symbols-outlined">chevron_left</span></a>
        <a class="text-sm font-bold leading-normal flex size-10 items-center justify-center rounded-full bg-primary/30" href="#">1</a>
        <a class="text-sm font-normal leading-normal flex size-10 items-center justify-center rounded-full hover:bg-primary/20" href="#">2</a>
        <a class="text-sm font-normal leading-normal flex size-10 items-center justify-center rounded-full hover:bg-primary/20" href="#">3</a>
        <span class="text-sm font-normal leading-normal flex size-10 items-center justify-center rounded-full">...</span>
        <a class="flex size-10 items-center justify-center" href="#"><span class="material-symbols-outlined">chevron_right</span></a>
    </nav>
</x-dosen-layout>
