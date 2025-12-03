<x-admin-layout>
    <!-- PageHeading -->
    <header class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h1 class="text-4xl font-black tracking-tight text-text-primary">Profile Management</h1>
    </header>

    <div class="space-y-6">
        <div class="p-4 sm:p-8 bg-card shadow sm:rounded-lg border border-border">
            <div class="max-w-xl">
                @include('admin.profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-card shadow sm:rounded-lg border border-border">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-card shadow sm:rounded-lg border border-border">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-admin-layout>
