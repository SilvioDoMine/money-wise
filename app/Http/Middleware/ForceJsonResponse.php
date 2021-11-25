<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceJsonResponse
{
    /**
     * Adiciona o header "Accept: application/json" em toda requisição que passa por este middleware.
     * Isso garante que todas as respostas sejam entregues no formato JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
