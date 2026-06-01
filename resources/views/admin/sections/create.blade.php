@extends('layouts.app')

@section('title', 'Create Section')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Header -->
        <div class="bg-slate-900 px-6 py-5 text-white flex items-center justify-between">
            <h2 class="text-xl font-bold">Create Test Section</h2>
            <a href="{{ route('admin.tests.sections.index', $test) }}" class="text-xs font-semibold text-slate-400 hover:text-white">
                Cancel
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.tests.sections.store', $test) }}" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- Test Title Display -->
            <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs text-slate-500 font-medium">
                Test: <span class="text-slate-800 font-bold">{{ $test->title }}</span>
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700">Section Title <span class="text-rose-500">*</span></label>
                <div class="mt-1">
                    <input type="text" name="title" id="title" required value="{{ old('title') }}"
                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Section I: Listening">
                </div>
                @error('title')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type select -->
            <div>
                <label for="type" class="block text-sm font-semibold text-slate-700">Section Type <span class="text-rose-500">*</span></label>
                <div class="mt-1">
                    <select name="type" id="type" required
                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                        <option value="" disabled selected>Select section type...</option>
                        <option value="listening" {{ old('type') === 'listening' ? 'selected' : '' }}>Listening Section</option>
                        <option value="reading" {{ old('type') === 'reading' ? 'selected' : '' }}>Reading Section</option>
                        <option value="language_use" {{ old('type') === 'language_use' ? 'selected' : '' }}>Language Use Section</option>
                    </select>
                </div>
                @error('type')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Duration minutes -->
            <div>
                <label for="duration_minutes" class="block text-sm font-semibold text-slate-700">Duration <span class="text-rose-500">*</span> <span class="text-slate-400 font-normal">(in minutes)</span></label>
                <div class="mt-1">
                    <input type="number" name="duration_minutes" id="duration_minutes" required min="1" value="{{ old('duration_minutes', 15) }}"
                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                </div>
                @error('duration_minutes')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Instructions -->
            <div>
                <label for="instructions" class="block text-sm font-semibold text-slate-700">Section Instructions <span class="text-slate-400 font-normal">(Optional)</span></label>
                <div class="mt-1">
                    <textarea name="instructions" id="instructions" rows="4"
                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Instructions displayed on top of student page when taking this section...">{{ old('instructions') }}</textarea>
                </div>
                @error('instructions')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Order -->
            <div>
                <label for="order" class="block text-sm font-semibold text-slate-700">Order Position <span class="text-rose-500">*</span> <span class="text-slate-400 font-normal">(sequential ordering)</span></label>
                <div class="mt-1">
                    <input type="number" name="order" id="order" required min="0" value="{{ old('order', 1) }}"
                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                </div>
                @error('order')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                <a href="{{ route('admin.tests.sections.index', $test) }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700 px-4 py-2">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-6 py-2 rounded-xl shadow-sm transition-all text-sm">
                    Create Section
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
