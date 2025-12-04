<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-display bg-background-dark text-text-primary">
    <div class="relative flex min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <!-- TopNavBar Component -->
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-border px-6 sm:px-10 lg:px-20 py-3 bg-card/80 backdrop-blur-sm sticky top-0 z-10">
            <div class="flex items-center gap-4 text-text-primary">
                <div class="size-8">
                    <img src="{{ asset('assets/logo.png') }}" alt="CodeBossArena Logo" class="w-full h-full object-contain">
                </div>
                <h2 class="text-text-primary text-lg font-bold leading-tight tracking-[-0.015em]">CodeBossArena</h2>
            </div>
            <div class="flex flex-1 justify-end gap-8">
                <div class="hidden md:flex items-center gap-4">
                    <a class="flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg pl-4 pr-4 transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary text-white shadow-sm font-bold' : 'hover:bg-primary/20 text-text-muted hover:text-text-primary font-medium' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg pl-4 pr-4 transition-colors {{ request()->routeIs('leaderboard*') ? 'bg-primary text-white shadow-sm font-bold' : 'hover:bg-primary/20 text-text-muted hover:text-text-primary font-medium' }}" href="#">Leaderboard</a>
                    <a class="flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg pl-4 pr-4 transition-colors {{ request()->routeIs('solo.*') ? 'bg-primary text-white shadow-sm font-bold' : 'hover:bg-primary/20 text-text-muted hover:text-text-primary font-medium' }}" href="{{ route('solo.index') }}">Events</a>
                    <a class="flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg pl-4 pr-4 transition-colors {{ request()->routeIs('profile.*') ? 'bg-primary text-white shadow-sm font-bold' : 'hover:bg-primary/20 text-text-muted hover:text-text-primary font-medium' }}" href="{{ route('profile.edit') }}">Profile</a>
                </div>
                <div class="flex items-center gap-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center justify-center rounded-lg h-10 bg-primary text-white gap-2 text-sm font-bold px-5 shadow-sm hover:bg-accent-hover transition-transform duration-200 hover:-translate-y-0.5">
                            <span class="truncate">Log Out</span>
                        </button>
                    </form>
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="User avatar" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}&background=random");'></div>
                </div>
            </div>
        </header>

        <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
