<?php

namespace Tests\Feature;

use App\Models\Test;
use App\Models\TestSection;
use App\Models\StudentAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionLockoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed a dummy active test and section
        $test = Test::create([
            'title' => 'Placement Test',
            'description' => 'Demo description',
            'is_active' => true,
        ]);

        $section = TestSection::create([
            'test_id' => $test->id,
            'title' => 'Listening Section',
            'type' => 'listening',
            'duration_minutes' => 15,
            'order' => 1,
        ]);
    }

    public function test_homepage_shows_start_button_when_no_active_session(): void
    {
        $response = $this->get(route('student.index'));

        $response->assertStatus(200);
        $response->assertSee('Start Placement Test');
    }

    public function test_homepage_shows_active_attempt_and_options_when_session_exists(): void
    {
        $test = Test::first();
        $section = TestSection::first();

        // Create an active attempt
        $attempt = StudentAttempt::create([
            'test_id' => $test->id,
            'full_name' => 'Mohamed Amine',
            'score' => 0,
            'total_questions' => 0,
            'started_at' => now(),
            'status' => 'in_progress',
            'current_section_id' => $section->id,
        ]);

        // Visit with session active_attempt_id set
        $response = $this->withSession(['active_attempt_id' => $attempt->id])
            ->get(route('student.index'));

        $response->assertStatus(200);
        $response->assertSee("Resume Candidate's Exam", false);
        $response->assertSee('Start a Brand New Exam');
    }

    public function test_homepage_clears_session_when_new_session_parameter_provided(): void
    {
        $test = Test::first();
        $section = TestSection::first();

        $attempt = StudentAttempt::create([
            'test_id' => $test->id,
            'full_name' => 'Mohamed Amine',
            'score' => 0,
            'total_questions' => 0,
            'started_at' => now(),
            'status' => 'in_progress',
            'current_section_id' => $section->id,
        ]);

        $response = $this->withSession(['active_attempt_id' => $attempt->id])
            ->get(route('student.index', ['new_session' => 1]));

        $response->assertStatus(200);
        $response->assertSessionMissing('active_attempt_id');
        $response->assertSee('Start Placement Test');
    }

    public function test_registration_clears_session_when_new_session_parameter_provided(): void
    {
        $test = Test::first();
        $section = TestSection::first();

        $attempt = StudentAttempt::create([
            'test_id' => $test->id,
            'full_name' => 'Mohamed Amine',
            'score' => 0,
            'total_questions' => 0,
            'started_at' => now(),
            'status' => 'in_progress',
            'current_section_id' => $section->id,
        ]);

        $response = $this->withSession(['active_attempt_id' => $attempt->id])
            ->get(route('student.test.start', [$test, 'new_session' => 1]));

        $response->assertStatus(200);
        $response->assertSessionMissing('active_attempt_id');
        $response->assertSee('Candidate Registration');
        $response->assertDontSee('Active Session Detected');
    }
}
