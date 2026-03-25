<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Event $event,
        public array $changes = [],
        public string $updateType = 'general'
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Event Updated: ' . $this->event->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('The event "' . $this->event->title . '" has been updated.')
            ->lineIf(!empty($this->changes), 'Changes made:')
            ->lineIf(isset($this->changes['date']), '• Date/Time: ' . $this->changes['date'])
            ->lineIf(isset($this->changes['venue']), '• Venue: ' . $this->changes['venue'])
            ->lineIf(isset($this->changes['description']), '• Description updated')
            ->action('View Event', route('events.guest.show', $this->event->slug))
            ->line('Thank you for using Jimma University Event Management System!');
    }

    public function toArray($notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'update_type' => $this->updateType,
            'changes' => $this->changes,
            'action_url' => route('events.guest.show', $this->event->slug),
            'message' => 'Event "' . $this->event->title . '" has been updated.',
        ];
    }
}