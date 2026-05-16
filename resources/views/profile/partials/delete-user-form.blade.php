@php
    $formAction = $action ?? '';
@endphp

<form method="post" action="{{ $formAction }}" class="space-y-6">
    @csrf
    @method('delete')

    <div class="p-4 rounded-xl border border-error/20 bg-error/10 text-text-primary">
        <p class="font-semibold">This action is permanent.</p>
        <p class="text-sm text-text-muted mt-1">
            Your account and all related data will be removed. This cannot be undone.
        </p>
    </div>

    <div>
        <label for="password" class="block text-sm font-bold text-text-light-secondary mb-2">Confirm Password</label>
        <input
            id="password"
            name="password"
            type="password"
            autocomplete="current-password"
            class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary placeholder-text-muted/50"
            placeholder="Enter your password to confirm"
        >
        @error('password', 'userDeletion')
            <p class="text-error text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end gap-4 pt-4 border-t border-border">
        <button
            type="submit"
            class="px-6 py-3 bg-error hover:bg-error/80 text-white font-bold rounded-xl shadow-lg shadow-error/20 transition-all transform hover:scale-105"
        >
            Delete Account
        </button>
    </div>
</form>
