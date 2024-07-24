<?php

namespace App\Jobs;

class RegenerateBookPreview extends RegenerateBook
{
    public $cacheKeyPattern = 'book-preview-%s';

    public $mediaQuality = 'optimized';

    public $mediaCollectionName = 'book-preview';

    public $dispatchRegenerateBook = true;

    public $dispatchRegenerateBookCover = true;
}
