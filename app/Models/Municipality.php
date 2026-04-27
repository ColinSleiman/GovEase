<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{

    protected $fillable = [
        'name',
        'region',

        'address',
        'google_maps_location',
        'latitude',
        'longitude',

        'working_hours',
        'contact_info'
    ];

    public function offices() { return $this->hasMany(Office::class); }
}
