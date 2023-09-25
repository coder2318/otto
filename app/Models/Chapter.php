<?php

namespace App\Models;

use App\Data\Chapter\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Chapter extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'content',
        'edit',
        'processing',
        'order',
        'status',
        'story_id',
        'timeline_id',
        'timeline_question_id',
        'guest_id',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function timeline(): BelongsTo
    {
        return $this->belongsTo(Timeline::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(TimelineQuestion::class);
    }

    public function cover(): MorphOne
    {
        return $this->media()->one()->ofMany(
            ['id' => 'MAX'],
            fn ($query) => $query->where('collection_name', 'cover')
        );
    }

    public function attachments(): MorphMany
    {
        return $this->media()->where('collection_name', 'attachments');
    }
}
