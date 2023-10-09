<?php

namespace App\Http\Controllers;

use App\Http\Requests\TranslateRequest;
use App\Services\TranslateService;

class TranslateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(TranslateRequest $request, TranslateService $service)
    {
        return response()->json($service->translate($request->text, $request->options));
    }
}
