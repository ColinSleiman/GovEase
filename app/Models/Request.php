<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'qr_code',
        'tracking_number',
        'user_id',
        'service_id',
        'appointment_id',
        'status_id',
        'status_note',
        'reviewed_by',
    ];

    public static function getAllowedTransitions(): array
    {
        return [
            'pending' => ['in review'],
            'in review' => ['missing documents', 'approved', 'rejected'],
            'missing documents' => ['in review'],
            'approved' => ['completed'],
            'rejected' => ['completed'],
            'completed' => [],
        ];
    }

    public static function normalizeStatusName(string $statusName): string
    {
        return strtolower(trim($statusName));
    }

    public static function isTransitionAllowed(string $fromStatus, string $toStatus): bool
    {
        $from = self::normalizeStatusName($fromStatus);
        $to = self::normalizeStatusName($toStatus);
        $allowedTransitions = self::getAllowedTransitions();

        if (!array_key_exists($from, $allowedTransitions)) {
            return false;
        }

        return in_array($to, $allowedTransitions[$from], true);
    }

    public static function getTransitionErrorMessage(string $fromStatus, string $toStatus): string
    {
        return 'Invalid request status transition: ' . $fromStatus . ' -> ' . $toStatus . '.';
    }

    public function status() { return $this->belongsTo(Status::class); }
    public function service() { return $this->belongsTo(Service::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function documentRequests() { return $this->hasMany(DocumentRequest::class); }
    public function documents() { return $this->hasManyThrough(Document::class, DocumentRequest::class, 'request_id', 'id', 'id', 'document_id'); }

    /*
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    */

}
