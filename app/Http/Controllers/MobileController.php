<?php

namespace App\Http\Controllers;

use App\Data\Chapter\Status;
use App\Http\Requests\Mobile\UploadRecordRequest;
use App\Models\Timeline;
use App\Services\MediaService;
use App\Models\Story;
use Illuminate\Support\Facades\Log;

class MobileController extends Controller
{
    public function upload(MediaService $service, UploadRecordRequest $request)
    {
        $title = $request->validated('title');
        $record = $request->validated('record');
        $filename = now()->format('Ymd_His') . '_' . $request->validated('filename');
        $mimetype = $request->validated('mimetype');

        $story = Story::first();
        $timeline = Timeline::first();

        if (!$story || !$timeline) {
            Log::error(json_encode(["message" => "Make sure that story and timelines exist."]));
            return response()->json(["message" => "Make sure that story and timelines exist."], 500);
        }

        $recordFilePath = null;
        $convertedFile = null;

        try {
            $recordFilePath = $this->saveBase64ToFile($record, storage_path('media-library/temp/' . $filename));
            $convertedFile = $this->convertToMp3($recordFilePath, storage_path('media-library/temp'));

            $chapter = $story->chapters()->create([
                'status' => Status::DRAFT,
                'title' => $title,
                'timeline_id' => $timeline->id,
            ]);

            $record = $chapter
                ->addMedia($convertedFile)
                ->withCustomProperties(['mime-type' => 'audio/mp3'])
                ->toMediaCollection('attachments', config('media-library.private_disk_name'));

            $chapter->save();

            return response()->json(compact('chapter'));
        } catch (\Exception $error) {
            Log::error(json_encode(['message' => $error->getMessage()]));
            return response()->json(['message' => 'An error occurred during the upload process.'], 500);
        } finally {
            if ($recordFilePath && file_exists($recordFilePath)) {
                @unlink($recordFilePath);
            }
            if ($convertedFile && file_exists($convertedFile)) {
                @unlink($convertedFile);
            }
        }
    }

    function saveBase64ToFile($base64String, $outputFilePath)
    {
        $fileData = base64_decode($base64String);

        if (file_put_contents($outputFilePath, $fileData) === false) {
            throw new \Exception("Failed to save the file: " . $outputFilePath);
        }

        return $outputFilePath;
    }

    function convertToMp3($originalFilePath, $outputPath)
    {
        $outputFile = $outputPath . DIRECTORY_SEPARATOR . pathinfo($originalFilePath, PATHINFO_FILENAME) . '.mp3';

        $command = "ffmpeg -i " . escapeshellarg($originalFilePath) . " -acodec libmp3lame " . escapeshellarg($outputFile);

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Conversion failed: " . implode("\n", $output));
        }

        return $outputFile;
    }
}
