<x-dosen-layout>
    @php
        $sections = ['Easy', 'Medium', 'Hard'];
        $grouped = collect($sections)->mapWithKeys(function ($s) use ($raids) {
            return [$s => $raids->where('section', $s)->sortBy([
                ['section_order', 'asc'],
                ['type', 'asc'],
            ])->values()];
        });
    @endphp

    <div x-data="eventSearch()" x-init="init()">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6 bg-card p-6 rounded-2xl shadow-sm border border-border">
            <div>
                <h1 class="text-4xl font-black text-text-primary tracking-tight">Manajemen Event</h1>
                <p class="text-text-muted mt-2 font-medium">Susunan event pembelajaran. Siswa akan mengikuti urutan ini: <strong>Easy</strong> → <strong>Medium</strong> → <strong>Hard</strong>.</p>
            </div>
            <a href="{{ route('dosen.events.create') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-11 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-wide shadow-sm hover:bg-accent-hover">
                <span class="material-symbols-outlined">add_circle</span>
                <span class="truncate">Buat Event Baru</span>
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-success/10 border border-success/30 rounded-lg text-sm text-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-error/10 border border-error/30 rounded-lg text-sm text-error">{{ session('error') }}</div>
        @endif

        <div class="mb-6 flex flex-wrap gap-3">
            <label class="flex flex-col h-12 flex-1 min-w-[200px]">
                <div class="flex w-full flex-1 items-stretch rounded-lg bg-card border border-border focus-within:ring-2 focus-within:ring-primary">
                    <div class="flex items-center justify-center pl-4">
                        <span class="material-symbols-outlined text-text-muted">search</span>
                    </div>
                    <input x-model="search"
                           @input="filterEvents()"
                           class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-base font-normal h-full placeholder:text-text-muted px-4 pl-2 focus:outline-none focus:ring-0 border-none bg-transparent text-text-primary"
                           placeholder="Cari event berdasarkan nama..."/>
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

        <div class="space-y-6">
            @foreach($sections as $section)
                @php
                    $sectionColor = ['Easy' => 'green', 'Medium' => 'yellow', 'Hard' => 'red'][$section];
                    $sectionRaids = $grouped[$section];
                @endphp

                <div class="bg-card border border-border rounded-2xl shadow-sm overflow-hidden section-block" data-section="{{ $section }}">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-border bg-{{ $sectionColor }}-500/5">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center size-9 rounded-lg bg-{{ $sectionColor }}-500/15 text-{{ $sectionColor }}-400 font-black">
                                {{ $loop->iteration }}
                            </span>
                            <div>
                                <h2 class="text-lg font-bold text-text-primary">Section {{ $section }}</h2>
                                <p class="text-xs text-text-muted">{{ $sectionRaids->count() }} event</p>
                            </div>
                        </div>
                        <span class="hidden md:inline-flex items-center gap-1 text-xs text-text-muted">
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                            <span>Materi → Boss</span>
                        </span>
                    </div>

                    @if($sectionRaids->isEmpty())
                        <div class="px-6 py-8 text-center text-sm text-text-muted">
                            Belum ada event di section {{ $section }}. Klik <strong>Buat Event Baru</strong> untuk menambahkan.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[900px] text-left">
                                <thead class="bg-surface-dark/50 border-b border-border">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-bold text-text-muted uppercase tracking-wider w-20">Urutan</th>
                                        <th class="px-4 py-3 text-xs font-bold text-text-muted uppercase tracking-wider">Nama Event</th>
                                        <th class="px-4 py-3 text-xs font-bold text-text-muted uppercase tracking-wider">Tipe</th>
                                        <th class="px-4 py-3 text-xs font-bold text-text-muted uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-3 text-xs font-bold text-text-muted uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-xs font-bold text-text-muted uppercase tracking-wider text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sectionRaids as $raid)
                                        <tr class="border-b border-border last:border-b-0 hover:bg-primary/10 event-row"
                                            data-nama="{{ strtolower($raid->nama) }}"
                                            data-type="{{ $raid->type }}"
                                            data-status="{{ $raid->status }}">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-center size-9 rounded-md bg-{{ $sectionColor }}-500/10 text-{{ $sectionColor }}-400 font-mono font-bold">
                                                    #{{ $raid->section_order }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-sm font-medium">
                                                {{ $raid->nama }}
                                                <p class="text-xs text-text-muted font-normal mt-0.5">
                                                    Bank: {{ $banksByGroup[$raid->question_bank_id] ?? ('Bank #' . $raid->question_bank_id) }}
                                                </p>
                                            </td>
                                            <td class="px-4 py-4 text-sm">
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
                                            <td class="px-4 py-4 text-sm text-text-muted">
                                                {{ \Carbon\Carbon::parse($raid->tanggal_mulai)->format('d M Y') }}
                                                <span class="text-xs">→ {{ \Carbon\Carbon::parse($raid->tanggal_selesai)->format('d M Y') }}</span>
                                            </td>
                                            <td class="px-4 py-4 text-sm">
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
                                            <td class="px-4 py-4 text-sm">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('dosen.events.monitoring', $raid) }}" class="flex items-center justify-center size-8 rounded-md hover:bg-primary/20 text-primary" title="Monitoring">
                                                        <span class="material-symbols-outlined text-base">monitoring</span>
                                                    </a>
                                                    <a href="{{ route('dosen.events.edit', $raid) }}"
                                                       class="flex items-center justify-center gap-1.5 h-8 px-3 rounded-md hover:bg-primary/20 text-xs font-semibold
                                                              {{ $raid->type === 'boss' ? 'text-red-400 hover:text-red-300' : 'text-primary hover:text-primary' }}"
                                                       title="Edit">
                                                        <span class="material-symbols-outlined text-sm">edit</span>
                                                        Edit
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
                    @endif
                </div>
            @endforeach
        </div>

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
                        const nama   = row.dataset.nama   || '';
                        const type   = row.dataset.type   || '';
                        const status = row.dataset.status || '';

                        const matchesSearch = !searchTerm || nama.includes(searchTerm) || type.includes(searchTerm);
                        const matchesStatus = !this.filterStatus || status === this.filterStatus;
                        const matchesType   = !this.filterType   || type   === this.filterType;

                        if (matchesSearch && matchesStatus && matchesType) {
                            row.style.display = '';
                            count++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    this.filteredCount = count;

                    document.querySelectorAll('.section-block').forEach(block => {
                        const visible = block.querySelectorAll('.event-row:not([style*="display: none"])').length;
                        block.style.display = (this.search || this.filterStatus || this.filterType) && visible === 0 ? 'none' : '';
                    });
                }
            }
        }
    </script>
</x-dosen-layout>
