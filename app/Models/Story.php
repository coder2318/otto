<?php

namespace App\Models;

use App\Data\Story\Book;
use App\Data\Story\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Laravel\Scout\Searchable;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as Pdf;
use Mpdf\Mpdf;
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
    ];

    protected $casts = [
        'status' => Status::class,
        'book' => Book::class,
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

    protected function pages(): Attribute
    {
        return Attribute::get(function () {
            $chapters = $this->chapters()
                ->with('images')
                ->where('status', Status::PUBLISHED)
                ->orderBy('timeline_id', 'asc')
                ->orderBy('order', 'asc')
                ->lazy();

            $pdf = Pdf::loadView('pdf.book', ['story' => $this, 'chapters' => $chapters]);
            /** @var Mpdf */
            $mpdf = $pdf->getMpdf();
            $mpdf->curlAllowUnsafeSslRequests = true;
            $pdf->output();

            return $mpdf->page;
        })->shouldCache();
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
