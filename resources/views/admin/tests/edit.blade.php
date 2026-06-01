@extends('layouts.app')

@section('title', 'Edit Test: ' . $test->title)

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Header -->
        <div class="bg-slate-900 px-6 py-5 text-white flex items-center justify-between">
            <h2 class="text-xl font-bold">Edit Placement Test</h2>
            <a href="{{ route('admin.tests.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white">
                Cancel
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.tests.update', $test) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700">Test Title <span class="text-rose-500">*</span></label>
                <div class="mt-1">
                    <input type="text" name="title" id="title" required value="{{ old('title', $test->title) }}"
                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                        placeholder="English Placement Test - EVOLVE">
                </div>
                @error('title')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700">Description <span class="text-slate-400 font-normal">(Optional)</span></label>
                <div class="mt-1">
                    <textarea name="description" id="description" rows="4"
                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Evaluation description and details...">{{ old('description', $test->description) }}</textarea>
                </div>
                @error('description')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Checkbox parameters -->
            <div class="space-y-3 pt-2">
                <!-- is_active -->
                <label class="flex items-start gap-3 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" value="1" {{ $test->is_active ? 'checked' : '' }} class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <span class="block text-sm font-semibold text-slate-800">Activate Intake Session</span>
                        <span class="block text-xs text-slate-400 mt-0.5">Students can select and start this test from the portal home page.</span>
                    </div>
                </label>

                <!-- show_result_to_student -->
                <label class="flex items-start gap-3 cursor-pointer select-none">
                    <input type="checkbox" name="show_result_to_student" value="1" {{ $test->show_result_to_student ? 'checked' : '' }} class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <span class="block text-sm font-semibold text-slate-800">Display Evaluation Results to Student</span>
                        <span class="block text-xs text-slate-400 mt-0.5">Students can see their final score and recommended placement level immediately on completion.</span>
                    </div>
                </label>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                <a href="{{ route('admin.tests.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700 px-4 py-2">
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
