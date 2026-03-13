<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Service extends Model
{
    protected $table = 'service';
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'office_id',
        'service_category_id',
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}
