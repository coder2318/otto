<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function local(string $path)
    {
        abort_unless(Storage::disk('local')->exists($path), 404);

        return Response::file(Storage::disk('local')->path($path));
    }
}
