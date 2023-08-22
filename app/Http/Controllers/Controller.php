<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function redirectBackOrRoute(Request $request)
    {
        $method = method_exists($request, 'validated') ? 'validated' : 'input';

        return ($redirect = $request->$method('redirect'))
            ? $redirect = redirect()->route($redirect, compact('story'))
            : $redirect = redirect()->back();
    }
}
