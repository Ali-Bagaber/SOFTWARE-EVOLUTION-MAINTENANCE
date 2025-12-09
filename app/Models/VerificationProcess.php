<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationProcess extends Model
{
    protected $table = 'verification_processes';
    protected $primaryKey = 'verificationprocess_id';
    
    protected $fillable = [
        'inquiry_id',
        'MCMC_ID',
        'staff_agency_id',
        'assigned_at',
        'notes',
        'rejection_reason',
        'explanation_text',
        'confirmed_at',
        'priority'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    /**
     * Get the inquiry this verification process belongs to
     */
    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class, 'inquiry_id', 'inquiry_id');
    }

    /**
     * Get the agency assigned to this verification process
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'staff_agency_id', 'agency_id');
    }
}
