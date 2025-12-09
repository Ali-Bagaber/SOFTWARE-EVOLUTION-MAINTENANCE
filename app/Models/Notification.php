<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    // Use the notifications table
    protected $table = 'notifications';

    // Primary key is notification_id
    protected $primaryKey = 'notification_id';

    // Allow mass assignment on these fields
    protected $fillable = [
        'inquiry_id',
        'user_id',
        'message',
    ];

    // Enable timestamps (created_at, updated_at)
    public $timestamps = true;

    // Cast date_sent to a datetime
    protected $dates = ['date_sent'];
}
