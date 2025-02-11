<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\TimelineQuestion;

class RandomImageController extends Controller
{
    public function __invoke()
    {
        $media = Media::where('model_type', TimelineQuestion::class)
            ->where('collection_name', 'cover')
            ->inRandomOrder()
            ->firstOrFail();

        return redirect($media->getUrl('chapters-list'));
    }
}
