<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Token;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = Token::where('token', $request->header('token'))
            ->where('expired', false)
            ->first();

        if($token->type==='users'){
            return response()->json([
                'loggedin'=>true,
                'type' => 'users'
            ]);
        }
        
        return $next($request);
    }
}
