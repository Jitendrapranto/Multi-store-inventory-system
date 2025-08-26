<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token= $request->cookie('token');
        $payload= JWTToken::verifyToken($token);

        if($payload==='Invalid Token'){
            return redirect('/userLogin');

        } else{
            $request->headers->add([
                'email' => $payload->user_email,
                'user_id' => $payload->user_id ?? '',
            ]);
        }       
        
        return $next($request);
    }
}
