<x-app-layout>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Welcome Card -->
        <div class="lg:col-span-2 bg-white/50 dark:bg-black/20 p-6 rounded-lg shadow-sm border border-black/5 dark:border-white/5">
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-2">Welcome back, {{ auth()->user()->nama }}!</h1>
            <p class="text-slate-600 dark:text-slate-400">Ready to conquer the code? Check out the latest events and boost your rank.</p>
            
            <div class="mt-6 flex gap-4">
                <a href="{{ route('solo.index') }}" class="inline-flex items-center justify-center h-10 px-6 rounded-full bg-primary text-black font-bold hover:brightness-110 transition-all">
                    Browse Events
                </a>
                <a href="#" class="inline-flex items-center justify-center h-10 px-6 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 font-medium hover:bg-slate-300 dark:hover:bg-slate-600 transition-all">
                    View Leaderboard
                </a>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="bg-white/50 dark:bg-black/20 p-6 rounded-lg shadow-sm border border-black/5 dark:border-white/5">
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4">Your Stats</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600 dark:text-slate-400">Level</span>
                    <span class="font-bold text-primary">{{ auth()->user()->level ?? 1 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-600 dark:text-slate-400">Total XP</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ auth()->user()->total_xp ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-600 dark:text-slate-400">Rank</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">#--</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity / Active Events -->
        <div class="lg:col-span-3">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4">Active Events</h2>
            <!-- This could be dynamic later -->
            <div class="bg-white/50 dark:bg-black/20 p-8 rounded-lg text-center border border-dashed border-slate-300 dark:border-slate-700">
                <p class="text-slate-500 dark:text-slate-400">No active events at the moment. Check back later!</p>
            </div>
        </div>
    </div>
</x-app-layout>
