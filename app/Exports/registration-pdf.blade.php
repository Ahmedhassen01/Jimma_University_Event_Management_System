<!-- resources/views/exports/registration-pdf.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Details - {{ $registration->registration_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
        }
        .header {
            background: #002789;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            opacity: 0.9;
        }
        .badge {
            display: inline-block;
            background: {{ $registration->status === 'confirmed' ? '#28a745' : ($registration->status === 'pending' ? '#ffc107' : '#dc3545') }};
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .section {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-top: none;
            padding: 20px;
            margin-bottom: 15px;
        }
        .section-title {
            color: #002789;
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #002789;
        }
        .row {
            display: flex;
            margin-bottom: 10px;
        }
        .label {
            width: 150px;
            font-weight: 600;
            color: #495057;
        }
        .value {
            flex: 1;
            color: #212529;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code img {
            width: 120px;
            height: 120px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 10px;
        }
        .event-details {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }
        .event-details h3 {
            margin: 0 0 10px;
            color: #002789;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th {
            background: #e9ecef;
            padding: 8px;
            text-align: left;
            font-weight: 600;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Event Registration Details</h1>
            <p>Jimma University Event Management System</p>
        </div>
        
        <div class="section" style="text-align: center;">
            <span class="badge">{{ strtoupper($registration->status) }}</span>
            <h2 style="margin: 10px 0 5px;">{{ $registration->event->title }}</h2>
            <p style="color: #666;">Registration #: <strong>{{ $registration->registration_number }}</strong></p>
        </div>
        
        <div class="section">
            <h3 class="section-title">Registrant Information</h3>
            <div class="row">
                <div class="label">Name:</div>
                <div class="value">{{ $registration->user->name }}</div>
            </div>
            <div class="row">
                <div class="label">Email:</div>
                <div class="value">{{ $registration->user->email }}</div>
            </div>
            <div class="row">
                <div class="label">User ID:</div>
                <div class="value">{{ $registration->user->id }}</div>
            </div>
        </div>
        
        <div class="section">
            <h3 class="section-title">Event Information</h3>
            <div class="event-details">
                <div class="row">
                    <div class="label">Date:</div>
                    <div class="value">{{ $registration->event->start_date->format('l, F j, Y') }}</div>
                </div>
                <div class="row">
                    <div class="label">Time:</div>
                    <div class="value">{{ $registration->event->start_date->format('g:i A') }} - {{ $registration->event->end_date->format('g:i A') }}</div>
                </div>
                <div class="row">
                    <div class="label">Venue:</div>
                    <div class="value">{{ $registration->event->venue_name }}</div>
                </div>
                <div class="row">
                    <div class="label">Building:</div>
                    <div class="value">{{ $registration->event->building_name }}</div>
                </div>
                <div class="row">
                    <div class="label">Campus:</div>
                    <div class="value">{{ $registration->event->campus_name }}</div>
                </div>
                <div class="row">
                    <div class="label">Organizer:</div>
                    <div class="value">{{ $registration->event->organizer }}</div>
                </div>
            </div>
        </div>
        
        <div class="section">
            <h3 class="section-title">Registration Details</h3>
            <div class="row">
                <div class="label">Registration #:</div>
                <div class="value"><strong>{{ $registration->registration_number }}</strong></div>
            </div>
            <div class="row">
                <div class="label">Status:</div>
                <div class="value">
                    <span style="color: {{ $registration->status === 'confirmed' ? '#28a745' : ($registration->status === 'pending' ? '#ffc107' : '#dc3545') }};">
                        {{ ucfirst($registration->status) }}
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="label">Guest Count:</div>
                <div class="value">{{ $registration->guest_count }} person(s)</div>
            </div>
            <div class="row">
                <div class="label">Registration Date:</div>
                <div class="value">{{ $registration->created_at->format('F j, Y g:i A') }}</div>
            </div>
            @if($registration->confirmed_at)
            <div class="row">
                <div class="label">Confirmed At:</div>
                <div class="value">{{ $registration->confirmed_at->format('F j, Y g:i A') }}</div>
            </div>
            @endif
            @if($registration->attended)
            <div class="row">
                <div class="label">Checked In:</div>
                <div class="value">{{ $registration->check_in_time->format('F j, Y g:i A') }}</div>
            </div>
            @endif
        </div>
        
        @if($registration->additional_info)
        <div class="section">
            <h3 class="section-title">Additional Information</h3>
            <p style="margin: 0;">{{ $registration->additional_info }}</p>
        </div>
        @endif
        
        @if($registration->notes)
        <div class="section">
            <h3 class="section-title">Admin Notes</h3>
            <p style="margin: 0; white-space: pre-line;">{{ $registration->notes }}</p>
        </div>
        @endif
        
        <div class="footer">
            <p>This is an official document from Jimma University Event Management System.</p>
            <p>Generated on: {{ now()->format('F j, Y g:i A') }}</p>
        </div>
    </div>
</body>
</html>