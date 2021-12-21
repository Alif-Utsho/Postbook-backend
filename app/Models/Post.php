<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function reacts(){
        return $this->hasMany(React::class);
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function reports(){
        return $this->hasMany(Report::class);
    }
    // public function reacts(){
    //     return $this->hasMany(React::class, 'user_id');
    // }
}
