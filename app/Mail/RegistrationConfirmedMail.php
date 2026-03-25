<?php
// app/Mail/RegistrationConfirmedMail.php

namespace App\Mail;

use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrationConfirmedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $registration;

    /**
     * Create a new message instance.
     */
    public function __construct(EventRegistration $registration)
    {
        $this->registration = $registration->load(['event', 'user']);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'Registration Confirmed: ' . $this->registration->event->title;

        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($subject)
            ->view('emails.registration-confirmed')
            ->with([
                'registration' => $this->registration,
                'event' => $this->registration->event,
                'user' => $this->registration->user,
            ]);
    }
}