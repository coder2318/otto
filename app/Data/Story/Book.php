<?php

namespace App\Data\Story;

use Spatie\LaravelData\Data;

class Book extends Data
{
    public ?int $pages = null;

    public array $bookmarks = [];
}
