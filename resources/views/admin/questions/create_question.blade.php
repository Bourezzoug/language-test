@extends('layouts.app')

@section('title', 'Add Question')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Header -->
        <div class="bg-slate-900 px-6 py-5 text-white flex items-center justify-between">
            <h2 class="text-xl font-bold">Add Question Item</h2>
            <a href="{{ route('admin.sections.questions.index', $section) }}" class="text-xs font-semibold text-slate-400 hover:text-white">
                Cancel
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.sections.questions.store', $section) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            <!-- Section context -->
            <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs text-slate-500 font-medium">
                Test: <span class="text-slate-800 font-semibold">{{ $section->test->title }}</span> • 
                Section: <span class="text-slate-800 font-bold">{{ $section->title }}</span> •
                Type: <span class="text-slate-800 font-semibold uppercase">{{ $section->type }}</span>
            </div>

            <!-- Question Number & Order -->
            <div class="grid gap-4 grid-cols-2">
                <div>
                    <label for="question_number" class="block text-sm font-semibold text-slate-700">Question Number <span class="text-rose-500">*</span></label>
                    <div class="mt-1">
                        <input type="number" name="question_number" id="question_number" required min="1" value="{{ old('question_number', $section->questions()->max('question_number') + 1) }}"
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    @error('question_number')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="order" class="block text-sm font-semibold text-slate-700">Order Position <span class="text-rose-500">*</span></label>
                    <div class="mt-1">
                        <input type="number" name="order" id="order" required min="0" value="{{ old('order', $section->questions()->max('order') + 1) }}"
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    @error('order')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Reading Passage Link (Only shown for Reading sections) -->
            @if($section->type === 'reading')
                <div>
                    <label for="passage_id" class="block text-sm font-semibold text-slate-700">Link Reading Passage <span class="text-rose-500">*</span></label>
                    <div class="mt-1">
                        <select name="passage_id" id="passage_id" required
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                            <option value="" disabled selected>Select parent passage...</option>
                            @foreach($section->passages as $passage)
                                <option value="{{ $passage->id }}" {{ old('passage_id') == $passage->id ? 'selected' : '' }}>
                                    {{ $passage->title ?? 'Untitled Reading Passage' }} (ID: #{{ $passage->id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('passage_id')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Listening Context Fields (Only shown for Listening sections) -->
            @if($section->type === 'listening')
                <!-- Situation Description -->
                <div>
                    <label for="situation" class="block text-sm font-semibold text-slate-700">Situation Prompt <span class="text-slate-400 font-normal">(Optional)</span></label>
                    <div class="mt-1">
                        <input type="text" name="situation" id="situation" value="{{ old('situation') }}"
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Situation 1: Emily and Jason are talking about work.">
                    </div>
                    @error('situation')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Audio Upload -->
                <div>
                    <label for="audio" class="block text-sm font-semibold text-slate-700">Situation Audio File <span class="text-slate-400 font-normal">(Optional, MP3/WAV)</span></label>
                    <div class="mt-1">
                        <input type="file" name="audio" id="audio" accept="audio/*"
                            class="block w-full rounded-lg border border-slate-300 px-3 py-1.5 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    @error('audio')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Question Text -->
            <div>
                <label for="question_text" class="block text-sm font-semibold text-slate-700">Question Prompt / Text <span class="text-rose-500">*</span></label>
                <div class="mt-1">
                    <textarea name="question_text" id="question_text" rows="3" required
                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Type question text here (e.g. 'Emily .........')">{{ old('question_text') }}</textarea>
                </div>
                @error('question_text')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Answer Options A/B/C/D Form Fields -->
            <div class="space-y-4 pt-2 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900">Answer Options</h3>
                    <span class="text-xs text-slate-400 font-semibold">Mark correct option using the indicator</span>
                </div>

                @php
                    $labels = ['a', 'b', 'c', 'd'];
                @endphp

                <div class="space-y-3">
                    @foreach($labels as $i => $label)
                        <div class="flex items-center gap-3 bg-slate-50 border border-slate-150 p-3 rounded-xl">
                            
                            <!-- Hidden label -->
                            <input type="hidden" name="options[{{ $i }}][label]" value="{{ $label }}">

                            <!-- Correct Option Radio Indicator -->
                            <label class="flex items-center gap-1 cursor-pointer shrink-0" title="Mark as correct answer">
                                <input type="radio" name="correct_option_label" value="{{ $label }}" required 
                                    {{ old('correct_option_label') === $label || ($i === 0 && !old('correct_option_label')) ? 'checked' : '' }}
                                    class="rounded-full border-slate-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4">
                            </label>

                            <!-- Option label badge letter -->
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-300 bg-white text-slate-700 font-bold uppercase shrink-0 text-xs">
                                {{ $label }}
                            </span>

                            <!-- Option Text input -->
                            <input type="text" name="options[{{ $i }}][option_text]" required 
                                value="{{ old("options.{$i}.option_text") }}"
                                class="block w-full rounded-lg border border-slate-300 px-3 py-1.5 text-slate-850 shadow-2xs focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-xs"
                                placeholder="Type option choice text...">

                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                <a href="{{ route('admin.sections.questions.index', $section) }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700 px-4 py-2">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-6 py-2 rounded-xl shadow-sm transition-all text-sm">
                    Create Question
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
