<x-admin-layout>
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.badges.index') }}" class="p-2 hover:bg-card rounded-full transition-colors border border-transparent hover:border-border">
                <span class="material-symbols-outlined text-text-muted">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-black text-text-primary">{{ isset($badge) ? 'Edit Badge' : 'Create Badge' }}</h1>
                <p class="text-text-muted mt-1">Configure achievement details and unlock criteria.</p>
            </div>
        </div>

        <form action="{{ isset($badge) ? route('admin.badges.update', $badge) : route('admin.badges.store') }}" method="POST" class="bg-card rounded-2xl shadow-sm border border-border p-8 space-y-6">
            @csrf
            @if(isset($badge))
                @method('PUT')
            @endif

            <div class="grid grid-cols-2 gap-6">
                <!-- Emoji Picker (Text Input for now) -->
                <div>
                    <label class="block text-sm font-bold text-text-light-secondary mb-2">Icon (Emoji)</label>
                    <input type="text" name="emoji" value="{{ old('emoji', $badge->emoji ?? '🏆') }}" required 
                           class="w-full text-4xl text-center rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary"
                           placeholder="🏆">
                    @error('emoji') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-text-light-secondary mb-2">Badge Name</label>
                    <input type="text" name="name" value="{{ old('name', $badge->name ?? '') }}" required 
                           class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary"
                           placeholder="e.g. Boss Slayer">
                    @error('name') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Slug -->
            <div>
                <label class="block text-sm font-bold text-text-light-secondary mb-2">Slug (Identifier)</label>
                <input type="text" name="slug" value="{{ old('slug', $badge->slug ?? '') }}" required {{ isset($badge) && $badge->is_system ? 'readonly' : '' }}
                       class="w-full rounded-xl border-border bg-surface-dark font-mono text-sm focus:border-primary focus:ring-primary {{ isset($badge) && $badge->is_system ? 'bg-surface-light cursor-not-allowed text-text-muted' : 'text-text-primary' }}"
                       placeholder="e.g. boss-slayer-novice">
                @if(isset($badge) && $badge->is_system)
                    <p class="text-xs text-text-muted mt-1">System badges cannot change their slug identifier.</p>
                @endif
                @error('slug') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-bold text-text-light-secondary mb-2">Description</label>
                <textarea name="description" rows="3" required class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary"
                          placeholder="How to unlock this badge...">{{ old('description', $badge->description ?? '') }}</textarea>
                @error('description') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <!-- Requirements JSON -->
            <div>
                <label class="block text-sm font-bold text-text-light-secondary mb-2">
                    Requirements (JSON Configuration)
                    <span class="text-xs font-normal text-text-muted ml-2">Leaving empty uses legacy/system logic.</span>
                </label>
                <div class="space-y-2">
                    <textarea name="requirements" rows="6" 
                              class="w-full font-mono text-sm rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary"
                              placeholder='{"type": "solo_victory_count", "count": 10}'>{{ old('requirements', isset($badge) && $badge->requirements ? json_encode($badge->requirements, JSON_PRETTY_PRINT) : '') }}</textarea>
                    
                    <div class="text-xs text-text-muted bg-surface-light dark:bg-black/20 p-3 rounded-lg border border-border">
                        <p class="font-bold mb-1">Common Examples:</p>
                        <code class="block text-primary mb-1">{"type": "solo_victory_count", "count": 10}</code>
                        <code class="block text-primary mb-1">{"type": "complete_difficulties", "levels": ["Easy", "Hard"]}</code>
                        <code class="block text-primary mb-1">{"type": "solo_victory_count", "count": 6, "unique_raid": true}</code>
                        <code class="block text-primary">{"type": "event_participation_count", "count": 5}</code>
                    </div>
                </div>
                @error('requirements') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="pt-4 border-t border-border flex justify-end gap-4">
                <a href="{{ route('admin.badges.index') }}" class="px-6 py-3 rounded-xl font-bold hover:bg-surface-light text-text-muted transition-colors border border-transparent hover:border-border">Cancel</a>
                <button type="submit" class="px-6 py-3 bg-primary hover:bg-accent-hover text-white font-bold rounded-xl shadow-lg shadow-primary/20 transition-all transform hover:scale-105">
                    {{ isset($badge) ? 'Update Badge' : 'Create Badge' }}
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
