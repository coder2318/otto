<?php

namespace App\Http\Controllers;

use App\Data\Chapter\Status;
use App\Models\Timeline;
use App\Services\MediaService;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MobileController extends Controller
{
    public function upload(MediaService $service, Request $request)
    {
        $title = $request->input('title');
        $record = $request->input('record');

        if (!$record || !$title) {
            Log::error(json_encode(["message" => "Make sure that you upload record and title."]));
            return response()->json(["message" => "Make sure that you upload record and title."], 500);
        }

        $story = Story::first();
        $timeline = Timeline::first();

        if (!$story || !$timeline) {
            Log::error(json_encode(["message" => "Make sure that story and timelines exists."]));
            return  response()->json(["message" => "Make sure that story and timelines exists."], 500);
        }

        $chapter = $story->chapters()->create([
            'status' => Status::DRAFT,
            'title' => $request->title,
            'timeline_id' => $timeline->id,
        ]);

        $record = $chapter->addMediaFromString($record)
            ->toMediaCollection('attachments', config('media-library.private_disk_name'));

        $chapter->save();

        return response()->json(
            compact('chapter')
        );
    }
}
