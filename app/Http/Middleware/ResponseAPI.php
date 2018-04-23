<?php

namespace App\Http\Middleware;

use Closure;

class ResponseAPI {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $response = $next($request);

        if (in_array($response->status(), [200, 201, 404, 401, 422])) {
            $response->header('Content-Type', 'application/json');
        }

        return $response;
    }

}
