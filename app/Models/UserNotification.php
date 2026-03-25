<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserNotification extends Model
{
    use HasFactory;

    protected $table = 'user_notifications';
    
    protected $fillable = [
        'user_id',
        'notification_id',
        'read_at',
        'dismissed_at',
        'email_sent',
        'email_sent_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'dismissed_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'email_sent' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function markAsRead()
    {
        $this->read_at = now();
        $this->save();
    }

    public function markAsUnread()
    {
        $this->read_at = null;
        $this->save();
    }

    public function dismiss()
    {
        $this->dismissed_at = now();
        $this->save();
    }

    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function isDismissed()
    {
        return !is_null($this->dismissed_at);
    }
}