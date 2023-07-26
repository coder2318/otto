<?php

namespace App\Models;

use App\Data\Story\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'cover',
        'status',
        'user_id',
        'timeline_id',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }
}
