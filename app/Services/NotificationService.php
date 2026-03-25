<?php
// app/Services/NotificationService.php
namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    public function getStatistics($startDate, $endDate)
    {
        // ... existing code ...
    }
    
    public function sendCustomNotification($recipientIds, $title, $message, $eventId, $priority, $recipientType)
    {
        // Create the notification
        $notification = Notification::create([
            'type' => 'custom',
            'title' => $title,
            'message' => $message,
            'priority' => $priority,
            'event_id' => $eventId,
            'sender_id' => auth()->id(),
            'recipient_type' => $recipientType,
            'recipient_ids' => $recipientIds,
            'sent_at' => now(),
        ]);
        
        // Determine recipients
        $users = collect();
        
        if ($recipientType === 'all') {
            $users = User::where('is_active', true)->get();
        } elseif (!empty($recipientIds)) {
            $users = User::whereIn('id', $recipientIds)->where('is_active', true)->get();
        }
        
        // Attach users to notification
        if ($users->isNotEmpty()) {
            foreach ($users as $user) {
                UserNotification::create([
                    'user_id' => $user->id,
                    'notification_id' => $notification->id,
                    'read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        return [
            'notification' => $notification,
            'count' => $users->count(),
        ];
    }
}