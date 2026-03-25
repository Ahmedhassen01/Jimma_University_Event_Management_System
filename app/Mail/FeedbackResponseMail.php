<?php
// app/Mail/FeedbackResponseMail.php

namespace App\Mail;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $feedback;
    public $response;

    public function __construct(Feedback $feedback, $response)
    {
        $this->feedback = $feedback;
        $this->response = $response;
    }

    public function build()
    {
        return $this->subject('Response to Your Feedback: ' . ($this->feedback->subject ?: 'No Subject'))
            ->markdown('emails.feedback.response')
            ->with([
                'feedback' => $this->feedback,
                'response' => $this->response,
            ]);
    }
}