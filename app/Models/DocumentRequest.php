<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{

    protected $fillable = [
        'request_id',
        'document_id'
    ];

    public function request() { return $this->belongsTo(Request::class); }

    public function document() { return $this->belongsTo(Document::class); }
}
