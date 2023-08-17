<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use Illuminate\Http\Request;

class PreorderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:preorders'],
        ]);

        Preorder::create($data);

        return redirect()->back()->with(['message' => 'Your preorder request was received!']);
    }
}
