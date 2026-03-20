<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{

    protected $fillable = [
        'file_path',
        'document_type',
        'uploaded_by'
    ];

    public function documentRequests() { return $this->hasMany(DocumentRequest::class); }

    public function uploadedBy() { return $this->belongsTo(User::class); }
}
