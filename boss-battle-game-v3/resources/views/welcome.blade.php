<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>CodeBossArena Login &amp; Landing</title>
<link rel="icon" href="{{ asset('build/assets/logo.png') }}" type="image/png"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "vsc-bg": "#1e1e1e",
            "vsc-blue": "#569cd6",
            "vsc-green": "#6a9955",
            "vsc-orange": "#ce9178",
            "vsc-gray": "#808080",
            "vsc-light-gray": "#cccccc",
            "vsc-card-bg": "rgba(40, 40, 40, 0.8)",
            "vsc-input-bg": "#3c3c3c",
            "vsc-border": "#3c3c3c",
            "vsc-focus": "#007acc",
          },
          fontFamily: {
            "display": ["Space Grotesk"]
          },
          boxShadow: {
            'button-glow': '0 4px 14px 0 rgba(106, 153, 85, 0.39)',
          }
        },
      },
    }
  </script>
<style>
  body {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }
</style>
</head>
<body class="font-display bg-vsc-bg text-vsc-light-gray">
<div class="relative flex min-h-screen w-full flex-col group/design-root overflow-hidden">
<span class="absolute top-[10%] left-[5%] text-7xl text-vsc-blue/20 font-bold opacity-30 select-none z-0 rotate-[-15deg]">&lt;div&gt;</span>
<span class="absolute top-[25%] right-[45%] text-5xl text-vsc-orange/20 font-bold opacity-30 select-none z-0">;</span>
<span class="absolute bottom-[20%] left-[15%] text-4xl text-vsc-green/20 font-bold opacity-30 select-none z-0">const</span>
<span class="absolute bottom-[10%] left-[30%] text-6xl text-vsc-blue/20 font-bold opacity-30 select-none z-0">{ }</span>
<div class="relative flex h-full min-h-screen grow flex-col z-10">
<div class="flex h-full min-h-screen flex-1">
<div class="w-full lg:w-3/5 flex flex-col justify-center items-start p-8 sm:p-12 md:p-24 relative">
<header class="absolute top-0 left-0 p-6 md:p-8">
<div class="flex items-center gap-3">
<img src="{{ asset('build/assets/logo.png') }}" alt="CodeBossArena Logo" class="h-12 w-12 object-contain">
<h2 class="text-xl font-bold tracking-wider">CodeBossArena</h2>
</div>
</header>
<main class="w-full max-w-2xl">
<h1 class="text-5xl font-bold leading-tight tracking-tighter md:text-7xl lg:text-8xl">CodeBossArena</h1>
<h2 class="mt-4 text-lg font-normal leading-normal text-vsc-light-gray/80 md:text-2xl">Platform Pembelajaran Pemrograman Berbasis Gamifikasi</h2>
</main>
</div>
<div class="w-full lg:w-2/5 flex items-center justify-center p-8 bg-vsc-card-bg/20 backdrop-blur-sm">
<div class="w-full max-w-sm">
@auth
    <div class="text-center">
        <h3 class="text-3xl font-bold text-white mb-6">Welcome Back!</h3>
        <p class="text-vsc-light-gray mb-8">You are already logged in.</p>
        <a href="{{ route('dashboard') }}" class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-4 bg-primary text-white text-base font-bold leading-normal tracking-wide transition-all duration-200 ease-in-out hover:bg-accent-hover hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-dark">
            Go to Dashboard
        </a>
    </div>
@else
    <h3 class="text-3xl font-bold text-center text-white mb-6">Masuk</h3>
    <form class="space-y-6" method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label class="text-sm font-medium text-vsc-light-gray" for="email">Email</label>
            <div class="mt-1">
                <input class="block w-full rounded-md border-vsc-border bg-vsc-input-bg px-3 py-2 text-vsc-light-gray placeholder-vsc-gray focus:border-vsc-focus focus:outline-none focus:ring-2 focus:ring-vsc-focus sm:text-sm" id="email" name="email" placeholder="kamu@email.com" required="" type="email" value="{{ old('email') }}"/>
                @error('email')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div>
            <div class="flex items-center justify-between">
                <label class="text-sm font-medium text-vsc-light-gray" for="password">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-vsc-blue hover:underline" href="{{ route('password.request') }}">Lupa Password?</a>
                @endif
            </div>
            <div class="mt-1">
                <input class="block w-full rounded-md border-vsc-border bg-vsc-input-bg px-3 py-2 text-vsc-light-gray placeholder-vsc-gray focus:border-vsc-focus focus:outline-none focus:ring-2 focus:ring-vsc-focus sm:text-sm" id="password" name="password" required="" type="password"/>
                @error('password')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div>
            <button class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-4 bg-primary text-white text-base font-bold leading-normal tracking-wide transition-all duration-200 ease-in-out hover:bg-accent-hover hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-dark" type="submit">
                <span class="truncate">Masuk</span>
            </button>
        </div>
    </form>
    <div class="relative mt-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-vsc-border"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="bg-vsc-bg px-2 text-vsc-gray">Atau masuk dengan</span>
        </div>
    </div>
    <div class="mt-6 grid grid-cols-2 gap-3">
        <a class="inline-flex w-full items-center justify-center rounded-md border border-vsc-border bg-vsc-input-bg py-2 px-4 text-sm font-medium text-vsc-light-gray shadow-sm hover:bg-vsc-border" href="#">
            <svg class="h-5 w-5" fill="currentColor" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.0003 4.878C12.0003 4.878 12 4.878 12 4.878c-3.8182 0-6.909 3.0908-6.909 6.9092 0 3.0363 1.9855 5.629 4.7182 6.5455.3455.0637.4718-.1491.4718-.331 0-.1645-.0055-.7336-.0091-1.3063-1.9282.4182-2.3364-.84-2.3364-.84-.3145-.799-.7682-.9982-.7682-1.009-.6264-.4272.0473-.4181.0473-.4181.6918.0482 1.0545.7082 1.0545.7082.6145 1.0537 1.6145.7482 2.009.5718.0628-.4445.24-.7482.4382-.9209-1.5336-.1746-3.1463-.7673-3.1463-3.4146 0-.7537.2682-1.37.709-1.8528-.07-.1745-.3073-.8763.0664-1.8254 0 0 .58-.1854 1.9.7045.55-.1527 1.14-.229 1.73-.2318.59-.0027 1.18.079 1.73.2318 1.32-0.89 1.9-.7045 1.9-.7045.3737.949.1364 1.6509.0664 1.8254.4418.4827.709 1.099.709 1.8527 0 2.6545-1.6145 3.2382-3.1528 3.409.2455.211.4655.631.4655 1.2728 0 .92-.0073 1.8254-.0073 1.9618 0 .1818.1255.3972.4746.331C16.9275 17.4165 18.909 14.8239 18.909 11.7872c0-3.8183-3.0908-6.9092-6.909-6.9092Z"></path>
            </svg>
        </a>
        <a class="inline-flex w-full items-center justify-center rounded-md border border-vsc-border bg-vsc-input-bg py-2 px-4 text-sm font-medium text-vsc-light-gray shadow-sm hover:bg-vsc-border" href="#">
            <svg class="h-5 w-5" fill="currentColor" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"></path>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"></path>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"></path>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"></path>
            </svg>
        </a>
    </div>
    <p class="mt-8 text-center text-sm text-vsc-gray">
        Belum punya akun?
        @if (Route::has('register'))
            <a class="font-medium text-vsc-blue hover:underline" href="{{ route('register') }}">Daftar di sini</a>
        @else
            <span class="font-medium text-vsc-blue">Registrasi ditutup</span>
        @endif
    </p>
@endauth
</div>
</div>
</div>
</div>
</div>
</body></html>
