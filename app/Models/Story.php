<?php

namespace App\Models;

use App\Data\Story\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Story extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'status',
        'user_id',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function cover()
    {
        return $this->media()->one()->ofMany(
            ['id' => 'MAX'],
            fn ($query) => $query->where('collection_name', 'cover')
        );
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
