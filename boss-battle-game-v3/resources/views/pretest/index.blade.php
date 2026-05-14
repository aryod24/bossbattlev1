<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4">
        <!-- Header as small centered card (like result) -->
        <div class="mb-8">
            <div class="bg-card p-4 rounded-lg border border-border shadow-sm flex items-center gap-4">
                <span class="material-symbols-outlined text-4xl text-primary">quiz</span>
                <div class="text-left">
                    <h1 class="text-2xl font-black text-text-primary mb-0">Pre-test Penempatan</h1>
                    <p class="text-text-secondary text-sm">30 Menit • 30 Soal • PHP Only</p>
                </div>
            </div>
        </div>

        <!-- Short intro card (kept as single full-width card) -->
        <div class="bg-card p-6 rounded-lg border border-border shadow-sm mb-6">
            <h2 class="text-lg font-bold text-text-primary mb-2">Sebelum memulai perjalanan belajar</h2>
            <p class="text-text-secondary">Selesaikan pre-test ini untuk menentukan Section awal Anda. Hasil penempatan akan membantu menyarankan materi yang sesuai dengan tingkat kemampuan Anda.</p>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 mb-8">
            <div class="bg-card rounded-lg border border-border p-5 text-center shadow-sm">
                <span class="material-symbols-outlined text-3xl text-green-400 mb-2 block">timer</span>
                <p class="text-text-primary font-bold text-lg">30 Menit</p>
                <p class="text-text-secondary text-sm">Waktu Pengerjaan</p>
            </div>
            <div class="bg-card rounded-lg border border-border p-5 text-center shadow-sm">
                <span class="material-symbols-outlined text-3xl text-primary mb-2 block">help</span>
                <p class="text-text-primary font-bold text-lg">30 Soal</p>
                <p class="text-text-secondary text-sm">10 Easy + 10 Medium + 10 Hard</p>
            </div>
            <div class="bg-card rounded-lg border border-border p-5 text-center shadow-sm">
                <span class="material-symbols-outlined text-3xl text-purple-400 mb-2 block">school</span>
                <p class="text-text-primary font-bold text-lg">PHP Only</p>
                <p class="text-text-secondary text-sm">Pemrograman PHP</p>
            </div>
        </div>

        <!-- Placement Info split into two cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="bg-card rounded-lg border border-border p-6 shadow-sm">
                <h3 class="text-text-primary font-bold text-lg mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">info</span>
                    Sistem Penempatan
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="bg-green-500/20 text-green-400 text-xs font-bold px-3 py-1 rounded-full">0 - 40%</span>
                        <span class="text-text-secondary">→ Section 1: <span class="text-text-primary font-semibold">Easy</span></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="bg-yellow-500/20 text-yellow-400 text-xs font-bold px-3 py-1 rounded-full">41 - 70%</span>
                        <span class="text-text-secondary">→ Section 2: <span class="text-text-primary font-semibold">Medium</span></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="bg-red-500/20 text-red-400 text-xs font-bold px-3 py-1 rounded-full">71 - 100%</span>
                        <span class="text-text-secondary">→ Section 3: <span class="text-text-primary font-semibold">Hard</span></span>
                    </div>
                </div>
            </div>

            <div class="bg-card rounded-lg border border-border p-6 shadow-sm flex items-center">
                <div class="flex items-start gap-4">
                    <span class="material-symbols-outlined text-3xl text-primary">campaign</span>
                    <div>
                        <h4 class="text-text-primary font-bold mb-2">Kerjakan dengan sungguh-sungguh</h4>
                        <p class="text-text-secondary">Jawablah setiap soal sejujurnya dan perhatikan waktu. Hasil yang akurat membantu sistem menempatkan Anda di Section yang sesuai.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action -->
        <div class="text-center">
            @if($activeSession)
                <a href="{{ route('pretest.play', $activeSession->id) }}" 
                   class="inline-flex items-center justify-center rounded-lg h-10 px-5 bg-primary text-white gap-2 text-sm font-bold shadow-sm hover:bg-accent-hover transition-transform duration-200 hover:-translate-y-0.5">
                    <span class="material-symbols-outlined">play_arrow</span>
                    Lanjutkan Pre-test
                </a>
            @else
                <form action="{{ route('pretest.start') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center justify-center rounded-lg h-10 px-5 bg-primary text-white gap-2 text-sm font-bold shadow-sm hover:bg-accent-hover transition-transform duration-200 hover:-translate-y-0.5">
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
