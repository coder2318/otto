<?php

namespace App\Models;

use App\Data\Chapter\Status as ChapterStatus;
use App\Data\Story\Status;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Storage;

/**
 * @property ?integer $pages
 */
class Story extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'status',
        'print_job_id',
        'user_id',
        'story_type_id',
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
            Storage::disk('local')->put($file = 'temp.pdf', Pdf::loadView('pdf.book', [
                'story' => $this,
                'chapters' => $this->chapters()
                    ->where('status', ChapterStatus::PUBLISHED)
                    ->orderBy('timeline_id', 'asc')
                    ->orderBy('order', 'asc')
                    ->lazy(),
            ])->output());

            exec('pdfinfo '.Storage::disk('local')->path($file), $output);
            Storage::disk('local')->delete($file);

            $pages = null;

            foreach ($output as $line) {
                if (preg_match("/Pages:\s*(\d+)/i", $line, $matches)) {
                    $pages = $matches[1];
                    break;
                }
            }

            return $pages;
        })->shouldCache();
    }
}
