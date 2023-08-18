<?php

namespace App\Http\Controllers;

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
}
