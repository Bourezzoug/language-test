<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestSection;
use App\Models\StudentAttempt;
use App\Models\StudentAnswer;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Services\ScoringService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentTestController extends Controller
{
    /**
     * Temporary toggle: Set to true to bypass preparation audios for testing.
     * Set to false to enforce them.
     */
    protected bool $bypassPrepAudios = false;

    /**
     * Display the student portal homepage listing active tests.
     */
    public function index(Request $request)
    {
        if ($request->has('new_session')) {
            session()->forget('active_attempt_id');
        }

        if (session()->has('active_attempt_id')) {
            $attempt = StudentAttempt::find(session('active_attempt_id'));
            if ($attempt && $attempt->status === 'in_progress') {
                $currentSection = TestSection::find($attempt->current_section_id);
                if ($currentSection) {
                    return redirect()->route('student.test.section', [$attempt, $currentSection]);
                }
            }
        }

        $juniorTest = Test::where('title', 'like', '%Academy Stars%')->where('is_active', true)->first();
        $seniorTest = Test::where('title', 'like', '%Language Hub%')->where('is_active', true)->first();
        
        // Fallback to Evolve if Senior Test is not found
        if (!$seniorTest) {
            $seniorTest = Test::where('title', 'like', '%EVOLVE%')->where('is_active', true)->first();
        }
        
        // Final fallback to first active test
        if (!$juniorTest && !$seniorTest) {
            $juniorTest = Test::where('is_active', true)->first();
        }

        return view('student.index', compact('juniorTest', 'seniorTest'));
    }

    /**
     * Show the intake form before starting the test.
     */
    public function start(Request $request, Test $test)
    {
        if (session()->has('active_attempt_id')) {
            $attempt = StudentAttempt::find(session('active_attempt_id'));
            if ($attempt && $attempt->status === 'in_progress') {
                $currentSection = TestSection::find($attempt->current_section_id);
                if ($currentSection) {
                    return redirect()->route('student.test.section', [$attempt, $currentSection]);
                }
            }
        }

        if (!$test->is_active) {
            return redirect()->route('student.index')->with('error', 'This test is currently inactive.');
        }

        return view('student.start', compact('test'));
    }

    /**
     * Initialize the test attempt and redirect to the first section.
     */
    public function initialize(Request $request, Test $test)
    {
        if (session()->has('active_attempt_id')) {
            $attempt = StudentAttempt::find(session('active_attempt_id'));
            if ($attempt && $attempt->status === 'in_progress') {
                $currentSection = TestSection::find($attempt->current_section_id);
                if ($currentSection) {
                    return redirect()->route('student.test.section', [$attempt, $currentSection]);
                }
            }
        }

        if (!$test->is_active) {
            return redirect()->route('student.index')->with('error', 'This test is currently inactive.');
        }

        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $firstSection = $test->sections()->orderBy('order')->first();

        if (!$firstSection) {
            return back()->withErrors(['error' => 'This test does not have any sections configured yet. Please contact your administrator.']);
        }

        $attempt = StudentAttempt::create([
            'test_id' => $test->id,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'score' => 0,
            'total_questions' => 0,
            'started_at' => Carbon::now(),
            'status' => 'in_progress',
            'current_section_id' => $firstSection->id,
            'current_section_started_at' => $this->bypassPrepAudios ? Carbon::now() : null,
        ]);

        session(['active_attempt_id' => $attempt->id]);

        return redirect()->route('student.test.section', [$attempt, $firstSection]);
    }

    /**
     * Show a specific section during a test attempt.
     */
    public function showSection(StudentAttempt $attempt, TestSection $section)
    {
        if ($attempt->status === 'completed') {
            return redirect()->route('student.test.result', $attempt);
        }

        // Cross-attempt verification
        if (session('active_attempt_id') !== $attempt->id) {
            abort(403, 'Unauthorized access to this test attempt.');
        }

        // Security check: ensure this section belongs to the active test
        if ($section->test_id !== $attempt->test_id) {
            abort(403, 'Unauthorized section access.');
        }

        // Security check: ensure the student only accesses their current active section
        if ($attempt->current_section_id !== $section->id) {
            $activeSection = TestSection::find($attempt->current_section_id);
            if ($activeSection) {
                return redirect()->route('student.test.section', [$attempt, $activeSection])
                    ->with('error', 'You cannot navigate away from your active test section.');
            }
        }

        // Initialize section timer state if the student is entering it for the first time
        if ($attempt->current_section_id !== $section->id) {
            $isFirst = $attempt->test->sections()->orderBy('order')->first()->id === $section->id;
            $attempt->update([
                'current_section_id' => $section->id,
                'current_section_started_at' => ($isFirst && !$this->bypassPrepAudios) ? null : Carbon::now(),
            ]);
        }

        // Calculate timer remaining seconds server-side (prevents browser refresh reset)
        $startedAt = $attempt->current_section_started_at;
        $durationSeconds = $section->duration_minutes * 60;
        
        $listeningAudioFlowActive = false;
        
        if ($startedAt === null && !$this->bypassPrepAudios) {
            $listeningAudioFlowActive = true;
            $remainingSeconds = $durationSeconds;
        } else {
            if ($startedAt === null) {
                $attempt->update(['current_section_started_at' => Carbon::now()]);
                $startedAt = $attempt->current_section_started_at;
            }
            $elapsedSeconds = Carbon::now()->diffInSeconds($startedAt, true);
            $remainingSeconds = max(0, $durationSeconds - $elapsedSeconds);

            // If time is up, auto-submit the section!
            if ($remainingSeconds <= 0) {
                return $this->submitSectionData(new Request(), $attempt, $section, true);
            }
        }

        // Load section, questions, and reading passages
        $section->load(['passages.questions.options', 'questions' => function($q) {
            $q->whereNull('passage_id')->with('options');
        }]);

        // Load existing answers (if candidate is returning to this section or refreshed)
        $savedAnswers = StudentAnswer::where('student_attempt_id', $attempt->id)
            ->pluck('question_option_id', 'question_id')
            ->toArray();

        $sections = $attempt->test->sections()->orderBy('order')->get();
        return view('student.section', compact('attempt', 'section', 'remainingSeconds', 'savedAnswers', 'sections', 'listeningAudioFlowActive'));
    }

    /**
     * Handle the POST request when submitting a section.
     */
    public function submitSection(Request $request, StudentAttempt $attempt, TestSection $section)
    {
        return $this->submitSectionData($request, $attempt, $section, false);
    }

    /**
     * Internal method to process section submission and route to next section or finalize test.
     */
    protected function submitSectionData(Request $request, StudentAttempt $attempt, TestSection $section, bool $isTimeOut = false)
    {
        if ($attempt->status === 'completed') {
            return redirect()->route('student.test.result', $attempt);
        }

        // Retrieve section questions
        $questions = Question::where('test_section_id', $section->id)->get();
        $submittedAnswers = $request->input('answers', []);

        foreach ($questions as $q) {
            $optionId = $submittedAnswers[$q->id] ?? null;

            $isCorrect = false;
            if ($optionId) {
                $option = QuestionOption::find($optionId);
                if ($option && $option->question_id == $q->id) {
                    $isCorrect = $option->is_correct;
                } else {
                    $optionId = null; // Guard against tampered inputs
                }
            }

            // Save or update response
            StudentAnswer::updateOrCreate(
                [
                    'student_attempt_id' => $attempt->id,
                    'question_id' => $q->id
                ],
                [
                    'question_option_id' => $optionId,
                    'is_correct' => $isCorrect,
                ]
            );
        }

        // Find next section in order
        $nextSection = $attempt->test->sections()
            ->where('order', '>', $section->order)
            ->orderBy('order')
            ->first();

        if ($nextSection) {
            // Move to next section
            $attempt->update([
                'current_section_id' => $nextSection->id,
                'current_section_started_at' => Carbon::now(),
            ]);

            $message = $isTimeOut ? 'Time expired! Your progress was saved. Moving to the next section.' : 'Section submitted successfully!';
            return redirect()->route('student.test.section', [$attempt, $nextSection])->with('success', $message);
        }

        // No more sections left - finalize the attempt
        return $this->finalizeAttempt($attempt, $isTimeOut);
    }

    /**
     * Finalize the candidate test attempt, calculate results, and score.
     */
    protected function finalizeAttempt(StudentAttempt $attempt, bool $isTimeOut = false)
    {
        $attempt->load('answers');
        
        $score = $attempt->answers()->where('is_correct', true)->count();
        
        // Count total questions in the test dynamically
        $totalQuestions = Question::whereIn(
            'test_section_id', 
            $attempt->test->sections()->pluck('id')
        )->count();

        $recommendedLevel = ScoringService::getRecommendedLevel($score, $attempt->test);

        $attempt->update([
            'score' => $score,
            'total_questions' => $totalQuestions,
            'recommended_level' => $recommendedLevel,
            'finished_at' => Carbon::now(),
            'status' => 'completed',
            'current_section_id' => null,
            'current_section_started_at' => null,
        ]);

        $message = $isTimeOut ? 'Time expired! Your test was submitted. Please contact the instructor to get your result.' : 'Test submitted successfully. Please contact the instructor to get your result.';
        return redirect()->route('student.test.result', $attempt)->with('success', $message);
    }

    /**
     * Start the Listening section timer by setting the start timestamp server-side.
     */
    public function startListeningTimer(StudentAttempt $attempt)
    {
        if (session('active_attempt_id') !== $attempt->id) {
            return response()->json(['error' => 'Unauthorized attempt access.'], 403);
        }

        if ($attempt->status === 'completed') {
            return response()->json(['error' => 'Test is already completed.'], 400);
        }

        if ($attempt->current_section_started_at === null) {
            $attempt->update([
                'current_section_started_at' => \Carbon\Carbon::now()
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Show test completion results.
     */
    public function showResult(StudentAttempt $attempt)
    {
        $attempt->load('test');
        return view('student.result', compact('attempt'));
    }

    /**
     * Securely increment the play count of an audio track (limit of 2).
     */
    public function incrementAudioPlayCount(Request $request, StudentAttempt $attempt)
    {
        if (session('active_attempt_id') !== $attempt->id) {
            return response()->json(['error' => 'Unauthorized attempt access.'], 403);
        }

        if ($attempt->status === 'completed') {
            return response()->json(['error' => 'Test is already completed.'], 400);
        }

        $track = $request->input('track');
        if (!$track) {
            return response()->json(['error' => 'Track identifier required.'], 400);
        }

        $audioPlays = $attempt->audio_plays ?? [];
        $currentCount = $audioPlays[$track] ?? 0;

        if ($currentCount >= 2) {
            return response()->json([
                'success' => false,
                'error' => 'Max plays reached (2/2).',
                'plays' => $currentCount
            ]);
        }

        $newCount = $currentCount + 1;
        $audioPlays[$track] = $newCount;
        $attempt->update(['audio_plays' => $audioPlays]);

        return response()->json([
            'success' => true,
            'plays' => $newCount
        ]);
    }

    /**
     * Save a single answer in real-time.
     */
    public function saveSingleAnswer(Request $request, StudentAttempt $attempt)
    {
        if (session('active_attempt_id') !== $attempt->id) {
            return response()->json(['error' => 'Unauthorized attempt access.'], 403);
        }

        if ($attempt->status === 'completed') {
            return response()->json(['error' => 'Test is already completed.'], 400);
        }

        $questionId = $request->input('question_id');
        $optionId = $request->input('option_id');

        if (!$questionId || !$optionId) {
            return response()->json(['error' => 'Question and option identifiers required.'], 400);
        }

        $question = Question::find($questionId);
        $option = QuestionOption::find($optionId);

        if (!$question || !$option || $option->question_id != $question->id) {
            return response()->json(['error' => 'Invalid question or option.'], 400);
        }

        // Check if correct
        $isCorrect = $option->is_correct;

        // Save or update response
        StudentAnswer::updateOrCreate(
            [
                'student_attempt_id' => $attempt->id,
                'question_id' => $questionId
            ],
            [
                'question_option_id' => $optionId,
                'is_correct' => $isCorrect,
            ]
        );

        return response()->json(['success' => true]);
    }
}
