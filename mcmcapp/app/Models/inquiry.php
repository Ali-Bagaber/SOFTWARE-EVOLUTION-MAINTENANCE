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
    public function assignedAgency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'assigned_agency_id', 'agency_id');
    }

    /**
     * Get formatted status label
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'Pending' => 'Pending Review',
            'Under Review' => 'Under Review',
            'Assigned' => 'Assigned to Agency',
            'In Progress' => 'In Progress',
            'Resolved' => 'Resolved',
            'Closed' => 'Closed',
            'Rejected' => 'Rejected',
            default => 'Unknown'
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'Pending' => 'warning',
            'Under Review' => 'info',
            'Assigned' => 'primary',
            'In Progress' => 'info',
            'Resolved' => 'success',
            'Closed' => 'secondary',
            'Rejected' => 'danger',
            default => 'secondary'
        };
    }
}
