<?php

namespace Tests\Feature;

use App\Models\Test;
use App\Models\TestSection;
use App\Models\StudentAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AudioPlayLimitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed a dummy active test and section
        $test = Test::create([
            'title' => 'Evolve Placement Test',
            'description' => 'Evolve test description',
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

    public function test_audio_play_increment_fails_unauthorized(): void
    {
        $test = Test::first();
        $attempt = StudentAttempt::create([
            'test_id' => $test->id,
            'full_name' => 'John Doe',
            'score' => 0,
            'total_questions' => 0,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        // Access without setting session active_attempt_id
        $response = $this->postJson(route('student.test.audio-play', $attempt), [
            'track' => 'track_1'
        ]);

        $response->assertStatus(403);
    }

    public function test_audio_play_increment_success(): void
    {
        $test = Test::first();
        $attempt = StudentAttempt::create([
            'test_id' => $test->id,
            'full_name' => 'John Doe',
            'score' => 0,
            'total_questions' => 0,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        // Access with active_attempt_id set in session
        $response = $this->withSession(['active_attempt_id' => $attempt->id])
            ->postJson(route('student.test.audio-play', $attempt), [
                'track' => 'track_1'
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'plays' => 1
        ]);

        $attempt->refresh();
        $this->assertEquals(1, $attempt->audio_plays['track_1']);
    }

    public function test_audio_play_increment_caps_at_two(): void
    {
        $test = Test::first();
        $attempt = StudentAttempt::create([
            'test_id' => $test->id,
            'full_name' => 'John Doe',
            'score' => 0,
            'total_questions' => 0,
            'started_at' => now(),
            'status' => 'in_progress',
            'audio_plays' => ['track_1' => 1]
        ]);

        // Play 2nd time
        $response = $this->withSession(['active_attempt_id' => $attempt->id])
            ->postJson(route('student.test.audio-play', $attempt), [
                'track' => 'track_1'
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'plays' => 2
        ]);

        // Attempt 3rd time
        $response = $this->withSession(['active_attempt_id' => $attempt->id])
            ->postJson(route('student.test.audio-play', $attempt), [
                'track' => 'track_1'
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
            'plays' => 2
        ]);

        $attempt->refresh();
        $this->assertEquals(2, $attempt->audio_plays['track_1']);
    }
}
