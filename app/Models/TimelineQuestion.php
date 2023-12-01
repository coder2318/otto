<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TimelineQuestion extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'question',
        'context',
        'order',
        'is_demo',
        'timeline_id',
        'sub_questions',
    ];

    protected $casts = [
        'sub_questions' => 'array',
        'is_demo' => 'boolean',
    ];

    public function timeline(): BelongsTo
    {
        return $this->belongsTo(Timeline::class);
    }

    public function covers(): MorphMany
    {
        return $this->media()->where('collection_name', 'cover');
    }

    public function cover(): MorphOne
    {
        return $this->covers()->inRandomOrder()->one();
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function chapter(): HasOne
    {
        return $this->chapters()->one()->latestOfMany();
    }
}
