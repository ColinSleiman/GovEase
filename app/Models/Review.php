<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'rating',
        'comment',
        'user_id',
        'office_id',
        'request_id',
        'service_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
