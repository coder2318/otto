<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BookUserCoverTemplate extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'parameters',
        'template_id',
        'story_id',
    ];

    protected $casts = [
        'parameters' => 'array',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(BookCoverTemplate::class, 'template_id', 'id');
    }

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class, 'story_id', 'id');
    }
}
