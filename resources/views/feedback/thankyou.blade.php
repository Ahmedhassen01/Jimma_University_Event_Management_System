{{-- resources/views/feedback/thankyou.blade.php --}}
@extends('layouts.app')

@section('title', 'Thank You')
@section('page-title', 'Thank You for Your Feedback')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    <h2 class="mb-3">Thank You!</h2>
                    <p class="lead mb-4">
                        Your feedback has been submitted successfully. 
                        We appreciate your input and will use it to improve our services.
                    </p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('home') }}" class="btn btn-primary me-md-2">
                            <i class="fas fa-home me-2"></i> Back to Home
                        </a>
                        <a href="{{ route('feedback.testimonials') }}" class="btn btn-outline-primary">
                            <i class="fas fa-star me-2"></i> View Testimonials
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection