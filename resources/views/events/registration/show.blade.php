@extends('layouts.app')

@section('title', 'Registration Details | Jimma University')
@section('page-title', 'Registration Details')
@section('page-subtitle', 'View your event registration information')

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('my-events.index') }}" class="d-flex align-items-center text-decoration-none hover-lift">
            <i class="fas fa-calendar-check me-2" style="color: var(--ju-gold);"></i>My Events
        </a>
    </li>
    <li class="breadcrumb-item active">
        <span class="fw-semibold" style="color: var(--ju-blue-dark);">Registration Details</span>
    </li>
@endsection

@section('content')
<style>
    /* ============================================
           PREMIUM JIMMA UNIVERSITY REGISTRATION DETAILS
           DARK BLUE & GOLD Color Scheme - OFFICIAL
           Perfect Layout | Premium Timeline
        ============================================ */
    
    :root {
        /* DARK BLUE - Official Jimma University Primary Color */
        --ju-blue: #0a2c6e;
        --ju-blue-dark: #041c47;
        --ju-blue-darker: #021230;
        --ju-blue-light: #1e4a8a;
        --ju-blue-lighter: #3a6ab0;
        --ju-blue-soft: rgba(10, 44, 110, 0.08);
        --ju-blue-glow: rgba(10, 44, 110, 0.2);
        --ju-blue-gradient: linear-gradient(145deg, #0a2c6e, #041c47);
        --ju-blue-gradient-light: linear-gradient(145deg, #0a2c6e, #1e4a8a);
        
        /* Ethiopian Gold Accents - Official */
        --ju-gold: #c4a747;
        --ju-gold-dark: #a5862e;
        --ju-gold-darker: #7e6623;
        --ju-gold-light: #d8be6e;
        --ju-gold-soft: rgba(196, 167, 71, 0.12);
        --ju-gold-glow: rgba(196, 167, 71, 0.25);
        --ju-gold-gradient: linear-gradient(145deg, #c4a747, #a5862e);
        
        /* Semantic Colors */
        --ju-success: #10b981;
        --ju-success-dark: #059669;
        --ju-success-soft: rgba(16, 185, 129, 0.12);
        --ju-warning: #f59e0b;
        --ju-warning-dark: #d97706;
        --ju-warning-soft: rgba(245, 158, 11, 0.12);
        --ju-danger: #dc2626;
        --ju-danger-dark: #b91c1c;
        --ju-danger-soft: rgba(220, 38, 38, 0.12);
        --ju-info: #0a2c6e;
        --ju-info-soft: rgba(10, 44, 110, 0.08);
        
        /* Neutral Colors */
        --ju-white: #ffffff;
        --ju-offwhite: #f8fafc;
        --ju-gray-50: #f9fbfd;
        --ju-gray-100: #f1f5f9;
        --ju-gray-200: #e9edf2;
        --ju-gray-300: #dee4e9;
        --ju-gray-400: #b8c2cc;
        --ju-gray-500: #8f9aa8;
        --ju-gray-600: #64748b;
        --ju-gray-700: #475569;
        --ju-gray-800: #334155;
        --ju-gray-900: #1e293b;
        
        /* Shadows - Dark Blue Tinted */
        --shadow-sm: 0 4px 12px rgba(10,44,110,0.08);
        --shadow: 0 8px 20px rgba(10,44,110,0.12);
        --shadow-lg: 0 16px 32px rgba(10,44,110,0.16);
        --shadow-gold: 0 8px 20px rgba(196,167,71,0.2);
        --shadow-gold-lg: 0 16px 32px rgba(196,167,71,0.25);
        
        /* Border Radius - Consistent */
        --radius-sm: 8px;
        --radius: 12px;
        --radius-md: 16px;
        --radius-lg: 20px;
        --radius-xl: 24px;
        --radius-2xl: 28px;
        --radius-3xl: 32px;
        --radius-full: 9999px;
        
        /* Transitions */
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-bounce: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        --transition-elastic: all 0.6s cubic-bezier(0.68, -0.6, 0.32, 1.6);
    }

    /* ============================================
           RESET & BASE
        ============================================ */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: var(--ju-offwhite);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        overflow-x: hidden;
    }

    .container-fluid {
        background: var(--ju-offwhite);
    }

    /* ============================================
           PREMIUM CARDS - Dark Blue Theme
        ============================================ */
    .premium-card {
        background: var(--ju-white);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--ju-gray-200);
        transition: var(--transition-bounce);
        margin-bottom: 24px;
        position: relative;
    }

    .premium-card:hover {
        box-shadow: var(--shadow);
        border-color: var(--ju-blue-soft);
        transform: translateY(-2px);
    }

    .premium-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--ju-blue), var(--ju-gold));
        opacity: 0;
        transition: var(--transition);
    }

    .premium-card:hover::before {
        opacity: 1;
    }

    .premium-card-header {
        padding: 24px 28px;
        border-bottom: 1px solid var(--ju-gray-200);
        background: linear-gradient(180deg, var(--ju-white) 0%, var(--ju-gray-50) 100%);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .premium-card-header.success {
        background: linear-gradient(145deg, var(--ju-success), var(--ju-success-dark));
        border-bottom: none;
    }

    .premium-card-header.success .premium-card-title {
        color: white;
    }

    .premium-card-header.success i {
        color: white;
    }

    .premium-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--ju-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .premium-card-title i {
        color: var(--ju-gold);
        font-size: 1.3rem;
    }

    .premium-card-body {
        padding: 28px;
    }

    /* ============================================
           PREMIUM SUCCESS REGISTRATION CARD
        ============================================ */
    .premium-success-card {
        background: linear-gradient(145deg, white, var(--ju-gray-50));
        border-left: 4px solid var(--ju-success);
    }

    .premium-success-icon {
        width: 80px;
        height: 80px;
        background: var(--ju-success-soft);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        animation: premiumBounceIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .premium-success-icon i {
        font-size: 3rem;
        color: var(--ju-success);
        filter: drop-shadow(0 8px 16px rgba(16,185,129,0.2));
    }

    @keyframes premiumBounceIn {
        0% { transform: scale(0); opacity: 0; }
        60% { transform: scale(1.1); }
        100% { transform: scale(1); opacity: 1; }
    }

    .premium-success-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--ju-gray-800);
        margin-bottom: 8px;
    }

    .premium-success-message {
        color: var(--ju-gray-600);
        font-size: 1.1rem;
    }

    .premium-registration-number {
        background: linear-gradient(145deg, var(--ju-gray-50), white);
        border-radius: var(--radius-lg);
        padding: 24px;
        border: 1px solid var(--ju-gray-200);
        position: relative;
        overflow: hidden;
    }

    .premium-registration-number::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, var(--ju-blue), var(--ju-gold));
    }

    .premium-registration-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--ju-gray-500);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .premium-registration-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--ju-blue);
        font-family: 'Montserrat', sans-serif;
        letter-spacing: 1px;
    }

    .premium-email-alert {
        background: var(--ju-info-soft);
        border: 1px solid rgba(10,44,110,0.15);
        border-radius: var(--radius-lg);
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .premium-email-alert i {
        font-size: 1.5rem;
        color: var(--ju-gold);
    }

    .premium-email-alert strong {
        color: var(--ju-blue);
    }

    /* ============================================
           PREMIUM EVENT DETAILS
        ============================================ */
    .premium-event-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--ju-blue);
        margin-bottom: 24px;
        line-height: 1.3;
    }

    .premium-details-list {
        background: var(--ju-gray-50);
        border-radius: var(--radius-lg);
        padding: 20px;
        border: 1px solid var(--ju-gray-200);
    }

    .premium-details-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid var(--ju-gray-200);
    }

    .premium-details-row:last-child {
        border-bottom: none;
    }

    .premium-details-label {
        width: 100px;
        color: var(--ju-gray-600);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .premium-details-value {
        flex: 1;
        color: var(--ju-gray-800);
        font-weight: 600;
    }

    .premium-details-value a {
        color: var(--ju-blue);
        text-decoration: none;
        transition: var(--transition);
    }

    .premium-details-value a:hover {
        color: var(--ju-gold);
    }

    .premium-event-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        transition: var(--transition-bounce);
    }

    .premium-event-image:hover {
        transform: scale(1.02);
        box-shadow: var(--shadow-lg);
    }

    .premium-image-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(145deg, var(--ju-blue-soft), var(--ju-gray-100));
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--ju-gray-200);
    }

    .premium-image-placeholder i {
        font-size: 3.5rem;
        color: var(--ju-blue);
        opacity: 0.5;
    }

    /* ============================================
           PREMIUM REGISTRATION DETAILS TABLE
        ============================================ */
    .premium-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .premium-table tr td {
        padding: 12px 0;
    }

    .premium-table td:first-child {
        color: var(--ju-gray-600);
        font-weight: 600;
        width: 140px;
    }

    .premium-table td:last-child {
        color: var(--ju-gray-800);
        font-weight: 600;
    }

    .premium-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        border-radius: var(--radius-full);
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .premium-status-badge i {
        font-size: 0.8rem;
    }

    .premium-status-badge.success {
        background: var(--ju-success-soft);
        color: var(--ju-success-dark);
        border: 1px solid rgba(16,185,129,0.3);
    }

    .premium-status-badge.warning {
        background: var(--ju-warning-soft);
        color: var(--ju-warning-dark);
        border: 1px solid rgba(245,158,11,0.3);
    }

    .premium-status-badge.danger {
        background: var(--ju-danger-soft);
        color: var(--ju-danger-dark);
        border: 1px solid rgba(220,38,38,0.3);
    }

    .premium-status-badge.info {
        background: var(--ju-info-soft);
        color: var(--ju-blue);
        border: 1px solid rgba(10,44,110,0.3);
    }

    .premium-status-badge.secondary {
        background: var(--ju-gray-100);
        color: var(--ju-gray-700);
        border: 1px solid var(--ju-gray-300);
    }

    .premium-additional-info {
        background: var(--ju-gray-50);
        border-radius: var(--radius-lg);
        padding: 20px;
        border: 1px solid var(--ju-gray-200);
        color: var(--ju-gray-700);
        line-height: 1.6;
    }

    /* ============================================
           PREMIUM ATTENDEE INFORMATION
        ============================================ */
    .premium-attendee-card {
        background: linear-gradient(145deg, var(--ju-gray-50), white);
        border-radius: var(--radius-lg);
        padding: 20px;
        border: 1px solid var(--ju-gray-200);
    }

    .premium-attendee-icon {
        width: 48px;
        height: 48px;
        background: var(--ju-blue-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        margin-bottom: 16px;
    }

    .premium-checked-in {
        background: var(--ju-success-soft);
        border: 1px solid rgba(16,185,129,0.3);
        border-radius: var(--radius-lg);
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .premium-checked-in i {
        font-size: 1.8rem;
        color: var(--ju-success);
    }

    .premium-checked-in strong {
        color: var(--ju-success-dark);
        font-size: 1rem;
    }

    /* ============================================
           PREMIUM QUICK ACTIONS
        ============================================ */
    .premium-actions-grid {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .premium-action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 16px 24px;
        border-radius: var(--radius-lg);
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        transition: var(--transition-bounce);
        border: 2px solid transparent;
        cursor: pointer;
        width: 100%;
    }

    .premium-action-btn-primary {
        background: var(--ju-blue-gradient);
        color: white;
        box-shadow: 0 8px 16px rgba(10,44,110,0.2);
    }

    .premium-action-btn-primary:hover {
        background: var(--ju-blue-dark);
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(10,44,110,0.3);
    }

    .premium-action-btn-outline-primary {
        background: transparent;
        color: var(--ju-blue);
        border: 2px solid var(--ju-blue);
    }

    .premium-action-btn-outline-primary:hover {
        background: var(--ju-blue);
        color: white;
        transform: translateY(-3px);
    }

    .premium-action-btn-outline-warning {
        background: transparent;
        color: var(--ju-warning-dark);
        border: 2px solid var(--ju-warning);
    }

    .premium-action-btn-outline-warning:hover {
        background: var(--ju-warning);
        color: white;
        transform: translateY(-3px);
    }

    .premium-action-btn-outline-success {
        background: transparent;
        color: var(--ju-success-dark);
        border: 2px solid var(--ju-success);
    }

    .premium-action-btn-outline-success:hover {
        background: var(--ju-success);
        color: white;
        transform: translateY(-3px);
    }

    .premium-action-btn-outline-info {
        background: transparent;
        color: var(--ju-blue);
        border: 2px solid var(--ju-blue);
    }

    .premium-action-btn-outline-info:hover {
        background: var(--ju-blue);
        color: white;
        transform: translateY(-3px);
    }

    .premium-action-btn i {
        transition: var(--transition-elastic);
    }

    .premium-action-btn:hover i {
        transform: translateX(6px) scale(1.1);
    }

    /* ============================================
           PREMIUM TIMELINE - Ethiopian Gold Theme
        ============================================ */
    .premium-timeline {
        position: relative;
        padding-left: 40px;
    }

    .premium-timeline::before {
        content: '';
        position: absolute;
        left: 14px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: linear-gradient(180deg, var(--ju-blue), var(--ju-gold), var(--ju-blue));
        border-radius: var(--radius-full);
    }

    .premium-timeline-item {
        position: relative;
        margin-bottom: 32px;
    }

    .premium-timeline-item:last-child {
        margin-bottom: 0;
    }

    .premium-timeline-marker {
        position: absolute;
        left: -40px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.8rem;
        border: 3px solid var(--ju-white);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 10;
        transition: var(--transition-bounce);
    }

    .premium-timeline-item:hover .premium-timeline-marker {
        transform: scale(1.15);
        box-shadow: 0 0 0 4px rgba(196,167,71,0.2);
    }

    .premium-timeline-marker.success { background: var(--ju-success); }
    .premium-timeline-marker.primary { background: var(--ju-blue); }
    .premium-timeline-marker.warning { background: var(--ju-warning); }
    .premium-timeline-marker.secondary { background: var(--ju-gray-500); }
    .premium-timeline-marker.gold { background: var(--ju-gold); }

    .premium-timeline-content {
        background: var(--ju-gray-50);
        border-radius: var(--radius-lg);
        padding: 20px;
        border: 1px solid var(--ju-gray-200);
        transition: var(--transition);
    }

    .premium-timeline-item:hover .premium-timeline-content {
        background: white;
        border-color: var(--ju-blue-soft);
        box-shadow: var(--shadow-sm);
    }

    .premium-timeline-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--ju-gray-800);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .premium-timeline-date {
        font-size: 0.85rem;
        color: var(--ju-gray-600);
        margin-bottom: 0;
    }

    .premium-timeline-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        background: var(--ju-gold-soft);
        color: var(--ju-gold-dark);
        border-radius: var(--radius-full);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid rgba(196,167,71,0.3);
        margin-top: 8px;
    }

    /* ============================================
           PREMIUM IMPORTANT NOTES
        ============================================ */
    .premium-notes-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .premium-notes-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px;
        background: var(--ju-gray-50);
        border-radius: var(--radius-lg);
        border: 1px solid var(--ju-gray-200);
        transition: var(--transition);
        margin-bottom: 12px;
    }

    .premium-notes-item:last-child {
        margin-bottom: 0;
    }

    .premium-notes-item:hover {
        background: white;
        border-color: var(--ju-blue-soft);
        transform: translateX(4px);
    }

    .premium-notes-icon {
        width: 40px;
        height: 40px;
        background: var(--ju-blue-soft);
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ju-blue);
        font-size: 1rem;
        flex-shrink: 0;
        transition: var(--transition-bounce);
    }

    .premium-notes-item:hover .premium-notes-icon {
        background: var(--ju-gold);
        color: white;
        transform: rotate(5deg) scale(1.1);
    }

    .premium-notes-content {
        flex: 1;
    }

    .premium-notes-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--ju-gray-800);
        margin-bottom: 4px;
    }

    .premium-notes-text {
        font-size: 0.85rem;
        color: var(--ju-gray-600);
        margin: 0;
        line-height: 1.5;
    }

    /* ============================================
           PREMIUM MODAL - Dark Blue Theme
        ============================================ */
    .premium-modal .modal-content {
        border: none;
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .premium-modal .modal-header {
        background: linear-gradient(145deg, var(--ju-warning), var(--ju-warning-dark));
        padding: 20px 24px;
        border-bottom: none;
    }

    .premium-modal .modal-title {
        color: white;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .premium-modal .modal-body {
        padding: 28px;
    }

    .premium-modal .modal-footer {
        padding: 20px 24px;
        border-top: 1px solid var(--ju-gray-200);
        background: var(--ju-gray-50);
    }

    .premium-modal .event-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--ju-blue);
        margin-bottom: 16px;
    }

    .premium-modal .event-meta {
        background: var(--ju-gray-50);
        border-radius: var(--radius-lg);
        padding: 16px;
        margin-bottom: 20px;
    }

    .premium-modal .btn-close-white {
        filter: brightness(0) invert(1);
    }

    .premium-modal .btn {
        padding: 12px 24px;
        border-radius: var(--radius-lg);
        font-weight: 700;
        transition: var(--transition-bounce);
    }

    .premium-modal .btn-secondary {
        background: var(--ju-gray-600);
        color: white;
        border: none;
    }

    .premium-modal .btn-secondary:hover {
        background: var(--ju-gray-700);
        transform: translateY(-2px);
    }

    .premium-modal .btn-warning {
        background: var(--ju-warning);
        color: white;
        border: none;
    }

    .premium-modal .btn-warning:hover {
        background: var(--ju-warning-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(245,158,11,0.3);
    }

    /* ============================================
           PREMIUM PRINT STYLES
        ============================================ */
    @media print {
        .ju-header, .ju-sidebar, .ju-footer, .premium-actions-grid, 
        .premium-modal, .premium-fab, .breadcrumb, .content-header {
            display: none !important;
        }
        
        .premium-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            break-inside: avoid;
            page-break-inside: avoid;
        }
        
        .premium-card::before {
            display: none;
        }
        
        .premium-timeline::before {
            background: #ddd;
        }
        
        .premium-registration-value {
            color: #000 !important;
        }
    }

    /* ============================================
           HOVER UTILITY CLASSES
        ============================================ */
    .hover-lift {
        transition: transform var(--transition-bounce);
    }
    .hover-lift:hover {
        transform: translateY(-3px);
    }

    .hover-gold-shine {
        position: relative;
        overflow: hidden;
    }
    .hover-gold-shine::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 50%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transform: skewX(-15deg);
        transition: left 0.6s ease;
    }
    .hover-gold-shine:hover::before {
        left: 150%;
    }

    .hover-scale {
        transition: transform var(--transition-bounce);
    }
    .hover-scale:hover {
        transform: scale(1.05);
    }

    .hover-rotate-icon i {
        transition: transform var(--transition-elastic);
    }
    .hover-rotate-icon:hover i {
        transform: rotate(360deg);
    }
</style>

<div class="container-fluid px-lg-5">
    <div class="row">
        <!-- Left Column - Main Content -->
        <div class="col-lg-8">
            <!-- ============================================
                   PREMIUM SUCCESS REGISTRATION CARD
                ============================================ -->
            <div class="premium-card premium-success-card" data-aos="fade-up" data-aos-duration="800">
                <div class="premium-card-header success">
                    <h5 class="premium-card-title m-0 text-white">
                        <i class="fas fa-check-circle"></i> Registration Confirmed!
                    </h5>
                </div>
                <div class="premium-card-body">
                    <div class="text-center mb-4">
                        <div class="premium-success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h4 class="premium-success-title">You're Registered!</h4>
                        <p class="premium-success-message">Your registration has been confirmed successfully.</p>
                    </div>
                    
                    <div class="premium-registration-number mb-4">
                        <div class="premium-registration-label">Registration Number</div>
                        <div class="premium-registration-value">{{ $registration->registration_number }}</div>
                    </div>
                    
                    <div class="premium-email-alert">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Confirmation Email Sent</strong>
                            <p class="mb-0 text-muted">A confirmation email has been sent to <strong>{{ $registration->user->email }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ============================================
                   PREMIUM EVENT INFORMATION CARD
                ============================================ -->
            <div class="premium-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                <div class="premium-card-header">
                    <h5 class="premium-card-title">
                        <i class="fas fa-calendar-alt"></i> Event Information
                    </h5>
                </div>
                <div class="premium-card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="premium-event-title">{{ $registration->event->title }}</h4>
                            
                            <div class="premium-details-list">
                                <div class="premium-details-row">
                                    <span class="premium-details-label">Date:</span>
                                    <span class="premium-details-value">{{ $registration->event->start_date->format('l, F d, Y') }}</span>
                                </div>
                                <div class="premium-details-row">
                                    <span class="premium-details-label">Time:</span>
                                    <span class="premium-details-value">{{ $registration->event->start_date->format('h:i A') }} - {{ $registration->event->end_date->format('h:i A') }}</span>
                                </div>
                                <div class="premium-details-row">
                                    <span class="premium-details-label">Venue:</span>
                                    <span class="premium-details-value">
                                        {{ $registration->event->venue_name }}<br>
                                        <small class="text-muted">{{ $registration->event->building_name }}, {{ $registration->event->campus_name }}</small>
                                    </span>
                                </div>
                                <div class="premium-details-row">
                                    <span class="premium-details-label">Organizer:</span>
                                    <span class="premium-details-value">{{ $registration->event->organizer }}</span>
                                </div>
                                @if($registration->event->contact_email)
                                <div class="premium-details-row">
                                    <span class="premium-details-label">Contact:</span>
                                    <span class="premium-details-value">
                                        <a href="mailto:{{ $registration->event->contact_email }}">{{ $registration->event->contact_email }}</a>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if($registration->event->image)
                                <img src="{{ $registration->event->image_url }}" 
                                     alt="{{ $registration->event->title }}" 
                                     class="premium-event-image">
                            @else
                                <div class="premium-image-placeholder">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ============================================
                   PREMIUM REGISTRATION DETAILS CARD
                ============================================ -->
            <div class="premium-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                <div class="premium-card-header">
                    <h5 class="premium-card-title">
                        <i class="fas fa-ticket-alt"></i> Registration Details
                    </h5>
                </div>
                <div class="premium-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="premium-table">
                                <tr>
                                    <td>Status:</td>
                                    <td>
                                        <span class="premium-status-badge {{ $registration->status_color }}">
                                            <i class="fas fa-{{ $registration->status === 'confirmed' ? 'check-circle' : ($registration->status === 'pending' ? 'clock' : 'times-circle') }}"></i>
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Guests:</td>
                                    <td class="fw-bold">{{ $registration->guest_count }} person(s)</td>
                                </tr>
                                <tr>
                                    <td>Registered on:</td>
                                    <td>{{ $registration->registration_date->format('M d, Y h:i A') }}</td>
                                </tr>
                                @if($registration->confirmed_at)
                                <tr>
                                    <td>Confirmed on:</td>
                                    <td>{{ $registration->confirmed_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($registration->additional_info)
                                <h6 class="fw-bold mb-3" style="color: var(--ju-blue);">Additional Information:</h6>
                                <div class="premium-additional-info">
                                    {{ $registration->additional_info }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ============================================
                   PREMIUM ATTENDEE INFORMATION CARD
                ============================================ -->
            <div class="premium-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                <div class="premium-card-header">
                    <h5 class="premium-card-title">
                        <i class="fas fa-user"></i> Attendee Information
                    </h5>
                </div>
                <div class="premium-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="premium-attendee-card">
                                <div class="premium-attendee-icon">
                                    {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                                </div>
                                <table class="premium-table">
                                    <tr>
                                        <td>Name:</td>
                                        <td class="fw-bold">{{ $registration->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email:</td>
                                        <td>{{ $registration->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>User ID:</td>
                                        <td>{{ $registration->user->id }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($registration->attended)
                                <div class="premium-checked-in">
                                    <i class="fas fa-check-circle"></i>
                                    <div>
                                        <strong>Checked In</strong><br>
                                        <small class="text-muted">{{ $registration->check_in_time ? $registration->check_in_time->format('M d, Y h:i A') : '' }}</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Sidebar -->
        <div class="col-lg-4">
            <!-- ============================================
                   PREMIUM QUICK ACTIONS
                ============================================ -->
            <div class="premium-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="150">
                <div class="premium-card-header">
                    <h5 class="premium-card-title">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h5>
                </div>
                <div class="premium-card-body">
                    <div class="premium-actions-grid">
                        <a href="{{ route('my-events.index') }}" class="premium-action-btn premium-action-btn-outline-primary hover-gold-shine">
                            <i class="fas fa-calendar"></i>
                            <span>View My Events</span>
                        </a>
                        
                        @if($registration->status === 'confirmed' && $registration->event->start_date > now())
                        <button type="button" class="premium-action-btn premium-action-btn-outline-warning hover-gold-shine" 
                                data-bs-toggle="modal" data-bs-target="#premiumCancelModal">
                            <i class="fas fa-times-circle"></i>
                            <span>Cancel Registration</span>
                        </button>
                        @endif
                        
                        <button type="button" class="premium-action-btn premium-action-btn-outline-success hover-gold-shine" onclick="window.print()">
                            <i class="fas fa-print"></i>
                            <span>Print Confirmation</span>
                        </button>
                        
                        <a href="{{ route('events.guest.show', $registration->event) }}" class="premium-action-btn premium-action-btn-outline-info hover-gold-shine">
                            <i class="fas fa-info-circle"></i>
                            <span>View Event Details</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- ============================================
                   PREMIUM EVENT TIMELINE - Ethiopian Gold
                ============================================ -->
            <div class="premium-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="250">
                <div class="premium-card-header">
                    <h5 class="premium-card-title">
                        <i class="fas fa-clock"></i> Event Timeline
                    </h5>
                </div>
                <div class="premium-card-body">
                    <div class="premium-timeline">
                        <!-- Registration Opened -->
                        <div class="premium-timeline-item">
                            <div class="premium-timeline-marker success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="premium-timeline-content">
                                <h6 class="premium-timeline-title">
                                    <i class="fas fa-door-open" style="color: var(--ju-success);"></i>
                                    Registration Opened
                                </h6>
                                <p class="premium-timeline-date">{{ $registration->event->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        
                        <!-- You Registered -->
                        <div class="premium-timeline-item">
                            <div class="premium-timeline-marker primary">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="premium-timeline-content">
                                <h6 class="premium-timeline-title">
                                    <i class="fas fa-user-plus" style="color: var(--ju-blue);"></i>
                                    You Registered
                                </h6>
                                <p class="premium-timeline-date">{{ $registration->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        
                        <!-- Event Starts -->
                        <div class="premium-timeline-item">
                            <div class="premium-timeline-marker {{ $registration->event->start_date < now() ? 'secondary' : 'warning' }}">
                                <i class="fas fa-{{ $registration->event->start_date < now() ? 'check' : 'clock' }}"></i>
                            </div>
                            <div class="premium-timeline-content">
                                <h6 class="premium-timeline-title">
                                    <i class="fas fa-flag-checkered" style="color: {{ $registration->event->start_date < now() ? 'var(--ju-gray-500)' : 'var(--ju-warning)' }};"></i>
                                    Event Starts
                                </h6>
                                <p class="premium-timeline-date">{{ $registration->event->start_date->format('M d, Y h:i A') }}</p>
                                @if($registration->event->start_date > now())
                                    <span class="premium-timeline-badge">
                                        <i class="fas fa-hourglass-half"></i> {{ $registration->event->start_date->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        @if($registration->attended)
                        <!-- Checked In -->
                        <div class="premium-timeline-item">
                            <div class="premium-timeline-marker gold">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="premium-timeline-content">
                                <h6 class="premium-timeline-title">
                                    <i class="fas fa-check-circle" style="color: var(--ju-gold);"></i>
                                    Checked In
                                </h6>
                                <p class="premium-timeline-date">{{ $registration->check_in_time->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- ============================================
                   PREMIUM IMPORTANT NOTES
                ============================================ -->
            <div class="premium-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="350">
                <div class="premium-card-header">
                    <h5 class="premium-card-title">
                        <i class="fas fa-info-circle"></i> Important Notes
                    </h5>
                </div>
                <div class="premium-card-body">
                    <ul class="premium-notes-list">
                        <li class="premium-notes-item">
                            <div class="premium-notes-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="premium-notes-content">
                                <div class="premium-notes-title">Bring your university ID</div>
                                <p class="premium-notes-text">Required for check-in at the event</p>
                            </div>
                        </li>
                        <li class="premium-notes-item">
                            <div class="premium-notes-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="premium-notes-content">
                                <div class="premium-notes-title">Arrive 15 minutes early</div>
                                <p class="premium-notes-text">Check-in opens 30 minutes before start</p>
                            </div>
                        </li>
                        <li class="premium-notes-item">
                            <div class="premium-notes-icon">
                                <i class="fas fa-ban"></i>
                            </div>
                            <div class="premium-notes-content">
                                <div class="premium-notes-title">Cancellation Policy</div>
                                <p class="premium-notes-text">Free cancellation up to 24 hours before event</p>
                            </div>
                        </li>
                        <li class="premium-notes-item">
                            <div class="premium-notes-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="premium-notes-content">
                                <div class="premium-notes-title">Save your confirmation</div>
                                <p class="premium-notes-text">Keep this registration number for reference</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================
       PREMIUM CANCEL REGISTRATION MODAL
    ============================================ -->
@if($registration->status === 'confirmed' && $registration->event->start_date > now())
<div class="modal fade premium-modal" id="premiumCancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Cancel Registration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('event-registration.cancel', $registration->event) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3">Are you sure you want to cancel your registration for:</p>
                    <div class="event-title">{{ $registration->event->title }}</div>
                    
                    <div class="event-meta">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-calendar me-2" style="color: var(--ju-gold);"></i>
                            <span>{{ $registration->event->start_date->format('l, F d, Y h:i A') }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt me-2" style="color: var(--ju-gold);"></i>
                            <span>{{ $registration->event->venue_name }}, {{ $registration->event->campus_name }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label fw-bold">Reason for cancellation (optional)</label>
                        <textarea class="form-control" id="cancellation_reason" 
                                  name="cancellation_reason" rows="3"
                                  placeholder="Please let us know why you're cancelling..."></textarea>
                    </div>
                    
                    <div class="alert alert-info d-flex align-items-center gap-3 mb-0">
                        <i class="fas fa-info-circle fa-lg"></i>
                        <div>
                            <strong>Your seat will be offered</strong><br>
                            <small>to someone on the waitlist</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-times-circle me-2"></i>Cancel Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- AOS Animation Script -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 20,
            easing: 'ease-in-out-cubic'
        });

        // Form validation enhancement
        $('form').on('submit', function() {
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...');
        });
    });
</script>

<!-- Add AOS CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
@endsection

@push('styles')
<style>
    /* Override any remaining green theme styles */
    .ju-card, .ju-card-header, .ju-card-body, .ju-card-title {
        display: none;
    }
    
    /* Form control styling for modal */
    .form-control {
        border: 2px solid var(--ju-gray-200);
        border-radius: var(--radius-lg);
        padding: 12px 16px;
        transition: var(--transition);
    }
    
    .form-control:focus {
        border-color: var(--ju-blue);
        box-shadow: 0 0 0 4px rgba(10,44,110,0.1);
        outline: none;
    }
    
    .alert-info {
        background: var(--ju-info-soft);
        border: 1px solid rgba(10,44,110,0.15);
        color: var(--ju-blue);
        border-radius: var(--radius-lg);
    }
    
    .alert-info i {
        color: var(--ju-gold);
    }
</style>
@endpush
