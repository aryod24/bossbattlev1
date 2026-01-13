<x-dosen-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-primary leading-tight">
            {{ __('Create Solo Raid') }}
        </h2>
    </x-slot>

    <!-- EasyMDE CSS -->
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <style>
        .EasyMDEContainer {
            background: #252526;
        }
        .EasyMDEContainer .CodeMirror {
            background: #1e1e1e;
            color: #d4d4d4;
            border: 1px solid #333;
        }
        /* Target scroll container untuk limit height */
        .EasyMDEContainer .CodeMirror-scroll {
            max-height: 800px; /* ~50 lines */
        }
        .EasyMDEContainer .CodeMirror-fullscreen .CodeMirror-scroll {
            max-height: none; /* Remove limit on fullscreen */
        }
        .EasyMDEContainer .editor-toolbar {
            background: #252526;
            border-color: #333;
        }
        .EasyMDEContainer .editor-toolbar button {
            color: #858585 !important;
        }
        .EasyMDEContainer .editor-toolbar button:hover {
            background: #2d2d2d;
            color: #d4d4d4 !important;
        }
        .EasyMDEContainer .editor-toolbar.fullscreen {
            background: #1e1e1e;
        }
        .EasyMDEContainer .CodeMirror-cursor {
            border-color: #d4d4d4;
        }
        .editor-preview, .editor-preview-active {
            background: #1e1e1e !important;
            color: #d4d4d4 !important;
            padding: 1em !important;
        }
        
        /* Prose styling untuk preview */
        .editor-preview h1, .editor-preview-active h1 { font-size: 2em; font-weight: 800; color: #007acc; margin-top: 0; margin-bottom: 0.5em; }
        .editor-preview h2, .editor-preview-active h2 { font-size: 1.5em; font-weight: 700; color: #007acc; margin-top: 1.5em; margin-bottom: 0.5em; }
        .editor-preview h3, .editor-preview-active h3 { font-size: 1.25em; font-weight: 600; color: #4ec9b0; margin-top: 1.25em; margin-bottom: 0.5em; }
        .editor-preview h4, .editor-preview-active h4 { font-size: 1.1em; font-weight: 600; color: #9cdcfe; margin-top: 1em; margin-bottom: 0.5em; }
        
        .editor-preview p, .editor-preview-active p { margin-top: 0.75em; margin-bottom: 0.75em; line-height: 1.7; }
        .editor-preview strong, .editor-preview-active strong { color: #dcdcaa; font-weight: 600; }
        .editor-preview em, .editor-preview-active em { color: #ce9178; font-style: italic; }
        
        .editor-preview code, .editor-preview-active code {
            background: #2d2d2d;
            color: #ce9178;
            padding: 0.2em 0.4em;
            border-radius: 3px;
            font-size: 0.9em;
            font-family: 'Consolas', 'Monaco', monospace;
        }
        
        .editor-preview pre, .editor-preview-active pre {
            background: #1e1e1e;
            border: 1px solid #333;
            border-radius: 6px;
            padding: 1em;
            overflow-x: auto;
            margin: 1em 0;
        }
        
        .editor-preview pre code, .editor-preview-active pre code { background: transparent; padding: 0; color: #d4d4d4; font-size: 0.875em; }
        
        .editor-preview blockquote, .editor-preview-active blockquote { border-left: 4px solid #007acc; background: #252526; padding: 0.5em 1em; margin: 1em 0; font-style: italic; color: #858585; }
        
        .editor-preview ul, .editor-preview-active ul,
        .editor-preview ol, .editor-preview-active ol { margin: 0.75em 0; padding-left: 1.5em; }
        
        .editor-preview li, .editor-preview-active li { margin: 0.5em 0; }
        
        .editor-preview table, .editor-preview-active table { border-collapse: collapse; width: 100%; margin: 1em 0; }
        
        .editor-preview th, .editor-preview-active th,
        .editor-preview td, .editor-preview-active td { border: 1px solid #333; padding: 0.5em; text-align: left; }
        
        .editor-preview th, .editor-preview-active th { background: #2d2d2d; color: #007acc; font-weight: 600; }
        
        .editor-preview img, .editor-preview-active img { max-width: 100%; height: auto; border-radius: 6px; margin: 1em 0; }
        
        .editor-preview a, .editor-preview-active a { color: #007acc; text-decoration: none; }
        .editor-preview a:hover, .editor-preview-active a:hover { text-decoration: underline; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-card overflow-hidden shadow-sm sm:rounded-lg border border-border">
                <div class="p-6 text-text-primary">
                    <form action="{{ route('dosen.events.store') }}" method="POST">
                        @csrf

                        <!-- Basic Info -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-text-primary mb-4">Basic Information</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-text-muted">Raid Name</label>
                                    <input type="text" name="nama" id="nama" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary" required>
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-text-muted">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="draft" selected>Draft</option>
                                        <option value="active">Active</option>
                                        <option value="selesai">Finished</option>
                                    </select>
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

                        <!-- Materi Nodes (Markdown Editor) -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-text-primary mb-2">📚 Study Material (Markdown)</h3>
                            <p class="text-sm text-text-muted mb-4">Input materi pembelajaran dengan format Markdown. Mendukung code syntax, table, images, dll.</p>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="materi_node_1" class="block text-sm font-medium text-text-muted mb-2">
                                        📖 Materi Node 1 (Before Easy Level)
                                    </label>
                                    <textarea name="materi_node_1" id="materi_node_1"></textarea>
                                </div>
                                <div>
                                    <label for="materi_node_2" class="block text-sm font-medium text-text-muted mb-2">
                                        📖 Materi Node 2 (Before Medium Level)
                                    </label>
                                    <textarea name="materi_node_2" id="materi_node_2"></textarea>
                                </div>
                                <div>
                                    <label for="materi_node_3" class="block text-sm font-medium text-text-muted mb-2">
                                        📖 Materi Node 3 (Before Hard Level)
                                    </label>
                                    <textarea name="materi_node_3" id="materi_node_3"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Level Config -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-text-primary mb-4">Level Configuration</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Easy -->
                                <div class="border border-border p-4 rounded-md bg-background-dark">
                                    <div class="flex items-center mb-4">
                                        <input type="checkbox" name="easy_enabled" id="easy_enabled" value="1" checked class="h-4 w-4 text-primary focus:ring-primary border-border rounded bg-background-dark">
                                        <label for="easy_enabled" class="ml-2 block text-sm font-medium text-text-primary">Enable Easy</label>
                                    </div>
                                    <label for="boss_easy_name" class="block text-sm font-medium text-text-muted">Boss Name</label>
                                    <input type="text" name="boss_easy_name" id="boss_easy_name" value="Goblin King" class="mt-1 block w-full rounded-md bg-card border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                                </div>

                                <!-- Medium -->
                                <div class="border border-border p-4 rounded-md bg-background-dark">
                                    <div class="flex items-center mb-4">
                                        <input type="checkbox" name="medium_enabled" id="medium_enabled" value="1" class="h-4 w-4 text-primary focus:ring-primary border-border rounded bg-background-dark">
                                        <label for="medium_enabled" class="ml-2 block text-sm font-medium text-text-primary">Enable Medium</label>
                                    </div>
                                    <label for="boss_medium_name" class="block text-sm font-medium text-text-muted">Boss Name</label>
                                    <input type="text" name="boss_medium_name" id="boss_medium_name" value="Orc Warlord" class="mt-1 block w-full rounded-md bg-card border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                                </div>

                                <!-- Hard -->
                                <div class="border border-border p-4 rounded-md bg-background-dark">
                                    <div class="flex items-center mb-4">
                                        <input type="checkbox" name="hard_enabled" id="hard_enabled" value="1" class="h-4 w-4 text-primary focus:ring-primary border-border rounded bg-background-dark">
                                        <label for="hard_enabled" class="ml-2 block text-sm font-medium text-text-primary">Enable Hard</label>
                                    </div>
                                    <label for="boss_hard_name" class="block text-sm font-medium text-text-muted">Boss Name</label>
                                    <input type="text" name="boss_hard_name" id="boss_hard_name" value="Dragon Lord" class="mt-1 block w-full rounded-md bg-card border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('dosen.events.index') }}" class="bg-border hover:bg-text-muted text-text-primary font-bold py-2 px-4 rounded mr-2">Cancel</a>
                            <button type="submit" class="bg-primary hover:bg-primary/80 text-white font-bold py-2 px-4 rounded">Create Raid</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- EasyMDE Script -->
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
    <script>
        // Initialize EasyMDE for all 3 materi nodes
        const editorConfig = {
            spellChecker: false,
            placeholder: `Tulis materi dalam format Markdown...

Contoh:
# Heading 1
## Heading 2

**Bold text**

\`\`\`php
<?php
echo 'Hello';
?>
\`\`\``,
            toolbar: [
                "bold", "italic", "heading", "|",
                "code", "quote", "|",
                "unordered-list", "ordered-list", "|",
                "link", "|",
                "preview", "side-by-side", "fullscreen", "|",
                "guide"
            ],
            renderingConfig: {
                codeSyntaxHighlighting: true,
            }
        };

        const easyMDE1 = new EasyMDE({
            element: document.getElementById('materi_node_1'),
            ...editorConfig
        });

        const easyMDE2 = new EasyMDE({
            element: document.getElementById('materi_node_2'),
            ...editorConfig
        });

        const easyMDE3 = new EasyMDE({
            element: document.getElementById('materi_node_3'),
            ...editorConfig
        });
    </script>
</x-dosen-layout>
