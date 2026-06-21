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

<style>
    /* Force 100vh viewport layout for test taking page */
    html, body {
        overflow: hidden !important;
        height: 100vh !important;
        height: 100dvh !important;
    }
    body main {
        height: calc(100vh - 64px) !important;
        height: calc(100dvh - 64px) !important;
        overflow: hidden !important;
        display: flex;
        flex-direction: column;
    }
    body main > div {
        height: 100% !important;
        max-width: 100% !important;
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
</style>

@php
    $plays = $attempt->audio_plays ?? [];
    $playCount1 = $plays['track_1'] ?? 0;
    $playCount2 = $plays['track_2'] ?? 0;
    $playCount3 = $plays['track_3'] ?? 0;
@endphp

<div class="max-w-5xl mx-auto w-full h-full flex flex-col overflow-hidden gap-3 px-1 md:px-4">

    <!-- ---------------------------------------------------
         Combined Compact Header Banner
         --------------------------------------------------- -->
    <div class="bg-brand-navy text-white rounded-xl px-4 py-2.5 flex flex-col md:flex-row md:items-center justify-between gap-3 border border-brand-gold/20 shrink-0 shadow-sm">
        <!-- Left: Title & Timer -->
        <div class="flex items-center justify-between md:justify-start gap-4">
            <div>
                <span class="text-[9px] uppercase tracking-wider font-semibold text-brand-gold block">
                    LinguaBridge Evaluation
                </span>
                <h1 class="text-xs md:text-sm font-bold tracking-tight text-white flex items-center gap-1.5 mt-0.5">
                    <span class="text-brand-gold uppercase tracking-wider">{{ $section->title }}</span>
                    <span class="text-slate-400">Section {{ $section->order }} of {{ count($sections) }}</span>
                </h1>
            </div>
            
            <div class="flex items-center gap-2">
                <div id="timer-display" class="px-2.5 py-1 bg-brand-gold text-brand-navy-dark rounded-lg font-mono text-sm font-bold tracking-wider shadow-xs">
                    --:--
                </div>
            </div>
        </div>
        
        <!-- Middle: Compact Stepper -->
        <nav class="hidden md:flex items-center gap-1 text-[11px] overflow-x-auto py-1">
            @foreach($sections as $index => $sec)
                @php
                    $isCompleted = $sec->order < $section->order;
                    $isActive = $sec->order === $section->order;
                    $stepNumber = $index + 1;
                @endphp
                <div class="flex items-center gap-1 shrink-0">
                    @if($isCompleted)
                        <span class="flex h-4.5 w-4.5 items-center justify-center rounded-full bg-brand-gold text-brand-navy-dark font-extrabold text-[9px]">✓</span>
                    @elseif($isActive)
                        <span class="flex h-4.5 w-4.5 items-center justify-center rounded-full border border-brand-gold bg-brand-navy text-brand-gold font-extrabold text-[9px] ring-2 ring-brand-gold/25">{{ $stepNumber }}</span>
                    @else
                        <span class="flex h-4.5 w-4.5 items-center justify-center rounded-full border border-slate-600 text-slate-500 font-bold text-[9px]">{{ $stepNumber }}</span>
                    @endif
                    <span class="{{ $isActive ? 'text-white font-bold' : 'text-slate-400 font-medium' }} text-[10px]">{{ $sec->title }}</span>
                    @if($stepNumber < count($sections))
                        <span class="text-slate-600 text-[10px] mx-0.5">&rarr;</span>
                    @endif
                </div>
            @endforeach
        </nav>
        
        <!-- Right: Question Progress Bar & Stats -->
        <div class="flex items-center justify-between md:justify-end gap-3 shrink-0">
            <span id="question-stats" class="text-xs font-bold text-white shrink-0">Question -- of --</span>
            <div class="w-20 bg-slate-800 rounded-full h-1.5 overflow-hidden hidden sm:block">
                <div id="progress-bar-fill" class="bg-brand-gold h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
        </div>
    </div>

    <!-- Section Instructions Box -->
    @if($section->instructions)
        <div class="p-2 rounded-lg bg-brand-card border border-brand-border border-l-4 border-l-brand-gold text-brand-text-muted text-[11px] leading-normal shrink-0 shadow-2xs">
            <span class="font-bold text-brand-text-dark">Instructions:</span>
            {{ $section->instructions }}
        </div>
    @endif

    <!-- ---------------------------------------------------
         🎧 Premium Custom Sequential Audio Center (Stage 1)
         --------------------------------------------------- -->
    @if($listeningAudioFlowActive)
        <div id="sequential-audio-card" class="bg-brand-card border border-brand-border rounded-2xl p-5 shadow-md flex flex-col gap-4 border-t-4 border-t-brand-gold animate-fade-in flex-grow overflow-y-auto min-h-0">
            <div class="flex items-center gap-2 pb-1.5 border-b border-brand-border shrink-0">
                <div class="p-1.5 rounded-lg bg-brand-gold/15 text-brand-gold shrink-0">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2Zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2ZM9 10l12-3" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-brand-text-dark">Required Preparation Audios</h2>
                    <p class="text-[9px] uppercase tracking-widest text-brand-gold font-bold font-mono">Listen to all 3 audios to begin the quiz</p>
                </div>
            </div>

            <!-- Notice box -->
            <div class="p-3 rounded-lg bg-brand-navy-dark/5 border border-brand-border text-brand-text-muted text-[11px] leading-relaxed font-medium shrink-0">
                <span class="text-brand-gold font-bold">⚠️ PROTOCOL:</span> Listen to all three audios sequentially. Once Audio 3 ends, the <strong>Start Quiz</strong> button will activate. Playbacks are capped at <strong>2 opportunities per track</strong>.
            </div>

            <div class="flex flex-col gap-3 overflow-y-auto pr-1 flex-grow">
                
                <!-- Audio 1 Step -->
                <div id="audio-step-card-1" class="audio-step-card p-3 rounded-xl border border-brand-gold bg-brand-navy-dark/5 transition-all">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-start gap-2.5">
                            <span id="step-badge-1" class="flex h-6 w-6 items-center justify-center rounded-lg bg-brand-gold text-brand-navy-dark font-extrabold text-[11px] shrink-0">1</span>
                            <div>
                                <h4 class="text-xs font-bold text-brand-text-dark flex items-center gap-1.5">
                                    Step 1: Welcome & Exam Instructions
                                    <span id="play-count-1" class="text-[10px] font-bold text-brand-gold">({{ $playCount1 }}/2 plays)</span>
                                </h4>
                                <p class="text-[10px] text-brand-text-muted mt-0.5 font-medium">Listening section operations guidelines.</p>
                            </div>
                        </div>
                        <span id="step-status-1" class="text-[10px] font-bold text-brand-gold shrink-0">⏳ Awaiting Playback</span>
                    </div>
                    <div class="mt-2 pt-2 border-t border-brand-border/40">
                        <audio id="audio-track-1" controls class="w-full h-8 outline-none" controlsList="nodownload">
                            <source src="{{ asset('audio/1.mp3') }}" type="audio/mpeg">
                        </audio>
                    </div>
                </div>

                <!-- Audio 2 Step -->
                <div id="audio-step-card-2" class="audio-step-card p-3 rounded-xl border border-brand-border bg-brand-card opacity-50 pointer-events-none transition-all">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-start gap-2.5">
                            <span id="step-badge-2" class="flex h-6 w-6 items-center justify-center rounded-lg bg-brand-border text-brand-text-muted font-extrabold text-[11px] shrink-0">2</span>
                            <div>
                                <h4 class="text-xs font-bold text-brand-text-dark flex items-center gap-1.5">
                                    Step 2: Notice & Rules Guidelines
                                    <span id="play-count-2" class="text-[10px] font-bold text-brand-gold">({{ $playCount2 }}/2 plays)</span>
                                </h4>
                                <p class="text-[10px] text-brand-text-muted mt-0.5 font-medium">Important copyright rules and candidate guidelines.</p>
                            </div>
                        </div>
                        <span id="step-status-2" class="text-[10px] font-bold text-brand-text-muted shrink-0">🔒 Locked</span>
                    </div>
                    <div class="mt-2 pt-2 border-t border-brand-border/40">
                        <audio id="audio-track-2" class="w-full h-8 outline-none" controlsList="nodownload">
                            <source src="{{ asset('audio/2.mp3') }}" type="audio/mpeg">
                        </audio>
                    </div>
                </div>

                <!-- Audio 3 Step -->
                <div id="audio-step-card-3" class="audio-step-card p-3 rounded-xl border border-brand-border bg-brand-card opacity-50 pointer-events-none transition-all">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-start gap-2.5">
                            <span id="step-badge-3" class="flex h-6 w-6 items-center justify-center rounded-lg bg-brand-border text-brand-text-muted font-extrabold text-[11px] shrink-0">3</span>
                            <div>
                                <h4 class="text-xs font-bold text-brand-text-dark flex items-center gap-1.5">
                                    Step 3: Listening Passage Audio
                                    <span id="play-count-3" class="text-[10px] font-bold text-brand-gold">({{ $playCount3 }}/2 plays)</span>
                                </h4>
                                <p class="text-[10px] text-brand-text-muted mt-0.5 font-medium">The actual listening passage text for the quiz.</p>
                            </div>
                        </div>
                        <span id="step-status-3" class="text-[10px] font-bold text-brand-text-muted shrink-0">🔒 Locked</span>
                    </div>
                    <div class="mt-2 pt-2 border-t border-brand-border/40">
                        <audio id="audio-track-3" class="w-full h-8 outline-none" controlsList="nodownload">
                            <source src="{{ asset('audio/3.mp3') }}" type="audio/mpeg">
                        </audio>
                    </div>
                </div>

            </div>

            <!-- Start Quiz Button Wrapper -->
            <div id="start-quiz-btn-container" style="display: none;" class="pt-3 border-t border-brand-border text-center animate-fade-in shrink-0">
                <button type="button" id="start-quiz-btn" class="inline-flex items-center gap-2 bg-brand-gold hover:bg-brand-gold-dark text-brand-navy-dark font-extrabold px-6 py-3 rounded-xl shadow-md transition-all active:scale-95 duration-200 cursor-pointer text-sm">
                    Start Quiz
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
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
            width: 10px;
            height: 10px;
            border-radius: 9999px;
            background: var(--color-brand-gold);
            cursor: pointer;
            transition: transform 0.1s ease;
        }
        #audio-seek-slider::-webkit-slider-thumb:hover {
            transform: scale(1.2);
        }
        #audio-seek-slider::-moz-range-thumb {
            width: 10px;
            height: 10px;
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
    <div id="questions-wrapper" class="flex-grow flex flex-col min-h-0 overflow-hidden">

        <!-- Master Exam Form -->
        <form id="exam-form" action="{{ route('student.test.submit', [$attempt, $section]) }}" method="POST" class="flex-grow flex flex-col min-h-0 overflow-hidden {{ $listeningAudioFlowActive ? 'hidden' : '' }}">
            @csrf

            @if($section->type === 'listening')
                <!-- Compact Custom Audio Player Card (Always available during the quiz for review) -->
                <div id="custom-audio-player-card" class="bg-brand-card border border-brand-border rounded-xl p-3 shadow-3xs flex items-center justify-between gap-4 border-l-4 border-l-brand-gold transition-all duration-300 shrink-0 mb-2">
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="p-1 rounded-lg bg-brand-gold/10 text-brand-gold shrink-0">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                            </svg>
                        </span>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-brand-text-dark leading-none">Listening Passage Audio</span>
                            <span id="play-count-review" class="text-[10px] font-bold text-brand-gold mt-0.5">({{ $playCount3 }}/2 plays)</span>
                        </div>
                    </div>

                    <!-- Hidden native audio tag -->
                    <audio id="listening-audio" class="hidden" controlsList="nodownload">
                        <source src="{{ asset('audio/3.mp3') }}" type="audio/mpeg">
                    </audio>

                    <!-- Custom Player Controls UI -->
                    <div class="flex items-center gap-3 bg-brand-bg border border-brand-border/60 px-3 py-1.5 rounded-lg flex-grow max-w-md">
                        <button type="button" id="audio-play-pause-btn" class="h-8 w-8 shrink-0 flex items-center justify-center rounded-md bg-brand-navy hover:bg-brand-navy-dark text-white hover:text-brand-gold shadow-sm transition-all duration-200 cursor-pointer">
                            <svg id="audio-play-icon" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            <svg id="audio-pause-icon" class="h-4 w-4 hidden" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                            </svg>
                        </button>

                        <div class="flex-grow flex items-center gap-2 overflow-hidden">
                            <span id="audio-current-time" class="font-mono text-[10px] text-brand-text-muted select-none shrink-0">00:00</span>
                            <input type="range" id="audio-seek-slider" min="0" value="0" step="1" 
                                   class="flex-grow h-1.5 rounded-full appearance-none bg-brand-border cursor-pointer focus:outline-none accent-brand-gold"
                                   style="background: linear-gradient(to right, var(--color-brand-border) 0%, var(--color-brand-border) 100%);">
                            <span id="audio-duration" class="font-mono text-[10px] text-brand-text-muted select-none shrink-0">00:00</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- ----------------------------------------------------
                 LISTENING & LANGUAGE USE FLOW (Single Question Cards)
                 ---------------------------------------------------- -->
            @if($section->type !== 'reading')
                <div class="flex-grow min-h-0 overflow-y-auto pr-1">
                    @foreach($section->questions as $index => $question)
                        <div class="question-card hidden bg-brand-card rounded-xl border border-brand-border p-5 shadow-3xs flex flex-col justify-between gap-4"
                            data-index="{{ $index }}">
                            
                            <div class="space-y-4">
                                <!-- Situation Header -->
                                @if($question->situation)
                                    <div class="px-3 py-1.5 bg-brand-bg border-l-4 border-brand-gold rounded-r-lg text-[11px] font-semibold text-brand-text-muted tracking-wide">
                                        {{ $question->situation }}
                                    </div>
                                @endif

                                <!-- Question Prompt -->
                                <h4 class="text-sm font-bold text-brand-text-dark flex items-start gap-2">
                                    <span class="flex h-5.5 w-5.5 items-center justify-center rounded-lg bg-brand-navy-dark dark:bg-brand-navy text-white font-mono text-[11px] font-bold shrink-0">
                                        {{ $question->question_number }}
                                    </span>
                                    <span class="pt-0.5 leading-snug">{!! nl2br(e($question->question_text)) !!}</span>
                                </h4>

                                @if($question->image_path)
                                    <div class="flex justify-center mt-2.5">
                                        <img src="{{ asset($question->image_path) }}" alt="Question Image" class="max-w-full h-auto max-h-48 object-contain rounded-lg border border-brand-border/60 shadow-2xs">
                                    </div>
                                @endif
                            </div>

                            <!-- Options Grid -->
                            <div class="grid gap-2.5 sm:grid-cols-2 pt-1 flex-grow overflow-y-auto">
                                @foreach($question->options as $option)
                                    @php
                                        $isSelected = isset($savedAnswers[$question->id]) && $savedAnswers[$question->id] == $option->id;
                                    @endphp
                                    <label class="flex items-center gap-2.5 p-3 rounded-lg border border-brand-border cursor-pointer hover:bg-brand-navy-dark/5 hover:border-brand-gold/40 transition-all relative overflow-hidden select-none"
                                        onclick="selectOption(this)">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" 
                                            class="peer sr-only" {{ $isSelected ? 'checked' : '' }}>
                                        
                                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-brand-gold peer-checked:bg-brand-gold/10 pointer-events-none rounded-lg"></div>
                                        
                                        <span class="relative z-10 flex h-4.5 w-4.5 shrink-0 items-center justify-center rounded-full border border-brand-border bg-brand-bg text-white peer-checked:bg-brand-gold peer-checked:border-brand-gold transition-all">
                                            <span class="relative z-10 h-1.5 w-1.5 rounded-full bg-brand-navy-dark opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                                        </span>
                                        
                                        <span class="relative z-10 flex h-6 w-6 items-center justify-center rounded bg-brand-bg text-brand-text-muted font-bold uppercase shrink-0 text-[10px] peer-checked:border-brand-gold/50 transition-all">
                                            {{ $option->label }}
                                        </span>
                                        
                                        <span class="relative z-10 text-xs font-semibold text-brand-text-muted leading-snug transition-colors peer-checked:text-brand-text-dark">
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
                <div class="flex-grow min-h-0 grid gap-4 lg:grid-cols-12 overflow-hidden">
                    
                    <!-- Left Column: Reading Passages (Independent scroll) -->
                    <div class="lg:col-span-6 h-full overflow-y-auto pr-1 flex flex-col gap-4">
                        @foreach($section->passages as $passage)
                            <div id="passage-container-{{ $passage->id }}" class="passage-card hidden bg-brand-card rounded-xl border border-brand-border p-5 shadow-3xs flex flex-col h-full overflow-y-auto">
                                <div class="flex items-center gap-1.5 mb-2.5 text-[10px] text-brand-gold font-bold uppercase tracking-wider shrink-0">
                                    <span class="flex h-4.5 w-4.5 items-center justify-center rounded bg-brand-gold text-brand-navy-dark font-mono text-[10px] font-extrabold">R</span>
                                    Reading Passage
                                </div>
                                @if($passage->title)
                                    <h3 class="text-sm font-bold text-brand-text-dark mb-2.5 pb-2 border-b border-brand-border shrink-0">{{ $passage->title }}</h3>
                                @endif
                                <div class="text-brand-text-muted leading-relaxed text-xs whitespace-pre-line font-medium flex-grow overflow-y-auto">
                                    {!! nl2br(e($passage->content)) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Right Column: Question Card (Independent scroll) -->
                    <div class="lg:col-span-6 h-full overflow-y-auto flex flex-col gap-4">
                        @php
                            $readingIndex = 0;
                        @endphp
                        @foreach($section->passages as $passage)
                            @foreach($passage->questions as $question)
                                <div class="question-card hidden bg-brand-card rounded-xl border border-brand-border p-5 shadow-3xs flex flex-col justify-between gap-4 h-full overflow-y-auto"
                                    data-index="{{ $readingIndex++ }}" data-passage-id="{{ $passage->id }}">
                                    
                                    <div class="space-y-3">
                                        <div class="text-[10px] text-brand-gold font-bold uppercase tracking-wider">
                                            Linked to Passage: {{ $passage->title ?? 'Reading passage' }}
                                        </div>

                                        <!-- Question Prompt -->
                                        <h4 class="text-sm font-bold text-brand-text-dark flex items-start gap-2">
                                            <span class="flex h-5.5 w-5.5 items-center justify-center rounded bg-brand-navy-dark dark:bg-brand-navy text-white font-mono text-[10px] font-bold shrink-0">
                                                {{ $question->question_number }}
                                            </span>
                                            <span class="pt-0.5 leading-snug">{!! nl2br(e($question->question_text)) !!}</span>
                                        </h4>

                                        @if($question->image_path)
                                            <div class="flex justify-center mt-2.5">
                                                <img src="{{ asset($question->image_path) }}" alt="Question Image" class="max-w-full h-auto max-h-48 object-contain rounded-lg border border-brand-border/60 shadow-2xs">
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Option Selection Cards -->
                                    <div class="grid gap-2.5 sm:grid-cols-2 pt-1 flex-grow overflow-y-auto">
                                        @foreach($question->options as $option)
                                            @php
                                                $isSelected = isset($savedAnswers[$question->id]) && $savedAnswers[$question->id] == $option->id;
                                            @endphp
                                            <label class="flex items-center gap-2.5 p-3 rounded-lg border border-brand-border cursor-pointer hover:bg-brand-navy-dark/5 hover:border-brand-gold/40 transition-all relative overflow-hidden select-none"
                                                onclick="selectOption(this)">
                                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" 
                                                    class="peer sr-only" {{ $isSelected ? 'checked' : '' }}>
                                                
                                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-brand-gold peer-checked:bg-brand-gold/10 pointer-events-none rounded-lg"></div>
                                                
                                                <span class="relative z-10 flex h-4.5 w-4.5 shrink-0 items-center justify-center rounded-full border border-brand-border bg-brand-bg text-white peer-checked:bg-brand-gold peer-checked:border-brand-gold transition-all">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-brand-navy-dark opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                                                </span>
                                                
                                                <span class="relative z-10 flex h-6 w-6 items-center justify-center rounded bg-brand-bg text-brand-text-muted font-bold uppercase shrink-0 text-[10px] peer-checked:border-brand-gold/50 transition-all">
                                                    {{ $option->label }}
                                                </span>
                                                
                                                <span class="relative z-10 text-xs font-semibold text-brand-text-muted leading-snug transition-colors peer-checked:text-brand-text-dark">
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

            <!-- Form Submission Footer Button (Pinned at Bottom) -->
            <div class="mt-2.5 flex items-center justify-between border-t border-brand-border pt-2.5 gap-4 shrink-0 bg-brand-bg">
                <span class="text-[9px] text-brand-text-muted font-semibold tracking-wider uppercase hidden sm:inline">
                    All questions must be answered sequentially to complete the exam.
                </span>
                <span class="text-[9px] text-brand-text-muted font-semibold tracking-wider uppercase sm:hidden">
                    Answer sequentially to complete.
                </span>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <button type="button" id="next-btn" onclick="navigateQuestion(1)"
                        class="inline-flex items-center gap-1.5 bg-brand-gold hover:bg-brand-gold-dark text-brand-navy-dark font-bold py-2 px-5 rounded-lg transition-all text-xs cursor-pointer shadow-xs">
                        Next Question &rarr;
                    </button>
                    
                    <button type="button" id="submit-btn" onclick="submitExamSection()" style="display: none;"
                        class="inline-flex items-center gap-1.5 bg-brand-gold hover:bg-brand-gold-dark text-brand-navy-dark font-extrabold py-2 px-5 rounded-lg shadow-xs transition-all text-xs focus:outline-none cursor-pointer">
                        {{ $section->order === count($sections) ? 'Submit Exam' : 'Continue to Next Section' }}
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- Premium Floating Warning Toast -->
<div id="error-toast" style="display: none;" class="fixed bottom-16 right-4 md:right-10 bg-brand-navy border border-brand-gold/40 text-white py-2 px-4 rounded-xl shadow-lg z-50 flex items-center gap-2.5 transition-all duration-300 tracking-wide">
    <svg class="h-4 w-4 text-brand-gold shrink-0 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
    </svg>
    <span class="text-xs font-semibold font-sans text-white/95">Please select an answer before continuing!</span>
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
        if (percentStats) {
            percentStats.innerText = `(${percentCompleted}% completed)`;
        }
        if (progressBarFill) {
            progressBarFill.style.width = `${percentCompleted}%`;
        }

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
            
            // Scroll smoothly back to top of the active card/column
            const container = document.querySelector('.question-card:not(.hidden)');
            if (container) {
                container.scrollTop = 0;
            }
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
    // Audio Playback Tracking State Machine
    // ---------------------------------------------------
    const playsData = @json($attempt->audio_plays ?? []);
    const lockedTracks = {
        track_1: (playsData.track_1 || 0) >= 2,
        track_2: (playsData.track_2 || 0) >= 2,
        track_3: (playsData.track_3 || 0) >= 2
    };

    function registerAudioTracking(trackKey, audioEl, countBadgeEl, onPlaySuccess) {
        let isChecking = false;

        // Reset the started state on end or if rewound/seeked back to the beginning
        audioEl.addEventListener('ended', () => {
            audioEl.dataset.started = 'false';
            if (lockedTracks[trackKey]) {
                lockAudioEl(trackKey, audioEl);
            }
        });

        audioEl.addEventListener('seeked', () => {
            if (audioEl.currentTime === 0) {
                audioEl.dataset.started = 'false';
            }
        });

        audioEl.addEventListener('play', (e) => {
            if (lockedTracks[trackKey]) {
                audioEl.pause();
                audioEl.currentTime = 0;
                alert("This audio track has reached the maximum limit of 2 plays and is locked.");
                return;
            }

            // If it is a resume (unpausing without rewinding), do not count as a new play
            if (audioEl.dataset.started === 'true') {
                return;
            }

            if (isChecking) return;
            isChecking = true;

            fetch("{{ route('student.test.audio-play', $attempt) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ track: trackKey })
            })
            .then(res => res.json())
            .then(data => {
                isChecking = false;
                if (data.success) {
                    audioEl.dataset.started = 'true';
                    const plays = data.plays;
                    if (countBadgeEl) {
                        countBadgeEl.innerText = `(${plays}/2 plays)`;
                    }
                    if (trackKey === 'track_3') {
                        const otherBadge = document.getElementById(countBadgeEl.id === 'play-count-3' ? 'play-count-review' : 'play-count-3');
                        if (otherBadge) otherBadge.innerText = `(${plays}/2 plays)`;
                    }

                    if (plays >= 2) {
                        lockedTracks[trackKey] = true;
                    }
                    if (onPlaySuccess) onPlaySuccess(plays);
                } else {
                    audioEl.pause();
                    audioEl.currentTime = 0;
                    lockedTracks[trackKey] = true;
                    const plays = data.plays || 2;
                    if (countBadgeEl) {
                        countBadgeEl.innerText = `(${plays}/2 plays)`;
                    }
                    if (trackKey === 'track_3') {
                        const otherBadge = document.getElementById(countBadgeEl.id === 'play-count-3' ? 'play-count-review' : 'play-count-3');
                        if (otherBadge) otherBadge.innerText = `(${plays}/2 plays)`;
                    }
                    lockAudioEl(trackKey, audioEl);
                    alert("This audio track has reached the maximum limit of 2 plays and is locked.");
                }
            })
            .catch(err => {
                isChecking = false;
                console.error(err);
            });
        });
    }

    function lockAudioEl(trackKey, audioEl) {
        if (!audioEl) return;
        audioEl.pause();
        if (audioEl.id === 'listening-audio') {
            const playPauseBtn = document.getElementById('audio-play-pause-btn');
            if (playPauseBtn) {
                playPauseBtn.classList.add('opacity-40', 'pointer-events-none');
                playPauseBtn.disabled = true;
            }
            const seekSlider = document.getElementById('audio-seek-slider');
            if (seekSlider) {
                seekSlider.classList.add('opacity-40', 'pointer-events-none');
                seekSlider.disabled = true;
            }
        } else {
            audioEl.removeAttribute('controls');
        }
    }

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

        registerAudioTracking('track_3', audio, document.getElementById('play-count-review'));

        if (lockedTracks['track_3']) {
            lockAudioEl('track_3', audio);
        }

        playPauseBtn.addEventListener('click', () => {
            if (lockedTracks['track_3']) {
                alert("This audio track has reached the maximum limit of 2 plays and is locked.");
                return;
            }
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

        // Helpers for step status styling
        function markStepCompleted(cardEl, statusEl, badgeEl, plays) {
            statusEl.innerHTML = plays >= 2 ? '🔒 Locked (2/2 plays)' : '✅ Completed';
            statusEl.className = 'text-[10px] font-bold text-emerald-500 dark:text-emerald-400';
            cardEl.classList.remove('bg-brand-navy-dark/5', 'bg-brand-gold/5', 'border-brand-gold');
            cardEl.classList.add('bg-brand-card', 'border-brand-border', 'opacity-75');
            badgeEl.innerHTML = '✓';
            badgeEl.className = 'flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-500 text-white font-extrabold text-xs shrink-0';
        }

        function unlockStep(cardEl, audioEl, statusEl, badgeEl, plays) {
            cardEl.classList.remove('opacity-50', 'pointer-events-none');
            cardEl.classList.add('bg-brand-navy-dark/5', 'border-brand-gold');
            audioEl.setAttribute('controls', 'true');
            statusEl.innerText = plays >= 2 ? '🔒 Locked (2/2 plays)' : '⏳ Awaiting Playback';
            statusEl.className = 'text-[10px] font-bold text-brand-gold';
            badgeEl.className = 'flex h-6 w-6 items-center justify-center rounded-lg bg-brand-gold text-brand-navy-dark font-extrabold text-[11px] shrink-0';
            badgeEl.innerHTML = badgeEl.id.split('-').pop(); // reset badge step number
        }

        // Initialize Prep Flow on Page Load
        function initializePrepFlow() {
            audio2.removeAttribute('controls');
            audio3.removeAttribute('controls');

            const t1Plays = playsData.track_1 || 0;
            const t2Plays = playsData.track_2 || 0;
            const t3Plays = playsData.track_3 || 0;

            if (t1Plays >= 1) {
                markStepCompleted(card1, status1, badge1, t1Plays);
                if (t1Plays >= 2) lockAudioEl('track_1', audio1);
                unlockStep(card2, audio2, status2, badge2, t2Plays);
            } else {
                status1.innerText = '⏳ Awaiting Playback';
            }

            if (t1Plays >= 1 && t2Plays >= 1) {
                markStepCompleted(card2, status2, badge2, t2Plays);
                if (t2Plays >= 2) lockAudioEl('track_2', audio2);
                unlockStep(card3, audio3, status3, badge3, t3Plays);
            }

            if (t1Plays >= 1 && t2Plays >= 1 && t3Plays >= 1) {
                markStepCompleted(card3, status3, badge3, t3Plays);
                if (t3Plays >= 2) lockAudioEl('track_3', audio3);
                startQuizBtnContainer.style.display = 'block';
            }
        }

        // Register tracking
        registerAudioTracking('track_1', audio1, document.getElementById('play-count-1'), (plays) => {
            status1.innerText = '🔊 Playing...';
            card1.classList.remove('bg-brand-navy-dark/5');
            card1.classList.add('bg-brand-gold/5', 'border-brand-gold');
        });
        registerAudioTracking('track_2', audio2, document.getElementById('play-count-2'), (plays) => {
            status2.innerText = '🔊 Playing...';
            card2.classList.remove('bg-brand-navy-dark/5');
            card2.classList.add('bg-brand-gold/5', 'border-brand-gold');
        });
        registerAudioTracking('track_3', audio3, document.getElementById('play-count-3'), (plays) => {
            status3.innerText = '🔊 Playing...';
            card3.classList.remove('bg-brand-navy-dark/5');
            card3.classList.add('bg-brand-gold/5', 'border-brand-gold');
        });

        // Listen for ended events to unlock sequential steps
        audio1.addEventListener('ended', () => {
            const currentPlays = parseInt(document.getElementById('play-count-1').innerText.match(/\d+/)[0]) || 1;
            markStepCompleted(card1, status1, badge1, currentPlays);
            if (currentPlays >= 2) lockAudioEl('track_1', audio1);
            unlockStep(card2, audio2, status2, badge2, playsData.track_2 || 0);
        });

        audio2.addEventListener('ended', () => {
            const currentPlays = parseInt(document.getElementById('play-count-2').innerText.match(/\d+/)[0]) || 1;
            markStepCompleted(card2, status2, badge2, currentPlays);
            if (currentPlays >= 2) lockAudioEl('track_2', audio2);
            unlockStep(card3, audio3, status3, badge3, playsData.track_3 || 0);
        });

        audio3.addEventListener('ended', () => {
            const currentPlays = parseInt(document.getElementById('play-count-3').innerText.match(/\d+/)[0]) || 1;
            markStepCompleted(card3, status3, badge3, currentPlays);
            if (currentPlays >= 2) lockAudioEl('track_3', audio3);

            // Enable Start Quiz CTA Button
            startQuizBtnContainer.style.display = 'block';
            startQuizBtnContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            // If the student has listened to the last audio twice (the maximum limit),
            // start the exam directly/automatically without waiting.
            if (currentPlays >= 2) {
                if (startQuizBtn) {
                    startQuizBtn.click();
                }
            }
        });

        initializePrepFlow();

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
                        if (customAudio && !lockedTracks['track_3']) {
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

        // Real-time background AJAX saving
        const nameMatch = radio.name.match(/answers\[(\d+)\]/);
        const questionId = nameMatch ? nameMatch[1] : null;
        const optionId = radio.value;

        if (questionId && optionId) {
            fetch("{{ route('student.test.save-answer', $attempt) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    question_id: questionId,
                    option_id: optionId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to save answer:', data.error);
                }
            })
            .catch(err => {
                console.error('Error saving answer:', err);
            });
        }
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

