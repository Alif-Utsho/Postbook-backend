<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Connection;
use App\Models\Token;

class ConnectionController extends Controller
{
    //
    private $token;

    public function users(Request $req){
        $token = Token::where('token', $req->header('token'))->where('expired', false)->first();

        $users = User::with('profile')
                ->with('sendByfriends')
                ->with('recByfriends')
                ->with('request')
                ->with('sent')
                ->where('type', 'users')
                ->where('id', '!=', $token->user_id)
                ->get();
        return response()->json([
            'users' => $users,
            'authId' => $token->user_id
        ]);
    }


    public function connection(Request $req){

        $token = Token::where('token', $req->header('token'))->where('expired', false)->first();

        $request = Connection::where('receiver', $token->user_id)
                ->where('status', 'follower')
                ->with('sender_profile')
                ->with('receiver_profile')
                ->with('sender')
                ->with('receiver')
                ->orderByDesc('created_at')
                ->get();

        $sent = Connection::where('sender', $token->user_id)
                ->where('status', 'follower')
                ->with('sender_profile')
                ->with('receiver_profile')
                ->with('sender')
                ->with('receiver')
                ->orderByDesc('created_at')
                ->get();
        
        $friend = Connection::where(function($query) use ($token) {
                                $query->where('receiver', $token->user_id)
                                      ->where('status', 'friend');
                            })
                ->orWhere(function($query) use($token) {
                    $query->where('sender', $token->user_id)
                          ->where('status', 'friend');
                })
                ->with('sender_profile')
                ->with('receiver_profile')
                ->with('sender')
                ->with('receiver')
                ->orderByDesc('created_at')
                ->get();

        return response()->json([
            'request' => $request,
            'sent' => $sent,
            'friends' => $friend,
            'authId'=>$token->user_id
        ]);
    }

    public function addFriend(Request $req){
        $token = Token::where('token', $req->header('token'))->where('expired', false)->first();
        // return response()->json([
        //     'token'=>$token
        // ]);
        $var = new Connection();
        $var->sender = $token->user_id;
        $var->receiver = $req->receiver;
        $session = session()->get('user');
        if($var->save()){
            return response()->json([
                'status' => 200,
                'message' => $var,
                'session' => $session
            ]);
        }
    }

    public function confirmRequest(Request $req){
        $id = $req->id;
        $request = Connection::find($id);
        $request->status = 'friend';

        if($request->save()) return response()->json([
            'request' => $request
        ]);
    }

    public function cancelRequest(Request $req){
        $id = $req->id;
        $request = Connection::find($id);
        
        if($request->delete()) return response()->json([
            'status' => 200
        ]);
    }

    public function unfriend(Request $req){
        $id = $req->id;
        $friend = Connection::find($id);

        if($friend->delete()) return response()->json([
            'status' => 200
        ]);
    }
}
