{{-- resources/views/events/registration/create.blade.php --}}
{{-- This is the first page you shared --}}
@extends('layouts.app')

@section('title', 'Register for ' . $event->title . ' | Jimma University')
@section('page-title', 'Event Registration')
@section('page-subtitle', $event->title)

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
            <i class="fas fa-home me-2 text-primary"></i>Home
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('events.guest.dashboard') }}" class="d-flex align-items-center text-decoration-none">
            <i class="fas fa-calendar me-2 text-primary"></i>Events
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('events.guest.show', $event->slug) }}" class="d-flex align-items-center text-decoration-none">
            <i class="fas fa-info-circle me-2 text-primary"></i>{{ Str::limit($event->title, 20) }}
        </a>
    </li>
    <li class="breadcrumb-item active">
        <span class="text-primary fw-semibold">Register</span>
    </li>
@endsection

@section('content')
<style>
    :root {
        --ju-primary-dark: #002B5C;      /* Dark Jimma Blue */
        --ju-secondary-dark: #003B6F;    /* Secondary Dark Blue */
        --ju-accent-dark: #004A82;       /* Accent Dark Blue */
        --ju-deep-navy: #001F4A;         /* Deep Navy Blue */
        --ju-rich-blue: #003366;         /* Rich Blue */
        --ju-light-navy: #E6EEF5;        /* Light Navy Background */
        --ju-lighter-navy: #F0F5FA;      /* Very Light Navy */
        --ju-border-navy: #B8CCD9;       /* Border Color */
        --ju-dark-text: #001F3F;         /* Dark Blue Text */
        --ju-gold: #FFC72C;              /* Gold Accent */
        --ju-card-shadow: 0 8px 24px rgba(0, 43, 92, 0.12);
        --ju-card-hover: 0 16px 36px rgba(0, 55, 100, 0.22);
        --ju-gradient: linear-gradient(145deg, #002B5C 0%, #003B6F 100%);
        --ju-gradient-dark: linear-gradient(145deg, #001F4A 0%, #002B5C 100%);
        --ju-gradient-accent: linear-gradient(145deg, #003B6F 0%, #004A82 100%);
        --ju-gradient-gold: linear-gradient(145deg, #FFC72C 0%, #FFB300 100%);
        --ju-gradient-success: linear-gradient(145deg, #00A86B 0%, #008C4A 100%);
        --ju-gradient-danger: linear-gradient(145deg, #DC3545 0%, #B02A37 100%);
        --ju-transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        --ju-transition-bounce: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    /* Base Styles */
    body {
        background: linear-gradient(135deg, #F8FAFD 0%, #FFFFFF 100%);
    }
    
    .container-fluid {
        max-width: 1400px;
        padding: 2rem 1.5rem;
    }
    
    /* Dark Blue Theme Cards */
    .ju-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--ju-border-navy);
        box-shadow: var(--ju-card-shadow);
        overflow: hidden;
        transition: var(--ju-transition);
        margin-bottom: 1.8rem;
        position: relative;
    }
    
    .ju-card:hover {
        box-shadow: var(--ju-card-hover);
        border-color: var(--ju-primary-dark);
        transform: translateY(-4px);
    }
    
    .ju-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 3px;
        background: var(--ju-gradient);
        transition: width 0.4s ease;
    }
    
    .ju-card:hover::after {
        width: 100%;
    }
    
    .ju-card-header {
        background: white;
        padding: 1.2rem 1.8rem;
        border-bottom: 1px solid var(--ju-border-navy);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: var(--ju-transition);
    }
    
    .ju-card:hover .ju-card-header {
        background: var(--ju-light-navy);
    }
    
    .ju-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--ju-primary-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .ju-card-body {
        padding: 2rem;
    }
    
    /* Registration Card - Special Treatment */
    .registration-card {
        border-top: 4px solid var(--ju-primary-dark);
        position: relative;
        overflow: hidden;
    }
    
    .registration-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: linear-gradient(45deg, transparent, rgba(0, 43, 92, 0.02));
        border-radius: 50%;
        transform: translate(50px, -50px);
        pointer-events: none;
    }
    
    /* Event Header Icon */
    .rounded-circle.bg-primary {
        background: var(--ju-gradient) !important;
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 20px rgba(0, 43, 92, 0.25);
        transition: var(--ju-transition-bounce);
    }
    
    .rounded-circle.bg-primary:hover {
        transform: scale(1.1) rotate(8deg);
        box-shadow: 0 15px 30px rgba(0, 43, 92, 0.35);
    }
    
    .rounded-circle.bg-primary i {
        transition: var(--ju-transition-bounce);
    }
    
    .rounded-circle.bg-primary:hover i {
        transform: scale(1.2);
    }
    
    /* Event Title */
    h3 {
        color: var(--ju-primary-dark);
        font-weight: 700;
        letter-spacing: -0.02em;
        transition: var(--ju-transition);
    }
    
    .ju-card:hover h3 {
        color: var(--ju-secondary-dark);
    }
    
    /* Badges - Dark Blue Theme */
    .badge {
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.8rem;
        border-radius: 30px;
        transition: var(--ju-transition);
        border: 1px solid transparent;
    }
    
    .badge.bg-primary {
        background: var(--ju-gradient) !important;
        box-shadow: 0 4px 12px rgba(0, 43, 92, 0.2);
    }
    
    .badge.bg-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 43, 92, 0.3);
    }
    
    .badge.bg-secondary {
        background: var(--ju-light-navy) !important;
        color: var(--ju-primary-dark) !important;
        border: 1px solid var(--ju-border-navy);
    }
    
    .badge.bg-secondary:hover {
        background: var(--ju-primary-dark) !important;
        color: white !important;
        border-color: transparent;
        transform: translateY(-2px);
    }
    
    .badge.bg-info {
        background: var(--ju-accent-dark) !important;
        color: white;
    }
    
    .badge.bg-success {
        background: var(--ju-gradient-success) !important;
        color: white;
    }
    
    .badge.bg-danger {
        background: var(--ju-gradient-danger) !important;
        color: white;
    }
    
    .badge.bg-warning {
        background: var(--ju-gradient-gold) !important;
        color: var(--ju-deep-navy);
        font-weight: 700;
    }
    
    /* Waitlist Alert - Enhanced */
    .alert-warning {
        background: linear-gradient(145deg, #FFF4E5, #FFF9E6);
        border-left: 6px solid #FFB300;
        border-radius: 16px;
        color: #996633;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: var(--ju-transition);
    }
    
    .alert-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(255, 179, 0, 0.2);
    }
    
    .alert-warning::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255, 179, 0, 0.05), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }
    
    .alert-warning:hover::before {
        transform: translateX(100%);
    }
    
    .alert-warning h5 {
        color: #B85C00;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-warning i {
        animation: pulseWarning 2s infinite;
    }
    
    @keyframes pulseWarning {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    /* Form Elements - Dark Blue Theme */
    .form-label {
        color: var(--ju-primary-dark);
        font-weight: 600;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }
    
    .form-control, .form-select {
        border: 2px solid var(--ju-border-navy);
        border-radius: 14px;
        padding: 0.75rem 1.25rem;
        font-size: 0.95rem;
        transition: var(--ju-transition);
        background: white;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--ju-primary-dark);
        box-shadow: 0 0 0 4px rgba(0, 43, 92, 0.1);
        outline: none;
    }
    
    .form-control:hover, .form-select:hover {
        border-color: var(--ju-secondary-dark);
    }
    
    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }
    
    /* Radio Buttons - Dark Blue Theme */
    .form-check {
        margin-bottom: 0.5rem;
        transition: var(--ju-transition);
    }
    
    .form-check:hover {
        transform: translateX(5px);
    }
    
    .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        border: 2px solid var(--ju-border-navy);
        transition: var(--ju-transition);
        cursor: pointer;
    }
    
    .form-check-input:checked {
        background-color: var(--ju-primary-dark);
        border-color: var(--ju-primary-dark);
        box-shadow: 0 0 0 2px rgba(0, 43, 92, 0.2);
    }
    
    .form-check-input:hover {
        border-color: var(--ju-secondary-dark);
        transform: scale(1.1);
    }
    
    .form-check-input:disabled {
        background-color: var(--ju-light-navy);
        border-color: var(--ju-border-navy);
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .form-check-label {
        color: var(--ju-dark-text);
        font-weight: 500;
        margin-left: 0.5rem;
        cursor: pointer;
        transition: var(--ju-transition);
    }
    
    .form-check:hover .form-check-label {
        color: var(--ju-primary-dark);
    }
    
    .form-check-input:disabled + .form-check-label {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    /* Guest Count Options - Card Style */
    .col-auto .form-check {
        background: var(--ju-lighter-navy);
        padding: 0.75rem 1.25rem;
        border-radius: 14px;
        border: 2px solid transparent;
        transition: var(--ju-transition-bounce);
        margin-bottom: 0;
    }
    
    .col-auto .form-check:hover {
        background: white;
        border-color: var(--ju-primary-dark);
        transform: translateY(-3px);
        box-shadow: var(--ju-card-hover);
    }
    
    .col-auto .form-check-input:checked + .form-check-label {
        color: var(--ju-primary-dark);
        font-weight: 700;
    }
    
    .col-auto .form-check-input:checked ~ .form-check {
        background: var(--ju-gradient);
        border-color: var(--ju-primary-dark);
    }
    
    /* Form Text */
    .form-text {
        color: #6B7A8A;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .form-text i {
        color: var(--ju-primary-dark);
        transition: var(--ju-transition);
    }
    
    .form-text:hover i {
        transform: scale(1.2);
    }
    
    /* Error States */
    .text-danger {
        color: #DC3545 !important;
        font-weight: 600;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .text-danger i {
        font-size: 0.9rem;
    }
    
    .is-invalid {
        border-color: #DC3545 !important;
    }
    
    .is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1) !important;
    }
    
    .invalid-feedback {
        color: #DC3545;
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 0.5rem;
    }
    
    /* Submit Buttons - Dark Blue Theme */
    .btn-ju {
        background: var(--ju-gradient);
        color: white;
        border: none;
        padding: 0.9rem 2rem;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        transition: var(--ju-transition-bounce);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 43, 92, 0.25);
    }
    
    .btn-ju::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s ease;
    }
    
    .btn-ju:hover {
        background: var(--ju-gradient-dark);
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 16px 30px rgba(0, 43, 92, 0.35);
    }
    
    .btn-ju:hover::before {
        left: 100%;
    }
    
    .btn-ju i {
        transition: var(--ju-transition);
    }
    
    .btn-ju:hover i {
        transform: translateX(5px) scale(1.1);
    }
    
    .btn-ju-outline {
        background: transparent;
        color: var(--ju-primary-dark);
        border: 2px solid var(--ju-primary-dark);
        padding: 0.9rem 2rem;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        transition: var(--ju-transition-bounce);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        position: relative;
        overflow: hidden;
    }
    
    .btn-ju-outline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 100%;
        background: var(--ju-gradient);
        transition: width 0.4s ease;
        z-index: -1;
    }
    
    .btn-ju-outline:hover {
        color: white;
        border-color: transparent;
        transform: translateY(-4px);
        box-shadow: 0 16px 30px rgba(0, 43, 92, 0.25);
    }
    
    .btn-ju-outline:hover::before {
        width: 100%;
    }
    
    .btn-ju-outline i {
        transition: var(--ju-transition);
    }
    
    .btn-ju-outline:hover i {
        transform: translateX(-5px);
    }
    
    .btn-warning {
        background: var(--ju-gradient-gold);
        color: var(--ju-deep-navy);
        border: none;
        padding: 0.9rem 2rem;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        transition: var(--ju-transition-bounce);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(255, 179, 0, 0.25);
    }
    
    .btn-warning::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s ease;
    }
    
    .btn-warning:hover {
        background: #FFB300;
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 16px 30px rgba(255, 179, 0, 0.35);
    }
    
    .btn-warning:hover::before {
        left: 100%;
    }
    
    /* Progress Bar - Dark Blue Theme */
    .progress {
        height: 24px;
        background: var(--ju-light-navy);
        border-radius: 30px;
        overflow: hidden;
        border: 1px solid var(--ju-border-navy);
        margin: 1rem 0;
    }
    
    .progress-bar {
        background: var(--ju-gradient);
        border-radius: 30px;
        position: relative;
        overflow: hidden;
        font-weight: 600;
        font-size: 0.8rem;
        transition: width 1s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    .progress-bar.bg-success {
        background: var(--ju-gradient-success) !important;
    }
    
    .progress-bar.bg-danger {
        background: var(--ju-gradient-danger) !important;
    }
    
    .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        animation: progressShimmer 2s infinite;
    }
    
    @keyframes progressShimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    /* Event Details List */
    .ju-card-body h6 {
        color: var(--ju-primary-dark);
        font-weight: 700;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1rem;
    }
    
    .ju-card-body h6 i {
        transition: var(--ju-transition);
    }
    
    .ju-card-body h6:hover i {
        transform: scale(1.2);
        color: var(--ju-secondary-dark);
    }
    
    .ju-card-body p {
        color: #4A5A6A;
        line-height: 1.6;
        margin-bottom: 1.2rem;
        padding-left: 1.8rem;
    }
    
    /* Registration Tips List */
    .list-unstyled {
        margin-bottom: 0;
    }
    
    .list-unstyled li {
        padding: 0.6rem 1rem;
        border-radius: 12px;
        transition: var(--ju-transition);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .list-unstyled li:hover {
        background: var(--ju-light-navy);
        transform: translateX(8px);
    }
    
    .list-unstyled li i {
        color: var(--ju-primary-dark);
        transition: var(--ju-transition);
    }
    
    .list-unstyled li:hover i {
        transform: scale(1.2);
        color: var(--ju-secondary-dark);
    }
    
    /* Modal - Dark Blue Theme */
    .modal-content {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 30px 50px rgba(0, 31, 74, 0.3);
    }
    
    .modal-header {
        background: var(--ju-gradient);
        color: white;
        padding: 1.5rem;
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1));
        transform: skewX(-20deg) translateX(50px);
    }
    
    .modal-title {
        font-weight: 700;
        font-size: 1.3rem;
        position: relative;
        z-index: 2;
    }
    
    .modal-body {
        padding: 2rem;
    }
    
    .modal-body h6 {
        color: var(--ju-primary-dark);
        font-weight: 700;
        margin-bottom: 1rem;
        margin-top: 1.5rem;
        font-size: 1rem;
    }
    
    .modal-body h6:first-of-type {
        margin-top: 0;
    }
    
    .modal-body ol, .modal-body ul {
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .modal-body li {
        margin-bottom: 0.8rem;
        color: #4A5A6A;
        line-height: 1.6;
    }
    
    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--ju-border-navy);
        justify-content: center;
    }
    
    .btn-close {
        background: transparent;
        opacity: 0.8;
        transition: var(--ju-transition);
        position: relative;
        z-index: 2;
    }
    
    .btn-close:hover {
        transform: rotate(90deg) scale(1.1);
        opacity: 1;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .container-fluid {
            padding: 1.5rem;
        }
        
        .rounded-circle.bg-primary {
            width: 60px;
            height: 60px;
            margin-bottom: 1rem;
        }
        
        .rounded-circle.bg-primary i {
            font-size: 1.5rem;
        }
        
        h3 {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }
        
        .ju-card-header {
            padding: 1rem 1.2rem;
        }
        
        .ju-card-body {
            padding: 1.5rem;
        }
        
        .btn-ju, .btn-ju-outline, .btn-warning {
            padding: 0.8rem 1.5rem;
            font-size: 0.9rem;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
        
        .d-flex.justify-content-between a,
        .d-flex.justify-content-between button {
            width: 100%;
        }
        
        .col-auto .form-check {
            width: 100%;
            text-align: center;
        }
        
        .col-auto {
            width: 100%;
        }
    }
    
    /* Loading State */
    .btn-ju.loading, .btn-warning.loading {
        pointer-events: none;
        opacity: 0.8;
    }
    
    .btn-ju.loading i, .btn-warning.loading i {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Fade In Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .ju-card {
        animation: fadeInUp 0.6s ease-out both;
    }
    
    .ju-card:nth-child(1) { animation-delay: 0.1s; }
    .ju-card:nth-child(2) { animation-delay: 0.2s; }
    .ju-card:nth-child(3) { animation-delay: 0.3s; }
    
    /* Terms Link */
    a[data-bs-toggle="modal"] {
        color: var(--ju-primary-dark);
        font-weight: 700;
        text-decoration: none;
        position: relative;
        transition: var(--ju-transition);
    }
    
    a[data-bs-toggle="modal"]::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--ju-gradient);
        transition: width 0.3s ease;
    }
    
    a[data-bs-toggle="modal"]:hover {
        color: var(--ju-secondary-dark);
    }
    
    a[data-bs-toggle="modal"]:hover::after {
        width: 100%;
    }
    
    /* Status Message Styles */
    .status-message {
        margin-top: 20px;
        padding: 15px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .status-message.pending {
        background: linear-gradient(145deg, #FFF4E5, #FFF9E6);
        border-left: 6px solid #FFB300;
    }
    
    .status-message i {
        font-size: 2rem;
        color: #FFB300;
    }
    
    .status-message h4 {
        color: #B85C00;
        margin-bottom: 5px;
        font-weight: 700;
    }
    
    .status-message p {
        color: #996633;
        margin: 0;
    }
</style>

<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Registration Header -->
            <div class="ju-card registration-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title m-0">
                        <i class="fas fa-pen-to-square"></i>
                        Event Registration
                    </h5>
                </div>
                <div class="ju-card-body">
                    <div class="row align-items-center g-4">
                        <div class="col-md-2 text-center text-md-start">
                            <div class="rounded-circle bg-primary text-white p-3 d-inline-flex">
                                <i class="fas fa-calendar-check fa-2x"></i>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h3 class="mb-3">{{ $event->title }}</h3>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-primary">
                                    <i class="fas fa-calendar me-1"></i> {{ $event->start_date->format('M d, Y') }}
                                </span>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-clock me-1"></i> {{ $event->start_date->format('h:i A') }}
                                </span>
                                <span class="badge bg-info">
                                    <i class="fas fa-tag me-1"></i> {{ ucfirst($event->event_type) }}
                                </span>
                                @if($event->max_attendees)
                                <span class="badge {{ $isFull ? 'bg-danger' : 'bg-success' }}">
                                    <i class="fas fa-users me-1"></i> 
                                    {{ $event->registered_count ?? 0 }}/{{ $event->max_attendees }} seats
                                    @if(!$isFull && isset($availableSeats))
                                    ({{ $availableSeats }} available)
                                    @endif
                                </span>
                                @endif
                            </div>
                            <p class="mb-2 d-flex align-items-center gap-2">
                                <i class="fas fa-map-marker-alt" style="color: var(--ju-primary-dark);"></i>
                                <span class="text-muted">{{ $event->venue }}, {{ $event->campus ?? 'Main Campus' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($isFull)
            <!-- Waitlist Notice -->
            <div class="ju-card border-warning">
                <div class="ju-card-body">
                    <div class="alert alert-warning mb-0">
                        <h5>
                            <i class="fas fa-exclamation-triangle me-2"></i> 
                            Event at Full Capacity
                        </h5>
                        <p class="mb-0 mt-2">
                            This event has reached its maximum capacity. You can join the waitlist and will be automatically registered if a seat becomes available. You'll receive an email notification within 24 hours of a spot opening.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Registration Form -->
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title m-0">
                        <i class="fas fa-file-signature"></i>
                        Registration Form
                    </h5>
                </div>
                <div class="ju-card-body">
                    <form method="POST" action="{{ route('event-registration.store', $event) }}" id="registrationForm">
                        @csrf

                        <!-- Guest Count -->
                        <div class="mb-5">
                            <label for="guest_count" class="form-label fw-bold d-flex align-items-center gap-2">
                                <span class="badge bg-primary rounded-pill px-3 py-2">1</span>
                                Number of Guests (Including Yourself) *
                            </label>
                            <div class="row g-3">
                                @for($i = 1; $i <= 5; $i++)
                                <div class="col-auto col-sm-6 col-md-4 col-lg-auto">
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input" type="radio" 
                                               id="guest{{ $i }}" name="guest_count" value="{{ $i }}" 
                                               {{ old('guest_count', 1) == $i ? 'checked' : '' }}
                                               {{ $isFull && $i > 1 ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="guest{{ $i }}">
                                            {{ $i }} {{ $i == 1 ? 'Person' : 'People' }}
                                        </label>
                                    </div>
                                </div>
                                @endfor
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i>
                                @if($isFull)
                                    Only individual registration is available for waitlist.
                                @elseif($event->max_attendees && isset($availableSeats))
                                    Maximum {{ min(5, $availableSeats) }} guests allowed due to limited seating.
                                @else
                                    Maximum 5 guests per registration.
                                @endif
                            </div>
                            @error('guest_count')
                                <div class="text-danger">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-5">
                            <label for="additional_info" class="form-label fw-bold d-flex align-items-center gap-2">
                                <span class="badge bg-primary rounded-pill px-3 py-2">2</span>
                                Additional Information (Optional)
                            </label>
                            <textarea class="form-control" id="additional_info" name="additional_info" 
                                      rows="4" placeholder="Dietary restrictions, accessibility needs, special accommodations, or additional comments...">{{ old('additional_info') }}</textarea>
                            <div class="form-text">
                                <i class="fas fa-heart"></i>
                                This information helps organizers create a more inclusive and comfortable experience for everyone.
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-5">
                            <label class="form-label fw-bold d-flex align-items-center gap-2">
                                <span class="badge bg-primary rounded-pill px-3 py-2">3</span>
                                Confirmation
                            </label>
                            <div class="form-check p-3 bg-light-navy rounded-3" style="background: var(--ju-lighter-navy);">
                                <input class="form-check-input @error('agree_terms') is-invalid @enderror" 
                                       type="checkbox" id="agree_terms" name="agree_terms" value="1" {{ old('agree_terms') ? 'checked' : '' }}>
                                <label class="form-check-label" for="agree_terms">
                                    I have read and agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a> and <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Privacy Policy</a> *
                                </label>
                                @error('agree_terms')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- IMPORTANT: Changed button text and added info message -->
                        <div class="status-message pending mb-4">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h4>Registration Requires Admin Approval</h4>
                                <p>Your registration will be submitted for admin review. You'll receive a confirmation email once approved.</p>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('events.guest.show', $event->slug) }}" class="btn-ju-outline">
                                <i class="fas fa-arrow-left"></i>
                                Cancel & Return
                            </a>
                            @if($isFull)
                            <button type="submit" class="btn-warning">
                                <i class="fas fa-clock"></i>
                                Join Waitlist
                                <i class="fas fa-arrow-right"></i>
                            </button>
                            @else
                            <button type="submit" class="btn-ju">
                                <i class="fas fa-paper-plane"></i>
                                Submit for Approval
                                <i class="fas fa-arrow-right"></i>
                            </button>
                            @endif
                        </div>
                        
                        <p class="text-muted small text-center mt-4 mb-0">
                            <i class="fas fa-lock me-1" style="color: var(--ju-primary-dark);"></i>
                            Your information is securely processed. Your registration will be pending admin approval.
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Event Details -->
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title m-0">
                        <i class="fas fa-circle-info"></i>
                        Event Details
                    </h5>
                </div>
                <div class="ju-card-body">
                    <div class="mb-4">
                        <h6>
                            <i class="fas fa-align-left"></i>
                            Description
                        </h6>
                        <p class="text-muted">{{ Str::limit($event->description, 150) }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6>
                            <i class="fas fa-user-tie"></i>
                            Organizer
                        </h6>
                        <p class="text-muted d-flex align-items-center gap-2">
                            <span class="badge bg-secondary bg-opacity-10 text-dark px-3 py-2" style="background: var(--ju-light-navy); color: var(--ju-primary-dark) !important;">
                                {{ $event->organizer }}
                            </span>
                        </p>
                    </div>
                    
                    @if($event->contact_email)
                    <div class="mb-4">
                        <h6>
                            <i class="fas fa-envelope"></i>
                            Contact Email
                        </h6>
                        <a href="mailto:{{ $event->contact_email }}" class="text-decoration-none d-flex align-items-center gap-2 p-2 rounded-3" style="color: var(--ju-primary-dark); transition: var(--ju-transition);" onmouseover="this.style.background='var(--ju-light-navy)'" onmouseout="this.style.background='transparent'">
                            <i class="fas fa-envelope"></i>
                            {{ $event->contact_email }}
                        </a>
                    </div>
                    @endif
                    
                    @if($event->contact_phone)
                    <div class="mb-4">
                        <h6>
                            <i class="fas fa-phone"></i>
                            Contact Phone
                        </h6>
                        <a href="tel:{{ $event->contact_phone }}" class="text-decoration-none d-flex align-items-center gap-2 p-2 rounded-3" style="color: var(--ju-primary-dark); transition: var(--ju-transition);" onmouseover="this.style.background='var(--ju-light-navy)'" onmouseout="this.style.background='transparent'">
                            <i class="fas fa-phone-alt"></i>
                            {{ $event->contact_phone }}
                        </a>
                    </div>
                    @endif
                    
                    <div class="mt-4 pt-3 border-top" style="border-color: var(--ju-border-navy) !important;">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-clock" style="color: var(--ju-primary-dark); font-size: 1.2rem;"></i>
                            <div>
                                <small class="text-muted d-block">Event Duration</small>
                                <span class="fw-bold" style="color: var(--ju-primary-dark);">
                                    {{ $event->start_date->format('M d, Y • h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Tips -->
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title m-0">
                        <i class="fas fa-lightbulb" style="color: var(--ju-gold);"></i>
                        Registration Tips
                    </h5>
                </div>
                <div class="ju-card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle"></i>
                            Arrive 15 minutes before event starts
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle"></i>
                            Bring your registration confirmation
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle"></i>
                            Cancel at least 24 hours in advance
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle"></i>
                            Check "My Events" for updates
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle"></i>
                            Contact organizers for special needs
                        </li>
                    </ul>
                    
                    @if($event->max_attendees)
                    <hr class="my-4" style="border-color: var(--ju-border-navy);">
                    
                    <h6 class="d-flex align-items-center gap-2">
                        <i class="fas fa-chart-pie"></i>
                        Seat Availability
                    </h6>
                    <div class="progress mb-2">
                        @php
                            $percentage = $event->max_attendees > 0 ? min(100, (($event->registered_count ?? 0) / $event->max_attendees) * 100) : 0;
                        @endphp
                        <div class="progress-bar {{ $isFull ? 'bg-danger' : 'bg-success' }}" 
                             role="progressbar" 
                             style="width: {{ $percentage }}%;"
                             aria-valuenow="{{ $percentage }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            {{ round($percentage) }}%
                        </div>
                    </div>
                    <p class="small text-muted mb-0 d-flex justify-content-between">
                        <span><i class="fas fa-user-check me-1"></i> {{ $event->registered_count ?? 0 }} registered</span>
                        <span><i class="fas fa-chair me-1"></i> {{ $event->max_attendees }} total</span>
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal - Dark Blue Theme -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">
                    <i class="fas fa-file-contract me-2"></i>
                    Registration Terms & Conditions
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-4">
                            <h6>
                                <span class="badge bg-primary me-2">1</span>
                                Registration Terms
                            </h6>
                            <ol class="text-muted">
                                <li>Registration is required for this event and confirms your intent to attend.</li>
                                <li>You must be a valid member of Jimma University community (student, faculty, or staff) to register.</li>
                                <li>A confirmation email will be sent to your university email address within 15 minutes after admin approval.</li>
                                <li>Your registration will be pending until approved by an administrator.</li>
                                <li>Present your digital or printed confirmation at the event entrance.</li>
                            </ol>
                        </div>
                        
                        <div class="mb-4">
                            <h6>
                                <span class="badge bg-primary me-2">2</span>
                                Waitlist Policy
                            </h6>
                            <ul class="text-muted">
                                <li>Waitlist positions are strictly first-come, first-served.</li>
                                <li>You will be automatically registered when a seat becomes available.</li>
                                <li>You have 24 hours to confirm your registration when notified.</li>
                                <li>Failure to confirm within 24 hours will move your spot to the next person.</li>
                                <li>You may cancel your waitlist position at any time.</li>
                            </ul>
                        </div>
                        
                        <div class="mb-4">
                            <h6>
                                <span class="badge bg-primary me-2">3</span>
                                Cancellation Policy
                            </h6>
                            <ul class="text-muted">
                                <li>Cancellations are free and open up to 24 hours before event start time.</li>
                                <li>Late cancellations (within 24 hours) and no-shows will be recorded.</li>
                                <li>Three or more no-shows within a semester may restrict future registration privileges.</li>
                                <li>To cancel, visit "My Events" dashboard and select "Cancel Registration".</li>
                                <li>Cancelled spots are automatically offered to the next person on the waitlist.</li>
                            </ul>
                        </div>
                        
                        <div class="mb-4">
                            <h6>
                                <span class="badge bg-primary me-2">4</span>
                                Code of Conduct
                            </h6>
                            <ul class="text-muted">
                                <li>All attendees must follow Jimma University's Student Code of Conduct.</li>
                                <li>Respectful behavior toward organizers, speakers, and fellow attendees is required.</li>
                                <li>Photography and recording may be restricted - respect speaker preferences.</li>
                                <li>Disruptive behavior may result in removal from the event and future restrictions.</li>
                                <li>Report any concerns or violations to event staff immediately.</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-warning" style="background: linear-gradient(145deg, #FFF4E5, #FFF9E6); border-left-color: #FFB300;">
                            <i class="fas fa-shield-alt me-2" style="color: #B85C00;"></i>
                            <strong>Privacy Assurance:</strong> Your personal information is protected under Jimma University's data privacy policy and will only be used for event coordination and communication. We never share your data with third parties.
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ju" data-bs-dismiss="modal">
                    <i class="fas fa-check-circle me-2"></i>
                    I Understand & Agree
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Enhanced guest count validation with animation
        $('input[name="guest_count"]').change(function() {
            const selectedGuests = parseInt($(this).val());
            const availableSeats = parseInt('{{ $availableSeats ?? 5 }}');
            
            if(selectedGuests > availableSeats) {
                // Animated alert using our custom styled alert
                const alertHtml = `
                    <div class="alert alert-danger py-2 mb-3" style="animation: fadeInUp 0.4s ease;">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Only ${availableSeats} seat${availableSeats > 1 ? 's' : ''} available. Please select a lower number.
                    </div>
                `;
                
                // Remove any existing alerts first
                $('.alert-danger').remove();
                
                // Insert the new alert after the guest count radios
                $(this).closest('.row').after(alertHtml);
                
                // Uncheck the invalid option
                $(this).prop('checked', false);
                
                // Re-check the previously checked value or default to 1
                const currentChecked = $('input[name="guest_count"]:checked').val();
                if (!currentChecked) {
                    $('#guest1').prop('checked', true);
                }
                
                // Auto-dismiss the alert after 5 seconds
                setTimeout(function() {
                    $('.alert-danger').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }, 5000);
            } else {
                $('.alert-danger').remove();
            }
            
            // Animate the selected option
            $(this).closest('.form-check').addClass('animate__animated animate__pulse');
            setTimeout(() => {
                $(this).closest('.form-check').removeClass('animate__animated animate__pulse');
            }, 500);
        });

        // Enhanced form validation with visual feedback
        $('#registrationForm').submit(function(e) {
            let isValid = true;
            let errorMessage = '';
            
            // Check terms agreement
            if(!$('#agree_terms').prop('checked')) {
                isValid = false;
                errorMessage = 'Please agree to the Terms and Conditions to continue.';
                
                // Highlight the terms section
                $('#agree_terms').closest('.p-3').css({
                    'border': '2px solid #DC3545',
                    'background': 'rgba(220, 53, 69, 0.05)'
                });
                
                // Scroll to terms section
                $('html, body').animate({
                    scrollTop: $('#agree_terms').closest('.p-3').offset().top - 100
                }, 500);
            } else {
                $('#agree_terms').closest('.p-3').css({
                    'border': 'none',
                    'background': 'var(--ju-lighter-navy)'
                });
            }
            
            // Check guest count selection
            const guestCount = $('input[name="guest_count"]:checked').val();
            if (!guestCount) {
                isValid = false;
                errorMessage = errorMessage || 'Please select the number of guests attending.';
                
                // Highlight the guest count section
                $('.row.g-3').first().closest('.mb-5').css({
                    'border-left': '4px solid #DC3545',
                    'padding-left': '20px'
                });
                
                // Scroll to guest count section
                if (!errorMessage) {
                    $('html, body').animate({
                        scrollTop: $('.row.g-3').first().closest('.mb-5').offset().top - 100
                    }, 500);
                }
            } else {
                $('.row.g-3').first().closest('.mb-5').css({
                    'border-left': 'none',
                    'padding-left': '0'
                });
            }
            
            if (!isValid) {
                e.preventDefault();
                
                // Show custom error alert
                const errorAlert = `
                    <div class="alert alert-danger mb-4 animate__animated animate__shakeX" role="alert" style="border-left: 6px solid #B02A37;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                            <div>
                                <strong>Registration Incomplete</strong><br>
                                ${errorMessage}
                            </div>
                        </div>
                    </div>
                `;
                
                // Remove any existing error alerts
                $('.alert-danger').remove();
                
                // Insert the error alert at the top of the form
                $('#registrationForm').prepend(errorAlert);
                
                // Auto-dismiss after 8 seconds
                setTimeout(function() {
                    $('.alert-danger').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }, 8000);
                
                return false;
            }
            
            // Show loading state on submit button
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.addClass('loading');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i> Submitting for Approval...');
            
            // Prevent double submission
            $(this).find('button[type="submit"]').prop('disabled', true);
            
            // Re-enable after 10 seconds in case of timeout
            setTimeout(function() {
                submitBtn.html(originalText);
                submitBtn.removeClass('loading');
                submitBtn.prop('disabled', false);
            }, 10000);
        });

        // Animate progress bar on view
        const progressBar = $('.progress-bar');
        if (progressBar.length) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const width = progressBar[0].style.width;
                        progressBar.css('width', '0%');
                        setTimeout(() => {
                            progressBar.css('width', width);
                        }, 300);
                    }
                });
            }, { threshold: 0.5 });
            
            observer.observe(progressBar[0]);
        }

        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            const target = $(this.hash);
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 600);
            }
        });

        // Modal enhancement - ensure proper display on mobile
        $('#termsModal').on('shown.bs.modal', function() {
            $(this).find('.modal-body').scrollTop(0);
        });

        // Add pulse animation to badge on hover
        $('.badge').hover(
            function() { $(this).addClass('animate__animated animate__pulse'); },
            function() { $(this).removeClass('animate__animated animate__pulse'); }
        );

        // Character counter for additional info
        const additionalInfo = $('#additional_info');
        if (additionalInfo.length) {
            const counter = $('<div class="text-muted small mt-1"><span id="charCount">0</span>/500 characters</div>');
            additionalInfo.after(counter);
            
            additionalInfo.on('input', function() {
                const count = $(this).val().length;
                $('#charCount').text(count);
                if (count > 500) {
                    $('#charCount').css('color', '#DC3545');
                } else {
                    $('#charCount').css('color', 'var(--ju-primary-dark)');
                }
            });
            
            additionalInfo.trigger('input');
        }

        // Prevent multiple form submissions
        $('#registrationForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true);
        });

        // Tooltip initialization (if you're using Bootstrap tooltips)
        $('[data-bs-toggle="tooltip"]').tooltip();

        console.log('Registration page initialized with Dark Blue Jimma University theme');
    });
</script>
@endpush

<!-- Add Animate.css for enhanced animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection