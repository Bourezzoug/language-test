@extends('layouts.app')

@section('title', 'Review Candidate: ' . $attempt->full_name)

@section('content')
<div class="space-y-8">

    <!-- Header navigation and back -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <a href="{{ route('admin.attempts.index') }}" class="text-xs font-semibold text-brand-text-muted hover:text-brand-text-dark flex items-center gap-1">
                &larr; Back to Candidates List
            </a>
            <h1 class="text-3xl font-extrabold text-brand-text-dark tracking-tight mt-1">Review Candidate Evaluation</h1>
        </div>
        <div class="shrink-0 text-brand-text-muted font-mono text-xs font-semibold bg-brand-card border border-brand-border px-3 py-1.5 rounded-lg shadow-2xs">
            Attempt ID: #{{ $attempt->id }}
        </div>
    </div>

    <!-- Candidate Summary Panel Card -->
    <div class="grid gap-6 md:grid-cols-12 bg-brand-card rounded-2xl border border-brand-border shadow-sm overflow-hidden p-6">
        
        <!-- Details Column -->
        <div class="md:col-span-8 space-y-4">
            <h2 class="text-2xl font-extrabold text-brand-text-dark">{{ $attempt->full_name }}</h2>
            
            <div class="grid gap-4 sm:grid-cols-2 text-sm">
                <div>
                    <span class="block text-xs uppercase font-semibold text-brand-text-muted/70">Email Address</span>
                    <span class="text-brand-text-dark font-medium">{{ $attempt->email ?? 'Not Registered' }}</span>
                </div>
                <div>
                    <span class="block text-xs uppercase font-semibold text-brand-text-muted/70">Phone Number</span>
                    <span class="text-brand-text-dark font-medium">{{ $attempt->phone ?? 'Not Registered' }}</span>
                </div>
                <div>
                    <span class="block text-xs uppercase font-semibold text-brand-text-muted/70">Started At</span>
                    <span class="text-brand-text-dark font-medium">{{ $attempt->started_at ? $attempt->started_at->format('M d, Y H:i:s') : 'N/A' }}</span>
                </div>
                <div>
                    <span class="block text-xs uppercase font-semibold text-brand-text-muted/70">Finished At</span>
                    <span class="text-brand-text-dark font-medium">
                        {{ $attempt->finished_at ? $attempt->finished_at->format('M d, Y H:i:s') : 'In Progress' }}
                        @if($attempt->started_at && $attempt->finished_at)
                            <span class="text-xs text-brand-text-muted/80 font-normal">({{ round(abs($attempt->finished_at->diffInMinutes($attempt->started_at))) }} mins elapsed)</span>
                        @endif
                    </span>
                </div>
            </div>
            
            <div class="pt-2">
                <span class="block text-xs uppercase font-semibold text-brand-text-muted/70 mb-1">Attempt Status</span>
                @if($attempt->status === 'completed')
                    <span class="inline-flex items-center rounded-full bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-350 border border-emerald-100 dark:border-emerald-800/40 shadow-2xs">
                        Completed & Evaluated
                    </span>
                @else
                    <span class="inline-flex items-center rounded-full bg-amber-50 dark:bg-amber-950/40 px-3 py-1 text-xs font-semibold text-amber-700 dark:text-amber-350 border border-amber-100 dark:border-amber-800/40 shadow-2xs">
                        Exam Session Active / In Progress
                    </span>
                @endif
            </div>
        </div>

        <!-- Evaluation Results Column -->
        <div class="md:col-span-4 flex flex-col items-center justify-center p-6 bg-brand-bg border border-brand-border rounded-xl">
            @if($attempt->status === 'completed')
                <span class="text-xs uppercase font-semibold text-brand-text-muted/80 tracking-wider">Placement Score</span>
                <div class="mt-2 text-4xl font-black text-brand-text-dark">
                    {{ $attempt->score }} <span class="text-brand-text-muted/80 text-lg font-normal">/ {{ $attempt->total_questions }}</span>
                </div>
                
                <span class="text-xs text-brand-text-muted/80 font-semibold mt-1">Accuracy: {{ round(($attempt->score / max(1, $attempt->total_questions)) * 100) }}%</span>

                <div class="mt-5 px-5 py-2 bg-brand-navy text-white text-sm font-bold tracking-tight rounded-xl shadow-sm text-center border border-brand-gold/30">
                    {{ $attempt->recommended_level }}
                </div>
            @else
                <div class="text-center text-brand-text-muted text-sm py-4">
                    <svg class="mx-auto h-8 w-8 text-brand-gold animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1 1 21.222 13h-2.222" />
                    </svg>
                    <p class="mt-2 font-medium">Candidate is currently taking the exam.</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Diagnostic Answer Sheets Section -->
    @if($attempt->status === 'completed')
        <div>
            <h2 class="text-2xl font-bold text-brand-text-dark mb-6">Diagnostic Evaluation Sheet</h2>

            <div class="space-y-8">
                @php
                    // Group submitted answers by section
                    $answersBySection = $attempt->answers->groupBy(function($ans) {
                        return $ans->question->section->title ?? 'General Section';
                    });
                @endphp

                @forelse($answersBySection as $sectionTitle => $answers)
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-brand-text-dark border-b border-brand-border pb-2 flex items-center justify-between">
                            <span>{{ $sectionTitle }}</span>
                            <span class="text-xs bg-brand-bg text-brand-text-muted border border-brand-border px-2 py-0.5 rounded-full font-semibold">
                                {{ $answers->where('is_correct', true)->count() }} / {{ $answers->count() }} Correct
                            </span>
                        </h3>

                        <div class="grid gap-6">
                            @foreach($answers as $ans)
                                @php
                                    $q = $ans->question;
                                    $selectedOpt = $ans->option;
                                @endphp
                                <div class="bg-brand-card rounded-2xl border border-brand-border p-5 shadow-2xs space-y-3">
                                    
                                    <!-- Context indicators -->
                                    @if($q->passage)
                                        <div class="text-2xs text-brand-text-muted/70 font-bold uppercase tracking-wider">
                                            Passage: {{ $q->passage->title ?? 'Reading Passage' }}
                                        </div>
                                    @endif
                                    @if($q->situation)
                                        <div class="text-2xs text-brand-text-muted font-bold bg-brand-bg border-l-2 border-brand-gold px-2 py-0.5 rounded-r">
                                            Situation: {{ $q->situation }}
                                        </div>
                                    @endif

                                    <!-- Question Text -->
                                    <h4 class="text-sm font-bold text-brand-text-dark flex items-start gap-2">
                                        <span class="flex h-5 w-5 items-center justify-center rounded bg-brand-navy-dark dark:bg-brand-navy text-white font-mono text-2xs font-semibold shrink-0">
                                            {{ $q->question_number }}
                                        </span>
                                        <span>{{ $q->question_text }}</span>
                                    </h4>

                                    <!-- Options Checklist breakdown -->
                                    <div class="grid gap-2 sm:grid-cols-2 pt-1">
                                        @foreach($q->options as $opt)
                                            @php
                                                $isSelected = $selectedOpt && $selectedOpt->id === $opt->id;
                                                $isCorrect = $opt->is_correct;
                                                
                                                // Border/Background styling based on evaluation
                                                $styleClass = 'border-brand-border bg-brand-navy-dark/5';
                                                if ($isSelected) {
                                                    $styleClass = $isCorrect ? 'border-emerald-250 dark:border-emerald-800 bg-emerald-50/40 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-350' : 'border-rose-250 dark:border-rose-800 bg-rose-50/40 dark:bg-rose-950/20 text-rose-800 dark:text-rose-350';
                                                } elseif ($isCorrect) {
                                                    $styleClass = 'border-emerald-100 dark:border-emerald-900 bg-emerald-50/10 dark:bg-emerald-950/5 text-emerald-800 dark:text-emerald-350';
                                                }
                                            @endphp
                                            <div class="flex items-center justify-between p-3 rounded-lg border text-xs font-semibold {{ $styleClass }}">
                                                <div class="flex items-center gap-2">
                                                    <span class="flex h-5 w-5 items-center justify-center rounded font-bold uppercase shrink-0 border 
                                                        {{ $isSelected ? ($isCorrect ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-rose-600 border-rose-600 text-white') : ($isCorrect ? 'bg-emerald-100 dark:bg-emerald-900/50 border-emerald-300 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300' : 'bg-brand-bg border-brand-border text-brand-text-muted') }}">
                                                        {{ $opt->label }}
                                                    </span>
                                                    <span class="text-brand-text-dark">{{ $opt->option_text }}</span>
                                                </div>
                                                
                                                <!-- Icon markers -->
                                                <div class="flex items-center gap-1.5 shrink-0">
                                                    @if($isSelected)
                                                        @if($isCorrect)
                                                            <span class="text-2xs bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-350 px-1.5 py-0.5 rounded font-bold uppercase tracking-wide">Candidate Choice</span>
                                                        @else
                                                            <span class="text-2xs bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-350 px-1.5 py-0.5 rounded font-bold uppercase tracking-wide">Incorrect Choice</span>
                                                        @endif
                                                    @elseif($isCorrect)
                                                        <span class="text-2xs bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-350 px-1.5 py-0.5 rounded font-bold uppercase tracking-wide">Correct Answer</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-brand-text-muted bg-brand-card rounded-xl border border-brand-border">
                        No answers were logged for this attempt.
                    </div>
                @endforelse
            </div>
        </div>
    @endif

</div>
@endsection
