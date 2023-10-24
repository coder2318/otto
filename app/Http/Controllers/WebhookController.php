<?php

namespace App\Http\Controllers;

use App\Data\Lulu\PrintJobDetails;
use App\Models\PrintJob;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function lulu(Request $request)
    {
        if ($request->input('topic') !== 'PRINT_JOB_STATUS_CHANGED') {
            return;
        }

        $printJob = PrintJob::where('lulu_id', $request->input('data.id'))->first();

        if ($printJob === null) {
            return;
        }

        $printJob->update([
            'details' => PrintJobDetails::from($request->input('data')),
        ]);
    }
}
