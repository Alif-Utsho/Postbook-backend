<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Token;

class UserAuth
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
        $token = Token::where('token', $request->header('token'))->where('expired', false)->first();

        if(!$token || $token->type!=='users'){
            return response()->json([
                "message"=> "Unauthorized Request"
            ], 401); 
        }
        
        return $next($request);
    }
}
