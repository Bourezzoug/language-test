<!DOCTYPE html>
<html lang="en" class="h-full bg-brand-bg transition-colors duration-200">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Candidate Registration | LinguaBridge</title>
    
    <!-- SEO Best Practices -->
    <meta name="description" content="LinguaBridge Placement Test Registration.">
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
<body class="flex flex-col items-center justify-center min-h-screen bg-brand-bg text-brand-text-dark antialiased transition-colors duration-200 p-6 relative">

    <!-- Theme Switcher Button -->
    <div class="absolute top-6 right-6">
        <button id="theme-toggle" type="button" class="p-2.5 rounded-xl text-brand-text-muted hover:text-brand-gold hover:bg-brand-card border border-brand-border/40 shadow-xs transition-all cursor-pointer" aria-label="Toggle theme">
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
    <div class="flex flex-col items-center justify-center w-full max-w-md text-center space-y-8 animate-fade-in">
        
        <!-- Logo Image (Always Client Logo) -->
        <img src="{{ asset('image/logo.png') }}" alt="LinguaBridge Logo" class="w-72 sm:w-80 h-auto object-contain max-h-60 transition-transform duration-300 hover:scale-102">

        <!-- Main Card Container -->
        <div class="w-full bg-brand-card rounded-3xl border border-brand-border/80 dark:border-brand-border/30 shadow-xl overflow-hidden text-left transition-all duration-300">
            
            @if($activeAttempt)
                <!-- Prompt when an active session exists on this machine -->
                <div>
                    <div class="bg-brand-navy px-6 py-6 text-center text-white relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-500/15 to-transparent opacity-40"></div>
                        <h2 class="text-xl font-bold relative z-10 text-white">Active Session Detected</h2>
                        <p class="mt-1 text-amber-400 text-xs font-semibold uppercase tracking-wider relative z-10">Verification</p>
                    </div>

                    <div class="p-6 space-y-6 text-center">
                        <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl text-left">
                            <p class="text-xs text-brand-text-muted leading-relaxed font-medium">
                                The browser currently retains an active placement attempt in progress for:
                            </p>
                            <p class="mt-2 text-base font-extrabold text-brand-text-dark">
                                {{ $activeAttempt->full_name }}
                            </p>
                        </div>

                        <div class="space-y-4">
                            @php
                                $currentSection = \App\Models\TestSection::find($activeAttempt->current_section_id);
                            @endphp
                            @if($currentSection)
                                <a href="{{ route('student.test.section', [$activeAttempt, $currentSection]) }}" 
                                   class="w-full inline-flex items-center justify-center gap-2 bg-brand-navy hover:bg-brand-navy-dark text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition-all duration-200 cursor-pointer">
                                    Resume Candidate's Exam
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            @endif

                            <a href="{{ route('student.test.start', [$test, 'new_session' => 1]) }}" 
                               class="w-full inline-flex items-center justify-center gap-2 bg-transparent text-brand-text-dark hover:bg-slate-100 dark:hover:bg-slate-800 border border-brand-border font-bold py-3.5 px-4 rounded-xl transition-all duration-200 cursor-pointer">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Start a Brand New Exam
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Standard Intake Form when no session is active -->
                <div>
                    <!-- Header -->
                    <div class="bg-brand-navy px-6 py-6 text-center text-white relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-brand-gold/15 to-transparent opacity-40"></div>
                        <h2 class="text-xl font-bold relative z-10 text-white">Candidate Registration</h2>
                        <p class="mt-0.5 text-slate-400 text-xs relative z-10">LinguaBridge Placement Evaluation</p>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('student.test.initialize', $test) }}" method="POST" class="p-6 space-y-5">
                        @csrf

                        <!-- Full Name -->
                        <div>
                            <label for="full_name" class="block text-sm font-semibold text-brand-text-dark">Full Name <span class="text-rose-500">*</span></label>
                            <div class="mt-1">
                                <input type="text" name="full_name" id="full_name" required value="{{ old('full_name') }}"
                                    class="block w-full rounded-lg bg-brand-card border border-brand-border px-3 py-2.5 text-brand-text-dark shadow-sm focus:border-brand-gold focus:outline-none focus:ring-1 focus:ring-brand-gold sm:text-sm placeholder-slate-400 dark:placeholder-slate-500 transition-colors"
                                    placeholder="e.g. Mohamed Amine">
                            </div>
                            @error('full_name')
                                <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-brand-text-dark">Email Address <span class="text-brand-text-muted font-normal">(Optional)</span></label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="block w-full rounded-lg bg-brand-card border border-brand-border px-3 py-2.5 text-brand-text-dark shadow-sm focus:border-brand-gold focus:outline-none focus:ring-1 focus:ring-brand-gold sm:text-sm placeholder-slate-400 dark:placeholder-slate-500 transition-colors"
                                    placeholder="e.g. amine@example.com">
                            </div>
                            @error('email')
                                <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-brand-text-dark">Phone Number <span class="text-brand-text-muted font-normal">(Optional)</span></label>
                            <div class="mt-1">
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                    class="block w-full rounded-lg bg-brand-card border border-brand-border px-3 py-2.5 text-brand-text-dark shadow-sm focus:border-brand-gold focus:outline-none focus:ring-1 focus:ring-brand-gold sm:text-sm placeholder-slate-400 dark:placeholder-slate-500 transition-colors"
                                    placeholder="e.g. +212 600-000000">
                            </div>
                            @error('phone')
                                <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <div class="pt-4 border-t border-brand-border">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-brand-gold hover:bg-brand-gold-dark text-brand-navy-dark font-extrabold py-3.5 px-4 rounded-xl shadow-sm transition-all active:scale-98 focus:outline-none focus:ring-2 focus:ring-brand-gold cursor-pointer">
                                Enter & Begin Evaluation
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            @endif

        </div>

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
