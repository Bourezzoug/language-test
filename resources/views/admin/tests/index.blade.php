@extends('layouts.app')

@section('title', 'Manage Tests')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-brand-text-dark tracking-tight">Placement Evaluations</h1>
            <p class="text-sm text-brand-text-muted mt-1">Configure active tests, customize sections, and edit questions.</p>
        </div>
        <div>
            <a href="{{ route('admin.tests.create') }}" class="inline-flex items-center gap-1 bg-brand-gold hover:bg-brand-gold-dark text-brand-navy-dark font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all text-sm cursor-pointer">
                + Create Placement Test
            </a>
        </div>
    </div>

    <!-- Tests Cards Grid -->
    <div class="grid gap-6 md:grid-cols-2">
        @forelse($tests as $test)
            <div class="flex flex-col justify-between p-6 bg-brand-card rounded-2xl border border-brand-border shadow-2xs hover:shadow-sm transition-all">
                
                <div>
                    <!-- Active Status Badge -->
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-semibold ring-1 ring-inset 
                            {{ $test->is_active ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-350 ring-emerald-600/20' : 'bg-brand-bg text-brand-text-muted ring-brand-border' }}">
                            {{ $test->is_active ? 'Active Intake' : 'Deactivated' }}
                        </span>
                        
                        <span class="text-xs text-brand-text-muted/70 font-semibold font-mono">ID: #{{ $test->id }}</span>
                    </div>

                    <!-- Title & Details -->
                    <h3 class="text-xl font-bold text-brand-text-dark mt-4">{{ $test->title }}</h3>
                    <p class="mt-2 text-sm text-brand-text-muted line-clamp-3">
                        {{ $test->description ?? 'No description provided.' }}
                    </p>

                    <!-- Indicators -->
                    <div class="mt-4 flex flex-wrap gap-3 text-xs font-medium text-brand-text-muted/80">
                        <span class="flex items-center gap-1 bg-brand-bg px-2 py-1 rounded-lg border border-brand-border">
                            {{ $test->sections_count }} Sections
                        </span>
                        <span class="flex items-center gap-1 bg-brand-bg px-2 py-1 rounded-lg border border-brand-border">
                            {{ $test->show_result_to_student ? 'Shows Student Results' : 'Hides Student Results' }}
                        </span>
                    </div>
                </div>

                <!-- Footer Control Buttons -->
                <div class="mt-6 border-t border-brand-border pt-4 flex flex-wrap items-center justify-between gap-3">
                    
                    <div class="flex items-center gap-2">
                        <!-- Sections configuration -->
                        <a href="{{ route('admin.tests.sections.index', $test) }}" class="inline-flex items-center gap-1 text-xs font-bold bg-brand-navy hover:bg-brand-navy-dark text-white border border-brand-gold/30 px-3 py-2 rounded-lg transition-all cursor-pointer">
                            Manage Sections & Questions
                        </a>
                        
                        <!-- Toggle Active Form -->
                        <form action="{{ route('admin.tests.toggle-active', $test) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-semibold px-3 py-2 rounded-lg border border-brand-border bg-brand-card hover:bg-brand-navy-dark/5 text-brand-text-dark transition-all cursor-pointer">
                                {{ $test->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Edit -->
                        <a href="{{ route('admin.tests.edit', $test) }}" class="text-xs font-semibold text-brand-text-muted hover:text-brand-text-dark p-2 transition-colors">
                            Edit
                        </a>

                        <!-- Delete -->
                        <form action="{{ route('admin.tests.destroy', $test) }}" method="POST" class="inline"
                            onsubmit="return confirm('Are you absolutely sure you want to delete this placement test? All associated sections, passages, questions, and attempts will be deleted permanently.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-rose-600 dark:text-rose-400 hover:text-rose-500 p-2 transition-colors">
                                Delete
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        @empty
            <div class="md:col-span-2 p-12 text-center rounded-2xl border-2 border-dashed border-brand-border bg-brand-card">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5h.008v.008H12V17Z" />
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-brand-text-dark">No Tests Configured</h3>
                <p class="mt-1 text-sm text-brand-text-muted">Create a new placement test to get started.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
