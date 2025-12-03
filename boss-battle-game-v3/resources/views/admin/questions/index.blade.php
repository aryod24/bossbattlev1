<x-admin-layout>
    <!-- PageHeading -->
    <header class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h1 class="text-4xl font-black tracking-tight">Bank Soal</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.questions.template') }}" class="flex items-center justify-center gap-2 overflow-hidden rounded-lg h-11 px-5 bg-success hover:brightness-110 text-white text-sm font-bold leading-normal tracking-wide shadow-sm">
                <span class="material-symbols-outlined">download</span>
                <span class="truncate">Download Template</span>
            </a>
            <button onclick="toggleBulkUpload()" id="btnBulkUpload" class="flex items-center justify-center gap-2 overflow-hidden rounded-lg h-11 px-5 bg-info hover:brightness-110 text-white text-sm font-bold leading-normal tracking-wide shadow-sm">
                <span class="material-symbols-outlined">upload_file</span>
                <span class="truncate">Bulk Upload</span>
            </button>
            <a href="{{ route('admin.questions.create') }}" class="flex items-center justify-center gap-2 overflow-hidden rounded-lg h-11 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-wide shadow-sm hover:bg-accent-hover">
                <span class="material-symbols-outlined">add_circle</span>
                <span class="truncate">Tambah Soal</span>
            </a>
        </div>
    </header>

    <!-- Bank Tabs (Dynamic) -->
    <div class="flex gap-2 p-1 bg-card border border-border rounded-lg mb-4 overflow-x-auto">
        @foreach($bankConfig as $bankId => $bank)
            <a href="{{ route('admin.questions.index', ['bank' => $bankId, 'search' => request('search'), 'level' => request('level')]) }}" 
               class="flex h-10 shrink-0 items-center justify-center gap-2 px-4 rounded-lg {{ $currentBank == $bankId ? 'bg-primary text-white font-bold shadow-sm' : 'hover:bg-primary/20' }} transition-colors">
                <span class="material-symbols-outlined text-sm">{{ $bank['icon'] ?? 'quiz' }}</span>
                <span class="text-sm">{{ $bank['name'] }}</span>
                <span class="text-xs opacity-75">({{ $bankCounts[$bankId] ?? 0 }})</span>
            </a>
        @endforeach
    </div>

    <!-- SearchBar & Filter -->
    <div class="flex gap-4 mb-4">
        <!-- Search Bar -->
        <div class="flex-1">
            <label class="flex flex-col h-12 w-full">
                <div class="flex w-full flex-1 items-stretch rounded-lg bg-card border border-border focus-within:ring-2 focus-within:ring-primary">
                    <div class="flex items-center justify-center pl-4">
                        <span class="material-symbols-outlined text-text-muted">search</span>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        id="search-questions"
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-base font-normal h-full placeholder:text-text-muted px-4 pl-2 focus:outline-none focus:ring-0 border-none bg-transparent text-text-primary" 
                        placeholder="Cari soal..." 
                        value="{{ request('search') }}"
                        oninput="debounceSearch(this)"
                    />
                </div>
            </label>
        </div>

        <!-- Level Filter Dropdown -->
        <div class="w-48">
            <select 
                name="level" 
                id="level-filter"
                class="form-select w-full h-12 rounded-lg bg-card border border-border text-base font-normal px-4 focus:outline-none focus:ring-2 focus:ring-primary text-text-primary"
                onchange="filterByLevel(this)"
            >
                <option value="" {{ !request('level') ? 'selected' : '' }}>Semua Level</option>
                <option value="Easy" {{ request('level') == 'Easy' ? 'selected' : '' }}>Easy</option>
                <option value="Medium" {{ request('level') == 'Medium' ? 'selected' : '' }}>Medium</option>
                <option value="Hard" {{ request('level') == 'Hard' ? 'selected' : '' }}>Hard</option>
            </select>
        </div>
    </div>

    <!-- Bulk Upload Section (collapsible) -->
    <div id="bulkUploadSection" class="mb-4" style="display: none;">
        <div class="bg-card border border-border rounded-lg p-5">
            <h3 class="text-lg font-bold mb-4">Bulk Upload Questions (JSON)</h3>
            <form action="{{ route('admin.questions.bulk-upload') }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-4">
                @csrf
                <div class="flex-1">
                    <label for="file" class="block text-sm font-medium mb-2">Select JSON File</label>
                    <input type="file" name="file" id="file" accept=".json,.txt" class="block w-full text-sm
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-primary file:text-white
                        hover:file:bg-accent-hover
                    " required>
                </div>
                <button type="submit" class="flex items-center justify-center rounded-lg h-10 px-5 bg-info hover:brightness-110 text-white text-sm font-bold transition-colors">
                    Upload Questions
                </button>
            </form>
            <p class="text-xs text-text-muted mt-2">Make sure your JSON matches the template format.</p>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg border border-border bg-card">
        <table class="w-full min-w-[1000px] text-left">
            <thead class="border-b border-border">
                <tr>
                    <th class="px-4 py-3 text-sm font-medium">Level</th>
                    <th class="px-4 py-3 text-sm font-medium">Pertanyaan</th>
                    <th class="px-4 py-3 text-sm font-medium">Tipe</th>
                    <th class="px-4 py-3 text-sm font-medium">Jawaban Benar</th>
                    <th class="px-4 py-3 text-sm font-medium">XP</th>
                    <th class="px-4 py-3 text-sm font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $question)
                    <tr class="border-b border-border hover:bg-primary/10 transition-colors">
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset
                                {{ $question->level === 'Easy' ? 'bg-status-green-bg text-status-green-text ring-green-600/20' : '' }}
                                {{ $question->level === 'Medium' ? 'bg-warning/10 text-warning ring-yellow-600/20' : '' }}
                                {{ $question->level === 'Hard' ? 'bg-status-red-bg text-status-red-text ring-red-500/20' : '' }}">
                                {{ $question->level }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="max-w-md truncate" title="{{ $question->soal_text }}">
                                {{ $question->soal_text }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-text-muted">
                            {{ $question->tipe == 'multiple_choice' ? 'Pilihan Ganda' : 'Isian Singkat' }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium">
                            <div class="max-w-xs truncate" title="{{ $question->jawaban_benar }}">
                                {{ $question->jawaban_benar }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-text-muted">
                            {{ $question->bobot_xp }} XP
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.questions.edit', $question) }}" class="flex items-center justify-center size-8 rounded-md hover:bg-primary/20 transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </a>
                                <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center justify-center size-8 rounded-md hover:bg-error/20 text-error transition-colors" title="Delete" onclick="return confirm('Yakin ingin menghapus soal ini?')">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-text-muted">
                            Tidak ada soal ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($questions->hasPages())
        <nav class="flex items-center justify-center p-4 mt-4">
            @if($questions->onFirstPage())
                <span class="flex size-10 items-center justify-center text-text-muted cursor-not-allowed">
                    <span class="material-symbols-outlined">chevron_left</span>
                </span>
            @else
                <a href="{{ $questions->previousPageUrl() }}" class="flex size-10 items-center justify-center hover:bg-primary/20 rounded-md transition-colors">
                    <span class="material-symbols-outlined">chevron_left</span>
                </a>
            @endif

            @foreach(range(1, $questions->lastPage()) as $page)
                @if($page == $questions->currentPage())
                    <span class="text-sm font-bold leading-normal flex size-10 items-center justify-center rounded-full bg-primary/30">{{ $page }}</span>
                @elseif($page == 1 || $page == $questions->lastPage() || abs($page - $questions->currentPage()) <= 2)
                    <a href="{{ $questions->url($page) }}" class="text-sm font-normal leading-normal flex size-10 items-center justify-center rounded-full hover:bg-primary/20 transition-colors">{{ $page }}</a>
                @elseif(abs($page - $questions->currentPage()) == 3)
                    <span class="text-sm font-normal leading-normal flex size-10 items-center justify-center">...</span>
                @endif
            @endforeach

            @if($questions->hasMorePages())
                <a href="{{ $questions->nextPageUrl() }}" class="flex size-10 items-center justify-center hover:bg-primary/20 rounded-md transition-colors">
                    <span class="material-symbols-outlined">chevron_right</span>
                </a>
            @else
                <span class="flex size-10 items-center justify-center text-text-muted cursor-not-allowed">
                    <span class="material-symbols-outlined">chevron_right</span>
                </span>
            @endif
        </nav>
    @endif

    <script>
        // Toggle Bulk Upload Section
        function toggleBulkUpload() {
            const section = document.getElementById('bulkUploadSection');
            if (section.style.display === 'none') {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        }

        // Debounce Search
        let searchTimeout;
        function debounceSearch(input) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchValue = input.value;
                const currentLevel = '{{ request("level") }}';
                const currentBank = '{{ $currentBank }}';
                let url = '{{ route("admin.questions.index") }}?bank=' + currentBank + '&search=' + encodeURIComponent(searchValue);
                if (currentLevel) {
                    url += '&level=' + encodeURIComponent(currentLevel);
                }
                window.location.href = url;
            }, 500);
        }

        // Filter by Level
        function filterByLevel(select) {
            const levelValue = select.value;
            const currentSearch = '{{ request("search") }}';
            const currentBank = '{{ $currentBank }}';
            let url = '{{ route("admin.questions.index") }}?bank=' + currentBank + '&level=' + encodeURIComponent(levelValue);
            if (currentSearch) {
                url += '&search=' + encodeURIComponent(currentSearch);
            }
            window.location.href = url;
        }
    </script>
</x-admin-layout>
