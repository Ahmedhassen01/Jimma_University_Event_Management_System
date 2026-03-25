{{-- resources/views/events/guest/show.blade.php --}}
@extends('layouts.app')

@section('title', $event->title . ' | Jimma University Events')
@section('page-title', 'Event Details')
@section('page-subtitle', 'Complete Information & Registration')

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
            <i class="fas fa-home me-2" style="color: var(--ju-gold);"></i>Home
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('events.guest.dashboard') }}" class="d-flex align-items-center text-decoration-none">
            <i class="fas fa-calendar me-2" style="color: var(--ju-gold);"></i>Events
        </a>
    </li>
    <li class="breadcrumb-item active">
        <span class="fw-semibold" style="color: var(--ju-blue-dark);">{{ Str::limit($event->title, 25) }}</span>
    </li>
@endsection

@section('content')
<style>
    :root {
        /* ROYAL BLUE - Official Jimma University Primary Color - MATCHING LAYOUT */
        --ju-blue: #002789;              /* Official Royal Blue */
        --ju-blue-dark: #001a5c;          /* Darker shade for gradients */
        --ju-blue-darker: #021230;
        --ju-blue-light: #1a3a9a;          /* Lighter shade for accents */
        --ju-blue-lighter: #3a6ab0;
        --ju-blue-soft: rgba(0, 39, 137, 0.08);
        --ju-blue-glow: rgba(0, 39, 137, 0.2);
        --ju-blue-gradient: linear-gradient(145deg, #002789, #001a5c);
        --ju-blue-gradient-light: linear-gradient(145deg, #002789, #1a3a9a);
        
        /* Ethiopian Gold Accents - Official - MATCHING LAYOUT */
        --ju-gold: #C4A747;                /* Official Gold accent */
        --ju-gold-dark: #a5862e;
        --ju-gold-darker: #7e6623;
        --ju-gold-light: #e5d6a6;
        --ju-gold-soft: rgba(196, 167, 71, 0.12);
        --ju-gold-glow: rgba(196, 167, 71, 0.25);
        --ju-gold-gradient: linear-gradient(145deg, #C4A747, #a5862e);
        
        /* Neutral Colors - Matching Layout */
        --ju-white: #ffffff;
        --ju-offwhite: #f9f9f9;
        --ju-gray: #f0f0f0;
        --ju-gray-50: #f9fbfd;
        --ju-gray-100: #f1f5f9;
        --ju-gray-200: #e9edf2;
        --ju-gray-300: #dee4e9;
        --ju-gray-400: #b8c2cc;
        --ju-gray-500: #8f9aa8;
        --ju-gray-600: #64748b;
        --ju-gray-700: #475569;
        --ju-gray-800: #334155;
        
        /* Shadows - Royal Blue Tinted - Matching Layout */
        --shadow-xs: 0 2px 4px rgba(0,39,137,0.02);
        --shadow-sm: 0 4px 6px rgba(0,39,137,0.04);
        --shadow: 0 6px 12px rgba(0,39,137,0.06);
        --shadow-md: 0 8px 24px rgba(0,39,137,0.08);
        --shadow-lg: 0 16px 32px rgba(0,39,137,0.1);
        --shadow-xl: 0 24px 48px rgba(0,39,137,0.12);
        --shadow-2xl: 0 32px 64px rgba(0,39,137,0.15);
        --shadow-gold: 0 8px 20px rgba(196,167,71,0.2);
        --shadow-gold-lg: 0 16px 32px rgba(196,167,71,0.25);
        
        /* Border Radius - Consistent - Matching Layout */
        --radius-sm: 0.25rem;
        --radius: 0.375rem;
        --radius-md: 0.5rem;
        --radius-lg: 0.75rem;
        --radius-xl: 1rem;
        --radius-2xl: 1.25rem;
        --radius-full: 9999px;
        
        /* Transitions - Matching Layout */
        --transition-fast: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-slow: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-bounce: 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        --transition-elastic: 0.6s cubic-bezier(0.68, -0.6, 0.32, 1.6);
    }
    
    /* Professional Page Layout */
    .event-details-container {
        background: linear-gradient(135deg, var(--ju-offwhite) 0%, var(--ju-white) 100%);
        min-height: 100vh;
        padding-bottom: 60px;
    }
    
    /* Hero Header Section - Royal Blue Theme */
    .event-hero-section {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        padding: 50px 0;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 39, 137, 0.25);
    }
    
    .event-hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.08), transparent);
        transform: skewX(-20deg) translateX(100px);
        animation: shimmerRoyal 3s infinite;
    }
    
    .event-hero-section::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, transparent, var(--ju-gold), transparent);
        animation: slideGlow 2.5s infinite;
    }
    
    @keyframes shimmerRoyal {
        0% { transform: skewX(-20deg) translateX(100px); opacity: 0.4; }
        50% { transform: skewX(-20deg) translateX(-100px); opacity: 0.8; }
        100% { transform: skewX(-20deg) translateX(100px); opacity: 0.4; }
    }
    
    @keyframes slideGlow {
        0% { transform: translateX(-100%); opacity: 0; }
        50% { opacity: 1; }
        100% { transform: translateX(100%); opacity: 0; }
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 30px;
    }
    
    .hero-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        animation: slideInRight 0.8s ease-out;
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .hero-title-section {
        flex: 1;
        padding-right: 30px;
    }
    
    .hero-title {
        font-size: 2.4rem;
        font-weight: 800;
        margin-bottom: 15px;
        line-height: 1.2;
        letter-spacing: -0.5px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.15);
        animation: fadeInUp 0.8s ease-out 0.2s both;
    }
    
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
    
    .hero-subtitle {
        font-size: 1.2rem;
        opacity: 0.95;
        margin-bottom: 25px;
        max-width: 800px;
        line-height: 1.6;
        animation: fadeInUp 0.8s ease-out 0.3s both;
    }
    
    .hero-badges {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 25px;
        animation: fadeInUp 0.8s ease-out 0.4s both;
    }
    
    .hero-status-badge {
        padding: 10px 22px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        transition: var(--transition-bounce);
    }
    
    .hero-status-badge:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }
    
    .hero-status-badge i {
        transition: var(--transition-bounce);
    }
    
    .hero-status-badge:hover i {
        transform: scale(1.2) rotate(10deg);
    }
    
    .hero-type-badge {
        padding: 10px 22px;
        background: var(--ju-gold);
        color: #001a5c;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        border: none;
        box-shadow: 0 4px 12px rgba(196, 167, 71, 0.25);
        transition: var(--transition-bounce);
    }
    
    .hero-type-badge:hover {
        background: var(--ju-gold-dark);
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 20px rgba(196, 167, 71, 0.35);
    }
    
    /* Hero Image Section */
    .hero-image-container {
        width: 320px;
        height: 220px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 26, 92, 0.3);
        border: 4px solid rgba(255, 255, 255, 0.25);
        transition: var(--transition-bounce);
        animation: slideInLeft 0.8s ease-out 0.2s both;
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .hero-image-container:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 30px 50px rgba(0, 39, 137, 0.35);
        border-color: var(--ju-gold);
    }
    
    .hero-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition-bounce);
    }
    
    .hero-image-container:hover .hero-image {
        transform: scale(1.1);
    }
    
    /* PERFECT ACTION BUTTONS SECTION - Royal Blue Theme */
    .action-section {
        max-width: 1400px;
        margin: 0 auto 50px;
        padding: 0 30px;
    }
    
    .action-container {
        background: white;
        border-radius: 30px;
        padding: 40px;
        margin-top: -50px;
        position: relative;
        z-index: 100;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--ju-gray-200);
        animation: slideUpFade 0.8s ease-out 0.5s both;
    }
    
    @keyframes slideUpFade {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .section-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #002789;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }
    
    .section-title i {
        color: #002789;
        animation: pulseIcon 2s infinite;
    }
    
    @keyframes pulseIcon {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    .section-subtitle {
        color: var(--ju-gray-600);
        font-size: 1rem;
        max-width: 600px;
        margin: 0 auto;
    }
    
    /* PERFECT ACTION BUTTONS GRID */
    .action-buttons-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
        margin-bottom: 20px;
    }
    
    @media (max-width: 992px) {
        .action-buttons-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .action-buttons-grid {
            grid-template-columns: 1fr;
        }
        .hero-header {
            flex-direction: column;
        }
        .hero-image-container {
            width: 100%;
            margin-top: 20px;
        }
        .hero-title {
            font-size: 2rem;
        }
    }
    
    /* PERFECT BUTTON DESIGNS - Royal Blue Theme */
    .action-card {
        background: white;
        border-radius: 20px;
        padding: 35px 25px;
        text-align: center;
        border: 2px solid transparent;
        transition: var(--transition-bounce);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 200px;
        box-shadow: 0 5px 15px rgba(0, 39, 137, 0.05);
    }
    
    .action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(145deg, #002789, #001a5c);
        transition: height 0.4s ease;
    }
    
    .action-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(145deg, #002789, #001a5c);
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 1;
    }
    
    .action-card:hover {
        transform: translateY(-12px);
        box-shadow: var(--shadow-2xl);
        border-color: #002789;
    }
    
    .action-card:hover::before {
        height: 100%;
        opacity: 0.05;
    }
    
    .action-card:hover::after {
        opacity: 0.02;
    }
    
    /* Register Button - Royal Blue Theme with Gold */
    .register-card {
        border-color: var(--ju-gold);
        background: linear-gradient(145deg, rgba(196, 167, 71, 0.03) 0%, rgba(165, 134, 46, 0.03) 100%);
    }
    
    .register-card::before {
        background: var(--ju-gold-gradient);
    }
    
    /* Share Button - Royal Blue Theme */
    .share-card {
        border-color: #002789;
        background: linear-gradient(145deg, rgba(0, 39, 137, 0.03) 0%, rgba(0, 26, 92, 0.03) 100%);
    }
    
    .share-card::before {
        background: linear-gradient(145deg, #002789, #001a5c);
    }
    
    /* Calendar Button - Royal Blue Accent */
    .calendar-card {
        border-color: #1a3a9a;
        background: linear-gradient(145deg, rgba(26, 58, 154, 0.03) 0%, rgba(0, 39, 137, 0.03) 100%);
    }
    
    .calendar-card::before {
        background: linear-gradient(145deg, #1a3a9a, #002789);
    }
    
    /* Button Content */
    .action-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 2rem;
        position: relative;
        z-index: 2;
        transition: var(--transition-bounce);
    }
    
    .action-card:hover .action-icon {
        transform: scale(1.15) rotate(8deg);
    }
    
    .register-card .action-icon {
        background: var(--ju-gold-gradient);
        color: #001a5c;
        box-shadow: 0 8px 20px rgba(196, 167, 71, 0.35);
    }
    
    .share-card .action-icon {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        box-shadow: 0 8px 20px rgba(0, 39, 137, 0.35);
    }
    
    .calendar-card .action-icon {
        background: linear-gradient(145deg, #1a3a9a, #002789);
        color: white;
        box-shadow: 0 8px 20px rgba(26, 58, 154, 0.35);
    }
    
    .action-content {
        position: relative;
        z-index: 2;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .action-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: #001a5c;
        transition: var(--transition-bounce);
    }
    
    .action-card:hover .action-title {
        color: #002789;
        letter-spacing: 0.5px;
    }
    
    .action-description {
        font-size: 0.9rem;
        color: var(--ju-gray-600);
        margin-bottom: 25px;
        line-height: 1.5;
        max-width: 200px;
    }
    
    /* Action Button */
    .action-btn {
        padding: 14px 32px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        transition: var(--transition-bounce);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        min-width: 180px;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        letter-spacing: 0.5px;
    }
    
    .action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s ease;
    }
    
    .action-btn:hover::before {
        left: 100%;
    }
    
    .action-btn i {
        font-size: 1rem;
        transition: var(--transition-bounce);
    }
    
    .action-btn:hover i {
        transform: translateX(5px) scale(1.1);
    }
    
    .register-card .action-btn {
        background: var(--ju-gold-gradient);
        color: #001a5c;
        border-color: var(--ju-gold-dark);
    }
    
    .register-card .action-btn:hover {
        background: var(--ju-gold-dark);
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 12px 28px rgba(165, 134, 46, 0.4);
        color: #001a5c;
    }
    
    .share-card .action-btn {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        border-color: #001a5c;
    }
    
    .share-card .action-btn:hover {
        background: #001a5c;
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 12px 28px rgba(0, 39, 137, 0.4);
        color: white;
    }
    
    .calendar-card .action-btn {
        background: linear-gradient(145deg, #1a3a9a, #002789);
        color: white;
        border-color: #002789;
    }
    
    .calendar-card .action-btn:hover {
        background: #002789;
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 12px 28px rgba(0, 39, 137, 0.4);
        color: white;
    }
    
    /* Quick Info Cards - Royal Blue Theme */
    .quick-info-section {
        max-width: 1400px;
        margin: 0 auto 40px;
        padding: 0 30px;
    }
    
    .info-cards-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        margin-top: 20px;
    }
    
    @media (max-width: 1200px) {
        .info-cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .info-cards-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .info-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--ju-gray-200);
        transition: var(--transition-bounce);
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        overflow: hidden;
    }
    
    .info-card::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 3px;
        background: linear-gradient(145deg, #002789, #001a5c);
        transition: width 0.4s ease;
    }
    
    .info-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-2xl);
        border-color: #002789;
    }
    
    .info-card:hover::before {
        width: 100%;
    }
    
    .info-icon {
        width: 60px;
        height: 60px;
        background: #e6ebf7;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #002789;
        font-size: 1.4rem;
        flex-shrink: 0;
        transition: var(--transition-bounce);
    }
    
    .info-card:hover .info-icon {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        transform: scale(1.1) rotate(8deg);
    }
    
    .info-content {
        flex: 1;
    }
    
    .info-label {
        font-size: 0.75rem;
        color: var(--ju-gray-600);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 6px;
        font-weight: 700;
    }
    
    .info-value {
        font-size: 1.05rem;
        font-weight: 700;
        color: #001a5c;
        line-height: 1.4;
        transition: var(--transition-bounce);
    }
    
    .info-card:hover .info-value {
        color: #002789;
    }
    
    /* Main Content Area */
    .main-content-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 30px;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 35px;
    }
    
    @media (max-width: 992px) {
        .main-content-wrapper {
            grid-template-columns: 1fr;
        }
    }
    
    /* Content Cards - Royal Blue Theme */
    .content-card {
        background: white;
        border-radius: 20px;
        margin-bottom: 35px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--ju-gray-200);
        overflow: hidden;
        transition: var(--transition-bounce);
    }
    
    .content-card:hover {
        box-shadow: var(--shadow-2xl);
        border-color: #002789;
        transform: translateY(-5px);
    }
    
    .card-header {
        padding: 25px 30px;
        background: white;
        border-bottom: 1px solid var(--ju-gray-200);
        display: flex;
        align-items: center;
        gap: 15px;
        transition: var(--transition-bounce);
    }
    
    .content-card:hover .card-header {
        background: #e6ebf7;
    }
    
    .card-header-icon {
        width: 45px;
        height: 45px;
        background: #e6ebf7;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #002789;
        font-size: 1.1rem;
        transition: var(--transition-bounce);
    }
    
    .content-card:hover .card-header-icon {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        transform: scale(1.1) rotate(8deg);
    }
    
    .card-header-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #001a5c;
        margin: 0;
        transition: var(--transition-bounce);
    }
    
    .content-card:hover .card-header-title {
        color: #002789;
    }
    
    .card-body {
        padding: 30px;
    }
    
    /* Event Description */
    .event-description-content {
        line-height: 1.9;
        color: #2C3E50;
    }
    
    .event-description-content p {
        margin-bottom: 1.3rem;
    }
    
    .event-description-content h3,
    .event-description-content h4 {
        color: #002789;
        margin-top: 1.8rem;
        margin-bottom: 1.2rem;
        font-weight: 700;
    }
    
    .event-description-content ul,
    .event-description-content ol {
        padding-left: 1.8rem;
        margin-bottom: 1.3rem;
    }
    
    .event-description-content li {
        margin-bottom: 0.5rem;
    }
    
    /* Tags Section */
    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 25px;
    }
    
    .tag-item {
        padding: 8px 18px;
        background: #e6ebf7;
        color: #002789;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px solid var(--ju-gray-200);
        transition: var(--transition-bounce);
    }
    
    .tag-item:hover {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        transform: translateY(-3px) scale(1.02);
        border-color: transparent;
        box-shadow: 0 6px 16px rgba(0, 39, 137, 0.25);
    }
    
    /* Location Card */
    .location-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 35px;
    }
    
    @media (max-width: 768px) {
        .location-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .location-map {
        height: 250px;
        background: #e6ebf7;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--ju-gray-200);
        transition: var(--transition-bounce);
        position: relative;
        overflow: hidden;
    }
    
    .location-map:hover {
        border-color: #002789;
        transform: scale(1.02);
        box-shadow: var(--shadow-2xl);
    }
    
    .location-map i {
        transition: var(--transition-bounce);
    }
    
    .location-map:hover i {
        transform: scale(1.2) translateY(-5px);
        color: #002789 !important;
    }
    
    .location-details h5 {
        color: #002789;
        margin-bottom: 15px;
        font-weight: 700;
        font-size: 1.2rem;
    }
    
    .location-meta {
        margin-top: 20px;
    }
    
    .meta-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 15px;
        padding: 8px;
        border-radius: 10px;
        transition: var(--transition-bounce);
    }
    
    .meta-item:hover {
        background: #e6ebf7;
        transform: translateX(8px);
    }
    
    .meta-icon {
        color: #002789;
        font-size: 1.1rem;
        margin-top: 3px;
        transition: var(--transition-bounce);
    }
    
    .meta-item:hover .meta-icon {
        transform: scale(1.2);
        color: #001a5c;
    }
    
    /* Sidebar Cards - Royal Blue Theme */
    .organizer-card {
        background: white;
        border-radius: 20px;
        margin-bottom: 35px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--ju-gray-200);
        overflow: hidden;
        transition: var(--transition-bounce);
    }
    
    .organizer-card:hover {
        box-shadow: var(--shadow-2xl);
        border-color: #002789;
        transform: translateY(-5px);
    }
    
    .organizer-header {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        padding: 30px 25px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .organizer-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }
    
    .organizer-card:hover .organizer-header::before {
        transform: translateX(100%);
    }
    
    .organizer-avatar {
        width: 90px;
        height: 90px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        margin: 0 auto 18px;
        border: 4px solid rgba(255, 255, 255, 0.4);
        transition: var(--transition-bounce);
    }
    
    .organizer-card:hover .organizer-avatar {
        transform: scale(1.1) rotate(8deg);
        border-color: var(--ju-gold);
    }
    
    .organizer-body {
        padding: 25px;
    }
    
    .contact-details {
        margin-top: 25px;
        padding-top: 25px;
        border-top: 1px solid var(--ju-gray-200);
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 18px;
        padding: 12px;
        border-radius: 12px;
        transition: var(--transition-bounce);
        text-decoration: none;
        color: inherit;
    }
    
    .contact-item:hover {
        background: #e6ebf7;
        transform: translateX(8px);
        box-shadow: 0 4px 12px rgba(0, 39, 137, 0.08);
    }
    
    .contact-icon {
        width: 42px;
        height: 42px;
        background: #e6ebf7;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #002789;
        font-size: 1rem;
        flex-shrink: 0;
        transition: var(--transition-bounce);
    }
    
    .contact-item:hover .contact-icon {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        transform: scale(1.1) rotate(8deg);
    }
    
    /* Attendance Stats - Royal Blue Theme */
    .attendance-card {
        background: white;
        border-radius: 20px;
        margin-bottom: 35px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--ju-gray-200);
        overflow: hidden;
        transition: var(--transition-bounce);
    }
    
    .attendance-card:hover {
        box-shadow: var(--shadow-2xl);
        border-color: #002789;
        transform: translateY(-5px);
    }
    
    .attendance-header {
        padding: 25px;
        background: white;
        border-bottom: 1px solid var(--ju-gray-200);
        display: flex;
        align-items: center;
        gap: 12px;
        transition: var(--transition-bounce);
    }
    
    .attendance-card:hover .attendance-header {
        background: #e6ebf7;
    }
    
    .attendance-stats {
        padding: 25px;
    }
    
    .progress-container {
        margin-bottom: 25px;
    }
    
    .progress-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .progress-label {
        font-size: 0.9rem;
        color: var(--ju-gray-600);
        font-weight: 600;
    }
    
    .progress-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #002789;
    }
    
    .progress-bar-custom {
        height: 14px;
        background: #e6ebf7;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(145deg, #002789, #001a5c);
        border-radius: 8px;
        transition: width 1.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
    }
    
    .progress-fill::after {
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
    
    /* Similar Events - Royal Blue Theme */
    .similar-events-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--ju-gray-200);
        overflow: hidden;
        transition: var(--transition-bounce);
    }
    
    .similar-events-card:hover {
        box-shadow: var(--shadow-2xl);
        border-color: #002789;
        transform: translateY(-5px);
    }
    
    .similar-events-header {
        padding: 25px;
        background: white;
        border-bottom: 1px solid var(--ju-gray-200);
        display: flex;
        align-items: center;
        gap: 12px;
        transition: var(--transition-bounce);
    }
    
    .similar-events-card:hover .similar-events-header {
        background: #e6ebf7;
    }
    
    .similar-events-list {
        padding: 0;
    }
    
    .similar-event-item {
        padding: 20px 25px;
        border-bottom: 1px solid var(--ju-gray-200);
        transition: var(--transition-bounce);
        display: block;
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
    }
    
    .similar-event-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 4px;
        height: 0;
        background: linear-gradient(145deg, #002789, #001a5c);
        transition: height 0.3s ease;
    }
    
    .similar-event-item:hover {
        background: #e6ebf7;
        transform: translateX(8px);
    }
    
    .similar-event-item:hover::before {
        height: 100%;
    }
    
    .similar-event-item:last-child {
        border-bottom: none;
    }
    
    .similar-event-content {
        display: flex;
        align-items: center;
        gap: 18px;
    }
    
    .similar-event-image {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        border: 2px solid var(--ju-gray-200);
        transition: var(--transition-bounce);
    }
    
    .similar-event-item:hover .similar-event-image {
        border-color: #002789;
        transform: scale(1.05);
    }
    
    .similar-event-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition-bounce);
    }
    
    .similar-event-item:hover .similar-event-image img {
        transform: scale(1.1);
    }
    
    .similar-event-details {
        flex: 1;
    }
    
    .similar-event-title {
        font-size: 1rem;
        font-weight: 700;
        color: #001a5c;
        margin-bottom: 8px;
        line-height: 1.4;
        transition: var(--transition-bounce);
    }
    
    .similar-event-item:hover .similar-event-title {
        color: #002789;
    }
    
    .similar-event-meta {
        display: flex;
        gap: 18px;
        font-size: 0.85rem;
        color: var(--ju-gray-600);
    }
    
    .similar-event-meta i {
        color: #002789;
        margin-right: 6px;
        transition: var(--transition-bounce);
    }
    
    .similar-event-item:hover .similar-event-meta i {
        color: #001a5c;
        transform: scale(1.2);
    }
    
    /* Countdown Timer - Royal Blue Theme */
    .countdown-container {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 35px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--ju-gray-200);
        text-align: center;
        transition: var(--transition-bounce);
    }
    
    .countdown-container:hover {
        box-shadow: var(--shadow-2xl);
        border-color: #002789;
        transform: translateY(-5px);
    }
    
    .countdown-title {
        color: #002789;
        font-weight: 700;
        margin-bottom: 20px;
        font-size: 1.15rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .countdown-title i {
        animation: pulseIcon 2s infinite;
    }
    
    .countdown-timer {
        display: flex;
        justify-content: center;
        gap: 20px;
    }
    
    .countdown-unit {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #e6ebf7;
        border-radius: 12px;
        padding: 15px 18px;
        min-width: 85px;
        transition: var(--transition-bounce);
    }
    
    .countdown-unit:hover {
        background: linear-gradient(145deg, #002789, #001a5c);
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 8px 20px rgba(0, 39, 137, 0.25);
    }
    
    .countdown-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: #002789;
        line-height: 1;
        transition: var(--transition-bounce);
    }
    
    .countdown-unit:hover .countdown-value {
        color: white;
    }
    
    .countdown-label {
        font-size: 0.8rem;
        color: var(--ju-gray-600);
        margin-top: 8px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        transition: var(--transition-bounce);
    }
    
    .countdown-unit:hover .countdown-label {
        color: rgba(255, 255, 255, 0.9);
    }
    
    /* Footer Actions - Royal Blue Theme */
    .footer-actions {
        max-width: 1400px;
        margin: 50px auto 0;
        padding: 0 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .back-link {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #002789;
        text-decoration: none;
        font-weight: 700;
        padding: 14px 28px;
        border: 2px solid #002789;
        border-radius: 12px;
        transition: var(--transition-bounce);
        background: white;
    }
    
    .back-link:hover {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        transform: translateX(-8px);
        border-color: transparent;
        box-shadow: 0 8px 20px rgba(0, 39, 137, 0.3);
    }
    
    .back-link i {
        transition: var(--transition-bounce);
    }
    
    .back-link:hover i {
        transform: translateX(-5px);
        color: var(--ju-gold);
    }
    
    .social-share {
        display: flex;
        gap: 12px;
    }
    
    .share-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        font-size: 1rem;
        transition: var(--transition-bounce);
        position: relative;
        overflow: hidden;
    }
    
    .share-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(-100%) rotate(45deg);
        transition: transform 0.4s ease;
    }
    
    .share-btn:hover::before {
        transform: translateX(100%) rotate(45deg);
    }
    
    .share-btn.facebook { 
        background: #1877F2; 
        box-shadow: 0 4px 12px rgba(24, 119, 242, 0.3);
    }
    .share-btn.twitter { 
        background: #1DA1F2; 
        box-shadow: 0 4px 12px rgba(29, 161, 242, 0.3);
    }
    .share-btn.linkedin { 
        background: #0A66C2; 
        box-shadow: 0 4px 12px rgba(10, 102, 194, 0.3);
    }
    .share-btn.whatsapp { 
        background: #25D366; 
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
    }
    
    .share-btn:hover {
        transform: translateY(-6px) scale(1.15);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    }
    
    /* Royal Blue Theme Utility Classes */
    .bg-ju-blue {
        background-color: #002789 !important;
    }
    
    .bg-ju-blue-dark {
        background-color: #001a5c !important;
    }
    
    .text-ju-blue {
        color: #002789 !important;
    }
    
    .text-ju-gold {
        color: var(--ju-gold) !important;
    }
    
    .border-ju-blue {
        border-color: #002789 !important;
    }
    
    /* Enhanced Hover Effects */
    .alert-success {
        background: linear-gradient(145deg, #e6ebf7, var(--ju-white));
        border-color: var(--ju-gray-200);
        color: #001a5c;
        transition: var(--transition-bounce);
    }
    
    .alert-success:hover {
        background: linear-gradient(145deg, #d4e0f0, #e6ebf7);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 39, 137, 0.1);
    }
    
    .alert-warning {
        background: linear-gradient(145deg, #fff4e5, #fff9e6);
        border-color: #ffe0b3;
        color: #996633;
        transition: var(--transition-bounce);
    }
    
    .alert-warning:hover {
        background: linear-gradient(145deg, #ffebd4, #fff4e5);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 152, 0, 0.1);
    }
    
    .alert-danger {
        background: linear-gradient(145deg, #ffebee, #ffe5e5);
        border-color: #ffb3b3;
        color: #b33f3f;
        transition: var(--transition-bounce);
    }
    
    .alert-danger:hover {
        background: linear-gradient(145deg, #ffe1e4, #ffebee);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(220, 53, 69, 0.1);
    }
    
    /* Animation for page entrance */
    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.98);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .content-card, .organizer-card, .attendance-card, .similar-events-card {
        animation: fadeInScale 0.6s ease-out both;
    }
    
    .content-card:nth-child(1) { animation-delay: 0.1s; }
    .content-card:nth-child(2) { animation-delay: 0.2s; }
    .organizer-card { animation-delay: 0.3s; }
    .attendance-card { animation-delay: 0.4s; }
    .similar-events-card { animation-delay: 0.5s; }
</style>

<div class="event-details-container">
    <!-- Hero Section - Royal Blue Theme -->
    <div class="event-hero-section">
        <div class="hero-content">
            <div class="hero-header">
                <div class="hero-title-section">
                    <h1 class="hero-title">{{ $event->title }}</h1>
                    <p class="hero-subtitle">{{ $event->short_description ?? Str::limit($event->description, 180) }}</p>
                    
                    <div class="hero-badges">
                        <span class="hero-status-badge">
                            <i class="fas fa-{{ $event->status == 'ongoing' ? 'play-circle' : ($event->status == 'upcoming' ? 'clock' : 'check-circle') }} me-1"></i>
                            {{ $event->status == 'ongoing' ? 'Live Now' : ($event->status == 'upcoming' ? 'Upcoming' : 'Completed') }}
                        </span>
                        
                        <span class="hero-type-badge">
                            <i class="fas fa-{{ $event->event_type_icon }} me-1"></i>
                            {{ ucfirst($event->event_type) }}
                        </span>
                        
                        @if($event->is_featured)
                        <span class="hero-status-badge">
                            <i class="fas fa-star me-1" style="color: var(--ju-gold);"></i>
                            Featured Event
                        </span>
                        @endif
                        
                        @if($event->requires_registration)
                        <span class="hero-status-badge">
                            <i class="fas fa-user-plus me-1"></i>
                            Registration Required
                        </span>
                        @endif
                    </div>
                </div>
                
                <div class="hero-image-container">
                    @if($event->image)
                    <img src="{{ $event->image_url }}" 
                         alt="{{ $event->title }}"
                         class="hero-image"
                         onerror="this.src='{{ asset('images/default-event.jpg') }}'">
                    @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center" 
                         style="background: linear-gradient(145deg, #001a5c, #002789);">
                        <i class="fas fa-{{ $event->event_type_icon }} fa-3x text-white opacity-75"></i>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- PERFECT ACTION BUTTONS SECTION - Royal Blue Theme -->
    <div class="action-section">
        <div class="action-container">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </h2>
                <p class="section-subtitle">
                    Take immediate action for this event. Register, share with others, or add to your calendar.
                </p>
            </div>
            
            <div class="action-buttons-grid">
                <!-- Register Button - Royal Blue with Gold -->
                <div class="action-card register-card">
                    <div class="action-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Register Now</h3>
                        <p class="action-description">
                            Secure your spot for this event. Limited seats available!
                        </p>
                        @if($event->requires_registration)
                        <a href="{{ route('event-registration.create', $event) }}" 
                           class="action-btn p-4">
                            <i class="fas fa-user-plus"></i>
                            Register
                        </a>
                        @else
                        <span class="action-btn" style="background: var(--ju-gray-400); cursor: not-allowed; opacity: 0.8; color: white;">
                            <i class="fas fa-ban"></i>
                            Registration Closed
                        </span>
                        @endif
                    </div>
                </div>
                
                <!-- Share Button - Royal Blue Theme -->
                <div class="action-card share-card">
                    <div class="action-icon">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Share Event</h3>
                        <p class="action-description">
                            Share this event with friends, colleagues, or on social media.
                        </p>
                        <a href="{{ route('events.guest.share', $event->slug) }}" 
                           class="action-btn p-4">
                            <i class="fas fa-share-alt"></i>
                            Share Event
                        </a>
                    </div>
                </div>
                
                <!-- Calendar Button - Royal Blue Accent -->
                <div class="action-card calendar-card">
                    <div class="action-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Add to Calendar</h3>
                        <p class="action-description">
                            Never miss this event. Add it to your digital calendar.
                        </p>
                        <a href="{{ route('events.guest.export-ics', $event->slug) }}" 
                           class="action-btn p-4 w-2">
                            <i class="fas fa-calendar-plus"></i>
                            Add to Calendar
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1" style="color: #002789;"></i>
                    All actions open in new windows for your convenience
                </small>
            </div>
        </div>
    </div>

    <!-- Quick Info Cards - Royal Blue Theme -->
    <div class="quick-info-section">
        <div class="info-cards-grid">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Event Date</div>
                    <div class="info-value">{{ $event->start_date->format('l, F j, Y') }}</div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Time</div>
                    <div class="info-value">{{ $event->start_date->format('h:i A') }}</div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Venue</div>
                    <div class="info-value" title="{{ $event->venue_name }}, {{ $event->campus_name }}">
                        {{ Str::limit($event->venue_name . ', ' . $event->campus_name, 30) }}
                    </div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Attendance</div>
                    <div class="info-value">
                        {{ $event->registered_attendees ?? 0 }}
                        @if($event->max_attendees)
                        / {{ $event->max_attendees }} registered
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content-wrapper">
        <!-- Left Column - Main Content -->
        <div class="main-content">
            <!-- Event Description -->
            <div class="content-card">
                <div class="card-header">
                    <div class="card-header-icon">
                        <i class="fas fa-align-left"></i>
                    </div>
                    <h3 class="card-header-title">Event Description</h3>
                </div>
                <div class="card-body">
                    <div class="event-description-content">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                    
                    @if($event->tags && is_array($event->tags) && count($event->tags) > 0)
                    <div class="tags-container">
                        @foreach($event->tags as $tag)
                        @if(is_string($tag))
                        <span class="tag-item">{{ trim($tag) }}</span>
                        @endif
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Location Information -->
            <div class="content-card">
                <div class="card-header">
                    <div class="card-header-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="card-header-title">Location Details</h3>
                </div>
                <div class="card-body">
                    <div class="location-grid">
                        <div class="location-details">
                            <h5>{{ $event->venue_name }}</h5>
                            <p class="text-muted">{{ $event->campus_name }} Campus</p>
                            
                            <div class="location-meta">
                                <div class="meta-item">
                                    <i class="fas fa-directions meta-icon"></i>
                                    <div>
                                        <strong>Getting There:</strong>
                                        <p class="text-muted small mb-0 mt-1">
                                            Use the main university entrance and follow signs to the event venue.
                                            Parking available near the venue.
                                        </p>
                                    </div>
                                </div>
                                
                                @if($event->location_instructions)
                                <div class="meta-item">
                                    <i class="fas fa-info-circle meta-icon"></i>
                                    <div>
                                        <strong>Additional Instructions:</strong>
                                        <p class="text-muted small mb-0 mt-1">
                                            {{ $event->location_instructions }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="location-map">
                            <div class="text-center">
                                <i class="fas fa-map-marker-alt fa-3x mb-3" style="color: #002789;"></i>
                                <h6 style="color: #002789; font-weight: 700;">{{ $event->venue_name }}</h6>
                                <p class="text-muted mb-0 small">{{ $event->campus_name }} Campus</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="sidebar-content">
            <!-- Countdown Timer (for upcoming events) -->
            @if($event->status == 'upcoming')
            <div class="countdown-container">
                <div class="countdown-title">
                    <i class="fas fa-hourglass-half"></i>
                    Event Starts In
                </div>
                <div class="countdown-timer" id="countdownTimer">
                    <div class="countdown-unit">
                        <span class="countdown-value" id="days">00</span>
                        <span class="countdown-label">Days</span>
                    </div>
                    <div class="countdown-unit">
                        <span class="countdown-value" id="hours">00</span>
                        <span class="countdown-label">Hours</span>
                    </div>
                    <div class="countdown-unit">
                        <span class="countdown-value" id="minutes">00</span>
                        <span class="countdown-label">Minutes</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Organizer Info -->
            <div class="organizer-card">
                <div class="organizer-header">
                    <div class="organizer-avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h5 class="mb-0 fw-bold">{{ $event->organizer }}</h5>
                    <small class="opacity-75">Event Organizer</small>
                </div>
                <div class="organizer-body">
                    <p class="text-muted small mb-0 text-center">
                        {{ $event->organizer_description ?? 'Primary organizer for this event' }}
                    </p>
                    
                    <div class="contact-details">
                        @if($event->contact_email)
                        <a href="mailto:{{ $event->contact_email }}" class="contact-item text-decoration-none text-dark">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-info">
                                <div class="small text-muted">Email</div>
                                <div class="fw-medium">{{ $event->contact_email }}</div>
                            </div>
                        </a>
                        @endif
                        
                        @if($event->contact_phone)
                        <a href="tel:{{ $event->contact_phone }}" class="contact-item text-decoration-none text-dark">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-info">
                                <div class="small text-muted">Phone</div>
                                <div class="fw-medium">{{ $event->contact_phone }}</div>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Attendance Stats -->
            @if($event->max_attendees)
            <div class="attendance-card">
                <div class="attendance-header">
                    <div class="card-header-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="card-header-title mb-0">Registration Status</h3>
                </div>
                <div class="attendance-stats">
                    <div class="progress-container">
                        <div class="progress-info">
                            <span class="progress-label">Registration Progress</span>
                            <span class="progress-value">
                                {{ $event->registered_attendees ?? 0 }} / {{ $event->max_attendees }}
                            </span>
                        </div>
                        <div class="progress-bar-custom">
                            @php
                                $percentage = $event->max_attendees > 0 ? min(100, (($event->registered_attendees ?? 0) / $event->max_attendees) * 100) : 0;
                                $progressColor = $percentage > 80 ? 'linear-gradient(145deg, #DC3545 0%, #B02A37 100%)' : 
                                               ($percentage > 50 ? 'linear-gradient(145deg, #C4A747 0%, #a5862e 100%)' : 'linear-gradient(145deg, #002789, #001a5c)');
                            @endphp
                            <div class="progress-fill" 
                                 style="width: {{ $percentage }}%; background: {{ $progressColor }};">
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        @if($percentage >= 100)
                        <div class="alert alert-danger py-2 mb-0">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Registration Full
                        </div>
                        @elseif($percentage > 80)
                        <div class="alert alert-warning py-2 mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Limited Seats Available
                        </div>
                        @else
                        <div class="alert alert-success py-2 mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            Seats Available
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Similar Events -->
            @if(isset($similarEvents) && $similarEvents->count() > 0)
            <div class="similar-events-card">
                <div class="similar-events-header">
                    <div class="card-header-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <h3 class="card-header-title mb-0">Similar Events</h3>
                </div>
                <div class="similar-events-list">
                    @foreach($similarEvents as $similarEvent)
                    <a href="{{ route('events.guest.show', $similarEvent->slug) }}" 
                       class="similar-event-item">
                        <div class="similar-event-content">
                            <div class="similar-event-image">
                                @if($similarEvent->image)
                                <img src="{{ $similarEvent->image_url }}" 
                                     alt="{{ $similarEvent->title }}"
                                     onerror="this.src='{{ asset('images/default-event.jpg') }}'">
                                @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center" 
                                     style="background: #e6ebf7;">
                                    <i class="fas fa-{{ $similarEvent->event_type_icon }}" 
                                       style="color: #002789;"></i>
                                </div>
                                @endif
                            </div>
                            <div class="similar-event-details">
                                <div class="similar-event-title">
                                    {{ Str::limit($similarEvent->title, 45) }}
                                </div>
                                <div class="similar-event-meta">
                                    <span>
                                        <i class="fas fa-calendar"></i>
                                        {{ $similarEvent->start_date->format('M d') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock"></i>
                                        {{ $similarEvent->start_date->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Footer Actions -->
    <div class="footer-actions">
        <a href="{{ route('events.guest.dashboard') }}" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Events
        </a>
        
        <div class="social-share">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
               target="_blank" class="share-btn facebook" title="Share on Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ urlencode(url()->current()) }}" 
               target="_blank" class="share-btn twitter" title="Share on Twitter">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($event->title) }}" 
               target="_blank" class="share-btn linkedin" title="Share on LinkedIn">
                <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="https://wa.me/?text={{ urlencode($event->title . ' - ' . url()->current()) }}" 
               target="_blank" class="share-btn whatsapp" title="Share on WhatsApp">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Countdown Timer
        @if(isset($event) && $event->status == 'upcoming')
        const eventDate = new Date('{{ $event->start_date->toIso8601String() }}').getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const timeLeft = eventDate - now;
            
            if (timeLeft > 0) {
                const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                
                document.getElementById('days').textContent = String(days).padStart(2, '0');
                document.getElementById('hours').textContent = String(hours).padStart(2, '0');
                document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            } else {
                const countdownTimer = document.getElementById('countdownTimer');
                if (countdownTimer) {
                    countdownTimer.innerHTML = `
                        <div class="alert alert-success mb-0 w-100">
                            <i class="fas fa-play-circle me-2"></i>
                            Event has started!
                        </div>
                    `;
                }
                clearInterval(countdownInterval);
            }
        }
        
        updateCountdown();
        const countdownInterval = setInterval(updateCountdown, 60000);
        @endif
        
        // Button hover effects
        document.querySelectorAll('.action-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-12px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId !== '#') {
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
        
        // Animate progress bar on scroll
        const progressFill = document.querySelector('.progress-fill');
        if (progressFill) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const width = progressFill.style.width;
                        progressFill.style.width = '0%';
                        setTimeout(() => {
                            progressFill.style.width = width;
                        }, 300);
                    }
                });
            }, { threshold: 0.5 });
            
            observer.observe(progressFill.parentElement);
        }
        
        // Share button analytics tracking
        document.querySelectorAll('.share-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                console.log('Shared via:', this.classList[1]);
            });
        });
        
        // Add animation to cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.content-card, .organizer-card, .attendance-card, .similar-events-card').forEach(card => {
            observer.observe(card);
        });
    });
</script>

<!-- Add Animate.css for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection
