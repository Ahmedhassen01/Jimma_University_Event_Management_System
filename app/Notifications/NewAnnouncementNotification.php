<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAnnouncementNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Announcement $announcement
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Announcement: ' . $this->announcement->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new announcement has been published:')
            ->line('**' . $this->announcement->title . '**')
            ->line($this->announcement->content)
            ->action('View Announcement', route('announcements.show', $this->announcement->id))
            ->line('Thank you for using Jimma University Event Management System!');
    }

    public function toArray($notifiable): array
    {
        return [
            'announcement_id' => $this->announcement->id,
            'announcement_title' => $this->announcement->title,
            'announcement_type' => $this->announcement->announcement_type,
            'action_url' => route('announcements.show', $this->announcement->id),
            'message' => 'New announcement: ' . $this->announcement->title,
        ];
    }
}