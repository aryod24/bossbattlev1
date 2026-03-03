<x-admin-layout>
    <!-- EasyMDE CSS -->
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <style>
        .EasyMDEContainer { background: #252526; }
        .EasyMDEContainer .CodeMirror { background: #1e1e1e; color: #d4d4d4; border: 1px solid #333; }
        .EasyMDEContainer .CodeMirror-scroll { max-height: 400px; }
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

    <div class="max-w-7xl mx-auto space-y-6" x-data="raidEditForm()">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.solo-raids.index') }}" class="p-2 hover:bg-card rounded-full transition-colors border border-transparent hover:border-border">
                <span class="material-symbols-outlined text-text-muted">arrow_back</span>
            </a>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    @if($soloRaid->type === 'boss')
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full bg-red-500/10 text-red-400 border border-red-500/20">
                            <span class="material-symbols-outlined" style="font-size:14px">skull</span> Boss Battle Event
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full bg-primary/10 text-primary border border-primary/20">
                            <span class="material-symbols-outlined" style="font-size:14px">menu_book</span> Materi Event
                        </span>
                    @endif
                    <span class="text-xs text-text-muted">Section: {{ $soloRaid->section }} · Order #{{ $soloRaid->section_order }}</span>
                </div>
                <h1 class="text-3xl font-black text-text-primary">{{ $soloRaid->nama }}</h1>
                <p class="text-text-muted mt-1">Configure event details{{ $soloRaid->type === 'learning' ? ', nodes,' : '' }} and settings.</p>
            </div>
        </div>

        <div class="bg-card rounded-2xl shadow-sm border border-border p-8">
            <form action="{{ route('admin.solo-raids.update', $soloRaid) }}" method="POST" @submit="prepareSubmit()">
                @csrf
                @method('PATCH')

                <!-- Basic Info -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-text-primary mb-4">📋 Basic Information</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-text-muted">Raid Name</label>
                            <input type="text" name="nama" id="nama" value="{{ $soloRaid->nama }}" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
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
                                    <option value="Easy" {{ $soloRaid->section === 'Easy' ? 'selected' : '' }}>Easy</option>
                                    <option value="Medium" {{ $soloRaid->section === 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Hard" {{ $soloRaid->section === 'Hard' ? 'selected' : '' }}>Hard</option>
                                </select>
                            </div>
                            <div>
                                <label for="section_order" class="block text-sm font-medium text-text-muted">Order in Section</label>
                                <input type="number" name="section_order" id="section_order" value="{{ $soloRaid->section_order }}" min="1" max="6" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-text-muted">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                                    <option value="draft" {{ $soloRaid->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="active" {{ $soloRaid->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="selesai" {{ $soloRaid->status === 'selesai' ? 'selected' : '' }}>Finished</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="question_bank_id" class="block text-sm font-medium text-text-muted">Question Bank</label>
                            <select name="question_bank_id" id="question_bank_id" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->bank_group }}" {{ $soloRaid->question_bank_id == $bank->bank_group ? 'selected' : '' }}>
                                        {{ $bank->bank_name ?? 'Bank #' . $bank->bank_group }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-text-muted">Description</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>{{ $soloRaid->deskripsi }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-text-muted">Start Date</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $soloRaid->tanggal_mulai ? $soloRaid->tanggal_mulai->format('Y-m-d') : '' }}" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>
                            </div>
                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-text-muted">End Date</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $soloRaid->tanggal_selesai ? $soloRaid->tanggal_selesai->format('Y-m-d') : '' }}" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Nodes (only for Learning type) -->
                <div class="mb-6" x-show="eventType === 'learning'" x-cloak>
                    <h3 class="text-lg font-medium text-text-primary mb-2">📚 Content Nodes</h3>
                    <p class="text-sm text-text-muted mb-4">Kelola materi dan quiz untuk event pembelajaran ini.</p>

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
                                        <input type="text" x-model="node.title" class="block w-full rounded-md bg-card border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary text-sm" required>
                                    </div>
                                    <div x-show="node.type === 'content'">
                                        <label class="block text-xs font-medium text-text-muted mb-1">Content (Markdown)</label>
                                        <textarea x-model="node.content" rows="6" placeholder="Tulis materi dalam format Markdown..." class="block w-full rounded-md bg-card border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary font-mono text-sm"></textarea>
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
                <div class="mb-6" x-show="eventType === 'boss'" x-cloak>
                    <h3 class="text-lg font-medium text-text-primary mb-4">⚔️ Boss Configuration</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="border border-border p-4 rounded-md bg-background-dark">
                            <div class="flex items-center mb-4">
                                <input type="checkbox" name="easy_enabled" id="easy_enabled" value="1" {{ $soloRaid->easy_enabled ? 'checked' : '' }} class="h-4 w-4 text-primary focus:ring-primary border-border rounded bg-background-dark">
                                <label for="easy_enabled" class="ml-2 block text-sm font-medium text-text-primary">Enable Easy</label>
                            </div>
                            <label for="boss_easy_name" class="block text-sm font-medium text-text-muted">Boss Name</label>
                            <input type="text" name="boss_easy_name" id="boss_easy_name" value="{{ $soloRaid->boss_easy_name }}" class="mt-1 block w-full rounded-md bg-card border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div class="border border-border p-4 rounded-md bg-background-dark">
                            <div class="flex items-center mb-4">
                                <input type="checkbox" name="medium_enabled" id="medium_enabled" value="1" {{ $soloRaid->medium_enabled ? 'checked' : '' }} class="h-4 w-4 text-primary focus:ring-primary border-border rounded bg-background-dark">
                                <label for="medium_enabled" class="ml-2 block text-sm font-medium text-text-primary">Enable Medium</label>
                            </div>
                            <label for="boss_medium_name" class="block text-sm font-medium text-text-muted">Boss Name</label>
                            <input type="text" name="boss_medium_name" id="boss_medium_name" value="{{ $soloRaid->boss_medium_name }}" class="mt-1 block w-full rounded-md bg-card border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div class="border border-border p-4 rounded-md bg-background-dark">
                            <div class="flex items-center mb-4">
                                <input type="checkbox" name="hard_enabled" id="hard_enabled" value="1" {{ $soloRaid->hard_enabled ? 'checked' : '' }} class="h-4 w-4 text-primary focus:ring-primary border-border rounded bg-background-dark">
                                <label for="hard_enabled" class="ml-2 block text-sm font-medium text-text-primary">Enable Hard</label>
                            </div>
                            <label for="boss_hard_name" class="block text-sm font-medium text-text-muted">Boss Name</label>
                            <input type="text" name="boss_hard_name" id="boss_hard_name" value="{{ $soloRaid->boss_hard_name }}" class="mt-1 block w-full rounded-md bg-card border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('admin.solo-raids.index') }}" class="bg-border hover:bg-text-muted text-text-primary font-bold py-2 px-4 rounded mr-2">Cancel</a>
                    <button type="submit" class="bg-primary hover:bg-primary/80 text-white font-bold py-2 px-4 rounded">Update Raid</button>
                </div>
            </form>
        </div>
    </div>

    @php
        $nodeData = $soloRaid->nodes->count() > 0
            ? $soloRaid->nodes->map(fn($n) => [
                'id'      => $n->id,
                'type'    => $n->type,
                'title'   => $n->title,
                'content' => $n->content ?? '',
                'order'   => $n->order,
              ])->values()
            : collect([
                ['type' => 'content', 'title' => '', 'content' => '', 'order' => 1],
                ['type' => 'quiz',    'title' => 'Quiz Akhir', 'content' => '', 'order' => 2],
              ]);
    @endphp
    <script>
        function raidEditForm() {
            return {
                eventType: '{{ $soloRaid->type ?? "boss" }}',
                nodes: @json($nodeData),

                addNode() {
                    if (this.nodes.length < 6) {
                        this.nodes.push({ type: 'content', title: '', content: '', order: this.nodes.length + 1 });
                    }
                },

                removeNode(index) {
                    this.nodes.splice(index, 1);
                    this.nodes.forEach((n, i) => n.order = i + 1);
                },

                prepareSubmit() {}
            }
        }
    </script>
</x-admin-layout>
