<?php

namespace App\Http\Controllers\Guests;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class SetupController extends Controller
{
    public function setup()
    {
        return Inertia::render('Guests/Setup');
    }
}
