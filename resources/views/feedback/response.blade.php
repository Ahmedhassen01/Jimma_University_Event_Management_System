{{-- resources/views/emails/feedback/response.blade.php --}}
@component('mail::message')
# Response to Your Feedback

Hello {{ $feedback->submitter_name }},

Thank you for your feedback submitted on {{ $feedback->created_at->format('F d, Y') }}.

**Our Response:**

{!! nl2br(e($response->message)) !!}

**Your Original Feedback:**
- **Type:** {{ ucfirst($feedback->feedback_type) }}
@if($feedback->subject)
- **Subject:** {{ $feedback->subject }}
@endif
- **Submitted:** {{ $feedback->created_at->format('F d, Y') }}

If you have any further questions or would like to discuss this further, please reply to this email or contact our support team.

@component('mail::button', ['url' => route('home'), 'color' => 'primary'])
Visit Our Events Portal
@endcomponent

Thank you for helping us improve!

Best regards,<br>
**Jimma University Event Management Team**<br>
{{ $response->responder->name ?? 'Administrator' }}
@endcomponent