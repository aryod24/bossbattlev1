<x-dosen-layout>
    <div class="space-y-6">
        <!-- Page Header & Stats -->
        <div class="bg-card rounded-2xl shadow-sm border border-border p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Welcome Section -->
                <div class="flex items-center gap-4 min-w-0">
                    <div class="flex items-center justify-center w-12 h-12 md:w-16 md:h-16 rounded-full bg-primary/10 border-2 border-primary shrink-0">
                        <span class="material-symbols-outlined text-2xl md:text-3xl text-primary">school</span>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-2xl md:text-3xl font-black text-text-primary tracking-tight truncate">Dashboard Dosen</h1>
                        <p class="text-xs md:text-sm text-text-muted mt-1 font-medium truncate">Selamat datang, {{ auth()->user()->nama }}</p>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="flex items-center gap-2 sm:gap-4 shrink-0 pt-4 md:pt-0 border-t md:border-t-0 border-border">
                    <!-- Events Stat -->
                    <a href="{{ route('dosen.events.index') }}" class="flex items-center gap-3 p-2 md:p-3 rounded-xl hover:bg-surface-light border border-transparent hover:border-border transition-all">
                        <div class="flex items-center justify-center w-10 h-10 md:w-12 md:h-12 rounded-xl bg-primary/10 shrink-0">
                            <span class="material-symbols-outlined text-xl md:text-2xl text-primary">calendar_month</span>
                        </div>
                        <div>
                            <p class="text-[10px] text-text-muted font-bold uppercase tracking-wider mb-0.5">Event</p>
                            <div class="flex items-baseline gap-1">
                                <h3 class="text-xl md:text-2xl font-black text-text-primary leading-none">{{ $totalEvents }}</h3>
                                <span class="text-[10px] md:text-xs text-primary font-medium hidden sm:inline">({{ $myEvents }} milik Anda)</span>
                            </div>
                        </div>
                    </a>
                    
                    <div class="w-px h-8 md:h-10 bg-border"></div>
                    
                    <!-- Banks Stat -->
                    <a href="{{ route('dosen.questions.index') }}" class="flex items-center gap-3 p-2 md:p-3 rounded-xl hover:bg-surface-light border border-transparent hover:border-border transition-all">
                        <div class="flex items-center justify-center w-10 h-10 md:w-12 md:h-12 rounded-xl bg-success/10 shrink-0">
                            <span class="material-symbols-outlined text-xl md:text-2xl text-success">quiz</span>
                        </div>
                        <div>
                            <p class="text-[10px] text-text-muted font-bold uppercase tracking-wider mb-0.5">Bank Soal</p>
                            <div class="flex items-baseline gap-1">
                                <h3 class="text-xl md:text-2xl font-black text-text-primary leading-none">{{ $totalBanks }}</h3>
                                <span class="text-[10px] md:text-xs text-success font-medium hidden sm:inline">({{ $totalQuestions }} soal)</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Layout Grid for Recent Events & Talent Scout -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            
            <!-- Recent Events (40%) -->
            <div class="lg:col-span-2 bg-card rounded-2xl shadow-sm border border-border overflow-hidden flex flex-col">
            <div class="bg-surface-dark border-b border-border px-6 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-info">history</span>
                        Event Terbaru
                    </h2>
                    <p class="text-xs text-text-muted mt-1">5 event terakhir di sistem.</p>
                </div>
            </div>
            <div class="p-6">
                @if($recentEvents->isEmpty())
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-dark mb-4 border border-border">
                            <span class="material-symbols-outlined text-3xl text-text-muted">calendar_month</span>
                        </div>
                        <h3 class="text-lg font-bold text-text-primary">Belum Ada Event</h3>
                        <p class="text-text-muted mt-2">Mulai buat event pertama!</p>
                        <a href="{{ route('dosen.events.create') }}" class="inline-flex items-center px-6 py-3 mt-4 bg-primary hover:bg-accent-hover text-white font-bold rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined mr-2">add_circle</span>
                            Buat Event
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recentEvents as $event)
                            <div class="flex items-center justify-between p-4 rounded-lg bg-surface-light/50 border border-border hover:border-primary/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-lg
                                                {{ $event->type === 'boss' ? 'bg-red-500/10' : 'bg-primary/10' }}">
                                        <span class="material-symbols-outlined {{ $event->type === 'boss' ? 'text-red-400' : 'text-primary' }}">
                                            {{ $event->type === 'boss' ? 'skull' : 'menu_book' }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-text-primary">{{ $event->nama }}</h4>
                                        <p class="text-xs text-text-muted">
                                            Section {{ $event->section }} · {{ $event->type === 'boss' ? 'Boss' : 'Materi' }}
                                            · {{ $event->tanggal_mulai ? \Carbon\Carbon::parse($event->tanggal_mulai)->format('d M Y') : '-' }}
                                            @if($event->creator)
                                                · oleh {{ $event->creator->nama }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($event->status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-status-green-bg text-status-green-text border border-status-green-text/20">Active</span>
                                    @elseif($event->status === 'draft')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-status-gray-bg text-status-gray-text border border-status-gray-text/20">Draft</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-status-red-bg text-status-red-text border border-status-red-text/20">Selesai</span>
                                    @endif
                                    <a href="{{ route('dosen.events.edit', $event) }}" class="p-2 text-info hover:bg-info/10 rounded-lg transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div> <!-- End Recent Events Card -->

        <!-- Talent Scout Section (60%) -->
        <div id="talent-scout" class="lg:col-span-3 bg-card rounded-2xl shadow-sm border border-border overflow-hidden scroll-mt-6">
            <div class="bg-surface-dark border-b border-border px-6 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-warning">military_tech</span>
                        Talent Scout
                    </h2>
                    <p class="text-xs text-text-muted mt-1">Cari mahasiswa berprestasi berdasarkan performa Boss Battle.</p>
                </div>
                
                <form action="{{ route('dosen.dashboard') }}#talent-scout" method="GET" class="flex items-center gap-2" id="sortForm">
                    <label for="sort" class="text-sm font-medium text-text-muted whitespace-nowrap">Urutkan:</label>
                    <select name="sort" id="sort" onchange="document.getElementById('sortForm').submit()" 
                            class="bg-surface-light border border-border text-text-primary text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5">
                        <option value="avg_score" {{ $sort === 'avg_score' ? 'selected' : '' }}>Rata-rata Skor Tertinggi</option>
                        <option value="win_rate" {{ $sort === 'win_rate' ? 'selected' : '' }}>Win Rate Tertinggi</option>
                        <option value="pretest_score" {{ $sort === 'pretest_score' ? 'selected' : '' }}>Skor Pre-test Tertinggi</option>
                        <option value="total_xp" {{ $sort === 'total_xp' ? 'selected' : '' }}>Total XP Terbanyak</option>
                    </select>
                </form>
            </div>
            
            <div class="p-6">
                @if($topStudents->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-text-muted">Belum ada data mahasiswa.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($topStudents as $std)
                            <div class="bg-surface-light border border-border rounded-xl p-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 transition-all hover:border-primary hover:shadow-md">
                                
                                <div class="flex items-center gap-4 flex-1 min-w-0 w-full md:w-auto">
                                    <div class="w-8 h-8 rounded-full bg-surface-dark flex items-center justify-center border border-border shrink-0 shadow-inner">
                                        <span class="font-bold text-text-muted text-sm">{{ $std->rank }}</span>
                                    </div>
                                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center border border-primary/20 shrink-0">
                                        <span class="text-lg font-bold text-primary">{{ substr($std->nama, 0, 1) }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-semibold text-text-primary text-sm truncate" title="{{ $std->nama }}">{{ $std->nama }}</h4>
                                        <p class="text-xs text-text-muted">{{ $std->kelas ?? '-' }} · Level {{ $std->level ?? 1 }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between md:justify-end gap-3 sm:gap-6 w-full md:w-auto mt-2 md:mt-0 px-2 md:px-0 shrink-0">
                                    <div class="text-center md:text-right">
                                        <p class="text-[10px] text-text-muted uppercase tracking-wider mb-0.5">Rata-rata</p>
                                        <p class="font-black text-primary text-xl">{{ $std->avg_score_val }}</p>
                                    </div>
                                    
                                    <div class="w-px h-8 bg-border hidden sm:block"></div>
                                    
                                    <div class="text-center md:text-right">
                                        <p class="text-[10px] text-text-muted uppercase tracking-wider mb-0.5">Win Rate</p>
                                        <p class="font-black {{ $std->win_rate >= 70 ? 'text-success' : ($std->win_rate >= 50 ? 'text-warning' : 'text-danger') }} text-xl">
                                            {{ $std->win_rate }}%
                                        </p>
                                    </div>
                                    
                                    <div class="w-px h-8 bg-border hidden sm:block"></div>
                                    
                                    <div class="text-center md:text-right">
                                        <p class="text-[10px] text-text-muted uppercase tracking-wider mb-0.5">Pre-test</p>
                                        <p class="font-black text-info text-xl">{{ $std->pretest_score ?? '-' }}</p>
                                    </div>
                                    
                                    <div class="w-px h-8 bg-border hidden sm:block"></div>
                                    
                                    <div class="text-center md:text-right">
                                        <p class="text-[10px] text-text-muted uppercase tracking-wider mb-0.5">Total XP</p>
                                        <p class="font-black text-text-primary text-xl">{{ number_format($std->total_xp) }}</p>
                                    </div>
                                </div>
                                
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        {{ $topStudents->fragment('talent-scout')->links() }}
                    </div>
                @endif
            </div>
        </div> <!-- End Talent Scout Card -->

        </div> <!-- End Layout Grid -->
    </div>
</x-dosen-layout>
