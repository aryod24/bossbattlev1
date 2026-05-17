<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <style>
        /* === Base font setup === */
        body {
            font-family: 'Hanken Grotesk', sans-serif;
            background-color: #0A0A0B;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.05'/%3E%3C/svg%3E");
            color: #e5e2e3;
        }
        .font-headline {
            font-family: 'Sora', sans-serif;
        }
        .font-body {
            font-family: 'Hanken Grotesk', sans-serif;
        }
        .font-mono-label {
            font-family: 'JetBrains Mono', monospace;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        /* === Cyber-noir shared components === */
        .glass-card {
            background: rgba(25, 25, 28, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 242, 255, 0.2);
            transition: all 0.3s ease;
        }
        .glass-card:hover {
            border-color: rgba(0, 242, 255, 0.6);
            box-shadow: 0 0 15px rgba(0, 242, 255, 0.15);
        }
        .glow-card {
            position: relative;
            overflow: hidden;
        }
        .boss-card {
            background: linear-gradient(135deg, rgba(206, 93, 255, 0.8), rgba(0, 242, 255, 0.2));
            border: 1px solid rgba(206, 93, 255, 0.5);
            box-shadow: 0 0 30px rgba(206, 93, 255, 0.2);
        }
        .neutral-card {
            background: linear-gradient(135deg, rgba(53, 52, 54, 0.8), rgba(32, 31, 32, 0.4));
            border: 1px solid rgba(132, 148, 149, 0.4);
            box-shadow: 0 0 30px rgba(132, 148, 149, 0.1);
        }
        .btn-cyber-primary {
            background: linear-gradient(135deg, #00f2ff, #ce5dff);
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }
        .btn-cyber-primary:hover {
            box-shadow: 0 0 20px rgba(0, 242, 255, 0.6);
        }
        .btn-cyber-secondary {
            background: transparent;
            border: 1px solid #00f2ff;
            color: #e5e2e3;
            transition: all 0.3s ease;
        }
        .btn-cyber-secondary:hover {
            background: rgba(0, 242, 255, 0.1);
        }
        .text-cyan-glow { color: #00f2ff; }
        .text-magenta-glow { color: #ce5dff; }
        .text-soft { color: #b9cacb; }
        .text-faint { color: #849495; }
        .border-cyan-soft { border-color: rgba(0, 242, 255, 0.3); }
        .bg-cyan-soft { background-color: rgba(0, 242, 255, 0.15); }
        .progress-bar-fill {
            background: linear-gradient(90deg, #00f2ff, #ce5dff);
        }
        .progress-glow-tip {
            box-shadow: 0 0 10px #ffffff;
        }
        .divider-soft {
            border-top: 1px solid rgba(58, 73, 75, 0.5);
        }

        /* === Navbar === */
        .nav-link {
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            letter-spacing: 0.01em;
            color: #b9cacb;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            color: #00f2ff;
            background-color: rgba(0, 242, 255, 0.08);
        }
        .nav-link-active {
            color: #00f2ff;
            background-color: rgba(0, 242, 255, 0.12);
            border: 1px solid rgba(0, 242, 255, 0.4);
            box-shadow: 0 0 12px rgba(0, 242, 255, 0.2);
        }
        .navbar-shell {
            background: rgba(14, 14, 15, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 242, 255, 0.15);
        }
        .brand-title {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            letter-spacing: -0.01em;
            background: linear-gradient(135deg, #00f2ff, #ce5dff);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar { width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #1e1e1e; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #404040; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="text-on-surface min-h-screen antialiased">
    <div class="relative flex min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        {{-- TopNavBar --}}
        <header x-data="{ mobileMenuOpen: false }" class="navbar-shell flex items-center justify-between whitespace-nowrap px-6 sm:px-10 lg:px-20 py-3 sticky top-0 z-50">
            {{-- Logo & Title --}}
            <div class="flex items-center gap-3">
                <div class="size-8">
                    <img src="{{ asset('assets/logo.png') }}" alt="CodeBossArena Logo" class="w-full h-full object-contain">
                </div>
                <h2 class="brand-title text-lg leading-tight">CodeBossArena</h2>
            </div>

            {{-- Desktop Navigation --}}
            <nav class="hidden lg:flex items-center gap-2">
                <a class="nav-link flex h-10 items-center justify-center rounded-lg px-4 text-sm {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link flex h-10 items-center justify-center rounded-lg px-4 text-sm {{ request()->routeIs('leaderboard*') ? 'nav-link-active' : '' }}" href="{{ route('leaderboard.index') }}">Leaderboard</a>
                <a class="nav-link flex h-10 items-center justify-center rounded-lg px-4 text-sm {{ request()->routeIs('solo.*') ? 'nav-link-active' : '' }}" href="{{ route('solo.index') }}">Events</a>
                <a class="nav-link flex h-10 items-center justify-center rounded-lg px-4 text-sm {{ request()->routeIs('profile.*') ? 'nav-link-active' : '' }}" href="{{ route('profile.edit') }}">Profile</a>
            </nav>

            {{-- Desktop: Logout + Avatar --}}
            <div class="hidden lg:flex items-center gap-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-cyber-primary flex items-center justify-center rounded-lg h-10 gap-2 text-sm font-bold px-5 font-headline">
                        <span class="truncate">Log Out</span>
                    </button>
                </form>
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 ring-1 ring-cyan-soft" data-alt="User avatar" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}&background=random");'></div>
            </div>

            {{-- Mobile/Tablet: Hamburger + Avatar --}}
            <div class="lg:hidden flex items-center gap-4">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="flex items-center justify-center size-10 rounded-lg hover:bg-cyan-soft transition-colors">
                    <span class="material-symbols-outlined" style="color: #e5e2e3;">menu</span>
                </button>
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 ring-1 ring-cyan-soft" data-alt="User avatar" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}&background=random");'></div>
            </div>

            {{-- Mobile Dropdown Menu --}}
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.outside="mobileMenuOpen = false"
                 class="lg:hidden absolute top-full right-0 left-0 navbar-shell shadow-lg"
                 style="display: none;">
                <nav class="flex flex-col px-6 py-4 gap-2">
                    <a class="nav-link flex h-12 items-center rounded-lg px-4 {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="nav-link flex h-12 items-center rounded-lg px-4 {{ request()->routeIs('leaderboard*') ? 'nav-link-active' : '' }}" href="{{ route('leaderboard.index') }}">Leaderboard</a>
                    <a class="nav-link flex h-12 items-center rounded-lg px-4 {{ request()->routeIs('solo.*') ? 'nav-link-active' : '' }}" href="{{ route('solo.index') }}">Events</a>
                    <a class="nav-link flex h-12 items-center rounded-lg px-4 {{ request()->routeIs('profile.*') ? 'nav-link-active' : '' }}" href="{{ route('profile.edit') }}">Profile</a>
                    <div class="divider-soft my-2"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-cyber-primary w-full flex h-12 items-center justify-center rounded-lg font-bold px-4 font-headline">
                            Log Out
                        </button>
                    </form>
                </nav>
            </div>
        </header>

        <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
