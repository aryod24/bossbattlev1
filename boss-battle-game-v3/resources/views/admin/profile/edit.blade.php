<x-admin-layout>
    <!-- PageHeading -->
    <header class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h1 class="text-4xl font-black tracking-tight text-text-primary">Profile Management</h1>
    </header>

    <div class="space-y-6">
        <div class="p-4 sm:p-8 bg-card shadow sm:rounded-lg border border-border">
            <div class="max-w-xl">
                <header class="mb-6">
                    <h2 class="text-lg font-medium text-text-primary">
                        {{ __('Profile Information') }}
                    </h2>
                    <p class="mt-1 text-sm text-text-muted">
                        {{ __("Update your account's profile information and email address.") }}
                    </p>
                </header>
                @include('admin.profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-card shadow sm:rounded-lg border border-border">
            <div class="max-w-xl">
                <header class="mb-6">
                    <h2 class="text-lg font-medium text-text-primary">
                        {{ __('Update Password') }}
                    </h2>
                    <p class="mt-1 text-sm text-text-muted">
                        {{ __('Ensure your account is using a long, random password to stay secure.') }}
                    </p>
                </header>
                @include('profile.partials.update-password-form', ['action' => route('admin.password.update')])
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-card shadow sm:rounded-lg border border-border">
            <div class="max-w-xl">
                <header class="mb-6">
                    <h2 class="text-lg font-medium text-text-primary">
                        {{ __('Delete Account') }}
                    </h2>
                    <p class="mt-1 text-sm text-text-muted">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
                    </p>
                </header>
                @include('profile.partials.delete-user-form', ['action' => route('admin.profile.destroy')])
            </div>
        </div>
    </div>
</x-admin-layout>
