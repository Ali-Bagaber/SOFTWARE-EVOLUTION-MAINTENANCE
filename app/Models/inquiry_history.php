<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class inquiry_history extends Model
{
    protected $table = 'inquiry_histories';
    
    protected $fillable = [
        'inquiry_id',
        'new_status',
        'user_id',
        'timestamp',
        'agency_id'
    ];

    protected $casts = [
        'timestamp' => 'datetime'
    ];

    /**
     * Get the inquiry this history belongs to
     */
    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class, 'inquiry_id');
    }

    /**
     * Get the user who made the change
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get formatted status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->new_status) {
            'Pending' => 'Pending',
            'Under Investigation' => 'Under Investigation',
            'Verified as True' => 'Verified as True',
            'Identified as Fake' => 'Identified as Fake',
            'Rejected' => 'Rejected',
            default => 'Unknown'
        };
    }
}