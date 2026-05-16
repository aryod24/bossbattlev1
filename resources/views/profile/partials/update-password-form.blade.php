@php
    $formAction = $action ?? '';
@endphp

<form method="post" action="{{ $formAction }}" class="space-y-6">
    @csrf
    @method('put')

    <div>
        <label for="current_password" class="block text-sm font-bold text-text-light-secondary mb-2">Current Password</label>
        <input
            id="current_password"
            name="current_password"
            type="password"
            autocomplete="current-password"
            class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary placeholder-text-muted/50"
            placeholder="Enter your current password"
        >
        @error('current_password', 'updatePassword')
            <p class="text-error text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-bold text-text-light-secondary mb-2">New Password</label>
        <input
            id="password"
            name="password"
            type="password"
            autocomplete="new-password"
            class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary placeholder-text-muted/50"
            placeholder="Enter a new password"
        >
        @error('password', 'updatePassword')
            <p class="text-error text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-bold text-text-light-secondary mb-2">Confirm New Password</label>
        <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            autocomplete="new-password"
            class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary placeholder-text-muted/50"
            placeholder="Re-enter the new password"
        >
    </div>

    <div class="flex items-center justify-between gap-4 pt-4 border-t border-border">
        <div>
            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-medium text-success flex items-center gap-2"
                >
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    Password updated successfully!
                </p>
            @endif
        </div>
        <button
            type="submit"
            class="px-6 py-3 bg-primary hover:bg-accent-hover text-white font-bold rounded-xl shadow-lg shadow-primary/20 transition-all transform hover:scale-105"
        >
            Update Password
        </button>
    </div>
</form>
