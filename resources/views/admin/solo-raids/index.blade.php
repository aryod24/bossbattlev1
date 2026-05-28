<x-admin-layout>
    @php
        // Group raids per section, sorted by section_order
        $sections = ['Easy', 'Medium', 'Hard'];
        $grouped = collect($sections)->mapWithKeys(function ($s) use ($raids) {
            return [$s => $raids->where('section', $s)->sortBy([
                ['section_order', 'asc'],
                ['type', 'asc'], // learning before boss when same order
            ])->values()];
        });
    @endphp

    <div x-data="eventSearch()" x-init="init()">
        <!-- PageHeading -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6 bg-card p-6 rounded-2xl shadow-sm border border-border">
            <div>
                <h1 class="text-4xl font-black text-text-primary tracking-tight">Manajemen Event</h1>
                <p class="text-text-muted mt-2 font-medium">Susunan event pembelajaran. Siswa akan mengikuti urutan ini: <strong>Easy</strong> → <strong>Medium</strong> → <strong>Hard</strong>.</p>
            </div>
            <a href="{{ route('admin.solo-raids.create') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-11 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-wide shadow-sm hover:bg-accent-hover">
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

        <!-- SearchBar + Filter -->
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
                        @php
                            // Inline grid template — ditulis sekali, dipakai di header & setiap baris.
                            // Identik di semua section ⇒ kolom otomatis sejajar antar tabel section.
                            $gridTemplate = 'grid-template-columns: minmax(0,1fr) 130px 200px 110px 220px;';
                        @endphp
                        <div class="overflow-x-auto">
                            <div class="min-w-[1000px]">
                                {{-- Grid header --}}
                                <div class="grid items-center gap-4 px-4 py-3 bg-surface-dark/50 border-b border-border text-xs font-bold text-text-muted uppercase tracking-wider"
                                     style="{{ $gridTemplate }}">
                                    <div>Nama Event</div>
                                    <div>Tipe</div>
                                    <div>Tanggal</div>
                                    <div>Status</div>
                                    <div class="text-right">Aksi</div>
                                </div>

                                {{-- Grid rows --}}
                                @foreach($sectionRaids as $raid)
                                    <div class="grid items-center gap-4 px-4 py-4 border-b border-border last:border-b-0 hover:bg-primary/10 event-row text-sm"
                                         data-nama="{{ strtolower($raid->nama) }}"
                                         data-type="{{ $raid->type }}"
                                         data-status="{{ $raid->status }}"
                                         style="{{ $gridTemplate }}">
                                        {{-- Nama Event (truncate-friendly) --}}
                                        <div class="min-w-0">
                                            <div class="font-medium truncate" title="{{ $raid->nama }}">{{ $raid->nama }}</div>
                                            <p class="text-xs text-text-muted truncate"
                                               title="{{ $banksByGroup[$raid->question_bank_id] ?? ('Bank #' . $raid->question_bank_id) }}">
                                                Bank: {{ $banksByGroup[$raid->question_bank_id] ?? ('Bank #' . $raid->question_bank_id) }}
                                            </p>
                                        </div>

                                        {{-- Tipe --}}
                                        <div>
                                            @if($raid->type === 'boss')
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/20">
                                                    <span class="material-symbols-outlined" style="font-size:13px">skull</span> Boss
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-bold bg-primary/10 text-primary border border-primary/20">
                                                    <span class="material-symbols-outlined" style="font-size:13px">menu_book</span> Materi
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Tanggal --}}
                                        <div class="text-text-muted">
                                            <div class="whitespace-nowrap">{{ \Carbon\Carbon::parse($raid->tanggal_mulai)->format('d M Y') }}</div>
                                            <div class="text-xs whitespace-nowrap">→ {{ \Carbon\Carbon::parse($raid->tanggal_selesai)->format('d M Y') }}</div>
                                        </div>

                                        {{-- Status --}}
                                        <div>
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
                                        </div>

                                        {{-- Aksi --}}
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.solo-raids.monitoring', $raid) }}"
                                               class="flex items-center justify-center size-10 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary transition-colors"
                                               title="Monitoring">
                                                <span class="material-symbols-outlined" style="font-size:22px">monitoring</span>
                                            </a>
                                            <a href="{{ route('admin.solo-raids.edit', $raid) }}"
                                               class="flex items-center justify-center size-10 rounded-lg transition-colors
                                                      {{ $raid->type === 'boss'
                                                          ? 'bg-red-500/10 hover:bg-red-500/20 text-red-400'
                                                          : 'bg-primary/10 hover:bg-primary/20 text-primary' }}"
                                               title="{{ $raid->type === 'boss' ? 'Edit Boss Battle' : 'Edit Materi' }}">
                                                <span class="material-symbols-outlined" style="font-size:22px">edit</span>
                                            </a>
                                            <form action="{{ route('admin.solo-raids.duplicate', $raid) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="flex items-center justify-center size-10 rounded-lg bg-border/40 hover:bg-primary/20 hover:text-primary text-text-muted transition-colors"
                                                        title="Duplicate ke section lain"
                                                        onclick="return confirm('Duplicate event ini ke section lain yang masih kosong?')">
                                                    <span class="material-symbols-outlined" style="font-size:22px">content_copy</span>
                                                </button>
                                            </form>
                                            @if($raid->status !== 'active')
                                                <form action="{{ route('admin.solo-raids.destroy', $raid) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="flex items-center justify-center size-10 rounded-lg bg-error/10 hover:bg-error/20 text-error transition-colors"
                                                            title="Hapus event"
                                                            onclick="return confirm('Yakin ingin menghapus event ini?')">
                                                        <span class="material-symbols-outlined" style="font-size:22px">delete</span>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="flex items-center justify-center size-10 rounded-lg bg-border/20 text-border cursor-not-allowed"
                                                      title="Event aktif tidak dapat dihapus. Ubah status ke Draft/Selesai dulu.">
                                                    <span class="material-symbols-outlined opacity-40" style="font-size:22px">delete</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Empty State (when filter has no result) -->
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

                    // Hide section blocks where all rows are hidden
                    document.querySelectorAll('.section-block').forEach(block => {
                        const visible = block.querySelectorAll('.event-row:not([style*="display: none"])').length;
                        block.style.display = (this.search || this.filterStatus || this.filterType) && visible === 0 ? 'none' : '';
                    });
                }
            }
        }
    </script>
</x-admin-layout>
