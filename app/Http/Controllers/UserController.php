<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function quickstart(Request $request)
    {
        return Inertia::render('Quickstart', [
            'details' => $request->user()->details
        ]);
    }
}
