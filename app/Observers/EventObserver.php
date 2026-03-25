<?php

namespace App\Observers;

use App\Models\Event;
use App\Services\NotificationService;

class EventObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        // Check if important fields were changed
        $changedFields = [];
        $importantFields = ['start_date', 'end_date', 'venue', 'campus', 'description'];
        
        foreach ($importantFields as $field) {
            if ($event->wasChanged($field)) {
                $changedFields[$field] = [
                    'from' => $event->getOriginal($field),
                    'to' => $event->getAttribute($field),
                ];
            }
        }
        
        // Send notification if important fields changed
        if (!empty($changedFields) && $event->has('registrations')) {
            $this->notificationService->sendEventUpdateNotification($event, $changedFields);
        }
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        // Send cancellation notification if event had registrations
        if ($event->registrations()->exists()) {
            $this->notificationService->sendEventCancellationNotification(
                $event,
                'Event has been cancelled by the organizer.'
            );
        }
    }
}