<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\TimelineQuestion;
use Illuminate\Http\Request;

class RandomImageController extends Controller
{
    public function __invoke(Request $request)
    {
        $media = Media::where('model_type', TimelineQuestion::class)
            ->where('collection_name', 'cover')
            ->inRandomOrder($request->query('key'))
            ->firstOrFail();

        return redirect($media->getUrl());
    }
}
