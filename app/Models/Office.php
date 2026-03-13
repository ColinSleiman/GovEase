<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Office extends Model
{
    protected $table = 'office';
    protected $fillable = [
        'name',
        'address',
        'google_maps_location',
        'working_hours',
        'contact_info',
        'municipality_id',
    ];
    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'office_id');
    }
}
