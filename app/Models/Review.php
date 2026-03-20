<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    protected $fillable = [
        'rating',
        'comment',
        'user_id',
        'office_id'
    ];

    public function user() { return $this->belongsTo('User'); }
    public function office() { return $this->belongsTo('Office'); }
}
