<?php

namespace App\Models\Models_Module3;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\inquiry;
use App\Models\Agency;

class VerificationProcess extends Model
{
    protected $table = 'verification_processes';
    protected $primaryKey = 'VerificationProcess_id';

    protected $fillable = [
        'inquiry_id',
        'MCMC_ID',
        'staff_agency_id',
        'assigned_date',
        'note',
        'status_update',
        'explanation_text',
        'confirmed_at'
    ];

    protected $casts = [
        'assigned_date' => 'datetime',
        'confirmed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the inquiry that this verification process belongs to
     */
    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(inquiry::class, 'inquiry_id', 'inquiry_id');
    }

    /**
     * Get the agency that performed the verification
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'staff_agency_id', 'agency_id');
    }

    /**
     * Get the MCMC staff who assigned this
     */
    public function mcmcStaff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'MCMC_ID', 'user_id');
    }

    /**
     * Create a new verification record for accept action
     */
    public static function createAcceptRecord($inquiryId, $staffAgencyId, $mcmcId = null, $note = null)
    {
        return self::create([
            'inquiry_id' => $inquiryId,
            'staff_agency_id' => $staffAgencyId,
            'MCMC_ID' => $mcmcId,
            'assigned_date' => now(),
            'status_update' => 'accepted',
            'note' => $note,
            'explanation_text' => 'Inquiry accepted by agency staff.',
            'confirmed_at' => now()
        ]);
    }

    /**
     * Create a new verification record for reject action
     */
    public static function createRejectRecord($inquiryId, $staffAgencyId, $rejectionData, $mcmcId = null)
    {
        $explanationText = "Inquiry rejected. Reason: " . ($rejectionData['rejection_reason'] ?? 'Not specified');
        if (!empty($rejectionData['rejection_comments'])) {
            $explanationText .= ". Comments: " . $rejectionData['rejection_comments'];
        }
        if (!empty($rejectionData['suggested_agency'])) {
            $explanationText .= ". Suggested agency: " . $rejectionData['suggested_agency'];
        }

        return self::create([
            'inquiry_id' => $inquiryId,
            'staff_agency_id' => $staffAgencyId,
            'MCMC_ID' => $mcmcId,
            'assigned_date' => now(),
            'status_update' => 'rejected',
            'note' => $rejectionData['rejection_comments'] ?? null,
            'explanation_text' => $explanationText,
            'confirmed_at' => now()
        ]);
    }

    /**
     * Get the status label
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status_update) {
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'pending' => 'Pending Verification',
            'assigned' => 'Assigned',
            default => 'Unknown'
        };
    }

    /**
     * Get the status color for badges
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status_update) {
            'accepted' => 'success',
            'rejected' => 'danger',
            'pending' => 'warning',
            'assigned' => 'info',
            default => 'secondary'
        };
    }
}