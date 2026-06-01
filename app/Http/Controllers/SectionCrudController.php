<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestSection;
use Illuminate\Http\Request;

class SectionCrudController extends Controller
{
    /**
     * List sections belonging to a test.
     */
    public function index(Test $test)
    {
        $test->load('sections');
        return view('admin.sections.index', compact('test'));
    }

    /**
     * Show form to create a section.
     */
    public function create(Test $test)
    {
        return view('admin.sections.create', compact('test'));
    }

    /**
     * Store a newly created section.
     */
    public function store(Request $request, Test $test)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:listening,reading,language_use'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'instructions' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
        ]);

        $test->sections()->create($validated);

        return redirect()->route('admin.tests.sections.index', $test)->with('success', 'Section created successfully.');
    }

    /**
     * Show form to edit a section.
     */
    public function edit(TestSection $section)
    {
        $section->load('test');
        return view('admin.sections.edit', compact('section'));
    }

    /**
     * Update a section.
     */
    public function update(Request $request, TestSection $section)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:listening,reading,language_use'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'instructions' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
        ]);

        $section->update($validated);

        return redirect()->route('admin.tests.sections.index', $section->test_id)->with('success', 'Section updated successfully.');
    }

    /**
     * Delete a section.
     */
    public function destroy(TestSection $section)
    {
        $testId = $section->test_id;
        $section->delete();

        return redirect()->route('admin.tests.sections.index', $testId)->with('success', 'Section deleted successfully.');
    }
}
