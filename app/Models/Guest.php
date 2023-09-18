<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Sqids\Sqids;

class Guest extends Authenticatable implements HasMedia
{
    use HasFactory, InteractsWithMedia, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'details',
    ];

    public function avatar(): MorphOne
    {
        return $this->media()->one()->ofMany(
            ['id' => 'MAX'],
            fn ($query) => $query->where('collection_name', 'avatar')
        );
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function resolveRouteBinding($value, $field = null): static
    {
        return match ($field) {
            'sqid' => $this->where('id', app(Sqids::class)->decode($value)[0])->first(),
            default => parent::resolveRouteBinding($value, $field),
        };
    }

    protected function sqid(): Attribute
    {
        return Attribute::get(fn () => app(Sqids::class)->encode([
            $this->id,
            ...str_split((string) random_int(100000, 999999)),
        ]))->shouldCache();
    }
}
