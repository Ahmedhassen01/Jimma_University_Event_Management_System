<!-- resources/views/emails/registration-confirmed.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmed</title>
    <style>
        body {
            font-family: 'Open Sans', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(145deg, #002789 0%, #001a5c 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 12px 12px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #e9ecef;
            border-top: none;
            border-radius: 0 0 12px 12px;
        }
        .badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .event-details {
            background: #f8f9fa;
            border-left: 4px solid #002789;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .event-details h3 {
            margin: 0 0 15px;
            color: #002789;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .detail-label {
            width: 120px;
            font-weight: 600;
            color: #666;
        }
        .detail-value {
            flex: 1;
            color: #333;
        }
        .registration-number {
            background: #e9ecef;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            margin: 20px 0;
        }
        .registration-number h4 {
            margin: 0 0 5px;
            color: #666;
            font-size: 14px;
        }
        .registration-number .number {
            font-size: 24px;
            font-weight: 700;
            color: #002789;
            letter-spacing: 2px;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code img {
            width: 150px;
            height: 150px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(145deg, #002789 0%, #001a5c 100%);
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            background: #001a5c;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #999;
            font-size: 14px;
        }
        .footer a {
            color: #002789;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registration Confirmed!</h1>
            <p>Jimma University Event Management System</p>
        </div>
        
        <div class="content">
            <div style="text-align: center;">
                <span class="badge">✓ CONFIRMED</span>
            </div>
            
            <p>Dear <strong>{{ $user->name }}</strong>,</p>
            
            <p>Your registration for the following event has been <strong style="color: #28a745;">confirmed</strong>. We're excited to have you join us!</p>
            
            <div class="event-details">
                <h3>{{ $event->title }}</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ $event->start_date->format('l, F j, Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Time:</span>
                    <span class="detail-value">{{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Venue:</span>
                    <span class="detail-value">{{ $event->venue_name }}, {{ $event->building_name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Campus:</span>
                    <span class="detail-value">{{ $event->campus_name }}</span>
                </div>
                
                @if($event->contact_email)
                <div class="detail-row">
                    <span class="detail-label">Contact:</span>
                    <span class="detail-value">{{ $event->contact_email }}</span>
                </div>
                @endif
            </div>
            
            <div class="registration-number">
                <h4>Your Registration Number</h4>
                <div class="number">{{ $registration->registration_number }}</div>
            </div>
            
            <div class="detail-row" style="justify-content: center; margin: 20px 0;">
                <span class="detail-label">Guests:</span>
                <span class="detail-value"><strong>{{ $registration->guest_count }}</strong> person(s)</span>
            </div>
            
            @if($registration->additional_info)
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <strong>Additional Information Provided:</strong>
                <p style="margin: 10px 0 0;">{{ $registration->additional_info }}</p>
            </div>
            @endif
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('my-events.index') }}" class="button">
                    View My Events <span style="font-size: 18px;">→</span>
                </a>
            </div>
            
            <div style="background: #f0f7ff; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p style="margin: 0; font-size: 14px;">
                    <strong>📌 Important Reminders:</strong>
                </p>
                <ul style="margin: 10px 0 0; padding-left: 20px; font-size: 14px;">
                    <li>Please bring your university ID for check-in</li>
                    <li>Arrive at least 15 minutes before the event starts</li>
                    <li>You can cancel up to 24 hours before the event</li>
                    <li>Save this email or your registration number for reference</li>
                </ul>
            </div>
            
            <div class="footer">
                <p>This is an automated message from Jimma University Event Management System.</p>
                <p>
                    <a href="{{ route('home') }}">Visit Events Portal</a> | 
                    <a href="{{ route('feedback.create') }}">Feedback</a> | 
                    <a href="{{ route('profile.show') }}">My Profile</a>
                </p>
                <p>&copy; {{ date('Y') }} Jimma University. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>