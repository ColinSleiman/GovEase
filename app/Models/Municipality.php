<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Municipality extends Model
{
    protected $table = 'municipality';
    protected $fillable = [
        'name',
        'region',
    ];
    public function offices(): HasMany
    {
        return $this->hasMany(Office::class, 'municipality_id');
    }
}
