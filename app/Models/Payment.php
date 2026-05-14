<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //

    protected $fillable = [
        'amount',
        'payment_method',
        'status',
        'status_id',
        'transaction_reference',
        'request_id'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
