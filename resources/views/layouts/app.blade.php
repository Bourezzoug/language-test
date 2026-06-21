<!DOCTYPE html>
<html lang="en" class="h-full bg-brand-bg transition-colors duration-200">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LinguaBridge Evaluation Portal')</title>
    
    <!-- SEO Best Practices -->
    <meta name="description" content="LinguaBridge Placement Test Portal - Where language meets opportunity.">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }
    </style>

    <!-- Dark Mode Initializer -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="flex flex-col h-full bg-brand-bg text-brand-text-muted antialiased transition-colors duration-200">

    <!-- Navbar -->
    <header class="bg-brand-navy text-white shadow-md border-b border-brand-gold/30">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                
                <!-- Client Logo -->
                <div class="flex items-center">
                    @if(request()->routeIs('student.test.section'))
                        <div class="h-9 flex items-center">
                            <img src="{{ asset('image/logo.png') }}" alt="LinguaBridge Logo" class="h-9 w-auto object-contain">
                        </div>
                    @else
                        <a href="{{ route('student.index') }}">
                            <img src="{{ asset('image/logo.png') }}" alt="LinguaBridge Logo" class="h-9 w-auto object-contain transition-transform duration-200 hover:scale-102">
                        </a>
                    @endif
                </div>

                <!-- Navigation Links & Theme Toggle -->
                <nav class="flex items-center gap-4">
                    <!-- Light / Dark Mode Toggle Switcher Button -->
                    <button id="theme-toggle" type="button" class="p-2 rounded-xl text-slate-300 hover:text-brand-gold hover:bg-slate-800/60 border border-transparent hover:border-brand-gold/20 transition-all cursor-pointer shadow-3xs" aria-label="Toggle theme">
                        <!-- Sun Icon (visible in dark mode) -->
                        <svg id="theme-toggle-sun" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21M4.93 4.93l1.59 1.59m10.96 10.96l1.59 1.59M3 12h2.25m13.5 0H21M4.93 19.07l1.59-1.59m10.96-10.96l1.59-1.59M12 8.25a3.75 3.75 0 1 0 0 7.5 3.75 3.75 0 0 0 0-7.5Z" />
                        </svg>
                        <!-- Moon Icon (visible in light mode) -->
                        <svg id="theme-toggle-moon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                    </button>

                    @if(request()->routeIs('admin.*'))
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-slate-300 hover:text-white px-3 py-2 rounded-md hover:bg-slate-850 transition-colors">
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.tests.index') }}" class="text-sm font-semibold text-slate-300 hover:text-white px-3 py-2 rounded-md hover:bg-slate-850 transition-colors">
                                    Tests
                                </a>
                                <a href="{{ route('admin.attempts.index') }}" class="text-sm font-semibold text-slate-300 hover:text-white px-3 py-2 rounded-md hover:bg-slate-850 transition-colors">
                                    Candidates
                                </a>
                                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm font-bold bg-brand-gold hover:bg-brand-gold-dark text-brand-navy-dark px-3.5 py-1.5 rounded-lg transition-all shadow-sm cursor-pointer">
                                        Logout
                                    </button>
                                </form>
                            @endif
                        @endauth
                    @endif
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            
            <!-- Global Flash Messages -->
            @if(session('success') && !request()->routeIs('student.test.result'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-2 shadow-sm">
                    <svg class="h-5 w-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error') || $errors->has('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-2 shadow-sm">
                    <svg class="h-5 w-5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') ?: $errors->first('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>



    <!-- Theme Toggle Logic Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggle = document.getElementById('theme-toggle');
            const sunIcon = document.getElementById('theme-toggle-sun');
            const moonIcon = document.getElementById('theme-toggle-moon');

            function updateToggleIcons() {
                if (document.documentElement.classList.contains('dark')) {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                } else {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                }
            }

            // Initialize icons
            updateToggleIcons();

            themeToggle.addEventListener('click', () => {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                updateToggleIcons();
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
