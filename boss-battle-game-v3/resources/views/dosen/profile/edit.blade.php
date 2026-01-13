<x-dosen-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Page Header -->
        <div class="bg-card rounded-2xl shadow-sm border border-border p-6">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-success/10 border-2 border-success">
                    <span class="material-symbols-outlined text-3xl text-success">school</span>
                </div>
                <div>
                    <h1 class="text-4xl font-black text-text-primary tracking-tight">Profil Dosen</h1>
                    <p class="text-text-muted mt-1 font-medium">Manage your account settings and preferences</p>
                </div>
            </div>
        </div>

        <!-- Profile Information Card -->
        <div class="bg-card rounded-2xl shadow-sm border border-border overflow-hidden">
            <div class="bg-surface-dark border-b border-border px-6 py-4">
                <h2 class="text-lg font-bold text-text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-info">person</span>
                    Profile Information
                </h2>
                <p class="text-sm text-text-muted mt-1">Update your account's profile information and email address.</p>
            </div>
            <div class="p-6">
                @include('dosen.profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Password Security Card -->
        <div class="bg-card rounded-2xl shadow-sm border border-border overflow-hidden">
            <div class="bg-surface-dark border-b border-border px-6 py-4">
                <h2 class="text-lg font-bold text-text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-warning">lock</span>
                    Password Security
                </h2>
                <p class="text-sm text-text-muted mt-1">Ensure your account is using a long, random password to stay secure.</p>
            </div>
            <div class="p-6">
                @include('profile.partials.update-password-form', ['action' => route('dosen.password.update')])
            </div>
        </div>
    </div>
</x-dosen-layout>
