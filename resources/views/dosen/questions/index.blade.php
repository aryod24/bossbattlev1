<x-dosen-layout>
    <!-- PageHeading -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6 bg-card p-6 rounded-2xl shadow-sm border border-border">
        <div>
            <h1 class="text-4xl font-black text-text-primary tracking-tight">Bank Soal</h1>
            <p class="text-text-muted mt-2 font-medium">Manage master data of questions and quizzes.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('dosen.questions.template') }}" class="flex items-center justify-center gap-2 overflow-hidden rounded-lg h-11 px-5 bg-success hover:brightness-110 text-white text-sm font-bold leading-normal tracking-wide shadow-sm">
                <span class="material-symbols-outlined">download</span>
                <span class="truncate">Download Template</span>
            </a>
            <button type="button" onclick="toggleBulkUpload()" id="btnBulkUpload" class="flex items-center justify-center gap-2 overflow-hidden rounded-lg h-11 px-5 bg-info hover:brightness-110 text-white text-sm font-bold leading-normal tracking-wide shadow-sm">
                <span class="material-symbols-outlined">upload_file</span>
                <span class="truncate">Import Excel/CSV</span>
            </button>
            <a href="{{ route('dosen.questions.banks.create') }}" class="flex items-center justify-center gap-2 overflow-hidden rounded-lg h-11 px-5 bg-warning hover:brightness-110 text-white text-sm font-bold leading-normal tracking-wide shadow-sm">
                <span class="material-symbols-outlined">library_add</span>
                <span class="truncate">Buat Bank Soal Baru</span>
            </a>
            <a href="{{ route('dosen.questions.create', ['bank' => $currentBank]) }}" class="flex items-center justify-center gap-2 overflow-hidden rounded-lg h-11 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-wide shadow-sm hover:bg-accent-hover">
                <span class="material-symbols-outlined">add_circle</span>
                <span class="truncate">Tambah Soal</span>
            </a>
        </div>
    </div>

    <!-- Bank Selector + Filters -->
    <form method="GET" action="{{ route('dosen.questions.index') }}" id="filter-form" class="flex flex-wrap gap-3 mb-4">
        <div class="flex flex-col w-72">
            <label class="text-xs font-bold uppercase tracking-wider text-text-muted mb-1">Bank Soal</label>
            <select name="bank" onchange="document.getElementById('filter-form').submit()"
                    class="h-12 rounded-lg bg-card border border-border text-text-primary text-sm font-medium px-3 focus:outline-none focus:ring-2 focus:ring-primary">
                @forelse($bankConfig as $bankId => $bank)
                    <option value="{{ $bankId }}" {{ $currentBank == $bankId ? 'selected' : '' }}>
                        {{ $bank['name'] }} ({{ $bankCounts[$bankId] ?? 0 }} soal)
                    </option>
                @empty
                    <option value="">— Belum ada bank soal —</option>
                @endforelse
            </select>
        </div>

        <div class="flex flex-col flex-1 min-w-[260px]">
            <label class="text-xs font-bold uppercase tracking-wider text-text-muted mb-1">Cari Soal</label>
            <div class="flex w-full items-stretch rounded-lg bg-card border border-border focus-within:ring-2 focus-within:ring-primary h-12">
                <div class="flex items-center justify-center pl-4">
                    <span class="material-symbols-outlined text-text-muted">search</span>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari teks pertanyaan..."
                       class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-base font-normal h-full placeholder:text-text-muted px-4 pl-2 focus:outline-none focus:ring-0 border-none bg-transparent text-text-primary"/>
            </div>
        </div>

        <div class="flex flex-col w-44">
            <label class="text-xs font-bold uppercase tracking-wider text-text-muted mb-1">Level</label>
            <select name="level" onchange="document.getElementById('filter-form').submit()"
                    class="h-12 rounded-lg bg-card border border-border text-text-primary text-sm font-medium px-3 focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="" {{ !request('level') ? 'selected' : '' }}>Semua Level</option>
                <option value="Easy" {{ request('level') == 'Easy' ? 'selected' : '' }}>Easy</option>
                <option value="Medium" {{ request('level') == 'Medium' ? 'selected' : '' }}>Medium</option>
                <option value="Hard" {{ request('level') == 'Hard' ? 'selected' : '' }}>Hard</option>
            </select>
        </div>

        <div class="flex flex-col justify-end">
            <button type="submit" class="h-12 px-5 rounded-lg bg-primary hover:bg-accent-hover text-white font-bold text-sm">Cari</button>
        </div>
    </form>

    @if(!empty($bankConfig) && isset($bankConfig[$currentBank]))
        <div class="mb-4 p-4 rounded-lg bg-card border border-border flex items-center gap-3">
            <span class="material-symbols-outlined text-primary text-3xl">{{ $bankConfig[$currentBank]['icon'] ?? 'quiz' }}</span>
            <div class="flex-1">
                <h2 class="text-lg font-bold text-text-primary">{{ $bankConfig[$currentBank]['name'] }}</h2>
                @if(!empty($bankConfig[$currentBank]['description']))
                    <p class="text-sm text-text-muted">{{ $bankConfig[$currentBank]['description'] }}</p>
                @endif
            </div>
            <span class="text-sm font-bold text-text-muted">{{ $bankCounts[$currentBank] ?? 0 }} soal</span>
            <form action="{{ route('dosen.questions.banks.destroy', $currentBank) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus seluruh bank soal &quot;{{ $bankConfig[$currentBank]['name'] }}&quot; beserta {{ $bankCounts[$currentBank] ?? 0 }} soal di dalamnya? Tindakan ini tidak bisa dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="flex items-center gap-1.5 h-9 px-3 rounded-md hover:bg-error/20 text-error text-xs font-bold border border-transparent hover:border-error/30 transition-colors"
                        title="Hapus seluruh bank soal ini">
                    <span class="material-symbols-outlined text-base">delete_forever</span>
                    Hapus Bank
                </button>
            </form>
        </div>
    @endif

    <!-- Bulk Upload Section -->
    <div id="bulkUploadSection" class="mb-4" style="display: none;">
        <div class="bg-card border border-border rounded-lg p-5"
             x-data="{ targetBank: '{{ $currentBank ?: 'new' }}' }">
            <div class="flex items-start justify-between mb-4 gap-4">
                <div>
                    <h3 class="text-lg font-bold text-text-primary">Import Soal dari Excel/CSV</h3>
                    <p class="text-xs text-text-muted mt-1">
                        Download template, isi di Excel/Google Sheets, lalu Save As <strong>CSV</strong> dan upload di sini.
                        Satu file berisi soal-soal untuk satu bank (baru atau bank yang sudah ada).
                    </p>
                </div>
                <a href="{{ route('dosen.questions.template') }}" class="flex items-center gap-1.5 text-xs font-semibold text-success hover:underline">
                    <span class="material-symbols-outlined text-sm">download</span> Template CSV
                </a>
            </div>

            <form action="{{ route('dosen.questions.bulk-upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-text-muted mb-1">Tujuan Bank Soal</label>
                        <select name="target_bank" x-model="targetBank"
                                class="block w-full h-11 rounded-lg bg-background-dark border border-border text-text-primary px-3 focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="new">+ Buat Bank Soal Baru</option>
                            @foreach($bankConfig as $bankId => $bank)
                                <option value="{{ $bankId }}">Tambahkan ke: {{ $bank['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text-muted mb-1">File CSV</label>
                        <input type="file" name="file" accept=".csv,.txt" required
                               class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-accent-hover bg-background-dark border border-border rounded-lg p-1">
                    </div>
                </div>

                <div x-show="targetBank === 'new'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-background-dark rounded-lg border border-border">
                    <div>
                        <label class="block text-sm font-medium text-text-muted mb-1">Nama Bank Soal Baru *</label>
                        <input type="text" name="new_bank_name" placeholder="Contoh: Laravel Routing"
                               class="block w-full h-11 rounded-lg bg-card border border-border text-text-primary px-3 focus:outline-none focus:ring-2 focus:ring-primary"
                               x-bind:required="targetBank === 'new'">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-muted mb-1">Deskripsi (opsional)</label>
                        <input type="text" name="new_bank_description" placeholder="Deskripsi singkat bank soal"
                               class="block w-full h-11 rounded-lg bg-card border border-border text-text-primary px-3 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="flex items-center justify-center gap-2 rounded-lg h-11 px-5 bg-info hover:brightness-110 text-white text-sm font-bold transition-colors">
                        <span class="material-symbols-outlined text-base">upload_file</span>
                        Upload Soal
                    </button>
                </div>
            </form>

            @if($errors->any())
                <div class="mt-4 p-3 bg-error/10 border border-error/30 rounded-lg text-sm text-error">
                    @foreach($errors->all() as $err)
                        <div>· {{ $err }}</div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-success/10 border border-success/30 rounded-lg text-sm text-success">
            {!! nl2br(e(session('success'))) !!}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-error/10 border border-error/30 rounded-lg text-sm text-error">
            {!! nl2br(e(session('error'))) !!}
        </div>
    @endif

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
                                <a href="{{ route('dosen.questions.edit', $question) }}" class="flex items-center justify-center size-8 rounded-md hover:bg-primary/20 transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </a>
                                <form action="{{ route('dosen.questions.destroy', $question) }}" method="POST" class="inline">
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
                            @if(empty($bankConfig))
                                Belum ada bank soal. Klik <strong>Buat Bank Soal Baru</strong> untuk mulai.
                            @else
                                Tidak ada soal ditemukan di bank ini.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($questions->hasPages())
        <nav class="flex items-center justify-center p-4 mt-4">
            {{ $questions->links() }}
        </nav>
    @endif

    <script>
        function toggleBulkUpload() {
            const section = document.getElementById('bulkUploadSection');
            section.style.display = (section.style.display === 'none' || !section.style.display) ? 'block' : 'none';
        }

        @if($errors->any())
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('bulkUploadSection').style.display = 'block';
            });
        @endif
    </script>
</x-dosen-layout>
