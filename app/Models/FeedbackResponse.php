<?php
// app/Models/FeedbackResponse.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackResponse extends Model
{
    protected $fillable = [
        'feedback_id',
        'responded_by',
        'message',
        'send_email',
        'email_sent_at',
    ];

    protected $casts = [
        'send_email' => 'boolean',
        'email_sent_at' => 'datetime',
    ];

    public function feedback(): BelongsTo
    {
        return $this->belongsTo(Feedback::class);
    }

    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function markEmailAsSent()
    {
        $this->update(['email_sent_at' => now()]);
    }
}