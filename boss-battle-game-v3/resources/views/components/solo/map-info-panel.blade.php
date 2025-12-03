@props(['soloRaid'])

<div class="w-full md:w-1/2 bg-white p-6 md:p-12 flex flex-col justify-center">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold">▲</span>
            </div>
            <h2 class="text-xl font-bold text-gray-900">CodeBossArena</h2>
        </div>
        <a href="{{ route('solo.index') }}" class="inline-block bg-yellow-400 hover:bg-yellow-500 px-6 py-2 rounded-full font-semibold transition-colors text-gray-900">
            ← Back to List
        </a>
    </div>

    <!-- Raid Title -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            {{ $soloRaid->nama }}
        </h1>
        <p class="text-gray-600 text-base md:text-lg leading-relaxed">
            {{ $soloRaid->deskripsi }}
        </p>
    </div>

    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-2">
            <span class="text-gray-700 font-semibold">Raid Progress</span>
            <span class="text-gray-600 font-medium">0/6 Completed</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 h-full rounded-full" style="width: 0%"></div>
        </div>
    </div>

    <!-- Next Challenge Card -->
    <div class="bg-gray-50 rounded-2xl p-6 border-2 border-gray-200">
        <h3 class="text-gray-700 font-semibold mb-4">Your Next Challenge</h3>
        <div class="flex items-center gap-4 mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-teal-400 to-teal-500 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-3xl">menu_book</span>
            </div>
            <div>
                <h4 class="text-xl font-bold text-gray-900">Info Node 1</h4>
                <p class="text-gray-600 text-sm">Click to read study material</p>
            </div>
        </div>
        <button @click="openInfo(1)" class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-3 rounded-xl transition-colors">
            Read Material
        </button>
    </div>

    <!-- Raid Info -->
    <div class="mt-6 text-sm text-gray-500">
        <p><strong>Period:</strong> {{ $soloRaid->tanggal_mulai }} - {{ $soloRaid->tanggal_selesai }}</p>
        <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-800">{{ strtoupper($soloRaid->status) }}</span></p>
    </div>
</div>
