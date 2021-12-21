<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Post;
use App\Models\React;
use App\Models\Token;
use App\Models\Report;
use App\Models\Comment;

class PostController extends Controller
{
    //
    public function posts(Request $req){
        
        $token = Token::where('token', $req->header('token'))->where('expired', false)->first();

        $posts = Post::with('user')
                ->with('reacts')
                ->with('comments')
                ->with('reports')
                ->where('user_id','!=', $token->user_id)
                ->orderBy('created_at', 'desc')
                ->get();
        return response()->json([
            'posts' => $posts
        ]);
    }


    public function postofuser(Request $req){
        if($req->id === 'auth'){
            $token = Token::where('token', $req->header('token'))->where('expired', false)->first();
            $id = $token->user_id;
        }
        else $id = $req->id;
        $posts = Post::with('user')
                ->with('reacts')
                ->with('comments')
                ->where('user_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

        return response()->json([
            'posts'=>$posts
        ]);
    }

    public function singlePost(Request $req){
        $token = Token::where('token', $req->header('token'))->where('expired', false)->first();

        $post = Post::where('id', $req->id)
            ->with('user')
            ->with('reacts')
            ->with('comments')
            ->with('reports')
            ->first();

        $user = User::where('id', $token->user_id)
                ->with('profile')
                ->with('sendByfriends')
                ->with('recByfriends')
                ->with('request')
                ->with('sent')
                ->with('posts')
                ->first();
        return response()->json([
            'post'=>$post,
            'authuser'=>$user
        ]);
    }

    public function createPost(Request $req){

        $token = Token::where('token', $req->header('token'))->where('expired', false)->first();
        $var = new Post();
        $var->user_id = $token->user_id;
        $var->desc = $req->desc;
        if($var->save()){
            return response()->json([
                'post' => $req->desc,
                'message'=> "New post created successfully"
            ]);
        }
        else{
            return response()->json([
                'message' => 'something went wrong'
            ]);
        }
    }

    public function editPost(Request $req){
        $post = Post::find($req->id);
        $post->desc = $req->desc;
        if($post->save()){
            return response()->json([
                'message'=> 'Post updated successfully'
            ]);
        }
    }

    public function deletePost(Request $req){
        $var = Post::find($req->id);

        $reacts = React::where('post_id', $req->id)->get();
        $comments = Comment::where('post_id', $req->id)->get();
        $reports = Report::where('post_id', $req->id)->get();

        if(count($reacts) > 0 ) $reacts->each->delete();
        if(count($comments) > 0 ) $comments->each->delete();
        if(count($reports) > 0 ) $reports->each->delete();

        if($var->delete()){
            return response()->json([
                'message'=> 'Post deleted successfully'
            ]);
        }
    }

    public function commentoff(Request $req){
        $post = Post::find($req->id);
        $post->isComment = false;
        
        if($post->save()){
            return response()->json([
                'message'=> "The post won't take any comment"
            ], 200);
        }
        
    }

    public function commenton(Request $req){
        $post = Post::find($req->id);
        $post->isComment = true;

        if($post->save()){
            return response()->json([
                'message'=> "The post start taking comment"
            ], 200);
        }
    }
}
