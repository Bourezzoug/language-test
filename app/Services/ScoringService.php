<?php

namespace App\Services;

class ScoringService
{
    /**
     * Get the recommended placement level based on the student's score.
     */
    public static function getRecommendedLevel(int $score): string
    {
        if ($score >= 69) return 'EVOLVE Level 6B';
        if ($score >= 62) return 'EVOLVE Level 6A';
        if ($score >= 56) return 'EVOLVE Level 5B';
        if ($score >= 50) return 'EVOLVE Level 5A';
        if ($score >= 43) return 'EVOLVE Level 4B';
        if ($score >= 37) return 'EVOLVE Level 4A';
        if ($score >= 31) return 'EVOLVE Level 3B';
        if ($score >= 24) return 'EVOLVE Level 3A';
        if ($score >= 18) return 'EVOLVE Level 2B';
        if ($score >= 12) return 'EVOLVE Level 2A';
        if ($score >= 6)  return 'EVOLVE Level 1B';
        if ($score >= 1)  return 'EVOLVE Level 1A';
        return 'Pre-A1 / No Score';
    }
}
