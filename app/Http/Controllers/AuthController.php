<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Token;

class AuthController extends Controller
{
    //
    public function login(Request $req){

        $email = $req->email;
        $password = $req->password;
        $user = User::where('email', $email)->first();

        if($user){
            if(Hash::check($req->password, $user->password)){

                $token = new Token();
                $token->user_id = $user->id;
                $token->type = $user->type;
                $token->token = Str::random(64);
                $token->save();

                return response()->json([
                    'status' => 200,
                    'user' => $user,
                    'token' => $token
                ], 200);
            }
            else{
                return response()->json([
                    'message' => "Password doesn't matched"
                ], 401);
            }
        }
        else{
            return response()->json([
                'message' => "User not found"
            ], 401);
        }

    }

    public function register(Request $req){
        $user = new User();
        $user->name = $req->name;
        $user->phone = $req->phone;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        if($user->save()){

            $token = new Token();
            $token->user_id = $user->id;
            $token->type = "users";
            $token->token = Str::random(64);
            $token->save();

            return response()->json([
                'message' => 'User registered',
                'token' => $token
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'Server problem occured'
            ], 501);
        }


    }

    public function signout(Request $req){
        $token = Token::where('token', $req->header('token'))->first();
        $token->expired = true;
        
        if($token->save()){
            return response()->json([
                'status'=> 200
            ]);
        }
    }
}
