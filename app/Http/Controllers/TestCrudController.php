<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestCrudController extends Controller
{
    /**
     * Display a listing of tests.
     */
    public function index()
    {
        $tests = Test::withCount('sections')->orderBy('created_at', 'desc')->get();
        return view('admin.tests.index', compact('tests'));
    }

    /**
     * Show the form for creating a new test.
     */
    public function create()
    {
        return view('admin.tests.create');
    }

    /**
     * Store a newly created test in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'show_result_to_student' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['show_result_to_student'] = $request->has('show_result_to_student');

        Test::create($validated);

        return redirect()->route('admin.tests.index')->with('success', 'Test created successfully.');
    }

    /**
     * Show the form for editing the specified test.
     */
    public function edit(Test $test)
    {
        return view('admin.tests.edit', compact('test'));
    }

    /**
     * Update the specified test in storage.
     */
    public function update(Request $request, Test $test)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'show_result_to_student' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['show_result_to_student'] = $request->has('show_result_to_student');

        $test->update($validated);

        return redirect()->route('admin.tests.index')->with('success', 'Test updated successfully.');
    }

    /**
     * Remove the specified test from storage.
     */
    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('admin.tests.index')->with('success', 'Test deleted successfully.');
    }

    /**
     * Toggle the active status of a test.
     */
    public function toggleActive(Test $test)
    {
        $test->is_active = !$test->is_active;
        $test->save();

        return back()->with('success', 'Test status updated.');
    }
}
