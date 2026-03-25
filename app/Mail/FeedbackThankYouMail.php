<?php
// app/Mail/FeedbackThankYouMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Thank You for Your Feedback - Jimma University')
            ->markdown('emails.feedback.thank-you')
            ->with($this->data);
    }
}