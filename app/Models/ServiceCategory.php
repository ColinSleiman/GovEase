<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $fillable = [
        'name',
        'office_id',
    ];

    public function office() { return $this->belongsTo(Office::class); }
    public function services() { return $this->hasMany(Service::class); }
    public function requests() { return $this->hasManyThrough(Request::class, Service::class, 'service_category_id', 'service_id');}
}
