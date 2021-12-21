<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory;

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function request(){
        return $this->hasMany(Connection::class, 'receiver')->where('status', 'follower');
    }

    public function sent(){
        return $this->hasMany(Connection::class, 'sender')->where('status', 'follower');
    }

    // public function friends(){
    //     $var = $this->hasMany(Connection::class, 'receiver')->where('status', 'friend');
    //     $var2 = $this->hasMany(Connection::class, 'sender')->where('status', 'friend');
    //     // $friends = array_combine($var, $var2);

    //     foreach($var2 as $v) {
    //         $var->add($v);
    //     }
    //     return $var;
    //     // foreach($var as $key=>$value) {
    //     //     $var2[$value->id] = $value;
    //     // }
    
    //     // $json = json_encode($var);
    //     // $json2 = json_encode($var2);

    //     // $friends = [];
    //     // $friends = json_decode($var.$var2);
    //     // foreach($var2 as $key=>$value){
    //     //     $friends[$value->id] = $value;
    //     // }

    //     // asort($var);
    //     // $friends = $var + $var2;
    //     // $friends = [...$var, ...$var2];
    //     // return $array2;
    //     // return [...$var, ...$var2];
    //     // $friends = [...$var, ...$var2];
    //     // return $friends;
    //     // return $this->hasMany(Connection::class, 'sender')->where('status', 'friend');
    // }

    public function sendByfriends(){
        return $this->hasMany(Connection::class, 'sender')->where('status', 'friend');
    }

    public function recByfriends(){
        return $this->hasMany(Connection::class, 'receiver')->where('status', 'friend');
    }

    // public function friends(){
    //     return $this->rec_friends()->toBase()->add($this->send_friends());
    // }


    public function posts(){
        return $this->hasMany(Post::class);
    }
}




// {
//     use HasApiTokens, HasFactory, Notifiable;

//     /**
//      * The attributes that are mass assignable.
//      *
//      * @var string[]
//      */
//     protected $fillable = [
//         'name',
//         'email',
//         'password',
//     ];

//     /**
//      * The attributes that should be hidden for serialization.
//      *
//      * @var array
//      */
//     protected $hidden = [
//         'password',
//         'remember_token',
//     ];

//     /**
//      * The attributes that should be cast.
//      *
//      * @var array
//      */
//     protected $casts = [
//         'email_verified_at' => 'datetime',
//     ];
// }
