<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{

    protected $fillable = [
        'qr_code',
        'tracking_number',
        'status_id',
        'service_id',
        'appointment_id',
        'payment_id'
    ];

    public function status() { return $this->belongsTo(Status::class); }

    public function service() { return $this->belongsTo(Service::class); }

    public function appointment() { return $this->belongsTo(Appointment::class); }

    public function payment() { return $this->hasOne(Payment::class); }

    public function documentRequests() { return $this->hasMany(DocumentRequest::class); }
}
