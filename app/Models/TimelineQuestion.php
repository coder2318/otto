<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('chapters-list')
            ->performOnCollections('cover')
            ->fit(Manipulations::FIT_MAX, 720, 360)
            ->optimize();
    }

    /**
     * Needed to correctly attach chapters using joined chapter entities (crutch)
     *
     * @see \App\Http\Requests\Chapters\ChaptersRequest
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
