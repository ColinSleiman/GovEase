<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $table = 'request';

    protected $fillable = [
        'qr_code',
        'tracking_number',
        'user_id',
        'service_id',
        'appointment_id',
        'status_id',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'request_id');
    }

    public function documentRequests(): HasMany
    {
        return $this->hasMany(DocumentRequest::class, 'request_id');
    }
}
