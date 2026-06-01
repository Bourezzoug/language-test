<?php

namespace Tests\Feature;

use App\Services\ScoringService;
use Tests\TestCase;

class ScoringTest extends TestCase
{
    /**
     * Test that scoring ranges map to the correct EVOLVE levels exactly.
     */
    public function test_scoring_ranges_map_to_correct_levels(): void
    {
        // Out of range / low score
        $this->assertEquals('Pre-A1 / No Score', ScoringService::getRecommendedLevel(0));

        // EVOLVE Level 1A: 1-5
        $this->assertEquals('EVOLVE Level 1A', ScoringService::getRecommendedLevel(1));
        $this->assertEquals('EVOLVE Level 1A', ScoringService::getRecommendedLevel(3));
        $this->assertEquals('EVOLVE Level 1A', ScoringService::getRecommendedLevel(5));

        // EVOLVE Level 1B: 6-11
        $this->assertEquals('EVOLVE Level 1B', ScoringService::getRecommendedLevel(6));
        $this->assertEquals('EVOLVE Level 1B', ScoringService::getRecommendedLevel(9));
        $this->assertEquals('EVOLVE Level 1B', ScoringService::getRecommendedLevel(11));

        // EVOLVE Level 2A: 12-17
        $this->assertEquals('EVOLVE Level 2A', ScoringService::getRecommendedLevel(12));
        $this->assertEquals('EVOLVE Level 2A', ScoringService::getRecommendedLevel(15));
        $this->assertEquals('EVOLVE Level 2A', ScoringService::getRecommendedLevel(17));

        // EVOLVE Level 2B: 18-23
        $this->assertEquals('EVOLVE Level 2B', ScoringService::getRecommendedLevel(18));
        $this->assertEquals('EVOLVE Level 2B', ScoringService::getRecommendedLevel(20));
        $this->assertEquals('EVOLVE Level 2B', ScoringService::getRecommendedLevel(23));

        // EVOLVE Level 3A: 24-30
        $this->assertEquals('EVOLVE Level 3A', ScoringService::getRecommendedLevel(24));
        $this->assertEquals('EVOLVE Level 3A', ScoringService::getRecommendedLevel(27));
        $this->assertEquals('EVOLVE Level 3A', ScoringService::getRecommendedLevel(30));

        // EVOLVE Level 3B: 31-36
        $this->assertEquals('EVOLVE Level 3B', ScoringService::getRecommendedLevel(31));
        $this->assertEquals('EVOLVE Level 3B', ScoringService::getRecommendedLevel(33));
        $this->assertEquals('EVOLVE Level 3B', ScoringService::getRecommendedLevel(36));

        // EVOLVE Level 4A: 37-42
        $this->assertEquals('EVOLVE Level 4A', ScoringService::getRecommendedLevel(37));
        $this->assertEquals('EVOLVE Level 4A', ScoringService::getRecommendedLevel(40));
        $this->assertEquals('EVOLVE Level 4A', ScoringService::getRecommendedLevel(42));

        // EVOLVE Level 4B: 43-49
        $this->assertEquals('EVOLVE Level 4B', ScoringService::getRecommendedLevel(43));
        $this->assertEquals('EVOLVE Level 4B', ScoringService::getRecommendedLevel(46));
        $this->assertEquals('EVOLVE Level 4B', ScoringService::getRecommendedLevel(49));

        // EVOLVE Level 5A: 50-55
        $this->assertEquals('EVOLVE Level 5A', ScoringService::getRecommendedLevel(50));
        $this->assertEquals('EVOLVE Level 5A', ScoringService::getRecommendedLevel(52));
        $this->assertEquals('EVOLVE Level 5A', ScoringService::getRecommendedLevel(55));

        // EVOLVE Level 5B: 56-61
        $this->assertEquals('EVOLVE Level 5B', ScoringService::getRecommendedLevel(56));
        $this->assertEquals('EVOLVE Level 5B', ScoringService::getRecommendedLevel(59));
        $this->assertEquals('EVOLVE Level 5B', ScoringService::getRecommendedLevel(61));

        // EVOLVE Level 6A: 62-68
        $this->assertEquals('EVOLVE Level 6A', ScoringService::getRecommendedLevel(62));
        $this->assertEquals('EVOLVE Level 6A', ScoringService::getRecommendedLevel(65));
        $this->assertEquals('EVOLVE Level 6A', ScoringService::getRecommendedLevel(68));

        // EVOLVE Level 6B: 69-70
        $this->assertEquals('EVOLVE Level 6B', ScoringService::getRecommendedLevel(69));
        $this->assertEquals('EVOLVE Level 6B', ScoringService::getRecommendedLevel(70));
    }
}
