<x-admin-layout>
    <div class="layout-content-container flex flex-col w-full gap-6 font-display" x-data="{ time: new Date() }" x-init="setInterval(() => time = new Date(), 1000)">
        <header>
            <div class="flex flex-wrap justify-between gap-3">
                <div class="flex min-w-72 flex-col gap-1">
                    <h1 class="text-text-primary text-4xl font-black leading-tight tracking-[-0.033em]">Admin Dashboard</h1>
                    <p class="text-text-muted text-base font-normal leading-normal">Welcome back, {{ auth()->user()->nama }}</p>
                </div>
            </div>
        </header>

        <!-- Stats Grid & Clock -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Clock Card (Prominent) -->
            <div class="flex flex-col justify-between rounded-2xl p-6 bg-gradient-to-br from-primary/20 to-card border border-primary/30 min-h-[160px] relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center gap-2 text-primary mb-2">
                        <span class="material-symbols-outlined text-2xl">schedule</span>
                        <span class="font-bold uppercase tracking-wider text-xs">System Time</span>
                    </div>
                    <div class="text-5xl font-black text-text-primary tracking-tighter tabular-nums" x-text="time.toLocaleTimeString('en-US', { hour12: false })">
                        {{ now()->format('H:i:s') }}
                    </div>
                    <div class="text-lg text-text-muted mt-1 font-medium" x-text="time.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })">
                        {{ now()->format('l, d F Y') }}
                    </div>
                </div>
                <!-- Decor -->
                <div class="absolute -right-4 -bottom-4 opacity-10 transform rotate-12 group-hover:rotate-0 transition-transform duration-700">
                    <span class="material-symbols-outlined text-9xl">calendar_month</span>
                </div>
            </div>

            <!-- Total Users -->
            <div class="flex flex-col justify-center gap-2 rounded-2xl p-6 bg-card border border-border min-h-[160px]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 text-info">
                        <span class="material-symbols-outlined text-3xl">group</span>
                        <p class="text-text-primary text-lg font-bold">Total Users</p>
                    </div>
                </div>
                <p class="text-text-primary tracking-tight text-5xl font-black leading-tight mt-2">{{ number_format($totalUsers) }}</p>
                <div class="text-sm text-text-muted mt-2 font-medium">Student & Admin Accounts</div>
            </div>

            <!-- Active Events -->
            <div class="flex flex-col justify-center gap-2 rounded-2xl p-6 bg-card border border-border min-h-[160px]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 text-warning">
                        <span class="material-symbols-outlined text-3xl">celebration</span>
                        <p class="text-text-primary text-lg font-bold">Active Events</p>
                    </div>
                </div>
                <p class="text-text-primary tracking-tight text-5xl font-black leading-tight mt-2">{{ number_format($activeEvents) }}</p>
                <div class="text-sm text-text-muted mt-2 font-medium">Ongoing Solo Raids</div>
            </div>
        </section>

        <!-- Secondary Stats -->
        <section class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Total Questions -->
            <div class="flex items-center justify-between rounded-2xl p-6 bg-card border border-border">
                <div class="flex flex-col">
                    <p class="text-text-muted text-sm font-bold uppercase tracking-wider mb-1">Total Questions</p>
                    <p class="text-text-primary text-4xl font-black">{{ number_format($totalQuestions) }}</p>
                </div>
                <div class="p-4 rounded-full bg-surface-dark border border-border text-status-green-text">
                    <span class="material-symbols-outlined text-3xl">help_center</span>
                </div>
            </div>

            <!-- Total Answers -->
            <div class="flex items-center justify-between rounded-2xl p-6 bg-card border border-border">
                <div class="flex flex-col">
                    <p class="text-text-muted text-sm font-bold uppercase tracking-wider mb-1">Total Interactions</p>
                    <p class="text-text-primary text-4xl font-black">{{ number_format($totalAnswers) }}</p>
                </div>
                <div class="p-4 rounded-full bg-surface-dark border border-border text-accent-hover">
                    <span class="material-symbols-outlined text-3xl">task_alt</span>
                </div>
            </div>
        </section>

        <!-- Bottom Section: Quick Actions & Activities -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Quick Actions -->
            <div class="lg:col-span-1 flex flex-col gap-4">
                <h3 class="text-xl font-bold text-text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">bolt</span> Quick Actions
                </h3>
                
                <a href="{{ route('admin.solo-raids.create') }}" class="group w-full flex items-center justify-between p-4 rounded-xl bg-card border border-border hover:border-primary/50 transition-all hover:bg-surface-light">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-primary/10 text-primary group-hover:bg-primary group-hover:text-black transition-colors">
                            <span class="material-symbols-outlined">add_circle</span>
                        </div>
                        <span class="font-bold text-text-primary">Create New Event</span>
                    </div>
                    <span class="material-symbols-outlined text-text-muted group-hover:text-primary group-hover:translate-x-1 transition-all">chevron_right</span>
                </a>
                
                <a href="{{ route('admin.questions.create') }}" class="group w-full flex items-center justify-between p-4 rounded-xl bg-card border border-border hover:border-info/50 transition-all hover:bg-surface-light">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-info/10 text-info group-hover:bg-info group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined">add_task</span>
                        </div>
                        <span class="font-bold text-text-primary">Add Question</span>
                    </div>
                    <span class="material-symbols-outlined text-text-muted group-hover:text-info group-hover:translate-x-1 transition-all">chevron_right</span>
                </a>
                
                <button class="group w-full flex items-center justify-between p-4 rounded-xl bg-card border border-border hover:border-status-green-text/50 transition-all hover:bg-surface-light">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-status-green-text/10 text-status-green-text group-hover:bg-status-green-text group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined">download</span>
                        </div>
                        <span class="font-bold text-text-primary">Export Data</span>
                    </div>
                    <span class="material-symbols-outlined text-text-muted group-hover:text-status-green-text group-hover:translate-x-1 transition-all">chevron_right</span>
                </button>
                
                <a href="{{ route('admin.profile.edit') }}" class="group w-full flex items-center justify-between p-4 rounded-xl bg-card border border-border hover:border-text-muted/50 transition-all hover:bg-surface-light">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-surface-dark text-text-muted group-hover:bg-text-muted group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined">settings</span>
                        </div>
                        <span class="font-bold text-text-primary">Settings</span>
                    </div>
                    <span class="material-symbols-outlined text-text-muted group-hover:text-text-primary group-hover:translate-x-1 transition-all">chevron_right</span>
                </a>
            </div>

            <!-- Recent Activities -->
            <div class="lg:col-span-2 flex flex-col gap-4 rounded-2xl border border-border p-6 bg-card">
                <h3 class="text-xl font-bold text-text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-text-muted">history</span> Recent Activities
                </h3>
                <div class="space-y-4">
                    @foreach($recentUsers as $user)
                        <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-surface-dark/50 transition-colors border border-transparent hover:border-border">
                            <div class="flex-shrink-0 size-12 rounded-full bg-accent/10 flex items-center justify-center text-accent">
                                <span class="material-symbols-outlined">person_add</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-text-primary">New User Registered</p>
                                <p class="text-sm text-text-muted mt-0.5">User <span class="text-text-primary font-mono bg-surface-dark px-1.5 rounded">{{ $user->email }}</span> joined the platform.</p>
                            </div>
                            <span class="text-xs font-bold text-text-muted px-3 py-1 bg-surface-dark rounded-full border border-border">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-auto pt-6 border-t border-border text-center">
                    <a class="text-sm font-bold text-text-muted hover:text-primary transition-colors inline-flex items-center gap-1" href="{{ route('admin.users.index') }}">
                        View All Activities <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                </div>
            </div>
        </section>
    </div>
</x-admin-layout>
