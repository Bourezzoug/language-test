@extends('layouts.app')

@section('title', 'LinguaBridge Admin')

@section('content')
<div class="space-y-8">
    
    <!-- Header banner -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-brand-text-dark tracking-tight">Lingua<span class="text-brand-gold">Bridge</span> Admin</h1>
            <p class="text-sm text-brand-text-muted mt-1">Configure active placement evaluations, customize sections, and track candidate evaluations centrally.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tests.index') }}" class="inline-flex items-center gap-1.5 bg-brand-navy hover:bg-brand-navy-dark text-white border border-brand-gold/30 hover:border-brand-gold/50 font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all text-sm cursor-pointer">
                Manage Tests
            </a>
            <a href="{{ route('admin.attempts.export') }}" class="inline-flex items-center gap-1.5 bg-brand-card hover:bg-brand-navy-dark/5 text-brand-text-dark font-semibold px-4 py-2.5 rounded-xl border border-brand-border transition-all text-sm">
                <svg class="h-4 w-4 text-brand-gold" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export Results
            </a>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid gap-5 grid-cols-2 lg:grid-cols-4">
        
        <!-- Total Tests -->
        <div class="bg-brand-card rounded-2xl border border-brand-border p-5 shadow-sm border-t-2 border-brand-gold">
            <div class="flex items-center justify-between text-brand-text-muted/70">
                <span class="text-xs uppercase tracking-wider font-semibold">Total Tests</span>
                <svg class="h-5 w-5 text-brand-gold" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-brand-text-dark leading-none">{{ $stats['total_tests'] }}</span>
            </div>
        </div>

        <!-- Total Attempts -->
        <div class="bg-brand-card rounded-2xl border border-brand-border p-5 shadow-sm border-t-2 border-brand-gold">
            <div class="flex items-center justify-between text-brand-text-muted/70">
                <span class="text-xs uppercase tracking-wider font-semibold">Total Candidates</span>
                <svg class="h-5 w-5 text-brand-gold" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-brand-text-dark leading-none">{{ $stats['total_attempts'] }}</span>
            </div>
        </div>

        <!-- Completed attempts -->
        <div class="bg-brand-card rounded-2xl border border-brand-border p-5 shadow-sm border-t-2 border-brand-gold">
            <div class="flex items-center justify-between text-brand-text-muted/70">
                <span class="text-xs uppercase tracking-wider font-semibold">Completed Exams</span>
                <svg class="h-5 w-5 text-brand-gold" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-brand-text-dark leading-none">{{ $stats['completed_attempts'] }}</span>
                <span class="text-xs text-brand-text-muted font-medium">in progress: {{ $stats['in_progress_attempts'] }}</span>
            </div>
        </div>

        <!-- Average Score -->
        <div class="bg-brand-card rounded-2xl border border-brand-border p-5 shadow-sm border-t-2 border-brand-gold">
            <div class="flex items-center justify-between text-brand-text-muted/70">
                <span class="text-xs uppercase tracking-wider font-semibold">Average Completed Score</span>
                <svg class="h-5 w-5 text-brand-gold" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.198-.396.772-.396.97 0l2.582 5.24 5.79.842c.442.064.62.61.3.928l-4.187 4.08 1.01 5.765a.552.552 0 0 1-.799.58L12 18.23l-5.18 2.73a.552.552 0 0 1-.798-.58l1.01-5.765-4.188-4.08a.552.552 0 0 1 .3-.928l5.79-.842 2.583-5.24Z" />
                </svg>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-brand-text-dark leading-none">{{ $stats['average_score'] }}</span>
                <span class="text-xs text-brand-text-muted font-medium">out of 70</span>
            </div>
        </div>

    </div>

    <!-- Recent Candidate Attempts -->
    <div class="bg-brand-card rounded-2xl border border-brand-border shadow-sm overflow-hidden">
        
        <div class="border-b border-brand-border px-6 py-4 flex items-center justify-between bg-brand-navy-dark/5">
            <h2 class="text-lg font-bold text-brand-text-dark">Recent Candidates</h2>
            <a href="{{ route('admin.attempts.index') }}" class="text-xs font-bold bg-brand-gold hover:bg-brand-gold-dark text-brand-navy-dark px-3 py-1.5 rounded-lg transition-all shadow-2xs cursor-pointer">
                View All &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-brand-navy-dark/10 text-brand-text-muted/80 text-2xs uppercase tracking-wider font-bold border-b border-brand-border">
                        <th class="px-6 py-3 font-semibold">Candidate</th>
                        <th class="px-6 py-3 font-semibold">Active Test</th>
                        <th class="px-6 py-3 font-semibold">Score</th>
                        <th class="px-6 py-3 font-semibold">Placement Level</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Date</th>
                        <th class="px-6 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-border text-sm">
                    @forelse($recentAttempts as $attempt)
                        <tr class="hover:bg-brand-navy-dark/5">
                            <!-- Candidate Details -->
                            <td class="px-6 py-4">
                                <div class="font-semibold text-brand-text-dark">{{ $attempt->full_name }}</div>
                                <div class="text-xs text-brand-text-muted/80">{{ $attempt->email ?? 'No Email' }} • {{ $attempt->phone ?? 'No Phone' }}</div>
                            </td>
                            <!-- Test -->
                            <td class="px-6 py-4 text-brand-text-muted font-medium">
                                {{ $attempt->test->title ?? 'N/A' }}
                            </td>
                            <!-- Score -->
                            <td class="px-6 py-4 font-mono font-bold text-brand-text-dark">
                                @if($attempt->status === 'completed')
                                    {{ $attempt->score }} <span class="text-brand-text-muted/70 font-normal">/ {{ $attempt->total_questions }}</span>
                                @else
                                    <span class="text-brand-text-muted/50 font-normal">--</span>
                                @endif
                            </td>
                            <!-- Placement Level -->
                            <td class="px-6 py-4">
                                @if($attempt->status === 'completed')
                                    <span class="inline-flex items-center rounded-md bg-brand-gold/10 px-2 py-1 text-xs font-semibold text-brand-text-dark border border-brand-gold/30">
                                        {{ $attempt->recommended_level }}
                                    </span>
                                @else
                                    <span class="text-brand-text-muted/50">-</span>
                                @endif
                            </td>
                            <!-- Status -->
                            <td class="px-6 py-4">
                                @if($attempt->status === 'completed')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 dark:bg-emerald-950/40 px-2 py-1 text-xs font-medium text-emerald-700 dark:text-emerald-350 ring-1 ring-inset ring-emerald-600/10">
                                        Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-50 dark:bg-amber-950/40 px-2 py-1 text-xs font-medium text-amber-700 dark:text-amber-350 ring-1 ring-inset ring-amber-600/10">
                                        In Progress
                                    </span>
                                @endif
                            </td>
                            <!-- Date -->
                            <td class="px-6 py-4 text-brand-text-muted/80 text-xs font-semibold">
                                {{ $attempt->created_at->format('M d, Y H:i') }}
                            </td>
                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.attempts.show', $attempt) }}" class="inline-flex items-center gap-1 text-xs font-bold text-brand-text-dark hover:text-brand-gold transition-colors">
                                    Review Details &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-brand-text-muted">
                                No candidate attempts registered yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
