<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>CodeBossArena — Login</title>
    <link rel="icon" href="{{ asset('assets/logo.png') }}" type="image/png"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

    <style>
        body {
            font-family: 'Hanken Grotesk', sans-serif;
            background-color: #0A0A0B;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.05'/%3E%3C/svg%3E");
            color: #e5e2e3;
        }
        .font-headline { font-family: 'Sora', sans-serif; }
        .font-body { font-family: 'Hanken Grotesk', sans-serif; }
        .font-mono-label { font-family: 'JetBrains Mono', monospace; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .text-cyan-glow { color: #00f2ff; }
        .text-magenta-glow { color: #ce5dff; }
        .text-soft { color: #b9cacb; }
        .text-faint { color: #849495; }

        .glass-card {
            background: rgba(25, 25, 28, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 242, 255, 0.2);
            transition: all 0.3s ease;
        }
        .glass-card:hover { border-color: rgba(0, 242, 255, 0.4); }

        .hero-title {
            font-family: 'Sora', sans-serif;
            font-weight: 500;
            font-size: clamp(3rem, 5vw, 6rem);
            line-height: 1.05;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #00f2ff 0%, #ce5dff 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 40px rgba(0, 242, 255, 0.15);
            display: inline-block;
            max-width: 100%;
        }

        .btn-cyber-primary {
            background: linear-gradient(135deg, #00f2ff, #ce5dff);
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }
        .btn-cyber-primary:hover {
            box-shadow: 0 0 24px rgba(0, 242, 255, 0.55);
            transform: translateY(-1px);
        }

        .cyber-input {
            background-color: #ffffff;
            border: 1px solid rgba(58, 73, 75, 0.6);
            color: #0a0a0b;
            transition: all 0.2s ease;
            font-family: 'Hanken Grotesk', sans-serif;
        }
        .cyber-input::placeholder { color: #6b7280; }
        .cyber-input:focus {
            outline: none;
            border-color: #00f2ff;
            box-shadow: 0 0 0 3px rgba(0, 242, 255, 0.15);
        }
        /* Override Chrome autofill yellow/blue background and force black text */
        .cyber-input:-webkit-autofill,
        .cyber-input:-webkit-autofill:hover,
        .cyber-input:-webkit-autofill:focus {
            -webkit-text-fill-color: #0a0a0b;
            -webkit-box-shadow: 0 0 0 1000px #ffffff inset;
            box-shadow: 0 0 0 1000px #ffffff inset;
            caret-color: #0a0a0b;
        }

        /* Floating code ornaments */
        .code-ornament {
            position: absolute;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            user-select: none;
            pointer-events: none;
            will-change: transform, opacity;
        }

        /* Six different float patterns for organic movement */
        @keyframes ornament-float-a {
            0%, 100% { transform: translate(0, 0) rotate(-15deg); opacity: 0.55; }
            50%      { transform: translate(8px, -18px) rotate(-12deg); opacity: 1; }
        }
        @keyframes ornament-float-b {
            0%, 100% { transform: translate(0, 0); opacity: 0.6; }
            50%      { transform: translate(-12px, 14px); opacity: 1; }
        }
        @keyframes ornament-float-c {
            0%, 100% { transform: translate(0, 0); opacity: 0.5; }
            50%      { transform: translate(14px, -10px); opacity: 0.95; }
        }
        @keyframes ornament-float-d {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.55; }
            50%      { transform: translate(-10px, -16px) scale(1.05); opacity: 1; }
        }
        @keyframes ornament-float-e {
            0%, 100% { transform: translate(0, 0); opacity: 0.5; }
            50%      { transform: translate(18px, 12px); opacity: 0.9; }
        }
        @keyframes ornament-float-f {
            0%, 100% { transform: translate(0, 0); opacity: 0.6; }
            50%      { transform: translate(-16px, -8px); opacity: 1; }
        }

        .ornament-anim-1 { animation: ornament-float-a 9s ease-in-out infinite; }
        .ornament-anim-2 { animation: ornament-float-b 11s ease-in-out infinite 1.2s; }
        .ornament-anim-3 { animation: ornament-float-c 8s ease-in-out infinite 0.4s; }
        .ornament-anim-4 { animation: ornament-float-d 13s ease-in-out infinite 2s; }
        .ornament-anim-5 { animation: ornament-float-e 10s ease-in-out infinite 0.8s; }
        .ornament-anim-6 { animation: ornament-float-f 12s ease-in-out infinite 1.6s; }
        .ornament-anim-7 { animation: ornament-float-b 14s ease-in-out infinite 2.4s; }
        .ornament-anim-8 { animation: ornament-float-c 15s ease-in-out infinite 0.2s; }

        @media (prefers-reduced-motion: reduce) {
            .code-ornament { animation: none !important; }
        }

        /* Side panel cyber border accent */
        .auth-panel {
            background: rgba(14, 14, 15, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-left: 1px solid rgba(0, 242, 255, 0.2);
            position: relative;
        }
        .auth-panel::before {
            content: "";
            position: absolute;
            top: 0; bottom: 0;
            left: -1px;
            width: 2px;
            background: linear-gradient(180deg, transparent, #00f2ff, #ce5dff, transparent);
            opacity: 0.5;
        }
        @media (max-width: 768px) {
            .auth-panel { border-left: 0; border-top: 1px solid rgba(0, 242, 255, 0.2); }
            .auth-panel::before { display: none; }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="relative flex min-h-screen w-full flex-col overflow-hidden">
        {{-- Background Code Ornaments --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <span class="code-ornament ornament-anim-1 top-[10%] left-[5%] text-7xl" style="color: rgba(0, 242, 255, 0.08);">&lt;div&gt;</span>
            <span class="code-ornament ornament-anim-2 top-[25%] right-[45%] text-5xl" style="color: rgba(206, 93, 255, 0.08);">;</span>
            <span class="code-ornament ornament-anim-3 bottom-[20%] left-[15%] text-4xl" style="color: rgba(134, 239, 172, 0.08);">const</span>
            <span class="code-ornament ornament-anim-4 bottom-[10%] left-[30%] text-6xl" style="color: rgba(0, 242, 255, 0.08);">{ }</span>
            <span class="code-ornament ornament-anim-5 top-[40%] left-[10%] text-4xl" style="color: rgba(253, 230, 138, 0.08);">function</span>
            <span class="code-ornament ornament-anim-6 bottom-[40%] right-[50%] text-5xl" style="color: rgba(255, 180, 171, 0.08);">=&gt;</span>
            <span class="code-ornament ornament-anim-7 top-[15%] left-[40%] text-3xl" style="color: rgba(206, 93, 255, 0.08);">return</span>
            <span class="code-ornament ornament-anim-8 bottom-[5%] left-[5%] text-4xl" style="color: rgba(132, 148, 149, 0.10);">// TODO</span>
        </div>

        <div class="relative flex h-full min-h-screen grow flex-col z-10">
            <div class="flex min-h-screen flex-col md:flex-row">
                {{-- Left Section (Hero) --}}
                <div class="hidden md:flex w-full md:w-3/5 flex-col justify-center items-start p-8 sm:p-12 md:p-12 lg:p-16 xl:p-20 relative min-h-[50vh] md:min-h-screen">
                    <main class="w-full max-w-3xl lg:max-w-4xl relative z-10">
                        <span class="font-mono-label text-xl uppercase tracking-[0.3em] text-cyan-glow mb-4 inline-block">
                            // welcome to the arena
                        </span>
                        <h1 class="hero-title leading-[1.05] tracking-tight">
                            CodeBossArena
                        </h1>
                        <h2 class="font-body mt-5 lg:mt-6 text-base md:text-lg lg:text-xl text-soft leading-relaxed max-w-2xl">
                            Platform pembelajaran pemrograman berbasis gamifikasi. Tantang boss, kuasai materi, dan naikkan level kamu.
                        </h2>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <span class="font-mono-label inline-flex items-center gap-2 text-xs uppercase tracking-wider px-3 py-1.5 rounded-full"
                                  style="background-color: rgba(0,242,255,0.08); color: #00f2ff; border: 1px solid rgba(0,242,255,0.3);">
                                <span class="material-symbols-outlined" style="font-size: 14px;">swords</span>
                                Boss Battle
                            </span>
                            <span class="font-mono-label inline-flex items-center gap-2 text-xs uppercase tracking-wider px-3 py-1.5 rounded-full"
                                  style="background-color: rgba(206,93,255,0.08); color: #ebb2ff; border: 1px solid rgba(206,93,255,0.3);">
                                <span class="material-symbols-outlined" style="font-size: 14px;">military_tech</span>
                                Achievements
                            </span>
                            <span class="font-mono-label inline-flex items-center gap-2 text-xs uppercase tracking-wider px-3 py-1.5 rounded-full"
                                  style="background-color: rgba(253,230,138,0.08); color: #fde68a; border: 1px solid rgba(253,230,138,0.3);">
                                <span class="material-symbols-outlined" style="font-size: 14px;">leaderboard</span>
                                Leaderboard
                            </span>
                        </div>
                    </main>
                </div>

                {{-- Right Section (Login Form) --}}
                <div class="auth-panel w-full md:w-2/5 flex items-center justify-center p-6 lg:p-8 min-h-screen">
                    <div class="w-full max-w-sm">
                        <div class="flex flex-col items-center justify-center mb-8 gap-3">
                            <img src="{{ asset('assets/logo.png') }}" alt="CodeBossArena Logo" class="h-16 w-16 object-contain drop-shadow-[0_0_18px_rgba(0,242,255,0.4)]">
                        </div>
                        <h3 class="font-headline text-3xl font-extrabold text-center mb-2" style="color: #e5e2e3;">Masuk</h3>
                        <p class="font-body text-center text-sm text-soft mb-8">Akses arena dan lanjutkan progres kamu.</p>

                        {{-- Session Status --}}
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form class="space-y-6" method="POST" action="{{ route('login') }}">
                            @csrf
                            {{-- Email --}}
                            <div>
                                <label class="font-mono-label text-xs uppercase tracking-wider text-soft" for="email">Email</label>
                                <div class="mt-2">
                                    <input class="cyber-input block w-full rounded-lg px-3 py-2.5 text-sm"
                                           id="email" name="email"
                                           placeholder="kamu@email.com"
                                           required type="email"
                                           value="{{ old('email') }}"
                                           autofocus autocomplete="username"/>
                                    @error('email')
                                        <span class="font-body text-xs mt-1 block" style="color: #ffb4ab;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div>
                                <div class="flex items-center justify-between">
                                    <label class="font-mono-label text-xs uppercase tracking-wider text-soft" for="password">Password</label>
                                    @if (Route::has('password.request'))
                                        <a class="font-headline text-xs text-cyan-glow hover:underline opacity-50 cursor-not-allowed pointer-events-none" href="javascript:void(0)">Lupa Password?</a>
                                    @endif
                                </div>
                                <div class="mt-2" style="position: relative;">
                                    <input class="cyber-input block w-full rounded-lg px-3 py-2.5 text-sm"
                                           style="padding-right: 2.5rem;"
                                           id="password" name="password"
                                           required type="password"
                                           autocomplete="current-password"/>
                                    <button type="button" onclick="togglePassword()" style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #849495; padding: 0; display: flex; align-items: center;" aria-label="Toggle password visibility">
                                        <span id="eye-icon" class="material-symbols-outlined" style="font-size: 20px;">visibility_off</span>
                                    </button>
                                    @error('password')
                                        <span class="font-body text-xs mt-1 block" style="color: #ffb4ab;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="pt-2">
                                <button class="btn-cyber-primary font-headline flex w-full items-center justify-center rounded-lg h-11 px-4 text-base font-bold tracking-wide" type="submit">
                                    <span class="truncate">Masuk</span>
                                </button>
                            </div>
                        </form>

                        <p class="font-body mt-8 text-center text-sm text-soft">
                            Belum punya akun?
                            <span class="font-headline font-medium text-cyan-glow">Hubungi Admin</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility_off';
            }
        }
    </script>
</body>
</html>
