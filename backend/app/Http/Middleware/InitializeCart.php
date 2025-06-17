<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Contracts\CartContract;

class InitializeCart
{
    protected CartContract $CartContract;

    public function __construct(CartContract $CartContract)
    {
        $this->CartContract = $CartContract;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        logInfo('InitializeCart Middleware: Handling request.', []);

        $response = $next($request);

        $this->CartContract->save();
        logInfo('InitializeCart Middleware: Cart saved after request.', []);

        return $response;
    }
}
