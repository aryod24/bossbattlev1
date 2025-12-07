<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Badges') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-3xl font-black text-gray-900 dark:text-white">Badge Collection</h3>
                            <p class="text-gray-500 dark:text-gray-400 mt-2">Kumpulkan semua badge prestasi!</p>
                        </div>
                        <div class="text-right">
                            <span class="text-4xl font-bold text-primary">{{ count($unlockedBadges) }}</span>
                            <span class="text-xl text-gray-400">/ {{ count($allBadges) }}</span>
                            <div class="text-sm text-gray-500">Unlocked</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($allBadges as $id => $badge)
                            @php
                                $isUnlocked = isset($unlockedBadges[$id]);
                                $unlockDate = $isUnlocked ? $unlockedBadges[$id]->unlock_date->format('d M Y') : null;
                            @endphp
                            
                            <div class="group relative p-6 rounded-2xl border-2 flex flex-col items-center text-center transition-all duration-300 hover:shadow-xl
                                {{ $isUnlocked 
                                    ? 'border-yellow-400 bg-yellow-50 dark:border-yellow-500/50 dark:bg-yellow-900/10' 
                                    : 'border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 grayscale opacity-60 hover:opacity-100' 
                                }}">
                                
                                <div class="text-7xl mb-6 transform transition-transform group-hover:scale-110 {{ $isUnlocked ? 'drop-shadow-lg' : '' }}">
                                    {{ $badge['emoji'] }}
                                </div>
                                
                                <h4 class="text-lg font-bold mb-2 {{ $isUnlocked ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ $badge['name'] }}
                                </h4>
                                
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 flex-grow leading-relaxed">
                                    {{ $badge['description'] }}
                                </p>
                                
                                <div class="w-full pt-4 border-t border-gray-200 dark:border-gray-700">
                                    @if($isUnlocked)
                                        <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <span class="mr-1">✓</span> Unlocked: {{ $unlockDate }}
                                        </div>
                                    @else
                                        <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                            🔒 Locked
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
