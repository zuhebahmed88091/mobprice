<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Session;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return string | void
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            Session::put('url.intended', url()->full());
            return route('login');
        }
    }
}
