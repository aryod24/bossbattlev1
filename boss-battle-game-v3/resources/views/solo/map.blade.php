<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $soloRaid->nama }} - Dungeon Map
            </h2>
            <a href="{{ route('solo.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back to List</a>
        </div>
    </x-slot>

    <div class="py-12" x-data="dungeonMap({{ $soloRaid->id }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Map Container -->
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg relative min-h-[600px] p-8" 
                 style="background-image: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1519074069444-1ba4fff66d16?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center;">
                
                <!-- Connection Lines (Visual only, simplified for grid) -->
                <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                    <!-- You can add SVG lines here if needed for more complex paths -->
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative z-10">
                    
                    <!-- Node 1: Info -->
                    <div class="flex flex-col items-center">
                        <button @click="openInfo(1)" class="w-24 h-24 rounded-full bg-blue-600 hover:bg-blue-500 border-4 border-blue-300 flex items-center justify-center shadow-lg transform hover:scale-110 transition-all duration-300 group">
                            <span class="text-4xl">📖</span>
                        </button>
                        <span class="mt-4 text-white font-bold bg-black bg-opacity-50 px-3 py-1 rounded">Study Room 1</span>
                    </div>

                    <!-- Node 2: Easy Level -->
                    <div class="flex flex-col items-center">
                        <button @click="checkLevel('easy')" 
                                :class="{'opacity-50 cursor-not-allowed': !levels.easy.available, 'hover:scale-110': levels.easy.available}"
                                class="w-24 h-24 rounded-full bg-green-600 border-4 border-green-300 flex items-center justify-center shadow-lg transform transition-all duration-300">
                            <span class="text-4xl">⭐</span>
                        </button>
                        <span class="mt-4 text-white font-bold bg-black bg-opacity-50 px-3 py-1 rounded">Easy: {{ $soloRaid->boss_easy_name }}</span>
                        <span x-show="!levels.easy.available" class="text-xs text-red-300 mt-1">Locked / Unavailable</span>
                    </div>

                    <!-- Node 3: Info -->
                    <div class="flex flex-col items-center">
                        <button @click="openInfo(2)" class="w-24 h-24 rounded-full bg-blue-600 hover:bg-blue-500 border-4 border-blue-300 flex items-center justify-center shadow-lg transform hover:scale-110 transition-all duration-300">
                            <span class="text-4xl">📖</span>
                        </button>
                        <span class="mt-4 text-white font-bold bg-black bg-opacity-50 px-3 py-1 rounded">Study Room 2</span>
                    </div>

                    <!-- Node 4: Medium Level -->
                    <div class="flex flex-col items-center">
                        <button @click="checkLevel('medium')" 
                                :class="{'opacity-50 cursor-not-allowed': !levels.medium.available, 'hover:scale-110': levels.medium.available}"
                                class="w-24 h-24 rounded-full bg-yellow-600 border-4 border-yellow-300 flex items-center justify-center shadow-lg transform transition-all duration-300">
                            <span class="text-4xl">⭐⭐</span>
                        </button>
                        <span class="mt-4 text-white font-bold bg-black bg-opacity-50 px-3 py-1 rounded">Medium: {{ $soloRaid->boss_medium_name }}</span>
                        <span x-show="!levels.medium.available" class="text-xs text-red-300 mt-1">Locked / Unavailable</span>
                    </div>

                    <!-- Node 5: Info -->
                    <div class="flex flex-col items-center">
                        <button @click="openInfo(3)" class="w-24 h-24 rounded-full bg-blue-600 hover:bg-blue-500 border-4 border-blue-300 flex items-center justify-center shadow-lg transform hover:scale-110 transition-all duration-300">
                            <span class="text-4xl">📖</span>
                        </button>
                        <span class="mt-4 text-white font-bold bg-black bg-opacity-50 px-3 py-1 rounded">Study Room 3</span>
                    </div>

                    <!-- Node 6: Hard Level -->
                    <div class="flex flex-col items-center">
                        <button @click="checkLevel('hard')" 
                                :class="{'opacity-50 cursor-not-allowed': !levels.hard.available, 'hover:scale-110': levels.hard.available}"
                                class="w-24 h-24 rounded-full bg-red-600 border-4 border-red-300 flex items-center justify-center shadow-lg transform transition-all duration-300">
                            <span class="text-4xl">⭐⭐⭐</span>
                        </button>
                        <span class="mt-4 text-white font-bold bg-black bg-opacity-50 px-3 py-1 rounded">Hard: {{ $soloRaid->boss_hard_name }}</span>
                        <span x-show="!levels.hard.available" class="text-xs text-red-300 mt-1">Locked / Unavailable</span>
                    </div>

                </div>
            </div>

        </div>

        <!-- Info Modal -->
        <div x-show="showInfoModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showInfoModal" @click="showInfoModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showInfoModal" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="infoTitle"></h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 whitespace-pre-line" x-text="infoContent"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="showInfoModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dungeonMap', (raidId) => ({
                raidId: raidId,
                levels: {
                    easy: { available: false },
                    medium: { available: false },
                    hard: { available: false }
                },
                showInfoModal: false,
                infoTitle: '',
                infoContent: '',

                init() {
                    this.fetchLevels();
                },

                fetchLevels() {
                    fetch(`/solo/${this.raidId}/level-select`)
                        .then(res => res.json())
                        .then(data => {
                            this.levels = data;
                        });
                },

                openInfo(nodeId) {
                    fetch(`/solo/${this.raidId}/info/${nodeId}`)
                        .then(res => res.json())
                        .then(data => {
                            this.infoTitle = data.title;
                            this.infoContent = data.content;
                            this.showInfoModal = true;
                        });
                },

                checkLevel(level) {
                    if (this.levels[level].available) {
                        // In next prompt: redirect to gameplay
                        alert(`Starting ${level} level... (Gameplay implementation in next prompt)`);
                    } else {
                        alert('This level is currently locked or unavailable.');
                    }
                }
            }))
        })
    </script>
</x-app-layout>
