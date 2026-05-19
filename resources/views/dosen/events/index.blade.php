<x-dosen-layout>
    <div x-data="eventSearch()" x-init="init()">
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

        <!-- SearchBar + Filter -->
        <div class="mb-4 flex flex-wrap gap-3">
            <label class="flex flex-col h-12 flex-1 min-w-[200px]">
                <div class="flex w-full flex-1 items-stretch rounded-lg bg-card border border-border focus-within:ring-2 focus-within:ring-primary">
                    <div class="flex items-center justify-center pl-4">
                        <span class="material-symbols-outlined text-text-muted">search</span>
                    </div>
                    <input x-model="search" 
                           @input="filterEvents()"
                           class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-base font-normal h-full placeholder:text-text-muted px-4 pl-2 focus:outline-none focus:ring-0 border-none bg-transparent text-text-primary" 
                           placeholder="Cari event berdasarkan nama, section, atau tipe..."/>
                </div>
            </label>
            <select x-model="filterStatus" @change="filterEvents()"
                    class="h-12 px-4 rounded-lg bg-card border border-border text-text-primary text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">Semua Status</option>
                <option value="active">Active</option>
                <option value="draft">Draft</option>
                <option value="selesai">Selesai</option>
            </select>
            <select x-model="filterType" @change="filterEvents()"
                    class="h-12 px-4 rounded-lg bg-card border border-border text-text-primary text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">Semua Tipe</option>
                <option value="learning">Materi</option>
                <option value="boss">Boss</option>
            </select>
        </div>

        <!-- Results Count -->
        <div class="mb-3 text-sm text-text-muted font-medium" x-show="search || filterStatus || filterType">
            <span x-text="filteredCount"></span> event ditemukan
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-border bg-card">
            <table class="w-full min-w-[1000px] text-left">
                <thead class="border-b border-border">
                    <tr>
                        <th class="px-4 py-3 text-sm font-medium">Nama Event</th>
                        <th class="px-4 py-3 text-sm font-medium">Tipe</th>
                        <th class="px-4 py-3 text-sm font-medium">Tanggal & Waktu</th>
                        <th class="px-4 py-3 text-sm font-medium">Status</th>
                        <th class="px-4 py-3 text-sm font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($raids as $raid)
                        <tr class="border-b border-border hover:bg-primary/10 event-row"
                            data-nama="{{ strtolower($raid->nama) }}"
                            data-section="{{ strtolower($raid->section) }}"
                            data-type="{{ $raid->type }}"
                            data-status="{{ $raid->status }}">
                            <td class="px-4 py-3 text-sm font-medium">
                                {{ $raid->nama }}
                                <p class="text-xs text-text-muted font-normal mt-0.5">Section: {{ $raid->section }} · Order #{{ $raid->section_order }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($raid->type === 'boss')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/20">
                                        <span class="material-symbols-outlined" style="font-size:13px">skull</span> Boss
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-bold bg-primary/10 text-primary border border-primary/20">
                                        <span class="material-symbols-outlined" style="font-size:13px">menu_book</span> Materi
                                    </span>
                                @endif
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
                                    <a href="{{ route('dosen.events.monitoring', $raid) }}" class="flex items-center justify-center size-8 rounded-md hover:bg-primary/20 text-primary" title="Monitoring">
                                        <span class="material-symbols-outlined text-base">monitoring</span>
                                    </a>
                                    <a href="{{ route('dosen.events.edit', $raid) }}"
                                       class="flex items-center justify-center gap-1.5 h-8 px-3 rounded-md hover:bg-primary/20 text-xs font-semibold
                                              {{ $raid->type === 'boss' ? 'text-red-400 hover:text-red-300' : 'text-primary hover:text-primary' }}"
                                       title="{{ $raid->type === 'boss' ? 'Edit Boss Battle' : 'Edit Materi' }}">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                        {{ $raid->type === 'boss' ? 'Edit Boss' : 'Edit Materi' }}
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

        <!-- Empty State -->
        <div x-show="filteredCount === 0 && (search || filterStatus || filterType)" 
             class="mt-4 p-8 text-center bg-card rounded-lg border border-border">
            <span class="material-symbols-outlined text-4xl text-text-muted mb-2">search_off</span>
            <p class="text-text-muted font-medium">Tidak ada event yang cocok dengan pencarian.</p>
            <button @click="search = ''; filterStatus = ''; filterType = ''; filterEvents()" 
                    class="mt-3 text-sm text-primary hover:underline font-semibold">
                Reset Filter
            </button>
        </div>
    </div>

    <script>
        function eventSearch() {
            return {
                search: '',
                filterStatus: '',
                filterType: '',
                filteredCount: 0,

                init() {
                    this.filteredCount = document.querySelectorAll('.event-row').length;
                },

                filterEvents() {
                    const rows = document.querySelectorAll('.event-row');
                    const searchTerm = this.search.toLowerCase().trim();
                    let count = 0;

                    rows.forEach(row => {
                        const nama = row.dataset.nama || '';
                        const section = row.dataset.section || '';
                        const type = row.dataset.type || '';
                        const status = row.dataset.status || '';

                        // Text search (nama or section)
                        const matchesSearch = !searchTerm || 
                            nama.includes(searchTerm) || 
                            section.includes(searchTerm) ||
                            type.includes(searchTerm);

                        // Status filter
                        const matchesStatus = !this.filterStatus || status === this.filterStatus;

                        // Type filter
                        const matchesType = !this.filterType || type === this.filterType;

                        if (matchesSearch && matchesStatus && matchesType) {
                            row.style.display = '';
                            count++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    this.filteredCount = count;
                }
            }
        }
    </script>
</x-dosen-layout>
