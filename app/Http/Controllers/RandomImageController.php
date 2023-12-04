<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\TimelineQuestion;
use Illuminate\Http\Request;

class RandomImageController extends Controller
{
    public function __invoke(Request $request)
    {
        $seed = $request->query('key');
        $seed = $seed ? intval($seed) : null;

        $media = Media::where('model_type', TimelineQuestion::class)
            ->where('collection_name', 'cover')
            ->inRandomOrder($seed)
            ->firstOrFail();

        return redirect($media->getUrl());
    }
}
