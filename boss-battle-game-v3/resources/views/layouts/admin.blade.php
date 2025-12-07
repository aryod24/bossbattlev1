<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <style type="text/tailwindcss">
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#007acc",
                        "background": "#1e1e1e",
                        "background-light": "#252526",
                        "background-dark": "#1e1e1e",
                        "card": "#252526",
                        "surface": "#252526",
                        "surface-light": "#2d2d2d",
                        "surface-dark": "#252526",
                        "text-primary": "#d4d4d4",
                        "text-light-primary": "#d4d4d4",
                        "text-dark-primary": "#d4d4d4",
                        "text-muted": "#858585",
                        "text-light-secondary": "#858585",
                        "text-dark-secondary": "#9d9d9d",
                        "border": "#333333",
                        "border-light": "#404040",
                        "border-dark": "#333333",
                        "status-green-bg": "rgb(56 161 105 / 0.1)",
                        "status-green-text": "#38a169",
                        "status-red-bg": "rgb(229 62 62 / 0.1)",
                        "status-red-text": "#e53e3e",
                        "status-gray-bg": "rgb(128 128 128 / 0.1)",
                        "status-gray-text": "#808080",
                        "accent": "#007acc",
                        "accent-hover": "#1a8ad4",
                        "success": "#4ec9b0",
                        "warning": "#dcdcaa",
                        "error": "#f44747",
                        "info": "#9cdcfe"
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-background-dark font-display text-text-primary">
    <div class="flex min-h-screen w-full">
        <!-- SideNavBar -->
        <aside class="flex h-screen w-64 flex-col border-r border-border bg-card sticky top-0">
            <div class="flex h-full flex-col justify-between p-4">
                <div class="flex flex-col gap-4">
                    <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 hover:bg-primary/10 p-2 -ml-2 rounded-lg transition-colors group">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 group-hover:ring-2 group-hover:ring-primary transition-all" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}&background=random");'></div>
                        <div class="flex flex-col">
                            <h1 class="font-medium group-hover:text-primary transition-colors">{{ auth()->user()->nama }}</h1>
                            <p class="text-sm text-text-muted">Admin</p>
                        </div>
                    </a>
                    <nav class="flex flex-col gap-2">
                        <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-primary/30' : 'hover:bg-primary/20' }}" href="{{ route('admin.dashboard') }}">
                            <span class="material-symbols-outlined">dashboard</span>Dashboard
                        </a>
                        <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.solo-raids.*') ? 'bg-primary/30' : 'hover:bg-primary/20' }}" href="{{ route('admin.solo-raids.index') }}">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">calendar_month</span>Manajemen Event
                        </a>
                        <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.questions.*') ? 'bg-primary/30' : 'hover:bg-primary/20' }}" href="{{ route('admin.questions.index') }}">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">quiz</span>Bank Soal
                        </a>
                        <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-primary/20" href="#">
                            <span class="material-symbols-outlined">group</span>Manajemen User
                        </a>
                        <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-primary/20" href="#">
                            <span class="material-symbols-outlined">book</span>Konten
                        </a>
                        <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-primary/20" href="#">
                            <span class="material-symbols-outlined">monitoring</span>Laporan
                        </a>
                        <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.sessions.*') ? 'bg-primary/30' : 'hover:bg-primary/20' }}" href="{{ route('admin.sessions.index') }}">
                            <span class="material-symbols-outlined">database</span>Session Monitor
                        </a>
                        <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.badges.*') ? 'bg-primary/30' : 'hover:bg-primary/20' }}" href="{{ route('admin.badges.index') }}">
                            <span class="material-symbols-outlined">emoji_events</span>Badges
                        </a>
                    </nav>
                </div>
                <div class="flex flex-col gap-1">
                    <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-primary/20" href="{{ route('profile.edit') }}">
                        <span class="material-symbols-outlined">settings</span>Pengaturan
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-primary/20 text-left">
                            <span class="material-symbols-outlined">logout</span>Keluar
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex flex-1 flex-col p-6 lg:p-8">
            <div class="w-full max-w-7xl mx-auto">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
