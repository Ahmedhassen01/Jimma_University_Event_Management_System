<!DOCTYPE html>
<html>
<head>
    <title>Participants - {{ $event->title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #006400;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #006400;
            margin: 0;
        }
        .header h3 {
            color: #666;
            margin: 5px 0;
        }
        .event-info {
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th {
            background: #006400;
            color: white;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .statistics {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            flex: 1;
            margin: 0 5px;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #006400;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Jimma University</h1>
        <h3>Event Participants List</h3>
        <h2>{{ $event->title }}</h2>
        <p>Generated on: {{ date('M d, Y h:i A') }}</p>
    </div>

    <!-- Event Information -->
    <div class="event-info">
        <p><strong>Event Date:</strong> {{ $event->start_date->format('M d, Y') }}</p>
        <p><strong>Event Time:</strong> {{ $event->start_date->format('h:i A') }}</p>
        <p><strong>Venue:</strong> {{ $event->venue }}, {{ $event->campus }}</p>
        <p><strong>Organizer:</strong> {{ $event->organizer }}</p>
    </div>

    <!-- Statistics -->
    <div class="statistics">
        <div class="stat-card">
            <div class="stat-number">{{ $participants->count() }}</div>
            <div class="stat-label">Total Participants</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $event->registered_count }}</div>
            <div class="stat-label">Confirmed Registrations</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $event->waitlist_count }}</div>
            <div class="stat-label">Waitlist</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $event->available_seats ?? 'Unlimited' }}</div>
            <div class="stat-label">Available Seats</div>
        </div>
    </div>

    <!-- Participants Table -->
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Registration No.</th>
                <th>Registration Date</th>
                <th>Guest Count</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $index => $participant)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $participant->user->name }}</td>
                <td>{{ $participant->user->email }}</td>
                <td>{{ $participant->user->phone ?? 'N/A' }}</td>
                <td>{{ $participant->registration_number }}</td>
                <td>{{ $participant->registration_date->format('M d, Y') }}</td>
                <td>{{ $participant->guest_count }}</td>
                <td>{{ ucfirst($participant->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Additional Information -->
    @if($participants->where('additional_info', '!=', null)->count() > 0)
    <div style="margin-top: 30px;">
        <h4 style="color: #006400;">Special Requirements</h4>
        <ul>
            @foreach($participants->where('additional_info', '!=', null) as $participant)
            <li><strong>{{ $participant->user->name }}:</strong> {{ $participant->additional_info }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Jimma University Event Management System</p>
        <p>Generated by: {{ Auth::user()->name }} | Page {{ $pdf->getPage() }} of {{ $pdf->getNumPages() }}</p>
    </div>
</body>
</html>