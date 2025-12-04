<x-admin-layout>
    <div class="mb-6">
        <h1 class="text-4xl font-black text-text-primary">Session Monitor</h1>
        <p class="text-text-muted mt-2">Monitor and manage active game sessions.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Session Solo -->
        <div class="bg-surface-light dark:bg-surface-dark rounded-lg border border-border-light dark:border-border-dark p-6">
            <h3 class="text-lg font-bold text-text-primary mb-2">Solo Sessions</h3>
            <p class="text-3xl font-black text-primary mb-4">{{ $stats['session_solo'] }}</p>
            <p class="text-sm text-text-muted mb-4">Total records in session_solo table</p>
            
            <form action="{{ route('admin.sessions.clear') }}" method="POST" onsubmit="return confirm('Are you sure? This will delete ALL solo session records.');">
                @csrf
                <input type="hidden" name="table" value="session_solo">
                <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/50 font-bold py-2 px-4 rounded transition-colors">
                    Clear Solo Sessions
                </button>
            </form>
        </div>

        <!-- Session Answers -->
        <div class="bg-surface-light dark:bg-surface-dark rounded-lg border border-border-light dark:border-border-dark p-6">
            <h3 class="text-lg font-bold text-text-primary mb-2">Answer Records</h3>
            <p class="text-3xl font-black text-primary mb-4">{{ $stats['session_answer'] }}</p>
            <p class="text-sm text-text-muted mb-4">Total records in session_answer table</p>
            
            <form action="{{ route('admin.sessions.clear') }}" method="POST" onsubmit="return confirm('Are you sure? This will delete ALL answer records.');">
                @csrf
                <input type="hidden" name="table" value="session_answer">
                <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/50 font-bold py-2 px-4 rounded transition-colors">
                    Clear Answers
                </button>
            </form>
        </div>

        <!-- Event Participants -->
        <div class="bg-surface-light dark:bg-surface-dark rounded-lg border border-border-light dark:border-border-dark p-6">
            <h3 class="text-lg font-bold text-text-primary mb-2">Event Participants</h3>
            <p class="text-3xl font-black text-primary mb-4">{{ $stats['event_participants'] }}</p>
            <p class="text-sm text-text-muted mb-4">Total records in event_participants table</p>
            
            <form action="{{ route('admin.sessions.clear') }}" method="POST" onsubmit="return confirm('Are you sure? This will delete ALL event participant records.');">
                @csrf
                <input type="hidden" name="table" value="event_participants">
                <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/50 font-bold py-2 px-4 rounded transition-colors">
                    Clear Participants
                </button>
            </form>
        </div>
    </div>

    <!-- Global Actions -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-lg border border-border-light dark:border-border-dark p-8 text-center">
        <h2 class="text-2xl font-bold text-text-primary mb-4">Danger Zone</h2>
        <p class="text-text-muted mb-6">This action will wipe all session data from all tables. Use with caution.</p>
        
        <form action="{{ route('admin.sessions.clear') }}" method="POST" onsubmit="return confirm('WARNING: This will delete EVERYTHING (Sessions, Answers, Participants). Are you absolutely sure?');">
            @csrf
            <input type="hidden" name="table" value="all">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                ⚠️ CLEAR ALL SESSION DATA
            </button>
        </form>
    </div>
</x-admin-layout>
