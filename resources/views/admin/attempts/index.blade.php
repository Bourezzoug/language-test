@extends('layouts.app')

@section('title', 'Candidates')

@section('content')
<div class="space-y-6">
    
    <!-- Sticky Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-brand-text-dark tracking-tight">Evaluated Candidates</h1>
            <p class="text-sm text-brand-text-muted mt-1">Review student performance, search logs, and export records.</p>
        </div>
        <div>
            <a href="{{ route('admin.attempts.export') }}" class="inline-flex items-center gap-1.5 bg-brand-navy hover:bg-brand-navy-dark text-white border border-brand-gold/30 hover:border-brand-gold/50 font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all text-sm cursor-pointer">
                <svg class="h-4 w-4 text-brand-gold" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export All to CSV
            </a>
        </div>
    </div>

    <!-- Filter and Search Box -->
    <div class="bg-brand-card rounded-2xl border border-brand-border p-4 shadow-sm">
        <form action="{{ route('admin.attempts.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
            
            <!-- Search Input -->
            <div class="grow w-full relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="block w-full rounded-lg bg-brand-card border border-brand-border pl-10 pr-3 py-2 text-brand-text-dark placeholder-slate-450 focus:border-brand-gold focus:outline-none focus:ring-1 focus:ring-brand-gold sm:text-sm"
                    placeholder="Search candidate by name, phone or email...">
            </div>

            <!-- Per Page Dropdown -->
            <div class="flex items-center gap-2 shrink-0 w-full md:w-auto">
                <label for="per_page" class="text-xs font-bold text-brand-text-dark shrink-0 uppercase tracking-wider">Per Page:</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()" 
                        class="rounded-lg bg-brand-card border border-brand-border px-3 py-2 text-brand-text-dark focus:border-brand-gold focus:outline-none focus:ring-1 focus:ring-brand-gold sm:text-sm cursor-pointer transition-all w-full md:w-auto">
                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 w-full md:w-auto shrink-0 justify-end">
                <button type="submit" class="bg-brand-navy hover:bg-brand-navy-dark text-white border border-brand-gold/30 hover:border-brand-gold/50 font-semibold px-5 py-2 rounded-lg text-sm transition-all cursor-pointer shadow-sm w-full md:w-auto">
                    Search
                </button>
                @if(request('search') || request('per_page'))
                    <a href="{{ route('admin.attempts.index') }}" class="bg-brand-card hover:bg-brand-navy-dark/5 text-brand-text-dark border border-brand-border font-semibold px-4 py-2 rounded-lg text-sm transition-all flex items-center justify-center w-full md:w-auto">
                        Clear
                    </a>
                @endif
            </div>
            
        </form>
    </div>

    <!-- Main Candidates Table -->
    <div class="bg-brand-card rounded-2xl border border-brand-border shadow-sm overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-brand-navy-dark/10 text-brand-text-muted/80 text-2xs uppercase tracking-wider font-bold border-b border-brand-border">
                        <th class="px-6 py-3 font-semibold">Candidate</th>
                        <th class="px-6 py-3 font-semibold">Active Test</th>
                        <th class="px-6 py-3 font-semibold">Score</th>
                        <th class="px-6 py-3 font-semibold">Placement Level</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Exam Session</th>
                        <th class="px-6 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-border text-sm">
                    @forelse($attempts as $attempt)
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
                            <!-- Session Duration -->
                            <td class="px-6 py-4 text-xs text-brand-text-muted font-semibold">
                                <div>Start: {{ $attempt->started_at ? $attempt->started_at->format('M d, H:i') : 'N/A' }}</div>
                                @if($attempt->finished_at)
                                    <div>Finish: {{ $attempt->finished_at->format('M d, H:i') }}</div>
                                @endif
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
                                No candidate attempts matched your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($attempts->hasPages() || $attempts->total() > 0)
            <div class="px-6 py-4 border-t border-brand-border bg-brand-navy-dark/5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-xs font-semibold text-brand-text-muted">
                    Showing <span class="text-brand-text-dark font-bold">{{ $attempts->firstItem() ?? 0 }}</span> to <span class="text-brand-text-dark font-bold">{{ $attempts->lastItem() ?? 0 }}</span> of <span class="text-brand-text-dark font-bold">{{ $attempts->total() }}</span> candidates
                </div>
                @if($attempts->hasPages())
                    <div class="shrink-0">
                        {{ $attempts->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        @endif

    </div>

</div>
@endsection
