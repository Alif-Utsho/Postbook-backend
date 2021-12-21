<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    public function sender(){
        return $this->belongsTo(User::class, 'sender');
    }

    public function receiver(){
        return $this->belongsTo(User::class, 'receiver');
    }

    public function sender_profile(){
        return $this->hasOne(Profile::class, 'user_id', 'sender');
    }

    public function receiver_profile(){
        return $this->hasOne(Profile::class, 'user_id', 'receiver');
    }
    use HasFactory;
}
