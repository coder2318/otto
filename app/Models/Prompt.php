<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prompt extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'icon',
        'perspective',
    ];

    protected $casts = [
        'perspective' => 'boolean',
    ];
}
