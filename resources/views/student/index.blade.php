<!DOCTYPE html>
<html lang="en" class="h-full transition-colors duration-200">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Welcome to LinguaBridge</title>
    
    <!-- SEO Best Practices -->
    <meta name="description" content="LinguaBridge Placement Test Portal.">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
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
<body class="flex flex-col items-center justify-center min-h-screen bg-[#050816] text-slate-300 antialiased transition-colors duration-200 p-6 relative">

    <!-- Theme Switcher Button -->
    <div class="absolute top-6 right-6">
        <button id="theme-toggle" type="button" class="p-2.5 rounded-xl text-slate-400 hover:text-brand-gold hover:bg-slate-800/40 border border-slate-800 transition-all cursor-pointer" aria-label="Toggle theme">
            <!-- Sun Icon (visible in dark mode) -->
            <svg id="theme-toggle-sun" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21M4.93 4.93l1.59 1.59m10.96 10.96l1.59 1.59M3 12h2.25m13.5 0H21M4.93 19.07l1.59-1.59m10.96-10.96l1.59-1.59M12 8.25a3.75 3.75 0 1 0 0 7.5 3.75 3.75 0 0 0 0-7.5Z" />
            </svg>
            <!-- Moon Icon (visible in light mode) -->
            <svg id="theme-toggle-moon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
            </svg>
        </button>
    </div>

    <!-- Centered Content Wrapper -->
    <div class="flex flex-col items-center justify-center w-full max-w-sm text-center space-y-12 animate-fade-in">
        
        <!-- Logo Image (Client Logo) -->
        <img src="{{ asset('image/logo.png') }}" alt="LinguaBridge Logo" class="w-72 sm:w-80 h-auto object-contain max-h-60 transition-transform duration-300 hover:scale-102">

        <!-- CTA Actions -->
        <div class="w-full">
            @if($childrenTest || $juniorTest || $seniorTest)
                <div class="flex flex-col space-y-4">
                    @if($childrenTest)
                        <a href="{{ route('student.test.start', $childrenTest) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 bg-brand-gold-light border border-brand-gold/30 text-brand-text-dark font-extrabold py-4 px-6 rounded-xl shadow-lg transition-all hover:scale-102 hover:opacity-95 duration-200 cursor-pointer text-base">
                            Start Children Test
                            <svg class="h-5 w-5 text-brand-gold" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @endif

                    @if($juniorTest)
                        <a href="{{ route('student.test.start', $juniorTest) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 bg-brand-gold hover:bg-brand-gold-dark text-brand-navy-dark font-extrabold py-4 px-6 rounded-xl shadow-lg transition-all hover:scale-102 duration-200 cursor-pointer text-base">
                            Start Junior Test
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @endif
                    
                    @if($seniorTest)
                        <a href="{{ route('student.test.start', $seniorTest) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 bg-brand-navy border border-brand-gold/40 text-white font-extrabold py-4 px-6 rounded-xl shadow-lg transition-all hover:scale-102 hover:border-brand-gold duration-200 cursor-pointer text-base">
                            Start Senior Test
                            <svg class="h-5 w-5 text-brand-gold" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @endif
                </div>
            @else
                <div class="p-4 rounded-xl border border-slate-800 bg-[#0a0f24]">
                    <p class="text-sm font-semibold text-slate-200">No Active Evaluation Available</p>
                </div>
            @endif

    </div>

    <!-- Theme Toggle Switcher Script -->
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
</body>
</html>
