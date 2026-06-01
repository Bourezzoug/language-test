<?php

namespace App\Http\Controllers;

use App\Models\TestSection;
use App\Models\Passage;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionCrudController extends Controller
{
    /**
     * Display a listing of questions and passages in a section.
     */
    public function index(TestSection $section)
    {
        $section->load(['test', 'passages', 'questions.passage', 'questions.options']);
        return view('admin.questions.index', compact('section'));
    }

    /**
     * Show the form to create a new passage (Reading section).
     */
    public function createPassage(TestSection $section)
    {
        return view('admin.questions.create_passage', compact('section'));
    }

    /**
     * Store a newly created passage.
     */
    public function storePassage(Request $request, TestSection $section)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'order' => ['required', 'integer', 'min:0'],
        ]);

        $section->passages()->create($validated);

        return redirect()->route('admin.sections.questions.index', $section)->with('success', 'Passage created successfully.');
    }

    /**
     * Show form to edit a passage.
     */
    public function editPassage(Passage $passage)
    {
        $passage->load('section');
        return view('admin.questions.edit_passage', compact('passage'));
    }

    /**
     * Update a passage.
     */
    public function updatePassage(Request $request, Passage $passage)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'order' => ['required', 'integer', 'min:0'],
        ]);

        $passage->update($validated);

        return redirect()->route('admin.sections.questions.index', $passage->test_section_id)->with('success', 'Passage updated successfully.');
    }

    /**
     * Delete a passage.
     */
    public function destroyPassage(Passage $passage)
    {
        $sectionId = $passage->test_section_id;
        $passage->delete();

        return redirect()->route('admin.sections.questions.index', $sectionId)->with('success', 'Passage deleted successfully.');
    }

    /**
     * Show form to create a question.
     */
    public function createQuestion(TestSection $section)
    {
        $section->load('passages');
        return view('admin.questions.create_question', compact('section'));
    }

    /**
     * Store a newly created question.
     */
    public function storeQuestion(Request $request, TestSection $section)
    {
        $validated = $request->validate([
            'question_number' => ['required', 'integer', 'min:1'],
            'passage_id' => ['nullable', 'exists:passages,id'],
            'situation' => ['nullable', 'string'],
            'question_text' => ['required', 'string'],
            'audio' => ['nullable', 'file', 'mimes:mp3,wav,ogg,aac', 'max:20480'], // Max 20MB
            'order' => ['required', 'integer', 'min:0'],
            'options' => ['required', 'array', 'min:2', 'max:6'],
            'options.*.label' => ['required', 'string', 'max:10'],
            'options.*.option_text' => ['required', 'string'],
            'correct_option_label' => ['required', 'string'],
        ]);

        $audioPath = null;
        if ($request->hasFile('audio')) {
            $file = $request->file('audio');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '', $file->getClientOriginalName());
            $audioPath = $file->storeAs('audios', $filename, 'public');
        }

        $question = Question::create([
            'test_section_id' => $section->id,
            'passage_id' => $request->passage_id,
            'question_number' => $request->question_number,
            'situation' => $request->situation,
            'question_text' => $request->question_text,
            'audio_path' => $audioPath,
            'order' => $request->order,
        ]);

        foreach ($request->options as $opt) {
            QuestionOption::create([
                'question_id' => $question->id,
                'label' => $opt['label'],
                'option_text' => $opt['option_text'],
                'is_correct' => ($opt['label'] === $request->correct_option_label),
            ]);
        }

        return redirect()->route('admin.sections.questions.index', $section)->with('success', 'Question created successfully.');
    }

    /**
     * Show form to edit a question.
     */
    public function editQuestion(Question $question)
    {
        $question->load(['section.passages', 'options']);
        return view('admin.questions.edit_question', compact('question'));
    }

    /**
     * Update a question.
     */
    public function updateQuestion(Request $request, Question $question)
    {
        $validated = $request->validate([
            'question_number' => ['required', 'integer', 'min:1'],
            'passage_id' => ['nullable', 'exists:passages,id'],
            'situation' => ['nullable', 'string'],
            'question_text' => ['required', 'string'],
            'audio' => ['nullable', 'file', 'mimes:mp3,wav,ogg,aac', 'max:20480'],
            'order' => ['required', 'integer', 'min:0'],
            'options' => ['required', 'array', 'min:2', 'max:6'],
            'options.*.id' => ['nullable', 'integer'],
            'options.*.label' => ['required', 'string', 'max:10'],
            'options.*.option_text' => ['required', 'string'],
            'correct_option_label' => ['required', 'string'],
        ]);

        $audioPath = $question->audio_path;
        if ($request->hasFile('audio')) {
            // Delete old file
            if ($question->audio_path && Storage::disk('public')->exists($question->audio_path)) {
                Storage::disk('public')->delete($question->audio_path);
            }
            $file = $request->file('audio');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '', $file->getClientOriginalName());
            $audioPath = $file->storeAs('audios', $filename, 'public');
        }

        $question->update([
            'passage_id' => $request->passage_id,
            'question_number' => $request->question_number,
            'situation' => $request->situation,
            'question_text' => $request->question_text,
            'audio_path' => $audioPath,
            'order' => $request->order,
        ]);

        // Re-seed options dynamically: delete old options and insert new ones
        // This is robust, simple, and avoids complex matching
        $question->options()->delete();

        foreach ($request->options as $opt) {
            QuestionOption::create([
                'question_id' => $question->id,
                'label' => $opt['label'],
                'option_text' => $opt['option_text'],
                'is_correct' => ($opt['label'] === $request->correct_option_label),
            ]);
        }

        return redirect()->route('admin.sections.questions.index', $question->test_section_id)->with('success', 'Question updated successfully.');
    }

    /**
     * Delete a question.
     */
    public function destroyQuestion(Question $question)
    {
        $sectionId = $question->test_section_id;

        // Delete audio file if it exists
        if ($question->audio_path && Storage::disk('public')->exists($question->audio_path)) {
            Storage::disk('public')->delete($question->audio_path);
        }

        $question->delete();

        return redirect()->route('admin.sections.questions.index', $sectionId)->with('success', 'Question deleted successfully.');
    }
}
