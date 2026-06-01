@extends('layouts.app')

@section('title', 'Exam Complete | LinguaBridge')

@section('content')
<div class="max-w-md mx-auto py-8">
    <div class="bg-brand-card rounded-2xl border border-brand-border shadow-sm overflow-hidden text-center p-8 border-t-4 border-brand-gold">
        
        <!-- Completion Check Icon (Emerald bg) -->
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/35 mb-6">
            <svg class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        <h1 class="text-2xl font-extrabold text-brand-text-dark tracking-tight">Evaluation Submitted</h1>
        <p class="mt-2 text-brand-text-muted text-sm font-semibold">Thank you, <strong>{{ $attempt->full_name }}</strong>.</p>
        
        <div class="mt-8 border-t border-brand-border pt-8">
            
            <!-- Message advising user to contact the instructor -->
            <div class="p-6 rounded-2xl bg-brand-bg border border-brand-border text-center shadow-2xs">
                <h3 class="font-bold text-brand-text-dark text-base mb-2 text-emerald-600 dark:text-emerald-500">Test Completed Successfully</h3>
                <p class="text-sm text-brand-text-muted leading-relaxed font-semibold">
                    Please contact the instructor to get your result.
                </p>
                <div class="mt-4 pt-4 border-t border-brand-border text-2xs text-brand-text-muted/75 font-semibold">
                    Registered Candidate: {{ $attempt->full_name }}<br>
                    Submission Time: {{ $attempt->finished_at ? $attempt->finished_at->format('M d, Y - H:i') : now()->format('M d, Y - H:i') }}
                </div>
            </div>

            <!-- Back to Portal Home (Forgets attempt session instantly for the next candidate) -->
            <div class="mt-8">
                <a href="{{ route('student.index', ['new_session' => 1]) }}" class="inline-flex items-center justify-center gap-2 text-sm font-bold bg-brand-navy hover:bg-brand-navy-dark text-white px-6 py-3.5 rounded-xl transition-all shadow-sm cursor-pointer w-full">
                    <svg class="h-4 w-4 text-brand-gold" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Back to Portal Home
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
