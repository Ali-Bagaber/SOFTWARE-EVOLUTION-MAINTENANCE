<?php

namespace App\Models\Module4;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Inquiry extends Model
{
    protected $table = 'inquiries';
    protected $primaryKey = 'inquiry_id';

    protected $fillable = [
        'title',
        'content',
        'category',
        'status',
        'evidence_url',
        'media_attachment',
        'user_id',
        'investigation_notes',
        'public_user_id'
    ];

    // Inquiry statuses
    const STATUS_UNDER_INVESTIGATION = 'Under Investigation';
    const STATUS_VERIFIED_TRUE = 'Verified as True';
    const STATUS_FAKE = 'Identified as Fake';
    const STATUS_REJECTED = 'Rejected';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date_submitted' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }    

    public function history(): HasMany
    {
        return $this->hasMany(Inquiry_history::class);
    }

    public function scopeUnderInvestigation($query)
    {
        return $query->where('status', self::STATUS_UNDER_INVESTIGATION);
    }

    public function scopeVerifiedTrue($query)
    {
        return $query->where('status', self::STATUS_VERIFIED_TRUE);
    }

    public function scopeIdentifiedFake($query)
    {
        return $query->where('status', self::STATUS_FAKE);
    }    

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function verificationProcess()
    {
        return $this->hasOne(VerificationProcess::class, 'inquiry_id', 'inquiry_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function assignToAgency($selectedAgencyId)
    {
        $this->update([
            'agency_id' => $selectedAgencyId,
            // other fields as needed
        ]);
    }
}
