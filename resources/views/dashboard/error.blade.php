@extends('layouts.app')

@section('title', 'Dashboard Error - Jimma University')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Dashboard Temporarily Unavailable
                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <i class="fas fa-tachometer-alt fa-5x text-warning mb-4"></i>
                    <h2 class="text-warning mb-3">Dashboard Error</h2>
                    <p class="lead mb-4">
                        We're experiencing some technical difficulties with the dashboard.
                    </p>
                    
                    @if(isset($error))
                    <div class="alert alert-danger">
                        <strong>Error Details:</strong> {{ $error }}
                    </div>
                    @endif
                    
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Recent Events</h5>
                                    @if($recentEvents->isNotEmpty())
                                    <ul class="list-unstyled">
                                        @foreach($recentEvents as $event)
                                        <li class="mb-2">
                                            <a href="{{ route('events.guest.show', $event->slug) }}">
                                                {{ Str::limit($event->title, 30) }}
                                            </a>
                                            <br>
                                            <small class="text-muted">
                                                {{ $event->start_date->format('M d') }}
                                            </small>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <p class="text-muted">No recent events</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Recent Announcements</h5>
                                    @if($recentAnnouncements->isNotEmpty())
                                    <ul class="list-unstyled">
                                        @foreach($recentAnnouncements as $announcement)
                                        <li class="mb-2">
                                            <a href="{{ route('announcements.show', $announcement) }}">
                                                {{ Str::limit($announcement->title, 30) }}
                                            </a>
                                            <br>
                                            <small class="text-muted">
                                                {{ $announcement->created_at->diffForHumans() }}
                                            </small>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <p class="text-muted">No recent announcements</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-warning me-2">
                            <i class="fas fa-redo me-1"></i> Retry Dashboard
                        </a>
                        
                        <a href="{{ route('admin.events.index') }}" class="btn btn-primary me-2">
                            <i class="fas fa-calendar-alt me-1"></i> Manage Events
                        </a>
                        
                        <a href="{{ route('announcements.index') }}" class="btn btn-success">
                            <i class="fas fa-bullhorn me-1"></i> View Announcements
                        </a>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        The technical team has been notified of this issue.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection