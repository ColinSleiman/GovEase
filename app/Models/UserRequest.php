<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserRequest extends Model
{

    public function users(){
        return $this->belongsToMany(
            User::class,
            'user_requests',
            'request_id',
            'user_id'
        );
    }

    public function requests(){
        return $this->belongsToMany(
            Request::class,
            'user_requests',
            'user_id',
            'request_id'
        );
    }
}
