<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status';

    const UPDATED_AT = null;

    public const NAMES = [
        'pending',
        'in_review',
        'missing_document',
        'approved',
        'rejected',
        'completed',
    ];

    protected $fillable = [
        'name',
    ];

    public function requests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'status_id');
    }
}
