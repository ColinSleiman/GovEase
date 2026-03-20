<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'office_id',
        'service_category_id'
    ];

    public function office() { return $this->belongsTo(Office::class); }

    public function serviceCategory() { return $this->belongsTo(ServiceCategory::class); }

    public function requests() { return $this->hasMany(Request::class, 'service_id'); }

    public function appointments() { return $this->hasMany(Appointment::class, 'service_id'); }
}
