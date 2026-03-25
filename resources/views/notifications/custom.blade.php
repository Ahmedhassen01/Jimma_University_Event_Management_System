{{-- resources/views/emails/notifications/custom.blade.php --}}
@component('mail::message')
# {{ $title }}

{!! nl2br(e($content)) !!}

@if(!empty($data))
**Additional Information:**
@foreach($data as $key => $value)
@if(is_string($value))
- **{{ ucfirst(str_replace('_', ' ', $key)) }}:** {{ $value }}
@endif
@endforeach
@endif

@component('mail::button', ['url' => route('dashboard'), 'color' => 'primary'])
Go to Dashboard
@endcomponent

Thank you,<br>
Jimma University Event Management System
@endcomponent