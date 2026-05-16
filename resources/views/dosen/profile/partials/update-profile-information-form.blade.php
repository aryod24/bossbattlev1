<form method="post" action="{{ route('dosen.profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    <!-- Name Field -->
    <div>
        <label for="nama" class="block text-sm font-bold text-text-light-secondary mb-2">Full Name</label>
        <input 
            id="nama" 
            name="nama" 
            type="text" 
            value="{{ old('nama', $user->nama) }}" 
            required 
            autofocus 
            autocomplete="nama"
            class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary placeholder-text-muted/50"
            placeholder="Enter your full name"
        >
        @error('nama')
            <p class="text-error text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Email Field -->
    <div>
        <label for="email" class="block text-sm font-bold text-text-light-secondary mb-2">Email Address</label>
        <input 
            id="email" 
            name="email" 
            type="email" 
            value="{{ old('email', $user->email) }}" 
            required 
            autocomplete="username"
            class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary placeholder-text-muted/50"
            placeholder="your.email@example.com"
        >
        @error('email')
            <p class="text-error text-xs mt-1">{{ $message }}</p>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-3 p-3 bg-warning/10 border border-warning/20 rounded-lg">
                <p class="text-sm text-text-primary">
                    Your email address is unverified.
                </p>
                <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="inline">
                    @csrf
                    <button type="submit" class="underline text-sm text-info hover:text-info/80 mt-1">
                        Click here to re-send the verification email.
                    </button>
                </form>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-success">
                        A new verification link has been sent to your email address.
                    </p>
                @endif
            </div>
        @endif
    </div>

    <!-- Role Display (Read-only) -->
    <div>
        <label class="block text-sm font-bold text-text-light-secondary mb-2">Account Role</label>
        <div class="px-4 py-2.5 rounded-xl border border-border bg-surface-light/50 text-text-primary font-medium inline-flex items-center gap-2">
            <span class="material-symbols-outlined text-success text-[20px]">school</span>
            Dosen
        </div>
        <p class="text-xs text-text-muted mt-1.5">Your account role cannot be changed from this page.</p>
    </div>

    <!-- Submit Button -->
    <div class="flex items-center justify-between gap-4 pt-4 border-t border-border">
        <div>
            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-medium text-success flex items-center gap-2"
                >
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    Profile updated successfully!
                </p>
            @endif
        </div>
        <button 
            type="submit" 
            class="px-6 py-3 bg-primary hover:bg-accent-hover text-white font-bold rounded-xl shadow-lg shadow-primary/20 transition-all transform hover:scale-105"
        >
            Save Changes
        </button>
    </div>
</form>
