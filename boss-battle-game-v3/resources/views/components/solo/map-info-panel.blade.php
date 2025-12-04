@props(['soloRaid'])

<div class="w-full md:w-1/2 bg-card p-6 md:p-12 flex flex-col justify-center">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10">
                <img src="{{ asset('assets/logo.png') }}" alt="CodeBossArena Logo" class="w-full h-full object-contain">
            </div>
            <h2 class="text-xl font-bold text-text-primary">CodeBossArena</h2>
        </div>
        <a href="{{ route('solo.index') }}" class="inline-block bg-primary hover:bg-accent-hover px-6 py-2 rounded-lg font-semibold transition-colors text-white">
            ← Back to List
        </a>
    </div>

    <!-- Raid Title -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-text-primary mb-4">
            {{ $soloRaid->nama }}
        </h1>
        <p class="text-text-muted text-base md:text-lg leading-relaxed">
            {{ $soloRaid->deskripsi }}
        </p>
    </div>

    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-2">
            <span class="text-text-primary font-semibold">Raid Progress</span>
            <span class="text-text-muted font-medium">0/6 Completed</span>
        </div>
        <div class="w-full bg-border rounded-full h-3 overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-accent-hover h-full rounded-full" style="width: 0%"></div>
        </div>
    </div>

    <!-- Next Challenge Card -->
    <div class="bg-surface-light rounded-2xl p-6 border-2 border-border">
        <h3 class="text-text-muted font-semibold mb-4">Your Next Challenge</h3>
        <div class="flex items-center gap-4 mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-info to-blue-500 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-3xl">menu_book</span>
            </div>
            <div>
                <h4 class="text-xl font-bold text-text-primary">Info Node 1</h4>
                <p class="text-text-muted text-sm">Click to read study material</p>
            </div>
        </div>
        <button @click="openInfo(1)" class="w-full bg-primary hover:bg-accent-hover text-white font-bold py-3 rounded-lg transition-colors">
            Read Material
        </button>
    </div>

    <!-- Raid Info -->
    <div class="mt-6 text-sm text-text-muted">
        <p><strong>Period:</strong> {{ $soloRaid->tanggal_mulai }} - {{ $soloRaid->tanggal_selesai }}</p>
        <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs font-bold bg-success/20 text-success">{{ strtoupper($soloRaid->status) }}</span></p>
    </div>
</div>
