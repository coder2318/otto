<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'prices',
        'features',
    ];

    protected $casts = [
        'prices' => 'collection',
        'features' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
