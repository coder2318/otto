<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Honeypot\Honeypot;

class StaticController extends Controller
{
    public function index()
    {
        return Inertia::render('Index', [
            'honeypot' => fn () => app(Honeypot::class),
        ]);
    }

    public function about()
    {
        return Inertia::render('About');
    }

    public function privacyPolicy()
    {
        return Inertia::render('PrivacyPolicy');
    }

    public function termsAndConditions()
    {
        return Inertia::render('TermsAndConditions');
    }

    public function faq()
    {
        return Inertia::render('FAQ');
    }

    public function contact()
    {
        return Inertia::render('Contact');
    }

    public function postPreorder(Request $request)
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:preorders'],
        ]);

        Preorder::create($data);

        return redirect()->back()->with(['message' => 'Your preorder request was received!']);
    }

    public function postContact(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        // TODO: send contact

        return redirect()->back()->with(['message' => 'Your contact request was received!']);
    }
}
