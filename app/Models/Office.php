<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
        'name',
        'address',

        'google_maps_location',
        'latitude', 
        'longitude',

        'working_hours',
        'contact_info',
        
        'municipality_id'
    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function requests()
    {
        return $this->hasManyThrough(Request::class, Service::class, 'office_id', 'service_id');
    }

    public function serviceCategories()
    {
        return $this->hasMany(ServiceCategory::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
