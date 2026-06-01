@extends('layouts.app')

@section('title', 'Manage Sections: ' . $test->title)

@section('content')
<div class="space-y-6">

    <!-- Header navigation and back -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <a href="{{ route('admin.tests.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700 flex items-center gap-1">
                &larr; Back to Evaluations
            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Manage Test Sections</h1>
            <p class="text-sm text-slate-500 mt-1">Test Title: <span class="font-bold text-slate-800">{{ $test->title }}</span></p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('admin.tests.sections.create', $test) }}" class="inline-flex items-center gap-1 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all text-sm">
                + Create Section
            </a>
        </div>
    </div>

    <!-- Sections List -->
    <div class="space-y-4">
        @forelse($test->sections as $section)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-5 hover:shadow-xs transition-all">
                
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    
                    <div class="space-y-1.5 grow">
                        
                        <div class="flex items-center gap-2">
                            <!-- Type badge -->
                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold ring-1 ring-inset 
                                {{ $section->type === 'listening' ? 'bg-indigo-50 text-indigo-700 ring-indigo-600/20' : ($section->type === 'reading' ? 'bg-violet-50 text-violet-700 ring-violet-600/20' : 'bg-amber-50 text-amber-700 ring-amber-600/20') }}">
                                {{ ucfirst(str_replace('_', ' ', $section->type)) }}
                            </span>
                            
                            <!-- Order badge -->
                            <span class="text-xs text-slate-400 font-semibold">Order: #{{ $section->order }}</span>
                            
                            <!-- Duration badge -->
                            <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-semibold flex items-center gap-0.5">
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                {{ $section->duration_minutes }} minutes
                            </span>
                        </div>

                        <!-- Section Title -->
                        <h3 class="text-lg font-bold text-slate-900">{{ $section->title }}</h3>
                        
                        @if($section->instructions)
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                {{ $section->instructions }}
                            </p>
                        @endif

                    </div>

                    <!-- Actions Panel -->
                    <div class="flex sm:flex-col items-end gap-2 shrink-0 border-t sm:border-t-0 border-slate-100 pt-3 sm:pt-0">
                        
                        <!-- Manage Content -->
                        <a href="{{ route('admin.sections.questions.index', $section) }}" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-500 bg-indigo-50 hover:bg-indigo-100/60 px-3 py-2 rounded-lg transition-all w-full justify-center">
                            Manage Questions & Passages
                        </a>

                        <div class="flex items-center gap-2 mt-1">
                            <!-- Edit -->
                            <a href="{{ route('admin.sections.edit', $section) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 p-2">
                                Edit Section
                            </a>
                            
                            <!-- Delete -->
                            <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" class="inline"
                                onsubmit="return confirm('Are you sure you want to delete this section? All associated reading passages, listening situations, questions, options, and candidate answers will be permanently deleted.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-500 p-2">
                                    Delete
                                </button>
                            </form>
                        </div>

                    </div>

                </div>

            </div>
        @empty
            <div class="p-12 text-center rounded-2xl border-2 border-dashed border-slate-200 bg-white">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5h.008v.008H12V17Z" />
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-slate-900">No Sections Configured</h3>
                <p class="mt-1 text-sm text-slate-500">Add a new section (Listening, Reading, Language Use) to get started.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
