@extends('layouts.app')

@section('title', $section->title)

@section('content')
@php
    // Find all questions in the section in the order they are shown.
    // In reading section, questions are nested in passages. In other sections, they are on the section directly.
    $allQuestionsInOrder = [];
    if ($section->type === 'reading') {
        foreach($section->passages as $passage) {
            foreach($passage->questions as $q) {
                $allQuestionsInOrder[] = $q;
            }
        }
    } else {
        foreach($section->questions as $q) {
            $allQuestionsInOrder[] = $q;
        }
    }

    $firstUnansweredIndex = 0;
    foreach($allQuestionsInOrder as $idx => $q) {
        if (!isset($savedAnswers[$q->id])) {
            $firstUnansweredIndex = $idx;
            break;
        }
    }
    // fallback to last if all are answered (should only happen upon review submission)
    if (count($allQuestionsInOrder) > 0 && $firstUnansweredIndex >= count($allQuestionsInOrder)) {
        $firstUnansweredIndex = count($allQuestionsInOrder) - 1;
    }
@endphp

<div class="max-w-5xl mx-auto flex flex-col gap-6">

    <!-- ---------------------------------------------------
         Section Stepper (LinguaBridge Gold/Navy Progress Stepper)
         --------------------------------------------------- -->
    <div class="bg-brand-card border border-brand-border rounded-2xl p-5 shadow-2xs">
        <nav aria-label="Progress">
            <ol role="list" class="flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-2">
                @foreach($sections as $index => $sec)
                    @php
                        $isCompleted = $sec->order < $section->order;
                        $isActive = $sec->order === $section->order;
                        $isUpcoming = $sec->order > $section->order;
                        
                        $stepNumber = $index + 1;
                    @endphp
                    
                    <li class="flex-1 flex items-center gap-3">
                        @if($isCompleted)
                            <!-- Completed Step -->
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-gold text-brand-navy-dark font-bold text-sm shadow-2xs">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </span>
                            <span class="text-sm font-semibold text-brand-gold leading-none">
                                <span class="block text-2xs uppercase tracking-wider text-brand-gold/70 font-bold">Step {{ $stepNumber }}</span>
                                {{ $sec->title }}
                            </span>
                        @elseif($isActive)
                            <!-- Active Step -->
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-brand-gold bg-brand-card text-brand-gold font-extrabold text-sm shadow-sm ring-4 ring-brand-gold/15">
                                {{ $stepNumber }}
                            </span>
                            <span class="text-sm font-bold text-brand-text-dark leading-none">
                                <span class="block text-2xs uppercase tracking-wider text-brand-gold font-bold">Active Step</span>
                                {{ $sec->title }}
                            </span>
                        @else
                            <!-- Upcoming Step -->
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-brand-border bg-brand-card text-slate-400 font-semibold text-sm">
                                {{ $stepNumber }}
                            </span>
                            <span class="text-sm font-medium text-slate-450 leading-none">
                                <span class="block text-2xs uppercase tracking-wider text-slate-450 font-bold">Upcoming</span>
                                {{ $sec->title }}
                            </span>
                        @endif

                        @if($stepNumber < count($sections))
                            <!-- Stepper Connector Line (Desktop only) -->
                            <div class="hidden md:block flex-1 h-0.5 bg-brand-border mx-4"></div>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>

    <!-- Sticky Header with Title, Timer & Gold Progress Bar -->
    <div class="sticky top-0 z-40 bg-brand-navy text-white rounded-2xl p-5 shadow-md flex flex-col gap-4 border border-brand-gold/20">
        
        <div class="flex items-center justify-between gap-4">
            <div>
                <span class="text-2xs uppercase tracking-wider font-semibold text-brand-gold">
                    LinguaBridge Evaluation • Section {{ $section->order }} of {{ count($sections) }}
                </span>
                <h1 class="text-lg md:text-xl font-bold tracking-tight mt-0.5">{{ $section->title }}</h1>
            </div>
            
            <div class="flex items-center gap-3 shrink-0">
                <span class="hidden sm:inline text-2xs font-semibold text-slate-400 tracking-wide uppercase">SECTION TIMER</span>
                <div id="timer-display" class="px-4 py-2 bg-brand-gold text-brand-navy-dark rounded-xl font-mono text-lg font-bold tracking-wider shadow-sm">
                    --:--
                </div>
            </div>
        </div>

        <!-- Real-time Progress Bar & Question Stats -->
        <div class="border-t border-slate-800 pt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div class="flex items-baseline gap-2">
                <span id="question-stats" class="text-sm font-bold text-white">Question -- of --</span>
                <span id="percent-stats" class="text-xs text-brand-gold font-semibold">(---% completed)</span>
            </div>
            <div class="w-full sm:max-w-xs bg-slate-800 rounded-full h-2.5 overflow-hidden">
                <div id="progress-bar-fill" class="bg-brand-gold h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
        </div>

    </div>

    <!-- Section Instructions Box -->
    @if($section->instructions)
        <div class="p-4 rounded-xl bg-brand-card border border-brand-border border-l-4 border-l-brand-gold text-brand-text-muted shadow-2xs text-xs">
            <h3 class="font-bold flex items-center gap-1.5 mb-1 text-brand-text-dark text-sm">
                <svg class="h-4 w-4 text-brand-gold shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                Instructions:
            </h3>
            <p class="leading-relaxed font-semibold text-brand-text-muted">{{ $section->instructions }}</p>
        </div>
    @endif

    <!-- ---------------------------------------------------
         🎧 Premium Branded Listening Audio Center
         --------------------------------------------------- -->
    <!-- ---------------------------------------------------
         🎧 Premium Custom Listening Audio Portal
         --------------------------------------------------- -->
    <!-- ---------------------------------------------------
         🎧 Premium Custom Sequential Audio Center (Stage 1)
         --------------------------------------------------- -->
    @if($section->type === 'listening' && $listeningAudioFlowActive)
        <div id="sequential-audio-card" class="bg-brand-card border border-brand-border rounded-3xl p-6 md:p-8 shadow-xl flex flex-col gap-6 border-t-4 border-t-brand-gold animate-fade-in mb-6">
            <div class="flex items-center gap-2.5 pb-2 border-b border-brand-border">
                <div class="p-2 rounded-xl bg-brand-gold/15 text-brand-gold">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2Zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2ZM9 10l12-3" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-brand-text-dark">Required Preparation Audios</h2>
                    <p class="text-[9px] uppercase tracking-widest text-brand-gold font-bold font-mono">You must listen to all 3 audios to begin the quiz</p>
                </div>
            </div>

            <!-- Notice box -->
            <div class="p-4 rounded-xl bg-brand-navy-dark/5 border border-brand-border text-brand-text-muted text-xs leading-relaxed font-semibold">
                <span class="text-brand-gold font-bold">⚠️ PROTOCOL:</span> Listen to all three audios sequentially. Once Audio 3 ends, the <strong>Start Quiz</strong> button will activate. The countdown timer remains paused at <span class="text-brand-gold font-bold">{{ $section->duration_minutes }}:00</span>.
            </div>

            <div class="flex flex-col gap-4">
                
                <!-- Audio 1 Step -->
                <div id="audio-step-card-1" class="audio-step-card p-4 rounded-xl border border-brand-gold bg-brand-navy-dark/5 transition-all">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div class="flex items-start gap-2.5">
                            <span id="step-badge-1" class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-gold text-brand-navy-dark font-extrabold text-xs shrink-0">1</span>
                            <div>
                                <h4 class="text-sm font-bold text-brand-text-dark">Step 1: Welcome & Exam Instructions</h4>
                                <p class="text-2xs text-brand-text-muted mt-0.5 font-medium">Listening section operations guidelines.</p>
                            </div>
                        </div>
                        <span id="step-status-1" class="text-2xs font-bold text-brand-gold shrink-0">⏳ Awaiting Playback</span>
                    </div>
                    <div class="mt-3.5 pt-3 border-t border-brand-border/40">
                        <audio id="audio-track-1" controls class="w-full h-9 outline-none" controlsList="nodownload">
                            <source src="{{ asset('audio/1.mp3') }}" type="audio/mpeg">
                        </audio>
                    </div>
                </div>

                <!-- Audio 2 Step -->
                <div id="audio-step-card-2" class="audio-step-card p-4 rounded-xl border border-brand-border bg-brand-card opacity-50 pointer-events-none transition-all">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div class="flex items-start gap-2.5">
                            <span id="step-badge-2" class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-border text-brand-text-muted font-extrabold text-xs shrink-0">2</span>
                            <div>
                                <h4 class="text-sm font-bold text-brand-text-dark">Step 2: Notice & Rules Guidelines</h4>
                                <p class="text-2xs text-brand-text-muted mt-0.5 font-medium">Important copyright rules and candidate guidelines.</p>
                            </div>
                        </div>
                        <span id="step-status-2" class="text-2xs font-bold text-brand-text-muted shrink-0">🔒 Locked</span>
                    </div>
                    <div class="mt-3.5 pt-3 border-t border-brand-border/40">
                        <audio id="audio-track-2" class="w-full h-9 outline-none" controlsList="nodownload">
                            <source src="{{ asset('audio/2.mp3') }}" type="audio/mpeg">
                        </audio>
                    </div>
                </div>

                <!-- Audio 3 Step -->
                <div id="audio-step-card-3" class="audio-step-card p-4 rounded-xl border border-brand-border bg-brand-card opacity-50 pointer-events-none transition-all">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div class="flex items-start gap-2.5">
                            <span id="step-badge-3" class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-border text-brand-text-muted font-extrabold text-xs shrink-0">3</span>
                            <div>
                                <h4 class="text-sm font-bold text-brand-text-dark">Step 3: Listening Passage Audio</h4>
                                <p class="text-2xs text-brand-text-muted mt-0.5 font-medium">The actual listening passage text for the quiz.</p>
                            </div>
                        </div>
                        <span id="step-status-3" class="text-2xs font-bold text-brand-text-muted shrink-0">🔒 Locked</span>
                    </div>
                    <div class="mt-3.5 pt-3 border-t border-brand-border/40">
                        <audio id="audio-track-3" class="w-full h-9 outline-none" controlsList="nodownload">
                            <source src="{{ asset('audio/3.mp3') }}" type="audio/mpeg">
                        </audio>
                    </div>
                </div>

            </div>

            <!-- Start Quiz Button Wrapper (Initially Hidden) -->
            <div id="start-quiz-btn-container" style="display: none;" class="pt-4 border-t border-brand-border text-center animate-fade-in">
                <button type="button" id="start-quiz-btn" class="inline-flex items-center gap-2 bg-brand-gold hover:bg-brand-gold-dark text-brand-navy-dark font-extrabold px-8 py-4 rounded-xl shadow-md transition-all active:scale-95 duration-200 cursor-pointer text-base">
                    Start Quiz
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Custom CSS style for range seek input thumb -->
    <style>
        #audio-seek-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 12px;
            height: 12px;
            border-radius: 9999px;
            background: var(--color-brand-gold);
            cursor: pointer;
            transition: transform 0.1s ease;
        }
        #audio-seek-slider::-webkit-slider-thumb:hover {
            transform: scale(1.2);
        }
        #audio-seek-slider::-moz-range-thumb {
            width: 12px;
            height: 12px;
            border-radius: 9999px;
            background: var(--color-brand-gold);
            border: none;
            cursor: pointer;
            transition: transform 0.1s ease;
        }
        #audio-seek-slider::-moz-range-thumb:hover {
            transform: scale(1.2);
        }
    </style>

    <!-- Master Exam Wrapper -->
    <div id="questions-wrapper">

        <!-- Master Exam Form -->
        <form id="exam-form" action="{{ route('student.test.submit', [$attempt, $section]) }}" method="POST" class="space-y-6 {{ $listeningAudioFlowActive ? 'hidden' : '' }}">
            @csrf

            @if($section->type === 'listening')
                <!-- Custom Audio Player Card (Always available during the quiz for review) -->
                <div id="custom-audio-player-card" class="bg-brand-card border border-brand-border rounded-2xl p-5 shadow-2xs flex flex-col gap-4 border-l-4 border-l-brand-gold transition-all duration-300 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="p-1.5 rounded-lg bg-brand-gold/10 text-brand-gold">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                                </svg>
                            </span>
                            <span class="text-xs font-bold text-brand-text-dark uppercase tracking-wider">Listening Passage Audio</span>
                        </div>
                        <span class="text-3xs font-extrabold text-brand-gold bg-brand-gold/10 px-2.5 py-0.5 rounded-md uppercase tracking-widest font-mono">Review Player</span>
                    </div>

                    <!-- Hidden native audio tag -->
                    <audio id="listening-audio" class="hidden" controlsList="nodownload">
                        <source src="{{ asset('audio/3.mp3') }}" type="audio/mpeg">
                    </audio>

                    <!-- Custom Player Controls UI -->
                    <div class="flex items-center gap-4 bg-brand-bg border border-brand-border/60 p-3 rounded-xl">
                        <button type="button" id="audio-play-pause-btn" class="h-10 w-10 shrink-0 flex items-center justify-center rounded-lg bg-brand-navy hover:bg-brand-navy-dark text-white hover:text-brand-gold shadow-sm transition-all duration-200 cursor-pointer">
                            <svg id="audio-play-icon" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            <svg id="audio-pause-icon" class="h-5 w-5 hidden" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                            </svg>
                        </button>

                        <div class="flex-grow flex items-center gap-3 overflow-hidden">
                            <span id="audio-current-time" class="font-mono text-xs text-brand-text-muted select-none shrink-0">00:00</span>
                            <input type="range" id="audio-seek-slider" min="0" value="0" step="1" 
                                   class="flex-grow h-1.5 rounded-full appearance-none bg-brand-border cursor-pointer focus:outline-none accent-brand-gold"
                                   style="background: linear-gradient(to right, var(--color-brand-border) 0%, var(--color-brand-border) 100%);">
                            <span id="audio-duration" class="font-mono text-xs text-brand-text-muted select-none shrink-0">00:00</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- ----------------------------------------------------
                 LISTENING & LANGUAGE USE FLOW (Single Question Cards)
                 ---------------------------------------------------- -->
            @if($section->type !== 'reading')
                <div class="space-y-6">
                    @foreach($section->questions as $index => $question)
                        <div class="question-card hidden bg-brand-card rounded-2xl border border-brand-border p-6 shadow-2xs space-y-5"
                            data-index="{{ $index }}">
                            
                            <!-- Situation Header (Listening only) -->
                            @if($question->situation)
                                <div class="px-4 py-2 bg-brand-bg border-l-4 border-brand-gold rounded-r-lg text-xs font-semibold text-brand-text-muted tracking-wide">
                                    {{ $question->situation }}
                                </div>
                            @endif

                            <!-- Question Prompt -->
                            <h4 class="text-base font-bold text-brand-text-dark flex items-start gap-2.5">
                                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-brand-navy-dark dark:bg-brand-navy text-white font-mono text-xs font-bold shrink-0">
                                    {{ $question->question_number }}
                                </span>
                                <span class="pt-0.5 leading-snug">{!! nl2br(e($question->question_text)) !!}</span>
                            </h4>

                            <!-- Custom Option Rows -->
                            <div class="grid gap-3 sm:grid-cols-2 pt-1">
                                @foreach($question->options as $option)
                                    @php
                                        $isSelected = isset($savedAnswers[$question->id]) && $savedAnswers[$question->id] == $option->id;
                                    @endphp
                                    <label class="flex items-center gap-3 p-4 rounded-xl border border-brand-border cursor-pointer hover:bg-brand-navy-dark/5 hover:border-brand-gold/40 transition-all relative overflow-hidden select-none"
                                        onclick="selectOption(this)">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" 
                                            class="peer sr-only" {{ $isSelected ? 'checked' : '' }}>
                                        
                                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-brand-gold peer-checked:bg-brand-gold/10 pointer-events-none rounded-xl"></div>
                                        
                                        <!-- Checkbox dot indicator -->
                                        <span class="relative z-10 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-brand-border bg-brand-bg text-white peer-checked:bg-brand-gold peer-checked:border-brand-gold transition-all">
                                            <span class="relative z-10 h-2 w-2 rounded-full bg-brand-navy-dark opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                                        </span>
                                        
                                        <span class="relative z-10 flex h-7 w-7 items-center justify-center rounded-lg border border-brand-border bg-brand-bg text-brand-text-muted font-bold uppercase shrink-0 text-xs peer-checked:border-brand-gold/50 transition-all">
                                            {{ $option->label }}
                                        </span>
                                        
                                        <span class="relative z-10 text-sm font-semibold text-brand-text-muted leading-snug transition-colors peer-checked:text-brand-text-dark">
                                            {{ $option->option_text }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

            <!-- ----------------------------------------------------
                 READING FLOW (Split-Screen Left Passage, Right Question)
                 ---------------------------------------------------- -->
            @if($section->type === 'reading')
                <div class="grid gap-6 lg:grid-cols-12 items-start">
                    
                    <!-- Left Column: Reading Passages (Hide all, display only the linked passage) -->
                    <div class="lg:col-span-6 space-y-6 lg:sticky lg:top-36 max-h-[70vh] overflow-y-auto pr-2">
                        @foreach($section->passages as $passage)
                            <div id="passage-container-{{ $passage->id }}" class="passage-card hidden bg-brand-card rounded-2xl border border-brand-border p-6 shadow-2xs relative overflow-hidden">
                                <div class="flex items-center gap-2 mb-3 text-xs text-brand-gold font-bold uppercase tracking-wider">
                                    <span class="flex h-5 w-5 items-center justify-center rounded bg-brand-gold text-brand-navy-dark font-mono text-2xs font-extrabold">R</span>
                                    Reading Passage
                                </div>
                                @if($passage->title)
                                    <h3 class="text-lg font-bold text-brand-text-dark mb-4 pb-2 border-b border-brand-border">{{ $passage->title }}</h3>
                                @endif
                                <div class="text-brand-text-muted leading-relaxed text-sm whitespace-pre-line space-y-4 font-medium">
                                    {!! nl2br(e($passage->content)) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Right Column: Single Question Card -->
                    <div class="lg:col-span-6">
                        @php
                            $readingIndex = 0;
                        @endphp
                        @foreach($section->passages as $passage)
                            @foreach($passage->questions as $question)
                                <div class="question-card hidden bg-brand-card rounded-2xl border border-brand-border p-6 shadow-2xs space-y-4"
                                    data-index="{{ $readingIndex++ }}" data-passage-id="{{ $passage->id }}">
                                    
                                    <div class="text-2xs text-brand-gold font-bold uppercase tracking-wider">
                                        Linked to Passage: {{ $passage->title ?? 'Reading passage' }}
                                    </div>

                                    <!-- Question Prompt -->
                                    <h4 class="text-base font-bold text-brand-text-dark flex items-start gap-2.5">
                                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-brand-navy-dark dark:bg-brand-navy text-white font-mono text-xs font-bold shrink-0">
                                            {{ $question->question_number }}
                                        </span>
                                        <span class="pt-0.5 leading-snug">{!! nl2br(e($question->question_text)) !!}</span>
                                    </h4>

                                    <!-- Custom Option Radio Selection Cards -->
                                    <div class="grid gap-3 sm:grid-cols-2 pt-1">
                                        @foreach($question->options as $option)
                                            @php
                                                $isSelected = isset($savedAnswers[$question->id]) && $savedAnswers[$question->id] == $option->id;
                                            @endphp
                                            <label class="flex items-center gap-3 p-4 rounded-xl border border-brand-border cursor-pointer hover:bg-brand-navy-dark/5 hover:border-brand-gold/40 transition-all relative overflow-hidden select-none"
                                                onclick="selectOption(this)">
                                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" 
                                                    class="peer sr-only" {{ $isSelected ? 'checked' : '' }}>
                                                
                                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-brand-gold peer-checked:bg-brand-gold/10 pointer-events-none rounded-xl"></div>
                                                
                                                <span class="relative z-10 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-brand-border bg-brand-bg text-white peer-checked:bg-brand-gold peer-checked:border-brand-gold transition-all">
                                                    <span class="h-2 w-2 rounded-full bg-brand-navy-dark opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                                                </span>
                                                
                                                <span class="relative z-10 flex h-7 w-7 items-center justify-center rounded-lg border border-brand-border bg-brand-bg text-brand-text-muted font-bold uppercase shrink-0 text-xs peer-checked:border-brand-gold/50 transition-all">
                                                    {{ $option->label }}
                                                </span>
                                                
                                                <span class="relative z-10 text-sm font-semibold text-brand-text-muted leading-snug transition-colors peer-checked:text-brand-text-dark">
                                                    {{ $option->option_text }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>

                                </div>
                            @endforeach
                        @endforeach
                    </div>

                </div>
            @endif

            <!-- Form Submission Footer Button (Question Stepper Controller) -->
            <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between border-t border-brand-border pt-6 gap-4">
                <span class="text-2xs text-brand-text-muted font-semibold tracking-wider uppercase">
                    All questions must be answered sequentially to complete the exam.
                </span>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <button type="button" id="next-btn" onclick="navigateQuestion(1)"
                        class="inline-flex items-center gap-1 bg-brand-gold hover:bg-brand-gold-dark text-brand-navy-dark font-bold py-2.5 px-6 rounded-xl transition-all text-sm cursor-pointer shadow-md">
                        Next Question &rarr;
                    </button>
                    
                    <button type="button" id="submit-btn" onclick="submitExamSection()" style="display: none;"
                        class="inline-flex items-center gap-2 bg-brand-gold hover:bg-brand-gold-dark text-brand-navy-dark font-bold py-3 px-8 rounded-xl shadow-md transition-all text-base focus:outline-none cursor-pointer">
                        {{ $section->order === count($sections) ? 'Submit Exam' : 'Continue to Next Section' }}
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- Premium Floating Warning Toast (Branded Navy & Gold) -->
<div id="error-toast" style="display: none;" class="fixed bottom-24 right-6 md:right-10 bg-brand-navy border border-brand-gold/40 text-white py-3.5 px-5 rounded-2xl shadow-xl z-50 flex items-center gap-3 transition-all duration-300 tracking-wide">
    <svg class="h-5 w-5 text-brand-gold shrink-0 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
    </svg>
    <span class="text-sm font-semibold font-sans text-white/95">Please select an answer before continuing!</span>
</div>
@endsection

@section('scripts')
<script>
    // Question-by-Question Navigation Engine (Strict Progression)
    let activeQuestionIndex = {{ $firstUnansweredIndex }};
    const questionCards = document.querySelectorAll('.question-card');
    const totalQuestions = questionCards.length;

    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    
    const questionStats = document.getElementById('question-stats');
    const percentStats = document.getElementById('percent-stats');
    const progressBarFill = document.getElementById('progress-bar-fill');

    function renderQuestion(index) {
        activeQuestionIndex = index;
        
        // Hide all questions and highlight only active index
        questionCards.forEach((card, idx) => {
            if (idx === index) {
                card.classList.remove('hidden');
                
                // If it is a reading section, handle passage sticky synchronizer
                const passageId = card.getAttribute('data-passage-id');
                if (passageId) {
                    showReadingPassage(passageId);
                }
            } else {
                card.classList.add('hidden');
            }
        });

        // Update top-area progress meters
        const currentCount = index + 1;
        const percentCompleted = Math.round((currentCount / totalQuestions) * 100);
        
        questionStats.innerText = `Question ${currentCount} of ${totalQuestions}`;
        percentStats.innerText = `(${percentCompleted}% completed)`;
        progressBarFill.style.width = `${percentCompleted}%`;

        // Update button stepper visibility (No Previous button!)
        if (index === totalQuestions - 1) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'inline-flex';
        } else {
            nextBtn.style.display = 'inline-flex';
            submitBtn.style.display = 'none';
        }
    }

    function navigateQuestion(direction) {
        // Intercept validation logic if moving FORWARD (direction === 1)
        if (direction === 1) {
            const activeCard = questionCards[activeQuestionIndex];
            const checkedOption = activeCard.querySelector('input[type="radio"]:checked');
            
            if (!checkedOption) {
                showWarningToast();
                return; // Blocks navigation completely!
            }
        }

        const targetIndex = activeQuestionIndex + direction;
        if (targetIndex >= 0 && targetIndex < totalQuestions) {
            renderQuestion(targetIndex);
            
            // Scroll smoothly back to top of the card
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }

    // Triggered when clicking "Continue to Next Section" or "Submit Exam"
    function submitExamSection() {
        const activeCard = questionCards[activeQuestionIndex];
        const checkedOption = activeCard.querySelector('input[type="radio"]:checked');
        
        if (!checkedOption) {
            showWarningToast();
            return; // Blocks submission!
        }

        // Proceed to submit the form
        document.getElementById('exam-form').submit();
    }

    // Displays the warning toast and automatically hides it
    let toastTimeout;
    function showWarningToast() {
        const toast = document.getElementById('error-toast');
        toast.style.display = 'flex';
        
        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toast.style.display = 'none';
        }, 3500);
    }

    // Helper to toggle active passage inside Reading split views
    function showReadingPassage(passageId) {
        const passageCards = document.querySelectorAll('.passage-card');
        passageCards.forEach(card => {
            if (card.id === `passage-container-${passageId}`) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }

    // Initialize active question load (starts at first unanswered index, only if audio flow is inactive)
    @if(!$listeningAudioFlowActive)
        if (totalQuestions > 0) {
            renderQuestion(activeQuestionIndex);
        }
    @endif

    // ---------------------------------------------------
    // Timer Count Engine (Formatted MM:SS, No Decimals)
    // ---------------------------------------------------
    const remainingSeconds = {{ $remainingSeconds }};
    let timeCount = Math.floor(remainingSeconds);
    const timerDisplay = document.getElementById('timer-display');
    const examForm = document.getElementById('exam-form');
    let timerInterval;

    function updateDisplay() {
        const minutes = Math.floor(timeCount / 60);
        const seconds = Math.floor(timeCount % 60);
        
        const minStr = String(minutes).padStart(2, '0');
        const secStr = String(seconds).padStart(2, '0');
        
        timerDisplay.innerText = minStr + ':' + secStr;

        // Visual warning if less than 60 seconds
        if (timeCount < 60) {
            timerDisplay.classList.remove('bg-brand-gold', 'text-brand-navy-dark');
            timerDisplay.classList.add('bg-rose-600', 'text-white', 'animate-pulse');
        }
    }

    function startTimerCountdown() {
        updateDisplay();
        timerDisplay.classList.remove('opacity-60');

        timerInterval = setInterval(() => {
            if (timeCount <= 0) {
                clearInterval(timerInterval);
                
                // Mark the form as auto-submitted due to timeout
                const autoSubmitInput = document.createElement('input');
                autoSubmitInput.type = 'hidden';
                autoSubmitInput.name = 'auto_submit';
                autoSubmitInput.value = '1';
                examForm.appendChild(autoSubmitInput);
                
                examForm.submit();
                return;
            }

            timeCount--;
            updateDisplay();
        }, 1000);
    }

    @if($listeningAudioFlowActive)
        // Timer display in paused visual state
        timerDisplay.innerText = String(Math.floor(remainingSeconds / 60)).padStart(2, '0') + ":00";
        timerDisplay.classList.add('opacity-60');
    @else
        startTimerCountdown();
    @endif

    // ---------------------------------------------------
    // Premium Custom Audio Player Controls
    // ---------------------------------------------------
    const audio = document.getElementById('listening-audio');
    if (audio) {
        const playPauseBtn = document.getElementById('audio-play-pause-btn');
        const playIcon = document.getElementById('audio-play-icon');
        const pauseIcon = document.getElementById('audio-pause-icon');
        const seekSlider = document.getElementById('audio-seek-slider');
        const currentTimeText = document.getElementById('audio-current-time');
        const durationText = document.getElementById('audio-duration');

        playPauseBtn.addEventListener('click', () => {
            if (audio.paused) {
                audio.play();
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
            } else {
                audio.pause();
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
            }
        });

        audio.addEventListener('loadedmetadata', () => {
            seekSlider.max = Math.floor(audio.duration);
            durationText.innerText = formatTime(audio.duration);
        });

        audio.addEventListener('timeupdate', () => {
            seekSlider.value = Math.floor(audio.currentTime);
            currentTimeText.innerText = formatTime(audio.currentTime);
            const pct = (audio.currentTime / audio.duration) * 100;
            seekSlider.style.background = `linear-gradient(to right, var(--color-brand-gold) ${pct}%, var(--color-brand-border) ${pct}%)`;
        });

        seekSlider.addEventListener('input', () => {
            audio.currentTime = seekSlider.value;
            currentTimeText.innerText = formatTime(audio.currentTime);
        });

        // Trigger loadedmetadata manually in case metadata is already cached
        if (audio.readyState >= 1) {
            seekSlider.max = Math.floor(audio.duration);
            durationText.innerText = formatTime(audio.duration);
        }

        function formatTime(secs) {
            if (isNaN(secs)) return '00:00';
            const m = Math.floor(secs / 60);
            const s = Math.floor(secs % 60);
            return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        }
    }

    // ---------------------------------------------------
    // Sequential Audio Playback State Engine & Start Gate
    // ---------------------------------------------------
    @if($listeningAudioFlowActive)
    document.addEventListener("DOMContentLoaded", () => {
        const audio1 = document.getElementById('audio-track-1');
        const audio2 = document.getElementById('audio-track-2');
        const audio3 = document.getElementById('audio-track-3');

        const card1 = document.getElementById('audio-step-card-1');
        const card2 = document.getElementById('audio-step-card-2');
        const card3 = document.getElementById('audio-step-card-3');

        const status1 = document.getElementById('step-status-1');
        const status2 = document.getElementById('step-status-2');
        const status3 = document.getElementById('step-status-3');

        const badge1 = document.getElementById('step-badge-1');
        const badge2 = document.getElementById('step-badge-2');
        const badge3 = document.getElementById('step-badge-3');

        const startQuizBtnContainer = document.getElementById('start-quiz-btn-container');
        const startQuizBtn = document.getElementById('start-quiz-btn');

        // Lock audio 2 and 3 initial setup
        audio2.removeAttribute('controls');
        audio3.removeAttribute('controls');

        // Audio 1
        audio1.addEventListener('play', () => {
            status1.innerText = '🔊 Playing...';
            card1.classList.remove('bg-brand-navy-dark/5');
            card1.classList.add('bg-brand-gold/5', 'border-brand-gold');
        });
        audio1.addEventListener('ended', () => {
            status1.innerHTML = '✅ Completed';
            status1.className = 'text-2xs font-bold text-emerald-500 dark:text-emerald-400';
            card1.classList.remove('bg-brand-gold/5', 'border-brand-gold');
            card1.classList.add('bg-brand-card', 'border-brand-border', 'opacity-75');
            badge1.innerHTML = '✓';
            badge1.className = 'flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-500 text-white font-extrabold text-xs shrink-0';

            // Unlock Audio 2
            card2.classList.remove('opacity-50', 'pointer-events-none');
            card2.classList.add('bg-brand-navy-dark/5', 'border-brand-gold');
            audio2.setAttribute('controls', 'true');
            status2.innerText = '⏳ Awaiting Playback';
            status2.className = 'text-2xs font-bold text-brand-gold';
            badge2.className = 'flex h-7 w-7 items-center justify-center rounded-lg bg-brand-gold text-brand-navy-dark font-extrabold text-xs shrink-0';
        });

        // Audio 2
        audio2.addEventListener('play', () => {
            status2.innerText = '🔊 Playing...';
            card2.classList.remove('bg-brand-navy-dark/5');
            card2.classList.add('bg-brand-gold/5', 'border-brand-gold');
        });
        audio2.addEventListener('ended', () => {
            status2.innerHTML = '✅ Completed';
            status2.className = 'text-2xs font-bold text-emerald-500 dark:text-emerald-400';
            card2.classList.remove('bg-brand-gold/5', 'border-brand-gold');
            card2.classList.add('bg-brand-card', 'border-brand-border', 'opacity-75');
            badge2.innerHTML = '✓';
            badge2.className = 'flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-500 text-white font-extrabold text-xs shrink-0';

            // Unlock Audio 3
            card3.classList.remove('opacity-50', 'pointer-events-none');
            card3.classList.add('bg-brand-navy-dark/5', 'border-brand-gold');
            audio3.setAttribute('controls', 'true');
            status3.innerText = '⏳ Awaiting Playback';
            status3.className = 'text-2xs font-bold text-brand-gold';
            badge3.className = 'flex h-7 w-7 items-center justify-center rounded-lg bg-brand-gold text-brand-navy-dark font-extrabold text-xs shrink-0';
        });

        // Audio 3
        audio3.addEventListener('play', () => {
            status3.innerText = '🔊 Playing...';
            card3.classList.remove('bg-brand-navy-dark/5');
            card3.classList.add('bg-brand-gold/5', 'border-brand-gold');
        });
        audio3.addEventListener('ended', () => {
            status3.innerHTML = '✅ Completed';
            status3.className = 'text-2xs font-bold text-emerald-500 dark:text-emerald-400';
            card3.classList.remove('bg-brand-gold/5', 'border-brand-gold');
            card3.classList.add('bg-brand-card', 'border-brand-border', 'opacity-75');
            badge3.innerHTML = '✓';
            badge3.className = 'flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-500 text-white font-extrabold text-xs shrink-0';

            // Enable Start Quiz CTA Button
            startQuizBtnContainer.style.display = 'block';
            startQuizBtnContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });

        // Start Quiz Trigger
        if (startQuizBtn) {
            startQuizBtn.addEventListener('click', () => {
                startQuizBtn.disabled = true;
                startQuizBtn.innerText = 'Initializing Quiz...';

                fetch("{{ route('student.test.start-timer', $attempt) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hide sequential playback center
                        const sequentialCard = document.getElementById('sequential-audio-card');
                        if (sequentialCard) {
                            sequentialCard.style.display = 'none';
                        }

                        // Reveal exam form
                        const formElement = document.getElementById('exam-form');
                        formElement.classList.remove('hidden');

                        // Show first question
                        if (totalQuestions > 0) {
                            renderQuestion(activeQuestionIndex);
                        }

                        // Start countdown timer
                        startTimerCountdown();

                        // Auto-play the custom review player of Audio 3 immediately when starting
                        const customAudio = document.getElementById('listening-audio');
                        if (customAudio) {
                            customAudio.play().catch(e => console.log('Autoplay blocked by browser. Candidate must click play.'));
                            document.getElementById('audio-play-icon').classList.add('hidden');
                            document.getElementById('audio-pause-icon').classList.remove('hidden');
                        }
                    } else {
                        startQuizBtn.disabled = false;
                        startQuizBtn.innerText = 'Start Quiz';
                        alert('Failed to start quiz. Please refresh and try again.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    startQuizBtn.disabled = false;
                    startQuizBtn.innerText = 'Start Quiz';
                    alert('Connection error. Please try again.');
                });
            });
        }
    });
    @endif

    // Javascript logic to toggle visual styling on selectOption click
    function selectOption(element) {
        const radio = element.querySelector('input');
        const questionIdName = radio.name;
        const groupElements = document.querySelectorAll(`input[name="${questionIdName}"]`);
        
        groupElements.forEach(input => {
            const parentLabel = input.closest('label');
            
            // Reset parent borders and backgrounds
            parentLabel.classList.remove('border-brand-gold', 'bg-brand-gold/10');
            parentLabel.classList.add('border-brand-border');
        });

        // Set visual styling on the clicked option
        radio.checked = true;
        
        element.classList.remove('border-brand-border');
        element.classList.add('border-brand-gold', 'bg-brand-gold/10');
    }

    // Seed the visual checkmarks properly on page load
    document.addEventListener("DOMContentLoaded", () => {
        const checkedRadios = document.querySelectorAll('input[type="radio"]:checked');
        checkedRadios.forEach(radio => {
            const parentLabel = radio.closest('label');
            if (parentLabel) {
                parentLabel.classList.remove('border-brand-border');
                parentLabel.classList.add('border-brand-gold', 'bg-brand-gold/10');
            }
        });
    });
</script>
@endsection
