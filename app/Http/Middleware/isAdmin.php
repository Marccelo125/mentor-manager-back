<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;


class isAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_admin === true) {
            return $next($request);
        }
        return response()->json(
            [
                'success' => false,
                'message' => 'Rota de administrador.',
            ],
            HttpFoundationResponse::HTTP_FOUND
        );
    }
}
