<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    protected $table = 'user_requests';

    // Pivot entity with a composite primary key: (user_id, request_id).
    // We avoid relying on Eloquent route model binding for this model.
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'request_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id');
    }
}
