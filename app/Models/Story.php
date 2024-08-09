<?php

namespace App\Models;

use App\Data\Chapter\Status as ChapterStatus;
use App\Data\Story\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property ?integer $pages
 */
class Story extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Searchable;

    protected $fillable = [
        'title',
        'status',
        'print_job_id',
        'user_id',
        'story_type_id',
        'shopify_id',
        'font'
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

    public function sharedCover()
    {
        return $this->media()->one()->ofMany(
            ['id' => 'MAX'],
            fn ($query) => $query->where('collection_name', 'shared-cover')
        );
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function printJobs(): HasMany
    {
        return $this->hasMany(PrintJob::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function storyType(): BelongsTo
    {
        return $this->belongsTo(StoryType::class);
    }

    public function book(): MorphOne
    {
        return $this->media()->one()->ofMany(
            ['id' => 'MAX'],
            fn ($query) => $query->where('collection_name', 'book')
        );
    }

    public function book_preview(): MorphOne
    {
        return $this->media()->one()->ofMany(
            ['id' => 'MAX'],
            fn ($query) => $query->where('collection_name', 'book-preview')
        );
    }

    public function book_cover(): MorphOne
    {
        return $this->media()->one()->ofMany(
            ['id' => 'MAX'],
            fn ($query) => $query->where('collection_name', 'book-cover')
        );
    }

    protected function pages(): Attribute
    {
        return Attribute::get(fn () => $this->book?->getCustomProperty('pages') ?? 0); // @phpstan-ignore-line
    }

    protected function words(): Attribute
    {
        return Attribute::get(fn () => rescue(
            fn () => $this->chapters()->where('status', ChapterStatus::PUBLISHED)
                ->select(DB::raw('SUM(LENGTH(content) - LENGTH(REPLACE(content, " ", "")) + 1) as words'))
                ->value('words'),
            report: false,
        ));
    }

    protected function progress(): Attribute
    {
        return Attribute::get(function () {
            if (! Auth::check()) {
                return 0;
            }

            $max = Auth::user()?->plan?->metadata['pages'] ?? 250;
            $total = $this->pages / $max;

            return round(min($total, 1) * 100);
        });
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
