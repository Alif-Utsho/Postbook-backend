<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Post;
use App\Models\React;
use App\Models\Token;
use App\Models\Comment;
use App\Models\Report;

class AdminController extends Controller
{
    //
    public function countAll(){
        $posts = Post::all()->count();
        $users = User::all()->count();
        $comments = Comment::all()->count();
        $reacts = React::all()->count();
        $reports = Report::all()->count();

        return response()->json([
            'posts' => $posts,
            'users' => $users,
            'comments' => $comments,
            'reacts' => $reacts,
            'reports' => $reports
        ]);
    }
}
