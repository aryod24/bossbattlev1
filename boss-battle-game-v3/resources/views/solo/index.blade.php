<x-app-layout>
    <div class="flex flex-col gap-6" x-data="{ 
        currentFilter: 'all',
        searchQuery: '',
        filterEvents(status) {
            this.currentFilter = status;
        },
        isVisible(eventStatus, isExpired) {
            if (this.currentFilter === 'all') return true;
            if (this.currentFilter === 'active') return eventStatus === 'active' && !isExpired;
            if (this.currentFilter === 'closed') return isExpired;
            return true;
        }
    }">
        <!-- Page Heading -->
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div class="flex flex-col gap-2">
                <p class="text-4xl font-black leading-tight tracking-tight text-text-primary">Daftar Event</p>
                <p class="text-text-muted text-base font-normal leading-normal">Bergabunglah dengan teman & kompetisi</p>
            </div>
            <!-- Only show Create button for Admins if needed, or hide it for students -->
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.solo-raids.create') }}" class="flex items-center justify-center rounded-lg h-12 bg-primary text-white gap-2 text-sm font-bold leading-normal tracking-wide min-w-0 px-6 shadow-sm hover:bg-accent-hover transition-transform duration-200 hover:-translate-y-0.5">
                <span class="material-symbols-outlined">add_circle</span>
                <span class="truncate">Buat Event Baru</span>
            </a>
            @endif
        </div>

        <!-- Toolbar: Filters and Search -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex gap-2 p-1 bg-card border border-border rounded-lg overflow-x-auto">
                <div @click="filterEvents('all')" 
                     :class="currentFilter === 'all' ? 'bg-primary shadow-sm' : 'hover:bg-primary/20'" 
                     class="flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg pl-4 pr-4 transition-colors">
                    <p :class="currentFilter === 'all' ? 'text-white font-bold' : 'text-text-primary font-medium'" class="text-sm leading-normal">Semua</p>
                </div>
                <div @click="filterEvents('active')" 
                     :class="currentFilter === 'active' ? 'bg-primary shadow-sm' : 'hover:bg-primary/20'" 
                     class="flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg pl-4 pr-4 transition-colors">
                    <p :class="currentFilter === 'active' ? 'text-white font-bold' : 'text-text-primary font-medium'" class="text-sm leading-normal">Aktif</p>
                </div>
                <div @click="filterEvents('closed')" 
                     :class="currentFilter === 'closed' ? 'bg-primary shadow-sm' : 'hover:bg-primary/20'" 
                     class="flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg pl-4 pr-4 transition-colors">
                    <p :class="currentFilter === 'closed' ? 'text-white font-bold' : 'text-text-primary font-medium'" class="text-sm leading-normal">Selesai</p>
                </div>
            </div>
            <div class="w-full md:max-w-xs">
                <label class="flex flex-col min-w-40 h-12 w-full">
                    <div class="flex w-full flex-1 items-stretch rounded-lg h-full shadow-sm border border-border bg-card focus-within:ring-2 focus-within:ring-primary">
                        <div class="text-text-muted flex items-center justify-center pl-4 rounded-l-lg border-r-0">
                            <span class="material-symbols-outlined">search</span>
                        </div>
                        <input x-model="searchQuery" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-base font-normal h-full placeholder:text-text-muted px-4 pl-2 focus:outline-none focus:ring-0 border-none bg-transparent text-text-primary" placeholder="Cari event..." value=""/>
                    </div>
                </label>
            </div>
        </div>

        <!-- Event Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($raids as $raid)
                @php
                    $isExpired = now()->greaterThan($raid->tanggal_selesai);
                @endphp
                <div x-show="isVisible('{{ $raid->status }}', {{ $isExpired ? 'true' : 'false' }}) && (searchQuery === '' || '{{ strtolower($raid->nama) }}'.includes(searchQuery.toLowerCase()))"
                     x-transition
                     class="flex flex-col bg-card rounded-lg border border-border shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 cursor-pointer overflow-hidden h-full {{ $raid->status === 'draft' ? 'opacity-70' : '' }}">
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start gap-4 mb-2">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-text-primary mb-1">{{ $raid->nama }}</h3>
                                
                                {{-- Deadline / Countdown Section --}}
                                @if($raid->status === 'active')
                                    @if(!$isExpired)
                                        <div class="flex items-center text-error animate-pulse">
                                            <span class="material-symbols-outlined text-sm mr-1">timer</span>
                                            <p class="text-xs font-bold">Berakhir {{ \Carbon\Carbon::parse($raid->tanggal_selesai)->diffForHumans() }}</p>
                                        </div>
                                    @else
                                        <div class="flex items-center text-text-muted">
                                            <span class="material-symbols-outlined text-sm mr-1">event_busy</span>
                                            <p class="text-xs font-bold">Event Berakhir</p>
                                        </div>
                                    @endif
                                @elseif($raid->status === 'selesai')
                                     <div class="flex items-center text-success">
                                        <span class="material-symbols-outlined text-sm mr-1">check_circle</span>
                                        <p class="text-xs font-bold">Telah Diselesaikan</p>
                                    </div>
                                @else
                                    <div class="flex items-center text-text-muted">
                                        <span class="material-symbols-outlined text-sm mr-1">edit_note</span>
                                        <p class="text-xs font-bold">Mode Draft</p>
                                    </div>
                                @endif
                            </div>
                            <!-- Placeholder Boss Image -->
                            <img class="w-12 h-12 rounded-full border-2 border-border object-cover shrink-0" src="https://ui-avatars.com/api/?name={{ urlencode($raid->boss_easy_name ?? 'Boss') }}&background=random" alt="Boss">
                        </div>
                        
                        <p class="text-xs text-text-muted mb-4 line-clamp-2 mt-2">{{ $raid->deskripsi }}</p>

                        <div class="mt-auto">
                           <!-- Progress Bar Placeholder -->
                            <div class="text-xs text-text-muted mb-1 flex justify-between">
                                <span>XP Reward</span>
                                <span class="font-bold text-text-primary">{{ $raid->question_count ?? 10 }}0 XP</span>
                            </div>
                            <div class="w-full bg-border rounded-full h-1.5 mb-4">
                                <div class="bg-primary h-1.5 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-5 py-4 border-t border-border bg-background-dark/50">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center gap-2 text-sm text-text-muted">
                                <span class="material-symbols-outlined text-lg">calendar_today</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($raid->tanggal_mulai)->format('d M Y') }}</span>
                            </div>

                            {{-- Difficulty Badge (Moved here) --}}
                            @if($raid->hard_enabled)
                                <span class="text-[10px] font-bold px-2 py-0.5 bg-red-500/10 text-red-500 border border-red-500/20 rounded uppercase tracking-wider">Hard</span>
                            @elseif($raid->medium_enabled)
                                <span class="text-[10px] font-bold px-2 py-0.5 bg-orange-500/10 text-orange-500 border border-orange-500/20 rounded uppercase tracking-wider">Medium</span>
                            @else
                                <span class="text-[10px] font-bold px-2 py-0.5 bg-green-500/10 text-green-500 border border-green-500/20 rounded uppercase tracking-wider">Easy</span>
                            @endif
                        </div>
                        
                        <div class="flex justify-between items-center">
                            @if($raid->status === 'active' && !$isExpired)
                                <span class="pulse-red text-xs font-bold px-3 py-1 bg-error text-white rounded-md uppercase tracking-wider">Ongoing</span>
                                <a href="{{ route('solo.map', $raid) }}" class="flex items-center justify-center rounded-lg h-10 bg-primary text-white gap-2 text-sm font-bold px-5 shadow-sm hover:bg-accent-hover transition-transform duration-200 hover:scale-105">
                                    Bergabung
                                </a>
                            @elseif($raid->status === 'active' && $isExpired)
                                <span class="text-xs font-bold px-3 py-1 bg-text-muted text-white rounded-md uppercase tracking-wider">Tutup</span>
                                <button class="flex items-center justify-center rounded-lg h-10 bg-border text-text-muted gap-2 text-sm font-bold px-5 cursor-not-allowed" disabled>
                                    Tutup
                                </button>
                            @elseif($raid->status === 'selesai')
                                <span class="text-xs font-bold px-3 py-1 bg-success text-white rounded-md uppercase tracking-wider">Selesai</span>
                                <button class="flex items-center justify-center rounded-lg h-10 bg-info text-white gap-2 text-sm font-bold px-5 shadow-sm hover:brightness-110 transition-colors duration-200">
                                    Leaderboard
                                </button>
                            @else
                                <span class="text-xs font-bold px-3 py-1 bg-text-muted text-white rounded-md uppercase tracking-wider">Draft</span>
                                <button class="flex items-center justify-center rounded-lg h-10 bg-border text-text-muted gap-2 text-sm font-bold px-5 cursor-not-allowed" disabled>
                                    Daftar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 flex flex-col items-center justify-center text-center p-16 rounded-lg bg-card border border-border mt-4">
                    <span class="material-symbols-outlined text-6xl text-text-muted mb-4">sentiment_dissatisfied</span>
                    <h3 class="text-2xl font-bold mb-2 text-text-primary">Tidak ada event saat ini.</h3>
                    <p class="text-text-muted mb-4">Kembali lagi nanti untuk melihat keseruan baru!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $raids->links() }}
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route("solo.check-expired") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.processed > 0) {
                    console.log('Processed ' + data.processed + ' expired sessions.');
                    // Optional: Reload page if sessions were updated to reflect status changes
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error checking expired sessions:', error));
        });
    </script>
</x-app-layout>
