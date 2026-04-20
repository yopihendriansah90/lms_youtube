<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'LMS Member Area' }}</title>
        <meta name="theme-color" content="#06080d">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|sora:600,700,800" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="hero-orb left-[-5rem] top-[-6rem] h-48 w-48 bg-brand-500/20"></div>
            <div class="hero-orb right-[-3rem] top-[10rem] h-44 w-44 bg-cyan-400/10"></div>
            <div class="hero-orb bottom-[-6rem] left-1/2 h-56 w-56 -translate-x-1/2 bg-brand-500/10"></div>
        </div>

        <main class="app-shell relative">
            <div class="mb-5 flex items-center justify-between lg:mb-8">
                <a href="{{ route('member.home') }}" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-[18px] bg-[linear-gradient(135deg,#3f73f4,#35d6a6)] text-lg font-black text-white shadow-[0_10px_28px_rgba(63,115,244,0.3)]">
                        A
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold tracking-[0.28em] text-brand-200 uppercase">Alfaruq WFA</p>
                        <p class="font-['Sora'] text-[13px] font-semibold tracking-[-0.03em] text-white/72">Learning Portal</p>
                    </div>
                </a>

                @auth
                    <div class="desktop-nav">
                        <a href="{{ route('member.home') }}" class="desktop-nav-link {{ request()->routeIs('member.home') ? 'is-active' : '' }}">Beranda</a>
                        <a href="{{ route('member.materials') }}" class="desktop-nav-link {{ request()->routeIs('member.materials*') ? 'is-active' : '' }}">Materi</a>
                        <a href="{{ route('member.zoom') }}" class="desktop-nav-link {{ request()->routeIs('member.zoom') ? 'is-active' : '' }}">Rekaman</a>
                        <a href="{{ route('member.rooms') }}" class="desktop-nav-link {{ request()->routeIs('member.rooms') ? 'is-active' : '' }}">Room Zoom</a>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="hidden lg:block">
                        @csrf
                        <button type="submit" class="secondary-btn px-4 py-2.5 text-xs">Keluar</button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="lg:hidden">
                        @csrf
                        <button type="submit" class="secondary-btn px-4 py-2.5 text-xs">Keluar</button>
                    </form>
                @endauth
            </div>

            @if (session('status'))
                <div class="mb-5 rounded-2xl border border-mint-400/25 bg-mint-400/10 px-4 py-3 text-sm text-mint-400">
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </main>

        @auth
            <nav class="fixed inset-x-0 bottom-0 z-30 mx-auto w-full max-w-md px-4 pb-4 sm:max-w-3xl sm:px-6 lg:hidden">
                <div class="glass-panel mx-auto flex items-center gap-1 p-2">
                    <a href="{{ route('member.home') }}" class="mobile-nav-link {{ request()->routeIs('member.home') ? 'is-active' : '' }}">
                        <span>Beranda</span>
                    </a>
                    <a href="{{ route('member.materials') }}" class="mobile-nav-link {{ request()->routeIs('member.materials*') ? 'is-active' : '' }}">
                        <span>Materi</span>
                    </a>
                    <a href="{{ route('member.zoom') }}" class="mobile-nav-link {{ request()->routeIs('member.zoom') ? 'is-active' : '' }}">
                        <span>Zoom</span>
                    </a>
                    <a href="{{ route('member.rooms') }}" class="mobile-nav-link {{ request()->routeIs('member.rooms') ? 'is-active' : '' }}">
                        <span>Room</span>
                    </a>
                </div>
            </nav>
        @endauth
    </body>
</html>
