<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4">
        <!-- Header -->
        <div class="text-center mb-10">
            <span class="material-symbols-outlined text-6xl text-primary mb-4 block">quiz</span>
            <h1 class="text-4xl font-black text-text-dark-primary mb-3">Pre-test Penempatan</h1>
            <p class="text-text-dark-secondary text-lg max-w-xl mx-auto">
                Sebelum memulai perjalanan belajar, selesaikan pre-test ini untuk menentukan Section awal Anda.
            </p>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-surface-dark rounded-lg border border-border-dark p-5 text-center">
                <span class="material-symbols-outlined text-3xl text-green-400 mb-2 block">timer</span>
                <p class="text-text-dark-primary font-bold text-lg">30 Menit</p>
                <p class="text-text-dark-secondary text-sm">Waktu Pengerjaan</p>
            </div>
            <div class="bg-surface-dark rounded-lg border border-border-dark p-5 text-center">
                <span class="material-symbols-outlined text-3xl text-primary mb-2 block">help</span>
                <p class="text-text-dark-primary font-bold text-lg">30 Soal</p>
                <p class="text-text-dark-secondary text-sm">10 Easy + 10 Medium + 10 Hard</p>
            </div>
            <div class="bg-surface-dark rounded-lg border border-border-dark p-5 text-center">
                <span class="material-symbols-outlined text-3xl text-purple-400 mb-2 block">school</span>
                <p class="text-text-dark-primary font-bold text-lg">PHP Only</p>
                <p class="text-text-dark-secondary text-sm">Pemrograman PHP</p>
            </div>
        </div>

        <!-- Placement Info -->
        <div class="bg-surface-dark rounded-lg border border-border-dark p-6 mb-8">
            <h3 class="text-text-dark-primary font-bold text-lg mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">info</span>
                Sistem Penempatan
            </h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="bg-green-500/20 text-green-400 text-xs font-bold px-3 py-1 rounded-full">0 - 40%</span>
                    <span class="text-text-dark-secondary">→ Section 1: <span class="text-text-dark-primary font-semibold">Easy</span></span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-yellow-500/20 text-yellow-400 text-xs font-bold px-3 py-1 rounded-full">41 - 70%</span>
                    <span class="text-text-dark-secondary">→ Section 2: <span class="text-text-dark-primary font-semibold">Medium</span></span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-red-500/20 text-red-400 text-xs font-bold px-3 py-1 rounded-full">71 - 100%</span>
                    <span class="text-text-dark-secondary">→ Section 3: <span class="text-text-dark-primary font-semibold">Hard</span></span>
                </div>
            </div>
        </div>

        <!-- Action -->
        <div class="text-center">
            @if($activeSession)
                <a href="{{ route('pretest.play', $activeSession->id) }}" 
                   class="inline-flex items-center gap-2 rounded-lg h-12 px-8 bg-primary text-black font-bold text-base hover:brightness-95 transition-all shadow-lg shadow-primary/30">
                    <span class="material-symbols-outlined">play_arrow</span>
                    Lanjutkan Pre-test
                </a>
            @else
                <form action="{{ route('pretest.start') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center gap-2 rounded-lg h-12 px-8 bg-primary text-black font-bold text-base hover:brightness-95 transition-all shadow-lg shadow-primary/30">
                        <span class="material-symbols-outlined">rocket_launch</span>
                        Mulai Pre-test
                    </button>
                </form>
            @endif
        </div>

        @if(session('error'))
            <div class="mt-6 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg p-4 text-center text-sm">
                {{ session('error') }}
            </div>
        @endif
    </div>
</x-app-layout>
