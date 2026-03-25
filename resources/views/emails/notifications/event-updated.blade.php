{{-- resources/views/emails/notifications/event-updated.blade.php --}}
@component('mail::message')
# Event Updated: {{ $event->title }}

Hello {{ $notifiable->name ?? 'Participant' }},

The event **"{{ $event->title }}"** has been updated.

@if(!empty($changes))
**Changes Made:**
@foreach($changes as $key => $value)
@if(is_string($value))
- **{{ ucfirst($key) }}:** {{ $value }}
@endif
@endforeach
@endif

**Updated Event Details:**
- **Date:** {{ $event->start_date->format('F d, Y') }}
- **Time:** {{ $event->start_date->format('h:i A') }} - {{ $event->end_date->format('h:i A') }}
- **Venue:** {{ $event->venue }}, {{ $event->campus ?? 'Main Campus' }}
@if($event->contact_email)
- **Contact:** {{ $event->contact_email }}
@endif

@component('mail::button', ['url' => route('events.guest.show', $event->slug), 'color' => 'primary'])
View Updated Event
@endcomponent

If you have any questions, please contact the organizer.

Thank you,<br>
Jimma University Event Management System
@endcomponent