<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>CodeBossArena Login</title>
    <link rel="icon" href="{{ asset('assets/logo.png') }}" type="image/png"/>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-display bg-background-dark text-text-primary antialiased">
    <div class="relative flex min-h-screen w-full flex-col group/design-root overflow-hidden">
        <!-- Background Ornaments -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <span class="absolute top-[10%] left-[5%] text-7xl text-primary/10 font-bold rotate-[-15deg]">&lt;div&gt;</span>
            <span class="absolute top-[25%] right-[45%] text-5xl text-accent/10 font-bold">;</span>
            <span class="absolute bottom-[20%] left-[15%] text-4xl text-success/10 font-bold">const</span>
            <span class="absolute bottom-[10%] left-[30%] text-6xl text-primary/10 font-bold">{ }</span>
            <span class="absolute top-[40%] left-[10%] text-4xl text-warning/10 font-bold">function</span>
            <span class="absolute bottom-[40%] right-[50%] text-5xl text-error/10 font-bold">=&gt;</span>
            <span class="absolute top-[15%] left-[40%] text-3xl text-info/10 font-bold">return</span>
            <span class="absolute bottom-[5%] left-[5%] text-4xl text-text-muted/10 font-bold">// TODO</span>
        </div>

        <div class="relative flex h-full min-h-screen grow flex-col z-10">
            <div class="flex h-full min-h-screen flex-1">
                <!-- Left Section (Hero) -->
                <div class="w-full lg:w-3/5 flex flex-col justify-center items-start p-8 sm:p-12 md:p-24 relative">

                    <main class="w-full max-w-2xl relative z-10">
                        <h1 class="text-5xl font-bold leading-tight tracking-tighter md:text-7xl lg:text-8xl text-text-primary">CodeBossArena</h1>
                        <h2 class="mt-4 text-lg font-normal leading-normal text-text-muted md:text-2xl">Platform Pembelajaran Pemrograman Berbasis Gamifikasi</h2>
                    </main>
                </div>

                <!-- Right Section (Login Form) -->
                <div class="w-full lg:w-2/5 flex items-center justify-center p-8 bg-card/50 backdrop-blur-sm border-l border-border">
                    <div class="w-full max-w-sm">
                        <div class="flex flex-col items-center justify-center mb-8 gap-3">
                            <img src="{{ asset('assets/logo.png') }}" alt="CodeBossArena Logo" class="h-16 w-16 object-contain">
                            <h2 class="text-2xl font-bold tracking-wider text-text-primary">CodeBossArena</h2>
                        </div>
                        <h3 class="text-3xl font-bold text-center text-text-primary mb-8">Masuk</h3>
                        
                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        
                        <form class="space-y-6" method="POST" action="{{ route('login') }}">
                            @csrf
                            <!-- Email -->
                            <div>
                                <label class="text-sm font-medium text-text-primary" for="email">Email</label>
                                <div class="mt-2">
                                    <input class="block w-full rounded-lg border-border bg-background-dark px-3 py-2 text-text-primary placeholder-text-muted focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary sm:text-sm transition-colors" 
                                           id="email" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required autofocus autocomplete="username"
                                           placeholder="kamu@email.com"/>
                                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                                </div>
                            </div>

                            <!-- Password -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-text-primary" for="password">Password</label>
                                    @if (Route::has('password.request'))
                                        <a class="text-sm text-primary hover:text-accent-hover hover:underline" href="{{ route('password.request') }}">Lupa Password?</a>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    <input class="block w-full rounded-lg border-border bg-background-dark px-3 py-2 text-text-primary placeholder-text-muted focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary sm:text-sm transition-colors" 
                                           id="password" 
                                           type="password" 
                                           name="password" 
                                           required autocomplete="current-password"/>
                                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                                </div>
                            </div>
                            
                            <!-- Remember Me -->
                            <div class="block">
                                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                                    <input id="remember_me" type="checkbox" class="rounded border-border bg-background-dark text-primary shadow-sm focus:ring-primary" name="remember">
                                    <span class="ms-2 text-sm text-text-muted">{{ __('Remember me') }}</span>
                                </label>
                            </div>

                            <div class="pt-2">
                                <button class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-4 bg-primary text-white text-base font-bold leading-normal tracking-wide transition-all duration-200 ease-in-out hover:bg-accent-hover hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-dark" type="submit">
                                    <span class="truncate">Masuk</span>
                                </button>
                            </div>
                        </form>
                        
                        <p class="mt-8 text-center text-sm text-text-muted">
                            Belum punya akun?
                            @if (Route::has('register'))
                                <a class="font-medium text-primary hover:text-accent-hover hover:underline" href="{{ route('register') }}">Daftar di sini</a>
                            @else
                                <span class="font-medium text-text-muted">Registrasi ditutup</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
