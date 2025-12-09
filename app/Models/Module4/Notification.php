<?php

namespace App\Models\Module4;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'inquiry_id',
        'user_id',
        'message', 
        'date_sent',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'date_sent' => 'datetime'
    ];

    public static function createNotification(int $userId, int $inquiryId, string $message): self
    {
        \Log::info('Creating notification', [
            'user_id' => $userId,
            'inquiry_id' => $inquiryId,
            'message' => $message
        ]);

        return static::create([
            'user_id' => $userId,
            'inquiry_id' => $inquiryId,
            'message' => $message,
            'date_sent' => now(),
            'is_read' => false
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }
}
