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
            'title' => 'Academy Stars Placement Test',
            'description' => 'Junior Test',
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

    public function test_homepage_shows_start_buttons(): void
    {
        $response = $this->get(route('student.index'));

        $response->assertStatus(200);
        $response->assertSee('Start Junior Test');
    }

    public function test_registration_page_shows_intake_form(): void
    {
        $test = Test::first();
        $response = $this->get(route('student.test.start', $test));

        $response->assertStatus(200);
        $response->assertSee('Candidate Registration');
        $response->assertSee('Full Name');
    }

    public function test_registration_creates_attempt_and_routes_to_section(): void
    {
        $test = Test::first();
        $section = TestSection::first();

        $response = $this->post(route('student.test.initialize', $test), [
            'full_name' => 'Mohamed Amine',
            'email' => 'amine@example.com',
            'phone' => '+212600000000',
        ]);

        $attempt = StudentAttempt::first();
        $this->assertNotNull($attempt);
        $this->assertEquals('Mohamed Amine', $attempt->full_name);
        
        $response->assertRedirect(route('student.test.section', [$attempt, $section]));
    }

    public function test_multiple_identical_names_allowed_independently(): void
    {
        $test = Test::first();

        // First candidate
        $this->post(route('student.test.initialize', $test), [
            'full_name' => 'Mohamed Amine',
            'email' => 'amine1@example.com',
            'phone' => '+212600000001',
        ]);

        // Simulate a different candidate on a different browser/machine by clearing session
        session()->forget('active_attempt_id');

        // Second candidate with identical name
        $this->post(route('student.test.initialize', $test), [
            'full_name' => 'Mohamed Amine',
            'email' => 'amine2@example.com',
            'phone' => '+212600000002',
        ]);

        $this->assertEquals(2, StudentAttempt::where('full_name', 'Mohamed Amine')->count());
    }

    public function test_active_attempt_redirects_pages_to_current_section(): void
    {
        $test = Test::first();
        $section = TestSection::first();

        // Create active attempt
        $attempt = StudentAttempt::create([
            'test_id' => $test->id,
            'full_name' => 'Mohamed Amine',
            'score' => 0,
            'total_questions' => 0,
            'started_at' => now(),
            'status' => 'in_progress',
            'current_section_id' => $section->id,
            'current_section_started_at' => null,
        ]);

        // Place in session
        session(['active_attempt_id' => $attempt->id]);

        // 1. Check index page redirect
        $response1 = $this->get(route('student.index'));
        $response1->assertRedirect(route('student.test.section', [$attempt, $section]));

        // 2. Check start page redirect
        $response2 = $this->get(route('student.test.start', $test));
        $response2->assertRedirect(route('student.test.section', [$attempt, $section]));

        // 3. Check initialize post redirect
        $response3 = $this->post(route('student.test.initialize', $test), [
            'full_name' => 'Mohamed Amine',
            'email' => 'amine@example.com',
            'phone' => '+212600000000',
        ]);
        $response3->assertRedirect(route('student.test.section', [$attempt, $section]));
    }

    public function test_new_session_parameter_clears_active_attempt_from_session(): void
    {
        $test = Test::first();
        $section = TestSection::first();

        // Create active attempt and put in session
        $attempt = StudentAttempt::create([
            'test_id' => $test->id,
            'full_name' => 'Mohamed Amine',
            'score' => 0,
            'total_questions' => 0,
            'started_at' => now(),
            'status' => 'in_progress',
            'current_section_id' => $section->id,
            'current_section_started_at' => null,
        ]);
        session(['active_attempt_id' => $attempt->id]);

        // Get index page with new_session parameter
        $response = $this->get(route('student.index', ['new_session' => 1]));

        // Assert no redirect (index page loads successfully)
        $response->assertStatus(200);
        $response->assertSee('Start Junior Test');
        
        // Assert session variable is forgotten
        $this->assertFalse(session()->has('active_attempt_id'));
    }
}
