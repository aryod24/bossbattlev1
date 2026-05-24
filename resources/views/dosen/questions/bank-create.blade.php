<x-dosen-layout>
    <div class="max-w-5xl mx-auto" x-data="bankForm()">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('dosen.questions.index') }}" class="p-2 hover:bg-card rounded-full transition-colors border border-transparent hover:border-border">
                <span class="material-symbols-outlined text-text-muted">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-black text-text-primary">Buat Bank Soal Baru</h1>
                <p class="text-text-muted mt-1">Isi metadata bank soal lalu tambahkan beberapa soal sekaligus.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 bg-error/10 border border-error/30 rounded-lg text-sm text-error">
                @foreach($errors->all() as $err)
                    <div>· {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('dosen.questions.banks.store') }}" class="space-y-6">
            @csrf

            <!-- Metadata Bank -->
            <div class="bg-card border border-border rounded-2xl p-6 shadow-sm">
                <h2 class="text-lg font-bold text-text-primary mb-4">Informasi Bank Soal</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-text-muted mb-1">Nama Bank Soal *</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" required maxlength="255"
                               placeholder="Contoh: Laravel Routing & Middleware"
                               class="block w-full h-11 rounded-lg bg-background-dark border border-border text-text-primary px-3 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-text-muted mb-1">Deskripsi</label>
                        <input type="text" name="bank_description" value="{{ old('bank_description') }}"
                               placeholder="Deskripsi singkat (opsional)"
                               class="block w-full h-11 rounded-lg bg-background-dark border border-border text-text-primary px-3 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                </div>
            </div>

            <!-- Repeater Soal -->
            <div class="bg-card border border-border rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-text-primary">
                        Daftar Soal <span class="text-text-muted font-normal text-sm">(<span x-text="questions.length"></span>)</span>
                    </h2>
                    <button type="button" @click="addQuestion()" class="flex items-center gap-2 h-10 px-4 rounded-lg bg-primary hover:bg-accent-hover text-white font-bold text-sm">
                        <span class="material-symbols-outlined text-base">add</span> Tambah Soal
                    </button>
                </div>

                <template x-for="(q, idx) in questions" :key="idx">
                    <div class="border border-border rounded-lg p-4 mb-4 bg-background-dark">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-bold text-text-primary">Soal #<span x-text="idx + 1"></span></h3>
                            <button type="button" @click="removeQuestion(idx)" x-show="questions.length > 1"
                                    class="flex items-center justify-center size-8 rounded-md hover:bg-error/20 text-error">
                                <span class="material-symbols-outlined text-base">delete</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-text-muted mb-1">Level *</label>
                                <select :name="`questions[${idx}][level]`" x-model="q.level" required
                                        class="block w-full h-10 rounded-lg bg-card border border-border text-text-primary px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="Easy">Easy</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Hard">Hard</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-text-muted mb-1">Tipe *</label>
                                <select :name="`questions[${idx}][tipe]`" x-model="q.tipe" required
                                        class="block w-full h-10 rounded-lg bg-card border border-border text-text-primary px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="multiple_choice">Pilihan Ganda</option>
                                    <option value="short_answer">Isian Singkat</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-text-muted mb-1">Bobot XP *</label>
                                <input type="number" min="1" :name="`questions[${idx}][bobot_xp]`" x-model.number="q.bobot_xp" required
                                       class="block w-full h-10 rounded-lg bg-card border border-border text-text-primary px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="block text-xs font-medium text-text-muted mb-1">Pertanyaan *</label>
                            <textarea :name="`questions[${idx}][soal_text]`" x-model="q.soal_text" required rows="2"
                                      class="block w-full rounded-lg bg-card border border-border text-text-primary px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                        </div>

                        <div x-show="q.tipe === 'multiple_choice'" class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                            <template x-for="opt in ['a','b','c','d']" :key="opt">
                                <div>
                                    <label class="block text-xs font-medium text-text-muted mb-1" x-text="`Pilihan ${opt.toUpperCase()} *`"></label>
                                    <input type="text" :name="`questions[${idx}][pilihan_${opt}]`"
                                           x-model="q[`pilihan_${opt}`]"
                                           :required="q.tipe === 'multiple_choice'"
                                           class="block w-full h-10 rounded-lg bg-card border border-border text-text-primary px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                            </template>
                        </div>

                        <div class="mt-3">
                            <label class="block text-xs font-medium text-text-muted mb-1">Jawaban Benar *</label>
                            <input type="text" :name="`questions[${idx}][jawaban_benar]`" x-model="q.jawaban_benar" required
                                   placeholder="Untuk pilihan ganda, ketik persis seperti pilihan yang benar."
                                   class="block w-full h-10 rounded-lg bg-card border border-border text-text-primary px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                    </div>
                </template>

                <button type="button" @click="addQuestion()"
                        class="w-full border-2 border-dashed border-border hover:border-primary rounded-lg py-3 text-sm font-bold text-text-muted hover:text-primary flex items-center justify-center gap-2 transition-colors">
                    <span class="material-symbols-outlined">add_circle</span> Tambah Soal Lagi
                </button>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('dosen.questions.index') }}" class="h-11 px-5 rounded-lg border border-border text-text-muted hover:bg-card flex items-center font-bold text-sm">Batal</a>
                <button type="submit" class="h-11 px-6 rounded-lg bg-primary hover:bg-accent-hover text-white font-bold text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">save</span>
                    Simpan Bank Soal
                </button>
            </div>
        </form>
    </div>

    <script>
        function bankForm() {
            return {
                questions: [],
                init() {
                    this.questions = [this.makeEmpty()];
                },
                makeEmpty() {
                    return {
                        level: 'Easy',
                        tipe: 'multiple_choice',
                        soal_text: '',
                        pilihan_a: '',
                        pilihan_b: '',
                        pilihan_c: '',
                        pilihan_d: '',
                        jawaban_benar: '',
                        bobot_xp: 10,
                    };
                },
                addQuestion() { this.questions.push(this.makeEmpty()); },
                removeQuestion(idx) {
                    if (this.questions.length <= 1) return;
                    this.questions.splice(idx, 1);
                },
            };
        }
    </script>
</x-dosen-layout>
