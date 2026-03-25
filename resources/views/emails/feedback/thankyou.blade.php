{{-- resources/views/emails/feedback/thank-you.blade.php --}}
@component('mail::message')
# Thank You for Your Feedback!

Hello {{ $name ?? 'Valued User' }},

Thank you for taking the time to submit your feedback to Jimma University Event Management System. We truly appreciate your input.

**Feedback Details:**
- **Type:** {{ ucfirst($feedback_type) }}
@if($subject)
- **Subject:** {{ $subject }}
@endif

Your feedback has been successfully submitted and will be reviewed by our team. We value your opinion as it helps us improve our services and events.

**What happens next?**
1. Your feedback will be reviewed by our administration team
2. If you requested a response, we will get back to you within 48 hours
3. Your suggestions will be considered for future improvements

If you have any urgent questions, please don't hesitate to contact us.

@component('mail::button', ['url' => route('home'), 'color' => 'primary'])
Visit Our Events Portal
@endcomponent

Thank you again for helping us make Jimma University events better!

Best regards,<br>
**Jimma University Event Management Team**<br>
Email: events@ju.edu.et<br>
Phone: +251 47 111 0000
@endcomponent