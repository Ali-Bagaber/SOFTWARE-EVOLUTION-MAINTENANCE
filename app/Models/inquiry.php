<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inquiry extends Model
{
    protected $table = 'inquiries';
    protected $primaryKey = 'inquiry_id';

    protected $fillable = [
        'public_user_id',
        'title',
        'status',
        'content',
        'media_attachment',
        'evidence_url',
        'date_submitted',
        'category'
    ];

    protected $casts = [
        'date_submitted' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'assigned_at' => 'datetime',
    ];

    /**
     * Get the user that submitted the inquiry
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'public_user_id', 'user_id');
    }    /**
         * Get the history of this inquiry
         */
    public function history(): HasMany
    {
        return $this->hasMany(inquiry_history::class, 'inquiry_id', 'inquiry_id');
    }

    /**
     * Get the assigned agency for this inquiry
     */
    public function verificationProcesses()
    {
        return $this->hasMany(VerificationProcess::class, 'inquiry_id', 'inquiry_id');
    }

    /**
     * Get the user who assigned this inquiry
     */
    public function assignedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by', 'user_id');
    }

    /**
     * Get formatted status label
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'under investigation' => 'Under Investigation',
            'Verified as True' => 'Verified as True',
            'Identified as Fake' => 'Identified as Fake',
            'rejected' => 'Rejected',
            default => 'Unknown'
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'under investigation' => 'info',
            'Verified as True' => 'success',
            'Identified as Fake' => 'danger',
            'rejected' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get public-friendly status for inquiry tracking
     */
    public function getPublicStatusAttribute()
    {
        return match ($this->status) {
            'Pending', 'pending' => 'Pending',
            'Under Investigation', 'under investigation', 'Assigned' => 'Under Investigation',
            'Verified as True' => 'Verified as True',
            'Identified as Fake' => 'Identified as Fake',
            'Rejected', 'rejected' => 'Rejected',
            'Completed', 'Closed' => 'Investigation Complete',
            default => $this->status
        };
    }

    /**
     * Get public status description
     */
    public function getPublicStatusDescriptionAttribute()
    {
        return match ($this->public_status) {
            'Under Investigation' => 'Your inquiry is being reviewed by the assigned agency.',
            'Verified as True' => 'The information has been confirmed as genuine news.',
            'Identified as Fake' => 'The information has been determined to be false or misleading.',
            'Rejected' => 'The inquiry was dismissed due to irrelevance or lack of sufficient evidence.',
            'Investigation Complete' => 'The investigation has been completed.',
            default => 'Your inquiry is being processed.'
        };
    }

    /**
     * Get public status color for display
     */
    public function getPublicStatusColorAttribute()
    {
        return match ($this->public_status) {
            'Under Investigation' => 'info',
            'Verified as True' => 'success',
            'Identified as Fake' => 'danger',
            'Rejected' => 'warning',
            'Investigation Complete' => 'secondary',
            default => 'info'
        };
    }

    /**
     * Check if inquiry was verified as true
     */
    private function isVerifiedTrue()
    {
        // Check if there's a verification record showing it's true
        if (class_exists('\App\Models\Models_Module3\VerificationProcess')) {
            $verification = \App\Models\Models_Module3\VerificationProcess::where('inquiry_id', $this->inquiry_id)
                ->where('status_update', 'accepted')
                ->first();
            return $verification !== null;
        }
        return false;
    }
}
