<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \Illuminate\Auth\Access\AuthorizationException;

class AcceptedAgreements
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     * @throws
     */
    public function handle($request, Closure $next)
    {
        throw_unless(
            auth()->user()->current_agreement === config('app.current_agreement'),
            new AuthorizationException('You must accept the latest user agreement to access this resource')
        );

        return $next($request);
    }
}
