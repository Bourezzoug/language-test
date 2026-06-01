@extends('layouts.app')

@section('title', 'Edit Reading Passage')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Header -->
        <div class="bg-slate-900 px-6 py-5 text-white flex items-center justify-between">
            <h2 class="text-xl font-bold">Edit Reading Passage</h2>
            <a href="{{ route('admin.sections.questions.index', $passage->test_section_id) }}" class="text-xs font-semibold text-slate-400 hover:text-white">
                Cancel
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.passages.update', $passage) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Section context -->
            <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs text-slate-500 font-medium">
                Test: <span class="text-slate-800 font-semibold">{{ $passage->section->test->title }}</span> • 
                Section: <span class="text-slate-800 font-bold">{{ $passage->section->title }}</span>
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700">Passage Title <span class="text-slate-400 font-normal">(Optional)</span></label>
                <div class="mt-1">
                    <input type="text" name="title" id="title" value="{{ old('title', $passage->title) }}"
                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Passage 1: Greetings from Florida!">
                </div>
                @error('title')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-semibold text-slate-700">Passage Content <span class="text-rose-500">*</span></label>
                <div class="mt-1">
                    <textarea name="content" id="content" rows="12" required
                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm font-sans"
                        placeholder="Type or paste reading passage text here...">{{ old('content', $passage->content) }}</textarea>
                </div>
                @error('content')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Order -->
            <div>
                <label for="order" class="block text-sm font-semibold text-slate-700">Order Position <span class="text-rose-500">*</span></label>
                <div class="mt-1">
                    <input type="number" name="order" id="order" required min="0" value="{{ old('order', $passage->order) }}"
                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                </div>
                @error('order')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                <a href="{{ route('admin.sections.questions.index', $passage->test_section_id) }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700 px-4 py-2">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-6 py-2 rounded-xl shadow-sm transition-all text-sm">
                    Save Changes
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
