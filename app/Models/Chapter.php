<?php

namespace App\Models;

use App\Data\Chapter\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function user()
    {
        return $this->through('story')->has('user');
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }

    public function cover()
    {
        return $this->media()->one()->ofMany(
            ['id' => 'MAX'],
            fn ($query) => $query->where('collection_name', 'cover')
        );
    }
}
