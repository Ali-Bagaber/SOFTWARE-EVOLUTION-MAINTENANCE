<?php

namespace App\Models\Module4;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class VerificationProcess extends Model
{
    protected $table = 'verification_processes';

    protected $fillable = [
        'inquiry_id',
        'status',
        'verified_by',
        'verification_date',
        'remarks',
        'evidence_checked',
        'verification_method'
    ];

    protected $casts = [
        'verification_date' => 'datetime',
        'evidence_checked' => 'boolean'
    ];

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class, 'inquiry_id', 'inquiry_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
