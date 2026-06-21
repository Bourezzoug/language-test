<?php

namespace Tests\Feature;

use App\Models\Test;
use App\Models\TestSection;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\StudentAttempt;
use App\Models\StudentAnswer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RealTimeAnswerSavingTest extends TestCase
{
    use RefreshDatabase;

    protected Test $test;
    protected TestSection $section;
    protected Question $question;
    protected QuestionOption $correctOption;
    protected QuestionOption $incorrectOption;
    protected StudentAttempt $attempt;

    protected function setUp(): void
    {
        parent::setUp();

        $this->test = Test::create([
            'title' => 'Academy Stars Placement Test',
            'description' => 'Junior Test',
            'is_active' => true,
        ]);

        $this->section = TestSection::create([
            'test_id' => $this->test->id,
            'title' => 'Language Use Section',
            'type' => 'grammar',
            'duration_minutes' => 15,
            'order' => 1,
        ]);

        $this->question = Question::create([
            'test_section_id' => $this->section->id,
            'question_number' => 1,
            'question_text' => 'Is it a bag?',
            'order' => 1,
        ]);

        $this->correctOption = QuestionOption::create([
            'question_id' => $this->question->id,
            'label' => 'A',
            'option_text' => 'Yes, it is.',
            'is_correct' => true,
        ]);

        $this->incorrectOption = QuestionOption::create([
            'question_id' => $this->question->id,
            'label' => 'B',
            'option_text' => 'No, it isn\'t.',
            'is_correct' => false,
        ]);

        $this->attempt = StudentAttempt::create([
            'test_id' => $this->test->id,
            'full_name' => 'John Doe',
            'score' => 0,
            'total_questions' => 0,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);
    }

    public function test_save_answer_fails_unauthorized(): void
    {
        $response = $this->postJson(route('student.test.save-answer', $this->attempt), [
            'question_id' => $this->question->id,
            'option_id' => $this->correctOption->id,
        ]);

        $response->assertStatus(403);
        $this->assertEquals(0, StudentAnswer::count());
    }

    public function test_save_answer_success_for_correct_option(): void
    {
        $response = $this->withSession(['active_attempt_id' => $this->attempt->id])
            ->postJson(route('student.test.save-answer', $this->attempt), [
                'question_id' => $this->question->id,
                'option_id' => $this->correctOption->id,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals(1, StudentAnswer::count());
        $answer = StudentAnswer::first();
        $this->assertEquals($this->question->id, $answer->question_id);
        $this->assertEquals($this->correctOption->id, $answer->question_option_id);
        $this->assertTrue($answer->is_correct);
    }

    public function test_save_answer_success_for_incorrect_option(): void
    {
        $response = $this->withSession(['active_attempt_id' => $this->attempt->id])
            ->postJson(route('student.test.save-answer', $this->attempt), [
                'question_id' => $this->question->id,
                'option_id' => $this->incorrectOption->id,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals(1, StudentAnswer::count());
        $answer = StudentAnswer::first();
        $this->assertEquals($this->question->id, $answer->question_id);
        $this->assertEquals($this->incorrectOption->id, $answer->question_option_id);
        $this->assertFalse($answer->is_correct);
    }

    public function test_save_answer_overwrites_previous_answer(): void
    {
        // First save correct option
        StudentAnswer::create([
            'student_attempt_id' => $this->attempt->id,
            'question_id' => $this->question->id,
            'question_option_id' => $this->correctOption->id,
            'is_correct' => true,
        ]);

        // Save incorrect option via API
        $response = $this->withSession(['active_attempt_id' => $this->attempt->id])
            ->postJson(route('student.test.save-answer', $this->attempt), [
                'question_id' => $this->question->id,
                'option_id' => $this->incorrectOption->id,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals(1, StudentAnswer::count());
        $answer = StudentAnswer::first();
        $this->assertEquals($this->incorrectOption->id, $answer->question_option_id);
        $this->assertFalse($answer->is_correct);
    }

    public function test_save_answer_fails_for_completed_attempt(): void
    {
        $this->attempt->update(['status' => 'completed']);

        $response = $this->withSession(['active_attempt_id' => $this->attempt->id])
            ->postJson(route('student.test.save-answer', $this->attempt), [
                'question_id' => $this->question->id,
                'option_id' => $this->correctOption->id,
            ]);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Test is already completed.']);
    }

    public function test_save_answer_fails_for_invalid_question_option_mismatch(): void
    {
        // Create another question with option
        $otherQuestion = Question::create([
            'test_section_id' => $this->section->id,
            'question_number' => 2,
            'question_text' => 'Is it a pen?',
            'order' => 2,
        ]);
        $otherOption = QuestionOption::create([
            'question_id' => $otherQuestion->id,
            'label' => 'A',
            'option_text' => 'Yes, it is.',
            'is_correct' => true,
        ]);

        // Attempt to save option that belongs to otherQuestion for the first question
        $response = $this->withSession(['active_attempt_id' => $this->attempt->id])
            ->postJson(route('student.test.save-answer', $this->attempt), [
                'question_id' => $this->question->id,
                'option_id' => $otherOption->id,
            ]);

        $response->assertStatus(400);
        $this->assertEquals(0, StudentAnswer::count());
    }
}
