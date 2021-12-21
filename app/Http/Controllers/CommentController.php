<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\React;
use App\Models\Comment;
use App\Models\Token;

class CommentController extends Controller
{
    //

    public function createcomment(Request $req){
        $token = Token::where('token', $req->header('token'))->where('expired', false)->first();
        $comment = new Comment();
        $comment->post_id = $req->post_id;
        $comment->user_id = $token->user_id;
        $comment->desc = $req->desc;

        if($comment->save()){
            return response()->json([
                'message'=> "Your comment submitted successfully"
            ]);
        }
    }

    public function comments(Request $req){
        $comments = Comment::where('post_id', $req->post_id)->with('user')->orderBy('created_at', 'asc')->get();
        if($comments){
            return response()->json([
                'comments'=> $comments
            ]);
        }
    }
    
    public function editComment(Request $req){
        $post = Comment::find($req->id);
        $post->desc = $req->desc;
        if($post->save()){
            return response()->json([
                'message'=> 'Comment updated'
            ]);
        }
    }

    public function deleteComment(Request $req){
        $var = Comment::find($req->id);
        
        if($var->delete()){
            return response()->json([
                'message'=> 'Comment Deleted'
            ]);
        }
        
    }
}
