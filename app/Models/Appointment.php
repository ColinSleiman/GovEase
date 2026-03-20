<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Appointment extends Model
{

    protected $fillable = [
        'appointment_date',
        'appointment_time',
        'status_id',
        'user_id',
        'office_id',
        'service_id',
    ];

    public function status() { return $this->belongsTo(Status::class); }

    public function user() { return $this->belongsTo(User::class); }

    public function office() { return $this->belongsTo(Office::class); }

    public function service() { return $this->belongsTo(Service::class); }
}
