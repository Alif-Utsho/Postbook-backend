<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\React;
use App\Models\Token;

class ReactController extends Controller
{
    //
    public function like(Request $req){
        $token = Token::where('token', $req->header('token'))->where('expired', false)->first();

        $react = new React();
        $react->post_id = $req->post_id;
        $react->user_id = $token->user_id;
        if($react->save()){
            return response()->json([
                'status'=>200,
                'message'=>'You loved the post'
            ]);
        }
    }

    public function unlike(Request $req){
        $token = Token::where('token', $req->header('token'))->where('expired', false)->first();

        $react = React::where('post_id', $req->post_id)
                ->where('user_id', $token->user_id)
                ->first()
                ->delete();

        return response()->json([
            'status'=>200,
            'message'=>'Your react has removed'
        ]);
    }

    public function reacts(Request $req){
        $token = Token::where('token', $req->header('token'))->where('expired', false)->first();
        $reacts = React::where('post_id', $req->post_id)->with('user')->get();

        return response()->json([
            'reacts' => $reacts,
            'authId' => $token->user_id
        ]);
    }
}
