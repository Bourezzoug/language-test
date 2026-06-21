<?php

namespace App\Services;

class ScoringService
{
    public static function getRecommendedLevel(int $score, $test = null): string
    {
        $testTitle = $test ? $test->title : '';

        if (stripos($testTitle, 'Academy Stars') !== false) {
            if ($score >= 51) return 'Academy Stars Level 6 (Pre-B1)';
            if ($score >= 41) return 'Academy Stars Level 5 (A2)';
            if ($score >= 31) return 'Academy Stars Level 4 (A1)';
            if ($score >= 21) return 'Academy Stars Level 3 (A1)';
            if ($score >= 11) return 'Academy Stars Level 2 (Pre-A1)';
            if ($score >= 6)  return 'Academy Stars Level 1 (Pre-A1)';
            return 'Starter level';
        }

        if (stripos($testTitle, 'Language Hub') !== false) {
            if ($score >= 63) return 'Language Hub Advanced';
            if ($score >= 49) return 'Language Hub Upper Intermediate';
            if ($score >= 35) return 'Language Hub Intermediate';
            if ($score >= 21) return 'Language Hub Pre-Intermediate';
            if ($score >= 7)  return 'Language Hub Elementary';
            return 'Language Hub Beginner';
        }

        // Default Evolve mapping
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
