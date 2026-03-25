<?php
// app/Mail/CustomNotificationMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $message;
    public $data;

    public function __construct(string $title, string $message, array $data = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject($this->title)
            ->markdown('emails.notifications.custom')
            ->with([
                'title' => $this->title,
                'content' => $this->message,
                'data' => $this->data,
            ]);
    }
}