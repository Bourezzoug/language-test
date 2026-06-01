<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentAttempt extends Model
{
    protected $fillable = [
        'test_id',
        'full_name',
        'email',
        'phone',
        'score',
        'total_questions',
        'recommended_level',
        'started_at',
        'finished_at',
        'status',
        'current_section_id',
        'current_section_started_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'current_section_started_at' => 'datetime',
    ];

    /**
     * Get the test associated with the attempt.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Get the active section of the test (if in progress).
     */
    public function currentSection(): BelongsTo
    {
        return $this->belongsTo(TestSection::class, 'current_section_id');
    }

    /**
     * Get all answers submitted by the student in this attempt.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class);
    }
}
