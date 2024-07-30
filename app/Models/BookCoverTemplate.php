<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCoverTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'fields',
        'back',
        'back_image',
        'spine',
        'front',
        'front_image',
    ];

    protected $casts = [
        'fields' => 'array',
    ];
}
