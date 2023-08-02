<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'order',
        'story_id',
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }
}
