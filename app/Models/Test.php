<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    protected $fillable = [
        'title',
        'description',
        'is_active',
        'show_result_to_student',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_result_to_student' => 'boolean',
    ];

    /**
     * Get sections belonging to the test.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(TestSection::class)->orderBy('order');
    }

    /**
     * Get student attempts for this test.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(StudentAttempt::class);
    }
}
