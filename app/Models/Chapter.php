<?php

namespace App\Models;

use App\Data\Chapter\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Chapter extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'content',
        'order',
        'status',
        'story_id',
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

    public function cover(): MorphOne
    {
        return $this->media()->one()->ofMany(
            ['id' => 'MAX'],
            fn ($query) => $query->where('collection_name', 'cover')
        );
    }
}
