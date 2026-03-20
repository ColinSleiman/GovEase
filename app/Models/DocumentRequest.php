<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    protected $table = 'document_requests';

    // Pivot entity with a composite primary key: (request_id, document_id).
    // We avoid relying on Eloquent route model binding for this model.
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'request_id',
        'document_id'
    ];

    public function request() { return $this->belongsTo(Request::class); }

    public function document() { return $this->belongsTo(Document::class); }
}
