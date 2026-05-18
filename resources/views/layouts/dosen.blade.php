<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Dosen</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        *:not(.material-symbols-outlined) {
            font-family: 'Outfit', sans-serif !important;
        }
    </style>

    <!-- Tailwind & Alpine via Vite (production build, no JIT in browser) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background-dark font-display font-medium text-text-primary">
    <div class="flex min-h-screen w-full">
        <!-- SideNavBar - Dosen Menu -->
        <aside class="flex h-screen w-64 flex-col border-r border-border bg-card sticky top-0">
            <div class="flex h-full flex-col justify-between p-4">
                <div class="flex flex-col gap-4">
                    <a href="{{ route('dosen.profile.edit') }}" class="flex items-center gap-3 hover:bg-primary/10 p-2 -ml-2 rounded-lg transition-colors group">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 group-hover:ring-2 group-hover:ring-primary transition-all" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}&background=random");'></div>
                        <div class="flex flex-col">
                            <h1 class="font-medium group-hover:text-primary transition-colors">{{ auth()->user()->nama }}</h1>
                            <p class="text-sm text-text-muted">Dosen</p>
                        </div>
                    </a>
                    <nav class="flex flex-col gap-2">
                        <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('dosen.dashboard') ? 'bg-primary/30' : 'hover:bg-primary/20' }}" href="{{ route('dosen.dashboard') }}">
                            <span class="material-symbols-outlined">dashboard</span>Dashboard
                        </a>
                        <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('dosen.events.*') ? 'bg-primary/30' : 'hover:bg-primary/20' }}" href="{{ route('dosen.events.index') }}">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">calendar_month</span>Manajemen Event
                        </a>
                        <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('dosen.questions.*') ? 'bg-primary/30' : 'hover:bg-primary/20' }}" href="{{ route('dosen.questions.index') }}">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">quiz</span>Bank Soal
                        </a>
                    </nav>
                </div>
                <div class="flex flex-col gap-1">
                    <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-primary/20" href="{{ route('dosen.profile.edit') }}">
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
