<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <script>
        (function () {
            const theme = localStorage.getItem('theme') ?? 'dark';
            document.documentElement.classList.remove('dark', 'light');
            document.documentElement.classList.add(theme);
        })();
    </script>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="flex min-h-screen flex-col">
        <header class="sticky top-0 z-50 border-b border-border backdrop-blur-md" style="background: var(--app-header);">
            <div class="flex w-full items-center justify-between gap-4 px-5 py-3 lg:px-8">
                <a href="{{ route('projects.index') }}" class="flex items-center gap-2.5 transition hover:opacity-90">
                    <span class="brand-mark">PD</span>
                    <div>
                        <p class="text-sm font-semibold tracking-tight text-foreground">{{ config('app.name', 'Project Documentor') }}</p>
                        <p class="hidden text-[11px] text-muted sm:block">Documentation hub</p>
                    </div>
                </a>

                <div class="flex items-center gap-2">
                    <button type="button" data-theme-toggle class="theme-toggle" aria-label="Toggle color theme">
                        <svg data-theme-icon-dark xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                        <svg data-theme-icon-light xmlns="http://www.w3.org/2000/svg" class="hidden size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                        <span data-theme-label class="hidden sm:inline">Light mode</span>
                    </button>

                    <a
                        href="{{ route('projects.index') }}"
                        @class([
                            'nav-link',
                            'nav-link-active' => request()->routeIs('projects.*'),
                        ])
                    >
                        Projects
                    </a>
                </div>
            </div>
        </header>

        @hasSection('hero')
            @yield('hero')
        @endif

        <main class="w-full flex-1 py-6 lg:py-8">
            <div class="page-container">
                @yield('content')
            </div>
        </main>

        <footer class="mt-auto border-t border-border bg-surface">
            <div class="page-container flex items-center justify-between py-4 text-xs text-muted">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Project Documentor') }}</p>
                <p class="hidden sm:block">Built for clear, maintainable project docs.</p>
            </div>
        </footer>
    </div>
</body>
</html>
