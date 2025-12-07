<!-- Materi Modal Component -->
<div x-show="showInfoModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-start justify-center min-h-screen px-4 py-4 sm:py-8">
        <!-- Backdrop -->
        <div x-show="showInfoModal" 
             @click="showInfoModal = false" 
             class="fixed inset-0 transition-opacity" 
             aria-hidden="true">
            <div class="absolute inset-0 bg-black opacity-75"></div>
        </div>

        <!-- Modal Container -->
        <div x-show="showInfoModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="relative inline-block align-bottom bg-card rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full sm:max-w-4xl border border-border my-auto">
            
            <!-- Modal Header -->
            <div class="bg-background-dark px-4 sm:px-6 py-4 border-b border-border flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-primary/20 flex-shrink-0">
                        <span class="material-symbols-outlined text-primary">school</span>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-text-primary truncate" x-text="infoTitle"></h3>
                </div>
                <button @click="showInfoModal = false" 
                        class="text-text-muted hover:text-text-primary transition-colors flex-shrink-0 ml-2">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <!-- Modal Body (Scrollable) -->
            <div class="bg-card px-4 sm:px-6 py-4 sm:py-6 max-h-[60vh] sm:max-h-[70vh] overflow-y-auto">
                <div class="prose prose-sm sm:prose" x-html="renderedContent"></div>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-background-dark px-4 sm:px-6 py-3 sm:py-4 border-t border-border flex justify-end">
                <button type="button" 
                        @click="showInfoModal = false" 
                        class="inline-flex items-center justify-center gap-2 rounded-md border border-transparent shadow-sm px-4 sm:px-6 py-2 sm:py-2.5 bg-primary text-sm sm:text-base font-medium text-white hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <span class="material-symbols-outlined" style="font-size: 20px;">check_circle</span>
                    <span>Got it</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Additional prose styling for mobile */
    @media (max-width: 640px) {
        .prose {
            font-size: 0.875rem;
        }
        .prose h1 { font-size: 1.5em; }
        .prose h2 { font-size: 1.25em; }
        .prose h3 { font-size: 1.1em; }
        .prose h4 { font-size: 1em; }
        .prose pre {
            font-size: 0.75rem;
        }
    }
</style>
