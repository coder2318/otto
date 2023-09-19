<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class StoryType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(Timeline::class);
    }

    public function questions(): HasManyThrough
    {
        return $this->through('timelines')->has('questions');
    }
}
