<x-app-layout>
    <div class="relative flex h-auto min-h-screen w-full flex-col bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden text-text-light-primary dark:text-text-dark-primary">
        <div class="layout-container flex h-full grow flex-col">
            <div class="px-4 sm:px-8 md:px-16 lg:px-24 xl:px-40 flex flex-1 justify-center py-5">
                <div class="layout-content-container flex flex-col max-w-6xl w-full flex-1">
                    <!-- Page Heading -->
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div class="flex flex-col gap-2">
                            <p class="text-4xl font-black leading-tight tracking-tight">Daftar Event</p>
                            <p class="text-text-light-secondary dark:text-text-dark-secondary text-base font-normal leading-normal">Bergabunglah dengan teman & kompetisi</p>
                        </div>
                        <!-- Only show Create button for Admins if needed, or hide it for students -->
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.solo-raids.create') }}" class="flex items-center justify-center rounded-lg h-12 bg-primary text-black gap-2 text-sm font-bold leading-normal tracking-wide min-w-0 px-6 shadow-sm hover:brightness-95 transition-transform duration-200 hover:-translate-y-0.5">
                            <span class="material-symbols-outlined">add_circle</span>
                            <span class="truncate">Buat Event Baru</span>
                        </a>
                        @endif
                    </div>

                    <!-- Toolbar: Filters and Search -->
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 p-4">
                        <div class="flex gap-2 p-1 bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-lg overflow-x-auto">
                            <div class="flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg bg-primary pl-4 pr-4 shadow-sm">
                                <p class="text-black text-sm font-bold leading-normal">Semua</p>
                            </div>
                            <div class="flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg hover:bg-primary/20 pl-4 pr-4 transition-colors">
                                <p class="text-sm font-medium leading-normal">Aktif</p>
                            </div>
                            <div class="flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg hover:bg-primary/20 pl-4 pr-4 transition-colors">
                                <p class="text-sm font-medium leading-normal">Mendatang</p>
                            </div>
                            <div class="flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg hover:bg-primary/20 pl-4 pr-4 transition-colors">
                                <p class="text-sm font-medium leading-normal">Selesai</p>
                            </div>
                        </div>
                        <div class="w-full md:max-w-xs">
                            <label class="flex flex-col min-w-40 h-12 w-full">
                                <div class="flex w-full flex-1 items-stretch rounded-lg h-full shadow-sm border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark focus-within:ring-2 focus-within:ring-primary">
                                    <div class="text-text-light-secondary dark:text-text-dark-secondary flex items-center justify-center pl-4 rounded-l-lg border-r-0">
                                        <span class="material-symbols-outlined">search</span>
                                    </div>
                                    <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-base font-normal h-full placeholder:text-text-light-secondary dark:placeholder:text-text-dark-secondary px-4 pl-2 focus:outline-none focus:ring-0 border-none bg-transparent" placeholder="Cari event..." value=""/>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Event Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
                        @forelse($raids as $raid)
                            <div class="flex flex-col bg-surface-light dark:bg-surface-dark rounded-lg border border-border-light dark:border-border-dark shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 cursor-pointer overflow-hidden {{ $raid->status === 'draft' ? 'opacity-70' : '' }}">
                                <div class="p-5">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <h3 class="text-lg font-bold">{{ $raid->nama }}</h3>
                                            @if($raid->hard_enabled)
                                                <span class="text-xs font-bold px-2 py-1 bg-red-500 text-white rounded-md">Hard</span>
                                            @elseif($raid->medium_enabled)
                                                <span class="text-xs font-bold px-2 py-1 bg-orange-500 text-white rounded-md">Medium</span>
                                            @else
                                                <span class="text-xs font-bold px-2 py-1 bg-green-500 text-white rounded-md">Easy</span>
                                            @endif
                                        </div>
                                        <!-- Placeholder Boss Image -->
                                        <img class="w-12 h-12 rounded-full border-2 border-border-light dark:border-border-dark object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($raid->boss_easy_name ?? 'Boss') }}&background=random" alt="Boss">
                                    </div>
                                    
                                    @if($raid->status === 'active')
                                        <div class="flex items-center text-red-500 dark:text-red-400 mt-3 animate-pulse">
                                            <span class="material-symbols-outlined text-base mr-1">timer</span>
                                            <p class="text-sm font-bold">Berakhir {{ \Carbon\Carbon::parse($raid->tanggal_selesai)->diffForHumans() }}</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="px-5 py-4 border-t border-border-light dark:border-border-dark bg-background-light/50 dark:bg-background-dark/50">
                                    <div class="flex items-center gap-2 text-sm text-text-light-secondary dark:text-text-dark-secondary mb-3">
                                        <span class="material-symbols-outlined text-lg">calendar_today</span>
                                        <span>{{ \Carbon\Carbon::parse($raid->tanggal_mulai)->format('d M Y') }}</span>
                                    </div>
                                    
                                    <!-- Progress Bar Placeholder (can be dynamic later) -->
                                    <div class="text-sm text-text-light-secondary dark:text-text-dark-secondary mb-2">XP Reward: {{ $raid->question_count ?? 10 }}0 XP</div>
                                    <div class="w-full bg-border-light dark:bg-border-dark rounded-full h-2 mb-3">
                                        <div class="bg-primary h-2 rounded-full" style="width: 0%"></div>
                                    </div>
                                    
                                    <p class="text-xs text-text-light-secondary dark:text-text-dark-secondary mb-4 line-clamp-2">{{ $raid->deskripsi }}</p>
                                    
                                    <div class="flex justify-between items-center">
                                        @if($raid->status === 'active')
                                            <span class="pulse-red text-xs font-bold px-3 py-1 bg-red-600 text-white rounded-md uppercase tracking-wider">Ongoing</span>
                                            <a href="{{ route('solo.map', $raid) }}" class="flex items-center justify-center rounded-lg h-10 bg-primary text-black gap-2 text-sm font-bold px-5 shadow-sm hover:brightness-95 transition-transform duration-200 hover:scale-105">
                                                Bergabung
                                            </a>
                                        @elseif($raid->status === 'selesai')
                                            <span class="text-xs font-bold px-3 py-1 bg-green-600 text-white rounded-md uppercase tracking-wider">Selesai</span>
                                            <button class="flex items-center justify-center rounded-lg h-10 bg-blue-500 text-white gap-2 text-sm font-bold px-5 shadow-sm hover:bg-blue-600 transition-colors duration-200">
                                                Leaderboard
                                            </button>
                                        @else
                                            <span class="text-xs font-bold px-3 py-1 bg-gray-500 text-white rounded-md uppercase tracking-wider">Draft</span>
                                            <button class="flex items-center justify-center rounded-lg h-10 bg-border-light dark:bg-border-dark text-text-light-secondary dark:text-text-dark-secondary gap-2 text-sm font-bold px-5 cursor-not-allowed" disabled>
                                                Daftar
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 flex flex-col items-center justify-center text-center p-16 rounded-lg bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark mt-4">
                                <span class="material-symbols-outlined text-6xl text-text-light-secondary dark:text-text-dark-secondary mb-4">sentiment_dissatisfied</span>
                                <h3 class="text-2xl font-bold mb-2">Tidak ada event saat ini.</h3>
                                <p class="text-text-light-secondary dark:text-text-dark-secondary mb-4">Kembali lagi nanti untuk melihat keseruan baru!</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $raids->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
