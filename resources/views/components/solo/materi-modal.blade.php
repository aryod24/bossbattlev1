{{-- Materi Modal Component (cyber-noir, matches dashboard/profile design) --}}
<div x-show="showInfoModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-start justify-center min-h-screen px-4 py-4 sm:py-8">
        {{-- Backdrop --}}
        <div x-show="showInfoModal"
             @click="showInfoModal = false"
             class="fixed inset-0 transition-opacity"
             aria-hidden="true">
            <div class="absolute inset-0 bg-black opacity-75"></div>
        </div>

        {{-- Modal Container --}}
        <div x-show="showInfoModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             class="relative inline-block align-bottom rounded-xl text-left overflow-hidden shadow-2xl transform transition-all w-full sm:max-w-4xl my-auto"
             style="background: rgba(25, 25, 28, 0.95);
                    backdrop-filter: blur(20px);
                    -webkit-backdrop-filter: blur(20px);
                    border: 1px solid rgba(0, 242, 255, 0.4);
                    box-shadow: 0 0 50px rgba(0, 242, 255, 0.15);">

            {{-- Modal Header --}}
            <div class="px-4 sm:px-6 py-4 flex items-center justify-between gap-3"
                 style="background-color: rgba(14, 14, 15, 0.7); border-bottom: 1px solid rgba(58, 73, 75, 0.5);">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex items-center justify-center h-10 w-10 rounded-xl flex-shrink-0"
                         style="background: linear-gradient(135deg, rgba(0,242,255,0.2), rgba(206,93,255,0.15));
                                border: 1px solid rgba(0, 242, 255, 0.4);">
                        <span class="material-symbols-outlined text-cyan-glow">school</span>
                    </div>
                    <div class="min-w-0">
                        <p class="font-mono-label text-[10px] uppercase tracking-widest text-faint mb-0.5">Materi</p>
                        <h3 class="font-headline text-lg sm:text-xl font-bold truncate" style="color: #e5e2e3;" x-text="infoTitle"></h3>
                    </div>
                </div>
                <button @click="showInfoModal = false"
                        class="flex items-center justify-center w-9 h-9 rounded-lg flex-shrink-0 transition-colors"
                        style="background-color: rgba(32, 31, 32, 0.6); border: 1px solid rgba(58, 73, 75, 0.4); color: #b9cacb;"
                        onmouseover="this.style.borderColor='rgba(0,242,255,0.4)'; this.style.color='#00f2ff';"
                        onmouseout="this.style.borderColor='rgba(58, 73, 75, 0.4)'; this.style.color='#b9cacb';">
                    <span class="material-symbols-outlined" style="font-size: 20px;">close</span>
                </button>
            </div>

            {{-- Modal Body (Scrollable) --}}
            <div class="px-4 sm:px-6 py-4 sm:py-6 max-h-[60vh] sm:max-h-[70vh] overflow-y-auto custom-scrollbar"
                 style="background-color: rgba(14, 14, 15, 0.4);">
                <div class="prose prose-sm sm:prose materi-prose" x-html="renderedContent"></div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex justify-between items-center gap-3"
                 style="background-color: rgba(14, 14, 15, 0.7); border-top: 1px solid rgba(58, 73, 75, 0.5);">
                <button type="button"
                        @click="showInfoModal = false"
                        class="font-headline inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors"
                        style="background-color: transparent; color: #b9cacb; border: 1px solid rgba(58, 73, 75, 0.5);"
                        onmouseover="this.style.backgroundColor='rgba(0,242,255,0.05)'; this.style.borderColor='rgba(0,242,255,0.3)';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.borderColor='rgba(58, 73, 75, 0.5)';">
                    Tutup
                </button>

                <button type="button"
                        @click="!isNodeAlreadyDone && markNodeDone()"
                        :disabled="isMarkingDone || isNodeAlreadyDone"
                        x-bind:class="isNodeAlreadyDone ? 'materi-btn-done' : 'materi-btn-active'"
                        class="font-headline inline-flex items-center justify-center gap-2 rounded-lg px-5 sm:px-6 py-2 sm:py-2.5 text-sm sm:text-base font-bold transition-all">
                    <span class="material-symbols-outlined" style="font-size: 20px;" x-show="!isMarkingDone">check_circle</span>
                    <span x-show="isMarkingDone" class="inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></span>
                    <span x-text="isNodeAlreadyDone ? 'Sudah Dibaca' : (isMarkingDone ? 'Menyimpan...' : 'Selesai Baca')"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Footer button states */
    .materi-btn-active {
        background: linear-gradient(135deg, #00f2ff, #ce5dff);
        color: #ffffff;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    }
    .materi-btn-active:not(:disabled):hover {
        box-shadow: 0 0 20px rgba(0, 242, 255, 0.55);
    }
    .materi-btn-active:disabled { opacity: 0.6; cursor: not-allowed; }

    .materi-btn-done {
        background-color: rgba(34, 197, 94, 0.15);
        color: #86efac;
        border: 1px solid rgba(34, 197, 94, 0.4);
        cursor: not-allowed;
        opacity: 0.85;
    }

    /* Cyber-noir prose overrides — only inside the materi modal */
    .materi-prose { color: #d4d4d4; max-width: none; }
    .materi-prose > :first-child { margin-top: 0 !important; }
    .materi-prose h1 { color: #00f2ff; }
    .materi-prose h2 { color: #00f2ff; }
    .materi-prose h3 { color: #ce5dff; }
    .materi-prose h4 { color: #b9cacb; }
    .materi-prose strong { color: #fde68a; }
    .materi-prose em { color: #ebb2ff; }
    .materi-prose blockquote {
        border-left-color: #00f2ff !important;
        background: rgba(0, 242, 255, 0.05) !important;
        color: #b9cacb !important;
    }
    .materi-prose th { color: #00f2ff !important; }
    .materi-prose a { color: #00f2ff !important; }
    .materi-prose code {
        background: rgba(206, 93, 255, 0.1);
        color: #ebb2ff;
    }
    .materi-prose pre {
        background: rgba(14, 14, 15, 0.85) !important;
        border-color: rgba(0, 242, 255, 0.2) !important;
    }
    .materi-prose pre code {
        background: transparent;
        color: #d4d4d4;
    }

    /* Mobile responsive */
    @media (max-width: 640px) {
        .materi-prose { font-size: 0.875rem; }
        .materi-prose h1 { font-size: 1.5em; }
        .materi-prose h2 { font-size: 1.25em; }
        .materi-prose h3 { font-size: 1.1em; }
        .materi-prose h4 { font-size: 1em; }
        .materi-prose pre { font-size: 0.75rem; }
    }
</style>
