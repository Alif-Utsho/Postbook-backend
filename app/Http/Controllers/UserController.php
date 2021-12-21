<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Connection;
use App\Models\Profile;
use App\Models\Token;

class UserController extends Controller
{
    //
    
    public function singleUser(Request $req){
        $token = Token::where('token', $req->header('token'))->where('expired', false)->first();
        $user = User::where('id', $req->id)
                ->with('profile')
                ->with('sendByfriends')
                ->with('recByfriends')
                ->with('request')
                ->with('sent')
                ->with('posts')
                ->first();

        return response()->json([
            'user'=> $user,
            'authId' => $token->user_id
        ]);
    }

    public function profile(Request $req){
        $token = Token::where('token', $req->header('token'))->where('expired', false)->first();
        $user = User::where('id', $token->user_id)
                ->with('profile')
                ->with('sendByfriends')
                ->with('recByfriends')
                ->with('request')
                ->with('sent')
                ->with('posts')
                ->first();

        return response()->json([
            'user'=> $user
        ]);
    }

    public function editprofile(Request $req){
        $user = User::where('id', $req->id)->first();
        $user->name = $req->name;

        $profile = Profile::where('user_id', $req->id)->first();
        if(!$profile){
            $profile= new Profile();
        }
        $profile->user_id = $req->id;
        $profile->bio = $req->bio;
        $profile->fb = $req->fb;
        $profile->instagram = $req->instagram;
        $profile->linkedin = $req->linkedin;
        $profile->github = $req->github;

        if($user->save() && $profile->save())return response()->json([
            'status'=> 200,
            'message' => 'Profile updated'
        ]);
        else return response()->json([
            'status'=>401,
            'message'=>'Something went wrong'
        ]);
    }

    public function username(Request $req){
        $token = Token::where('token', $req->header('token'))->where('expired', false)->first();
        $user = User::where('id', $token->user_id)->first();
        return response()->json([
            'user'=> $user
        ]);
    }
}
