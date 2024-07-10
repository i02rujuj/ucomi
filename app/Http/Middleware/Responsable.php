<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class Responsable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, String $tipos): Response
    {
        $valido = $request->user()->esResponsable($tipos);

        if(!$valido){
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
