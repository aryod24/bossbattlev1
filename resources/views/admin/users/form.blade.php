<x-admin-layout>
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.users.index') }}" class="p-2 hover:bg-card rounded-full transition-colors border border-transparent hover:border-border">
                <span class="material-symbols-outlined text-text-muted">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-black text-text-primary">{{ isset($user) ? 'Edit User' : 'Create User' }}</h1>
                <p class="text-text-muted mt-1">Manage account details and permissions.</p>
            </div>
        </div>

        <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST" class="bg-card rounded-2xl shadow-sm border border-border p-8 space-y-6">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <!-- Account Type -->
            <div>
                <label class="block text-sm font-bold text-text-light-secondary mb-2">Account Role</label>
                <select name="role" class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary">
                    <option value="student" {{ old('role', $user->role ?? '') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="dosen" {{ old('role', $user->role ?? '') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
                @error('role') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <!-- Name -->
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-text-light-secondary mb-2">Full Name</label>
                    <input type="text" name="nama" value="{{ old('nama', $user->nama ?? '') }}" required 
                           class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary placeholder-text-muted/50"
                           placeholder="John Doe">
                    @error('nama') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-text-light-secondary mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required 
                           class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary placeholder-text-muted/50"
                           placeholder="john@example.com">
                    @error('email') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- NIM (Student ID) -->
                <div>
                    <label class="block text-sm font-bold text-text-light-secondary mb-2">NIM (Optional)</label>
                    <input type="text" name="nim" value="{{ old('nim', $user->nim ?? '') }}" 
                           class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary placeholder-text-muted/50"
                           placeholder="12345678">
                    @error('nim') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Class -->
                <div>
                    <label class="block text-sm font-bold text-text-light-secondary mb-2">Class (Optional)</label>
                    <input type="text" name="kelas" value="{{ old('kelas', $user->kelas ?? '') }}" 
                           class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary placeholder-text-muted/50"
                           placeholder="TI-3A">
                    @error('kelas') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            @if(isset($user))
            <div class="border-t border-border pt-6 grid grid-cols-2 gap-6">
                <!-- Level -->
                <div>
                     <label class="block text-sm font-bold text-text-light-secondary mb-2">Game Level</label>
                     <input type="number" name="level" value="{{ old('level', $user->level ?? 1) }}" min="1" required
                            class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary">
                </div>
                <!-- XP -->
                <div>
                     <label class="block text-sm font-bold text-text-light-secondary mb-2">Total XP</label>
                     <input type="number" name="total_xp" value="{{ old('total_xp', $user->total_xp ?? 0) }}" min="0" required
                            class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary">
                </div>
            </div>
            @endif

            <div class="border-t border-border pt-6">
                <h3 class="text-lg font-bold text-text-primary mb-4">Password Security</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-text-light-secondary mb-2">
                            {{ isset($user) ? 'New Password (Optional)' : 'Password' }}
                        </label>
                        <input type="password" name="password" {{ isset($user) ? '' : 'required' }}
                               class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary"
                               placeholder="********">
                        @error('password') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-text-light-secondary mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" {{ isset($user) ? '' : 'required' }}
                               class="w-full rounded-xl border-border bg-surface-dark text-text-primary focus:border-primary focus:ring-primary"
                               placeholder="********">
                    </div>
                </div>
                @if(isset($user))
                    <p class="text-xs text-text-muted mt-2">Leave blank to keep the current password.</p>
                @endif
            </div>

            <div class="pt-4 border-t border-border flex justify-end gap-4">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-xl font-bold hover:bg-surface-light text-text-muted transition-colors border border-transparent hover:border-border">Cancel</a>
                <button type="submit" class="px-6 py-3 bg-primary hover:bg-accent-hover text-white font-bold rounded-xl shadow-lg shadow-primary/20 transition-all transform hover:scale-105">
                    {{ isset($user) ? 'Update User' : 'Create User' }}
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
