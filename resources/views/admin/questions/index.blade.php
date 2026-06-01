@extends('layouts.app')

@section('title', 'Configure Content: ' . $section->title)

@section('content')
<div class="space-y-6">

    <!-- Header navigation and back -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <a href="{{ route('admin.tests.sections.index', $section->test_id) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700 flex items-center gap-1">
                &larr; Back to Test Sections
            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Configure Section Content</h1>
            <p class="text-sm text-slate-500 mt-1">
                Test: <span class="font-semibold">{{ $section->test->title }}</span> • 
                Section: <span class="font-bold text-slate-800">{{ $section->title }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            @if($section->type === 'reading')
                <a href="{{ route('admin.sections.passages.create', $section) }}" class="inline-flex items-center gap-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-4 py-2.5 rounded-xl border border-slate-200 transition-all text-sm">
                    + Add Reading Passage
                </a>
            @endif
            <a href="{{ route('admin.sections.questions.create', $section) }}" class="inline-flex items-center gap-1 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all text-sm">
                + Add Question
            </a>
        </div>
    </div>

    <!-- ---------------------------------------------------
         PASSAGES GRID (Only shown in Reading sections)
         --------------------------------------------------- -->
    @if($section->type === 'reading')
        <div class="space-y-4">
            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2 border-b border-slate-200 pb-2">
                <span class="flex h-6 w-6 items-center justify-center rounded-md bg-indigo-600 text-white font-mono text-xs font-bold">R</span>
                Reading Passages
            </h2>
            
            @if($section->passages->isEmpty())
                <div class="p-8 text-center rounded-2xl border-2 border-dashed border-slate-200 bg-white">
                    <p class="text-sm text-slate-500">No reading passages configured. Create one before attaching questions!</p>
                </div>
            @else
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach($section->passages as $passage)
                        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs hover:shadow-xs transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between text-xs text-slate-400 font-semibold font-mono">
                                    <span>Passage Order: #{{ $passage->order }}</span>
                                    <span>ID: #{{ $passage->id }}</span>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 mt-2">{{ $passage->title ?? 'Untitled Reading Passage' }}</h3>
                                <p class="text-xs text-slate-500 line-clamp-4 mt-2 leading-relaxed whitespace-pre-line">
                                    {{ $passage->content }}
                                </p>
                            </div>
                            
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-end gap-2 text-xs">
                                <a href="{{ route('admin.passages.edit', $passage) }}" class="font-semibold text-indigo-600 hover:text-indigo-500 p-2">
                                    Edit Passage
                                </a>
                                <form action="{{ route('admin.passages.destroy', $passage) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this passage? All questions attached to this passage will lose their parent passage association.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-bold text-rose-600 hover:text-rose-500 p-2">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- ---------------------------------------------------
         QUESTIONS LIST GRID
         --------------------------------------------------- -->
    <div class="space-y-4">
        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2 border-b border-slate-200 pb-2">
            <span class="flex h-6 w-6 items-center justify-center rounded-md bg-slate-900 text-white font-mono text-xs font-bold">Q</span>
            Question Items
        </h2>

        @if($section->questions->isEmpty())
            <div class="p-12 text-center rounded-2xl border-2 border-dashed border-slate-200 bg-white">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5h.008v.008H12V17Z" />
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-slate-900">No Questions Configured</h3>
                <p class="mt-1 text-sm text-slate-500">Create a question item inside this section to begin loading evaluation data.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($section->questions as $question)
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs hover:shadow-xs transition-all">
                        
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            
                            <div class="grow space-y-3">
                                
                                <!-- Meta headers -->
                                <div class="flex flex-wrap items-center gap-2 text-2xs text-slate-400 font-semibold font-mono">
                                    <span>Question Number: {{ $question->question_number }}</span>
                                    <span>•</span>
                                    <span>Order: #{{ $question->order }}</span>
                                    <span>•</span>
                                    <span>ID: #{{ $question->id }}</span>
                                    
                                    @if($question->passage)
                                        <span>•</span>
                                        <span class="text-indigo-600">Linked Passage: {{ $question->passage->title ?? 'Passage' }}</span>
                                    @endif
                                    
                                    @if($question->audio_path)
                                        <span>•</span>
                                        <span class="text-emerald-600 flex items-center gap-0.5">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                                            </svg>
                                            Audio Linked
                                        </span>
                                    @endif
                                </div>

                                <!-- Situation -->
                                @if($question->situation)
                                    <div class="px-3 py-1 bg-slate-50 border-l-2 border-slate-700 rounded-r text-xs text-slate-700 font-semibold">
                                        Situation: {{ $question->situation }}
                                    </div>
                                @endif

                                <!-- Question Text -->
                                <h4 class="text-base font-bold text-slate-900 flex items-start gap-2">
                                    <span class="flex h-6 w-6 items-center justify-center rounded bg-slate-900 text-white font-mono text-xs font-semibold shrink-0">
                                        {{ $question->question_number }}
                                    </span>
                                    <span class="pt-0.5">{{ $question->question_text }}</span>
                                </h4>

                                <!-- Option Choices List -->
                                <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4 pt-1">
                                    @foreach($question->options as $opt)
                                        <div class="flex items-center gap-2 p-2.5 rounded-lg border text-xs font-semibold 
                                            {{ $opt->is_correct ? 'border-emerald-200 bg-emerald-50/30 text-emerald-800' : 'border-slate-100 bg-slate-50/50 text-slate-700' }}">
                                            <span class="flex h-5 w-5 items-center justify-center rounded font-bold uppercase shrink-0 border 
                                                {{ $opt->is_correct ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-slate-200 text-slate-500' }}">
                                                {{ $opt->label }}
                                            </span>
                                            <span class="truncate">{{ $opt->option_text }}</span>
                                        </div>
                                    @endforeach
                                </div>

                            </div>

                            <!-- Action buttons -->
                            <div class="flex items-center gap-2 md:flex-col md:items-end justify-end shrink-0 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">
                                <a href="{{ route('admin.questions.edit', $question) }}" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 p-2 bg-slate-50 hover:bg-slate-100 rounded-lg transition-all shrink-0">
                                    Edit Question
                                </a>
                                
                                <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this question? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-500 p-2 bg-rose-50 hover:bg-rose-100 rounded-lg transition-all shrink-0">
                                        Delete
                                    </button>
                                </form>
                            </div>

                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>

</div>
@endsection
