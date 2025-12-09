<x-admin-layout>
    <div class="max-w-4xl mx-auto space-y-8">
        <div>
            <h1 class="text-4xl font-black text-text-primary">Research Reports</h1>
            <p class="text-text-muted mt-2">Export comprehensive event data for NASA-TLX stress level analysis.</p>
        </div>

        <div class="bg-card rounded-2xl shadow-sm border border-border p-8">
            <h2 class="text-xl font-bold text-text-primary mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined">download</span>
                Export Event Data
            </h2>

            <form action="{{ route('admin.reports.export') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-text-light-secondary mb-2">Select Event Source</label>
                    <select name="report_source" required class="w-full rounded-xl border-border bg-surface-dark text-text-primary p-4 focus:ring-primary focus:border-primary">
                        <option value="" disabled selected>Choose a data source...</option>
                        
                        <optgroup label="Solo Raids (Single Player)">
                            @foreach($soloRaids as $raid)
                                <option value="solo:{{ $raid->id }}">
                                    {{ $raid->nama }} (Created: {{ $raid->created_at->format('d M Y') }})
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="Multiplayer Events (Leaderboard)">
                            @foreach($events as $event)
                                <option value="event:{{ $event->event_id }}">
                                    {{ $event->title }} ({{ $event->created_at->format('d M Y') }})
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div class="bg-surface-light dark:bg-black/20 p-4 rounded-xl border border-border">
                    <p class="text-sm font-bold text-text-primary mb-2">Data Included in CSV:</p>
                    <ul class="text-sm text-text-muted space-y-1 list-disc list-inside">
                        <li>Participant Identity (Name, Email)</li>
                        <li>Performance Metrics (Score, Rank, Accuracy)</li>
                        <li>Temporal Data (Duration, Start/End Times)</li>
                        <li>Battle Outcome (Boss HP, Defeat Status)</li>
                        <li><strong>NASA-TLX Placeholders</strong> (Ready for manual data entry)</li>
                    </ul>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-8 py-4 bg-primary hover:bg-accent-hover text-black font-bold rounded-xl shadow-lg shadow-primary/20 transition-all transform hover:scale-105 flex items-center gap-2">
                        <span class="material-symbols-outlined">file_download</span>
                        Download CSV Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
