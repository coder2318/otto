<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Timeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'order',
        'story_type_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(TimelineQuestion::class);
    }

    public function storyType(): BelongsTo
    {
        return $this->belongsTo(StoryType::class);
    }
}
