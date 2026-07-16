<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEtecContextIsSet
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!session('etec_ativa') && $user->etecs->isNotEmpty()) {
            session(['etec_ativa' => $user->etecs->first()->id]);
        }

        return $next($request);
    }
}
