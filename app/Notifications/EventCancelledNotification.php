<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Event $event,
        public string $cancellationReason = ''
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Event Cancelled: ' . $this->event->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We regret to inform you that the event "' . $this->event->title . '" has been cancelled.')
            ->lineIf($this->cancellationReason, 'Reason: ' . $this->cancellationReason)
            ->line('Your registration has been automatically cancelled.')
            ->lineIf($this->event->requires_registration, 'If you have any questions, please contact the organizer.')
            ->line('We apologize for any inconvenience caused.')
            ->line('Thank you for your understanding.');
    }

    public function toArray($notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'cancellation_reason' => $this->cancellationReason,
            'message' => 'Event "' . $this->event->title . '" has been cancelled.',
        ];
    }
}