<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestSection extends Model
{
    protected $fillable = [
        'test_id',
        'title',
        'type',
        'duration_minutes',
        'instructions',
        'order',
    ];

    /**
     * Get the test that owns the section.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Get passages belonging to the section.
     */
    public function passages(): HasMany
    {
        return $this->hasMany(Passage::class)->orderBy('order');
    }

    /**
     * Get questions belonging to the section.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }
}
