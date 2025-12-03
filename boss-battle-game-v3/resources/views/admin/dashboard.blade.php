<x-admin-layout>
    <!-- PageHeading -->
    <header class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h1 class="text-4xl font-black tracking-tight">Dashboard</h1>
    </header>

    <div class="bg-card rounded-lg shadow-sm p-6 border border-border">
        <h2 class="text-lg font-bold mb-4">Welcome back, {{ auth()->user()->nama }}!</h2>
        <p class="text-text-muted">
            You are logged in as an Administrator. Use the sidebar to manage events, users, and content.
        </p>
    </div>
</x-admin-layout>
