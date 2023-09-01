<?php

namespace App\Models;

use App\Data\Lulu\PrintJobDetails;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrintJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'lulu_id',
        'details',
        'story_id',
    ];

    protected $casts = [
        'details' => PrintJobDetails::class,
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }
}
