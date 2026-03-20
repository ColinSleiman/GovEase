<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

    protected $fillable = ['name'];

    public function requests() { return $this->hasMany(Request::class); }

    public function appointments() { return $this->hasMany(Appointment::class, 'status_id'); }

    public function payments() { return $this->hasMany(Payment::class); }
}
