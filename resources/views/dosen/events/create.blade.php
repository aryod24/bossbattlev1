<x-dosen-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-primary leading-tight">
            {{ __('Create Solo Raid') }}
        </h2>
    </x-slot>

    <!-- EasyMDE CSS -->
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <!-- EasyMDE JS -->
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
    <style>
        .EasyMDEContainer { background: #252526; }
        .EasyMDEContainer .CodeMirror { background: #1e1e1e; color: #d4d4d4; border: 1px solid #333; }
        .EasyMDEContainer .CodeMirror-scroll { max-height: 300px; }
        .EasyMDEContainer .CodeMirror-fullscreen .CodeMirror-scroll { max-height: none; }
        .EasyMDEContainer .editor-toolbar { background: #252526; border-color: #333; }
        .EasyMDEContainer .editor-toolbar button { color: #858585 !important; }
        .EasyMDEContainer .editor-toolbar button:hover { background: #2d2d2d; color: #d4d4d4 !important; }
        .EasyMDEContainer .CodeMirror-cursor { border-color: #d4d4d4; }
        .editor-preview, .editor-preview-active { background: #1e1e1e !important; color: #d4d4d4 !important; padding: 1em !important; }
        .editor-preview h1, .editor-preview-active h1 { font-size: 2em; font-weight: 800; color: #007acc; }
        .editor-preview h2, .editor-preview-active h2 { font-size: 1.5em; font-weight: 700; color: #007acc; }
        .editor-preview code, .editor-preview-active code { background: #2d2d2d; color: #ce9178; padding: 0.2em 0.4em; border-radius: 3px; font-family: 'Consolas', monospace; }
        .editor-preview pre, .editor-preview-active pre { background: #1e1e1e; border: 1px solid #333; border-radius: 6px; padding: 1em; overflow-x: auto; }
        .editor-preview pre code, .editor-preview-active pre code { background: transparent; padding: 0; color: #d4d4d4; }
    </style>

    <div class="py-12" x-data="raidForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-card overflow-hidden shadow-sm sm:rounded-lg border border-border">
                <div class="p-6 text-text-primary">
                    <form action="{{ route('dosen.events.store') }}" method="POST" @submit="prepareSubmit()">
                        @csrf

                        @if($errors->any())
                            <div class="mb-6 rounded-md bg-error/10 border border-error/30 p-4">
                                <div class="flex items-start gap-2 mb-2">
                                    <span class="material-symbols-outlined text-error">error</span>
                                    <div>
                                        <p class="text-sm font-bold text-error">Form belum bisa disimpan:</p>
                                    </div>
                                </div>
                                <ul class="text-sm text-error space-y-1 ml-8 list-disc">
                                    @foreach($errors->all() as $msg)
                                        <li>{{ $msg }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Basic Info -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-text-primary mb-4">📋 Basic Information</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-text-muted">Raid Name</label>
                                    <input type="text" name="nama" id="nama" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>
                                </div>
                                <div class="rounded-md bg-primary/5 border border-primary/20 px-3 py-2 text-xs text-text-muted">
                                    <strong class="text-primary">Aturan slot section:</strong> setiap section (Easy/Medium/Hard) hanya boleh berisi 1 event Materi dan 1 event Boss Battle. Urutan tampil ke mahasiswa: Materi dulu, lalu Boss.
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="type" class="block text-sm font-medium text-text-muted">Event Type</label>
                                        <select name="type" id="type" x-model="eventType" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                                            <option value="learning">📚 Learning (Materi)</option>
                                            <option value="boss">⚔️ Boss Battle</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="section" class="block text-sm font-medium text-text-muted">Section</label>
                                        <select name="section" id="section" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>
                                            <option value="Easy">Easy</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Hard">Hard</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-text-muted">Status</label>
                                        <select name="status" id="status" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                                            <option value="draft" selected>Draft</option>
                                            <option value="active">Active</option>
                                            <option value="selesai">Finished</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label for="question_bank_id" class="block text-sm font-medium text-text-muted">Question Bank</label>
                                    <select name="question_bank_id" id="question_bank_id" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>
                                        @foreach($banks as $bank)
                                            <option value="{{ $bank->bank_group }}">{{ $bank->bank_name ?? 'Bank #' . $bank->bank_group }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="deskripsi" class="block text-sm font-medium text-text-muted">Description</label>
                                    <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="tanggal_mulai" class="block text-sm font-medium text-text-muted">Start Date</label>
                                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>
                                    </div>
                                    <div>
                                        <label for="tanggal_selesai" class="block text-sm font-medium text-text-muted">End Date</label>
                                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Nodes (only for Learning type) -->
                        <div class="mb-6" x-show="eventType === 'learning'" x-cloak>
                            <h3 class="text-lg font-medium text-text-primary mb-2">📚 Content Nodes (5 Materi + 1 Quiz)</h3>
                            <p class="text-sm text-text-muted mb-4">Tambahkan materi dan quiz untuk event pembelajaran ini. Klik "Add Node" untuk menambah node baru.</p>

                            <div class="space-y-4">
                                <template x-for="(node, index) in nodes" :key="index">
                                    <div class="border border-border p-4 rounded-md bg-background-dark relative">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-3">
                                                <span class="bg-primary/20 text-primary text-xs font-bold px-2 py-1 rounded" x-text="'Node ' + (index + 1)"></span>
                                                <select x-model="node.type" class="text-sm rounded-md bg-card border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                                                    <option value="content">📖 Content (Materi)</option>
                                                    <option value="quiz">🎯 Quiz</option>
                                                </select>
                                            </div>
                                            <button type="button" @click="removeNode(index)" class="text-red-400 hover:text-red-300 text-sm" x-show="nodes.length > 1">
                                                ✕ Remove
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-text-muted mb-1">Title</label>
                                                <input type="text" x-model="node.title" :placeholder="node.type === 'content' ? 'Judul Materi' : 'Quiz Akhir'" class="block w-full rounded-md bg-card border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary text-sm" x-bind:required="eventType === 'learning'">
                                            </div>
                                            <div x-show="node.type === 'content'"
                                                 x-init="$nextTick(() => {
                                                     const ta = $el.querySelector('textarea');
                                                     if (ta && !ta._easymde) {
                                                         const easyMde = new EasyMDE({
                                                             element: ta,
                                                             initialValue: node.content,
                                                             spellChecker: false,
                                                             autosave: { enabled: false },
                                                             toolbar: ['bold','italic','strikethrough','|','heading-1','heading-2','heading-3','|','code','quote','unordered-list','ordered-list','|','link','image','table','|','preview','side-by-side','fullscreen'],
                                                             minHeight: '180px',
                                                         });
                                                         ta._easymde = easyMde;
                                                         easyMde.codemirror.on('change', () => {
                                                             node.content = easyMde.value();
                                                         });
                                                     }
                                                 })">
                                                <label class="block text-xs font-medium text-text-muted mb-1">Content (Markdown)</label>
                                                <textarea placeholder="Tulis materi dalam format Markdown..."></textarea>
                                            </div>
                                            <div x-show="node.type === 'quiz'">
                                                <p class="text-xs text-text-muted italic">Quiz akan menggunakan soal dari Question Bank yang dipilih, dengan konfigurasi level sesuai Section.</p>
                                            </div>
                                        </div>
                                        <input type="hidden" :name="'nodes[' + index + '][type]'" :value="node.type">
                                        <input type="hidden" :name="'nodes[' + index + '][title]'" :value="node.title">
                                        <input type="hidden" :name="'nodes[' + index + '][content]'" :value="node.content">
                                        <input type="hidden" :name="'nodes[' + index + '][order]'" :value="index + 1">
                                    </div>
                                </template>
                            </div>

                            <button type="button" @click="addNode()" x-show="nodes.length < 6" class="mt-4 bg-border hover:bg-text-muted text-text-primary font-bold py-2 px-4 rounded text-sm flex items-center gap-2">
                                + Add Node
                            </button>
                        </div>

                        <!-- Boss Config (only for Boss type) -->


                        <div class="flex justify-end">
                            <a href="{{ route('dosen.events.index') }}" class="bg-border hover:bg-text-muted text-text-primary font-bold py-2 px-4 rounded mr-2">Cancel</a>
                            <button type="submit" class="bg-primary hover:bg-primary/80 text-white font-bold py-2 px-4 rounded">Create Raid</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function raidForm() {
            return {
                eventType: 'learning',
                nodes: [
                    { type: 'content', title: '', content: '', order: 1 },
                    { type: 'content', title: '', content: '', order: 2 },
                    { type: 'content', title: '', content: '', order: 3 },
                    { type: 'content', title: '', content: '', order: 4 },
                    { type: 'content', title: '', content: '', order: 5 },
                    { type: 'quiz', title: 'Quiz Akhir', content: '', order: 6 },
                ],

                addNode() {
                    if (this.nodes.length < 6) {
                        this.nodes.push({ type: 'content', title: '', content: '', order: this.nodes.length + 1 });
                    }
                },

                removeNode(index) {
                    this.nodes.splice(index, 1);
                    this.nodes.forEach((n, i) => n.order = i + 1);
                },

                prepareSubmit() {
                    // Nodes are already bound via hidden inputs
                }
            }
        }
    </script>
</x-dosen-layout>
