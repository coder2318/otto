<?php

namespace App\Models;

use App\Data\Story\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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

    public function cover(): MorphOne
    {
        return $this->media()->one()->ofMany(
            ['id' => 'MAX'],
            fn ($query) => $query->where('collection_name', 'cover')
        );
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function pages(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->chapters()->where('status', Status::PUBLISHED)->selectRaw(<<<'SQL'
                LENGTH("chapters"."content") -
                LENGTH(REPLACE("chapters"."content", ' ', '')) + 1
                AS "words"
            SQL
            )->get()->map(fn ($chapter) => ceil($chapter->words / 500))->sum()
        );
    }
}
