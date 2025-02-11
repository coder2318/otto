<?php

namespace App\Http\Middleware;

use App\Http\Resources\GuestResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @var string
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        if (! $request->header('X-Inertia')) {
            $request->user('web')?->load('unreadNotifications');
        }

        $data = [
            'csrf_token' => csrf_token(),
            'auth.user' => fn () => ($user = $request->user('web'))
                ? UserResource::make($user->load('avatar'))
                : null,
            'auth.guest' => fn () => ($user = $request->user('web-guest'))
                ? GuestResource::make($user->load('avatar'))
                : null,
            'flash' => fn () => [
                'message' => $request->session()->get('message'),
                'status' => $request->session()->get('status'),
                'error' => $request->session()->get('error'),
            ],
        ];

        if (Session::has('search')) {
            $data['search'] = Session::get('search');
        }

        return array_merge(parent::share($request), $data);
    }
}
