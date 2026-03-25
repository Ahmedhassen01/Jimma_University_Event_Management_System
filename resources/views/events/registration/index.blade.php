@extends('layouts.app')

@section('title', 'Register for Events | Jimma University')
@section('page-title', 'Event Registration')
@section('page-subtitle', 'Join Exciting Events at Jimma University')

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none hover-lift">
            <i class="fas fa-home me-2" style="color: var(--ju-gold);"></i>Home
        </a>
    </li>
    <li class="breadcrumb-item active">
        <span class="fw-semibold" style="color: var(--ju-blue-dark);">Event Registration</span>
    </li>
@endsection

@section('content')
<style>
    /* ============================================
           PREMIUM JIMMA UNIVERSITY EVENT REGISTRATION
           ROYAL BLUE (#002789) & GOLD (#C4A747) - OFFICIAL
           Perfectly Fitted Cards | Fixed Layout
        ============================================ */
    
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
        
        /* Semantic Colors - Matching Layout Success/Error colors */
        --ju-success: #28a745;
        --ju-success-dark: #1e7e34;
        --ju-warning: #ffc107;
        --ju-warning-dark: #d39e00;
        --ju-danger: #dc3545;
        --ju-danger-dark: #bd2130;
        --ju-info: #17a2b8;
        
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
        --ju-gray-900: #1e293b;
        
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
        --radius-3xl: 1.5rem;
        --radius-full: 9999px;
        
        /* Transitions - Matching Layout */
        --transition-fast: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-slow: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-bounce: 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        --transition-elastic: 0.6s cubic-bezier(0.68, -0.6, 0.32, 1.6);
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
        font-family: var(--font-primary);
        overflow-x: hidden;
    }

    .container-fluid {
        background: var(--ju-offwhite);
    }

    /* ============================================
           PREMIUM HERO SECTION - Royal Blue Excellence
        ============================================ */
    .premium-hero-card {
        position: relative;
        background: linear-gradient(145deg, #002789, #001a5c);
        padding: 60px 50px;
        border-radius: var(--radius-3xl);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        border: 1px solid rgba(255,255,255,0.1);
        margin-bottom: 40px;
    }

    .premium-hero-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 10% 20%, rgba(255,255,255,0.1) 0%, transparent 40%),
            radial-gradient(circle at 90% 70%, rgba(196,167,71,0.15) 0%, transparent 40%),
            radial-gradient(circle at 30% 80%, rgba(255,255,255,0.08) 0%, transparent 35%);
        pointer-events: none;
    }

    .premium-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 24px;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: var(--radius-full);
        color: white;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 24px;
    }

    .premium-hero-badge i {
        color: var(--ju-gold);
        animation: starTwinkle 2s infinite;
    }

    @keyframes starTwinkle {
        0%, 100% { opacity: 0.8; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.2); }
    }

    .premium-hero-title {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        margin-bottom: 20px;
        line-height: 1.1;
        letter-spacing: -1.5px;
        text-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .gold-text {
        color: var(--ju-gold);
        position: relative;
        display: inline-block;
        text-shadow: 0 2px 10px rgba(196,167,71,0.3);
    }

    .gold-text::after {
        content: '';
        position: absolute;
        bottom: 5px;
        left: 0;
        width: 100%;
        height: 8px;
        background: rgba(196,167,71,0.3);
        border-radius: var(--radius-full);
        z-index: -1;
        animation: underlineExpand 0.8s ease 0.5s both;
    }

    @keyframes underlineExpand {
        from { width: 0; }
        to { width: 100%; }
    }

    .premium-hero-subtitle {
        font-size: 1.2rem;
        color: rgba(255,255,255,0.95);
        max-width: 700px;
        margin-bottom: 32px;
        line-height: 1.6;
    }

    .premium-hero-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
    }

    .premium-hero-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 16px 32px;
        border-radius: var(--radius-full);
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        transition: var(--transition-bounce);
        position: relative;
        overflow: hidden;
        border: none;
        cursor: pointer;
    }

    .premium-hero-btn-primary {
        background: white;
        color: #002789;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .premium-hero-btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
    }

    .premium-hero-btn-outline {
        background: transparent;
        color: white;
        border: 2px solid rgba(255,255,255,0.5);
        backdrop-filter: blur(5px);
    }

    .premium-hero-btn-outline:hover {
        background: white;
        color: #002789;
        border-color: white;
        transform: translateY(-3px);
    }

    .premium-hero-btn i {
        transition: transform var(--transition-elastic);
    }

    .premium-hero-btn:hover i {
        transform: translateX(6px);
    }

    .premium-hero-image {
        position: relative;
        z-index: 10;
        max-height: 280px;
        filter: drop-shadow(0 20px 30px rgba(0,0,0,0.2));
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }

    /* ============================================
           PREMIUM STATS CARDS - Royal Blue Theme
        ============================================ */
    .premium-stats-section {
        margin-bottom: 50px;
    }

    .premium-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid rgba(0,39,137,0.1);
    }

    .premium-section-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #002789;
        margin: 0;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .premium-section-title i {
        color: var(--ju-gold);
    }

    .premium-section-subtitle {
        color: var(--ju-gray-600);
        font-size: 1rem;
        margin: 4px 0 0;
    }

    .premium-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }

    @media (max-width: 1200px) {
        .premium-stats-grid { grid-template-columns: repeat(2, 1fr); }
        .premium-hero-title { font-size: 2.5rem; }
    }

    @media (max-width: 768px) {
        .premium-stats-grid { grid-template-columns: 1fr; }
        .premium-hero-card { padding: 40px 24px; }
        .premium-hero-title { font-size: 2rem; }
        .premium-hero-subtitle { font-size: 1.1rem; }
    }

    .premium-stat-card {
        background: white;
        border-radius: var(--radius-xl);
        padding: 24px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--ju-gray-200);
        transition: var(--transition-bounce);
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .premium-stat-card::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #002789, var(--ju-gold));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.5s ease;
    }

    .premium-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow);
        border-color: rgba(0,39,137,0.2);
    }

    .premium-stat-card:hover::before {
        transform: scaleX(1);
    }

    .premium-stat-icon {
        width: 64px;
        height: 64px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: white;
        flex-shrink: 0;
        transition: var(--transition-bounce);
    }

    .premium-stat-card:hover .premium-stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .premium-stat-icon.primary { 
        background: linear-gradient(145deg, #002789, #1a3a9a); 
    }
    .premium-stat-icon.success { 
        background: linear-gradient(145deg, #28a745, #1e7e34); 
    }
    .premium-stat-icon.warning { 
        background: linear-gradient(145deg, #ffc107, #d39e00); 
    }
    .premium-stat-icon.info { 
        background: linear-gradient(145deg, #1a3a9a, #3a6ab0); 
    }

    .premium-stat-content {
        flex: 1;
    }

    .premium-stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        color: #002789;
        margin: 0;
        line-height: 1;
        font-family: 'Montserrat', sans-serif;
    }

    .premium-stat-label {
        color: var(--ju-gray-600);
        margin: 4px 0 0;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .premium-stat-trend {
        position: absolute;
        top: 16px;
        right: 16px;
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--ju-gold-dark);
        background: rgba(196,167,71,0.1);
        padding: 4px 12px;
        border-radius: var(--radius-full);
        border: 1px solid rgba(196,167,71,0.3);
    }

    /* ============================================
           PREMIUM FILTER SECTION - Fixed Layout
        ============================================ */
    .premium-filter-section {
        margin-bottom: 50px;
    }

    .premium-filter-card {
        background: white;
        border-radius: var(--radius-xl);
        padding: 32px;
        box-shadow: var(--shadow);
        border: 1px solid var(--ju-gray-200);
        position: relative;
    }

    .premium-filter-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #002789, var(--ju-gold), #002789);
        border-radius: var(--radius-xl) var(--radius-xl) 0 0;
    }

    .premium-filter-grid {
        display: grid;
        grid-template-columns: 1fr 0.8fr 0.8fr 0.6fr;
        gap: 20px;
        align-items: end;
    }

    @media (max-width: 1200px) {
        .premium-filter-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .premium-filter-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }

    .premium-filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .premium-filter-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--ju-gray-700);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .premium-filter-label i {
        color: var(--ju-gold);
    }

    .premium-input-wrapper {
        position: relative;
        width: 100%;
    }

    .premium-input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ju-gray-400);
        font-size: 1rem;
        z-index: 10;
        transition: var(--transition);
    }

    .premium-input {
        width: 100%;
        padding: 14px 16px 14px 48px;
        border: 2px solid var(--ju-gray-200);
        border-radius: var(--radius-lg);
        font-size: 0.95rem;
        background: var(--ju-white);
        color: var(--ju-gray-800);
        transition: var(--transition);
        height: 52px;
    }

    .premium-input:focus {
        outline: none;
        border-color: #002789;
        box-shadow: 0 0 0 4px rgba(0,39,137,0.1);
    }

    .premium-input:focus + .premium-input-icon {
        color: #002789;
        transform: translateY(-50%) scale(1.1);
    }

    .premium-select {
        width: 100%;
        padding: 14px 20px;
        border: 2px solid var(--ju-gray-200);
        border-radius: var(--radius-lg);
        font-size: 0.95rem;
        background: white;
        color: var(--ju-gray-800);
        transition: var(--transition);
        height: 52px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23002789' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
    }

    .premium-select:focus {
        outline: none;
        border-color: #002789;
        box-shadow: 0 0 0 4px rgba(0,39,137,0.1);
    }

    .premium-filter-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .premium-filter-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px 24px;
        border-radius: var(--radius-lg);
        font-weight: 700;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: var(--transition-bounce);
        height: 52px;
        white-space: nowrap;
        text-decoration: none;
    }

    .premium-filter-apply {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        box-shadow: 0 8px 16px rgba(0,39,137,0.2);
        flex: 1;
    }

    .premium-filter-apply:hover {
        background: #001a5c;
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(0,39,137,0.3);
    }

    .premium-filter-reset {
        background: white;
        color: var(--ju-gray-700);
        border: 2px solid var(--ju-gray-200);
        width: 52px;
        padding: 14px;
    }

    .premium-filter-reset:hover {
        border-color: #002789;
        color: #002789;
        transform: rotate(180deg);
    }

    /* Active Filters */
    .premium-active-filters {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid var(--ju-gray-200);
    }

    .premium-filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: rgba(0,39,137,0.08);
        border-radius: var(--radius-full);
        font-size: 0.85rem;
        color: #002789;
        font-weight: 600;
    }

    .premium-filter-tag i {
        color: var(--ju-gold);
    }

    .premium-filter-tag .remove-filter {
        color: var(--ju-gray-500);
        text-decoration: none;
        font-size: 1.2rem;
        line-height: 1;
        margin-left: 4px;
        transition: var(--transition);
    }

    .premium-filter-tag .remove-filter:hover {
        color: var(--ju-danger);
        transform: scale(1.2);
    }

    .premium-clear-all {
        color: var(--ju-gray-600);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        margin-left: auto;
        transition: var(--transition);
    }

    .premium-clear-all:hover {
        color: var(--ju-danger);
    }

    /* ============================================
           PREMIUM EVENTS GRID - 3 Cards Per Row
           ROYAL BLUE THEME - PERFECT FIT
        ============================================ */
    .premium-events-section {
        margin-bottom: 50px;
    }

    .premium-events-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid rgba(0,39,137,0.1);
        flex-wrap: wrap;
        gap: 16px;
    }

    .premium-header-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .premium-header-icon {
        width: 56px;
        height: 56px;
        background: rgba(0,39,137,0.08);
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #002789;
        border: 1px solid rgba(0,39,137,0.15);
    }

    .premium-header-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #002789;
        margin: 0;
        letter-spacing: -1px;
    }

    .premium-header-subtitle {
        color: var(--ju-gray-600);
        margin: 4px 0 0;
        font-size: 1rem;
    }

    .premium-header-subtitle strong {
        color: #002789;
    }

    .premium-header-subtitle span {
        color: var(--ju-gold);
    }

    .premium-my-events-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 28px;
        background: transparent;
        color: #002789;
        border: 2px solid #002789;
        border-radius: var(--radius-full);
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        transition: var(--transition-bounce);
    }

    .premium-my-events-btn:hover {
        background: #002789;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,39,137,0.2);
    }

    .premium-my-events-btn i {
        transition: transform var(--transition-elastic);
    }

    .premium-my-events-btn:hover i {
        transform: translateX(6px);
    }

    /* PREMIUM EVENTS GRID - 3 CARDS PER ROW */
    .premium-events-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
        margin-bottom: 40px;
    }

    @media (max-width: 1200px) {
        .premium-events-grid { grid-template-columns: repeat(2, 1fr); }
        .premium-header-title { font-size: 1.6rem; }
    }

    @media (max-width: 768px) {
        .premium-events-grid { grid-template-columns: 1fr; }
        .premium-events-header { flex-direction: column; align-items: flex-start; }
        .premium-my-events-btn { width: 100%; justify-content: center; }
        .premium-header-title { font-size: 1.4rem; }
    }

    /* PREMIUM EVENT CARD - PERFECT FIT */
    .premium-event-card {
        background: white;
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--ju-gray-200);
        transition: var(--transition-bounce);
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 100%;
        position: relative;
    }

    .premium-event-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow);
        border-color: #002789;
    }

    /* CARD HEADER - FIXED HEIGHT */
    .premium-card-header {
        position: relative;
        height: 200px;
        width: 100%;
        overflow: hidden;
        flex-shrink: 0;
    }

    .premium-event-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .premium-event-card:hover .premium-event-image {
        transform: scale(1.08);
    }

    .premium-image-fallback {
        width: 100%;
        height: 100%;
        background: linear-gradient(145deg, #002789, #001a5c);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .premium-image-fallback i {
        font-size: 3.5rem;
        color: white;
        opacity: 0.9;
        filter: drop-shadow(0 8px 16px rgba(0,0,0,0.2));
        animation: iconFloat 3s ease infinite;
    }

    @keyframes iconFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    /* CARD BADGES - GOLD ACCENTS */
    .premium-card-badges {
        position: absolute;
        top: 16px;
        left: 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        z-index: 20;
        max-width: calc(100% - 32px);
    }

    .premium-badge {
        padding: 6px 16px;
        border-radius: var(--radius-full);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        width: fit-content;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .premium-badge-featured {
        background: linear-gradient(145deg, #C4A747, #a5862e);
        color: white;
    }

    .premium-badge-registered {
        background: linear-gradient(145deg, #28a745, #1e7e34);
        color: white;
    }

    .premium-badge-full {
        background: linear-gradient(145deg, #dc3545, #bd2130);
        color: white;
    }

    .premium-category-badge {
        position: absolute;
        bottom: 16px;
        right: 16px;
        background: rgba(255,255,255,0.98);
        backdrop-filter: blur(10px);
        padding: 8px 18px;
        border-radius: var(--radius-full);
        font-size: 0.75rem;
        font-weight: 700;
        color: #002789;
        border: 1px solid rgba(255,255,255,0.8);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        z-index: 20;
    }

    .premium-category-badge i {
        color: var(--ju-gold);
    }

    /* CARD BODY - PERFECT PADDING */
    .premium-card-body {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .premium-event-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--ju-gray-800);
        line-height: 1.4;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 56px;
    }

    .premium-event-title a {
        color: inherit;
        text-decoration: none;
        transition: var(--transition);
    }

    .premium-event-title a:hover {
        color: #002789;
    }

    .premium-event-description {
        color: var(--ju-gray-600);
        font-size: 0.9rem;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin: 0;
        min-height: 44px;
    }

    /* EVENT META - 2x2 GRID */
    .premium-event-meta {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin: 8px 0 0;
    }

    .premium-meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: var(--ju-gray-50);
        border-radius: var(--radius);
        border: 1px solid var(--ju-gray-200);
        transition: var(--transition);
        min-width: 0;
    }

    .premium-meta-icon {
        width: 36px;
        height: 36px;
        background: white;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #002789;
        font-size: 0.9rem;
        border: 1px solid var(--ju-gray-200);
        flex-shrink: 0;
        transition: var(--transition-bounce);
    }

    .premium-event-card:hover .premium-meta-icon {
        background: #002789;
        color: white;
        border-color: #002789;
        transform: scale(1.1);
    }

    .premium-meta-content {
        flex: 1;
        min-width: 0;
    }

    .premium-meta-label {
        font-size: 0.6rem;
        font-weight: 700;
        color: var(--ju-gray-500);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 2px;
    }

    .premium-meta-value {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--ju-gray-800);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* SEAT AVAILABILITY */
    .premium-availability {
        margin: 16px 0 8px;
    }

    .premium-availability-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .premium-availability-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--ju-gray-700);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .premium-availability-count {
        font-size: 0.8rem;
        font-weight: 700;
    }

    .premium-available-seats {
        color: var(--ju-success);
        font-size: 1rem;
        font-weight: 800;
    }

    .premium-progress {
        position: relative;
    }

    .premium-progress-bar-container {
        width: 100%;
        height: 8px;
        background: var(--ju-gray-200);
        border-radius: var(--radius-full);
        overflow: hidden;
    }

    .premium-progress-bar {
        height: 100%;
        border-radius: var(--radius-full);
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .premium-progress-bar.success { background: linear-gradient(90deg, #28a745, #34ce57); }
    .premium-progress-bar.warning { background: linear-gradient(90deg, #ffc107, #ffdb6e); }
    .premium-progress-bar.danger { background: linear-gradient(90deg, #dc3545, #e4606d); }

    .premium-progress-tooltip {
        position: absolute;
        right: 0;
        top: -25px;
        background: #002789;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 700;
        opacity: 0;
        transition: opacity var(--transition);
    }

    .premium-progress-bar-container:hover .premium-progress-tooltip {
        opacity: 1;
    }

    /* CARD FOOTER */
    .premium-card-footer {
        padding: 20px 24px;
        border-top: 1px solid var(--ju-gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        background: white;
        flex-shrink: 0;
    }

    .premium-attendance {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        flex: 1;
    }

    .premium-attendance-icon {
        width: 44px;
        height: 44px;
        background: rgba(0,39,137,0.08);
        border-radius: var(--radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #002789;
        font-size: 1rem;
        border: 2px solid white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        flex-shrink: 0;
        transition: var(--transition-bounce);
    }

    .premium-event-card:hover .premium-attendance-icon {
        background: var(--ju-gold);
        color: #001a5c;
        transform: rotate(360deg);
    }

    .premium-attendance-text {
        min-width: 0;
        flex: 1;
    }

    .premium-attendance-count {
        font-size: 1.1rem;
        font-weight: 800;
        color: #002789;
        line-height: 1;
    }

    .premium-attendance-label {
        font-size: 0.7rem;
        color: var(--ju-gray-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .premium-action-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .premium-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: var(--radius-lg);
        font-weight: 700;
        font-size: 0.85rem;
        text-decoration: none;
        transition: var(--transition-bounce);
        border: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .premium-btn-sm {
        padding: 8px 16px;
        font-size: 0.8rem;
    }

    .premium-btn-primary {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        box-shadow: 0 8px 16px rgba(0,39,137,0.2);
    }

    .premium-btn-primary:hover {
        background: #001a5c;
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(0,39,137,0.3);
    }

    .premium-btn-success {
        background: linear-gradient(145deg, #28a745, #1e7e34);
        color: white;
        box-shadow: 0 8px 16px rgba(40,167,69,0.2);
    }

    .premium-btn-success:hover {
        background: #1e7e34;
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(40,167,69,0.3);
    }

    .premium-btn-warning {
        background: linear-gradient(145deg, #ffc107, #d39e00);
        color: #001a5c;
        box-shadow: 0 8px 16px rgba(255,193,7,0.2);
    }

    .premium-btn-warning:hover {
        background: #d39e00;
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(255,193,7,0.3);
        color: #001a5c;
    }

    .premium-btn-outline {
        background: transparent;
        color: #002789;
        border: 2px solid #002789;
        box-shadow: none;
    }

    .premium-btn-outline:hover {
        background: #002789;
        color: white;
        transform: translateY(-2px);
    }

    .premium-btn-icon {
        width: 44px;
        height: 44px;
        padding: 0;
        background: white;
        color: var(--ju-gray-700);
        border: 2px solid var(--ju-gray-200);
    }

    .premium-btn-icon:hover {
        background: #002789;
        color: white;
        border-color: #002789;
        transform: translateY(-2px) rotate(360deg);
    }

    /* ============================================
           PREMIUM EMPTY STATE - Royal Blue
        ============================================ */
    .premium-empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 40px;
        background: white;
        border-radius: var(--radius-xl);
        border: 2px dashed rgba(0,39,137,0.2);
        box-shadow: var(--shadow-sm);
    }

    .premium-empty-icon {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 24px;
    }

    .empty-circle {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(0,39,137,0.08);
        animation: pulseCircle 2s infinite;
    }

    .premium-empty-icon i {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 3rem;
        color: #002789;
        z-index: 10;
    }

    @keyframes pulseCircle {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.1); opacity: 0.6; }
    }

    .premium-empty-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #002789;
        margin-bottom: 16px;
    }

    .premium-empty-message {
        color: var(--ju-gray-600);
        font-size: 1.1rem;
        max-width: 500px;
        margin: 0 auto 32px;
    }

    .premium-empty-btn {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 14px 32px;
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        border-radius: var(--radius-full);
        font-weight: 700;
        text-decoration: none;
        transition: var(--transition-bounce);
        box-shadow: 0 8px 20px rgba(0,39,137,0.25);
    }

    .premium-empty-btn:hover {
        background: #001a5c;
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(0,39,137,0.35);
    }

    /* ============================================
           PREMIUM PAGINATION - Royal Blue
        ============================================ */
    .premium-pagination-wrapper {
        margin-top: 40px;
    }

    .premium-pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .premium-pagination .page-link {
        padding: 12px 20px;
        border: 2px solid var(--ju-gray-200);
        border-radius: var(--radius);
        color: var(--ju-gray-700);
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        background: white;
        transition: var(--transition);
    }

    .premium-pagination .page-item.active .page-link {
        background: #002789;
        color: white;
        border-color: #002789;
        box-shadow: 0 8px 16px rgba(0,39,137,0.2);
    }

    .premium-pagination .page-link:hover:not(.active) {
        background: rgba(0,39,137,0.08);
        border-color: #002789;
        color: #002789;
        transform: translateY(-2px);
    }

    /* ============================================
           PREMIUM HOW IT WORKS - Royal Blue
        ============================================ */
    .premium-how-it-works {
        margin-top: 60px;
        margin-bottom: 40px;
    }

    .premium-steps-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin: 40px 0;
    }

    @media (max-width: 1200px) {
        .premium-steps-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .premium-steps-grid { grid-template-columns: 1fr; }
    }

    .premium-step-card {
        background: white;
        border-radius: var(--radius-xl);
        padding: 32px 24px;
        text-align: center;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--ju-gray-200);
        transition: var(--transition-bounce);
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .premium-step-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow);
        border-color: rgba(0,39,137,0.2);
    }

    .premium-step-number {
        position: absolute;
        top: 16px;
        right: 24px;
        font-size: 3rem;
        font-weight: 900;
        color: var(--ju-gray-200);
        transition: var(--transition);
    }

    .premium-step-card:hover .premium-step-number {
        color: rgba(196,167,71,0.2);
        transform: scale(1.2);
    }

    .premium-step-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(145deg, #002789, #1a3a9a);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 2rem;
        box-shadow: 0 10px 20px rgba(0,39,137,0.2);
        transition: var(--transition-bounce);
    }

    .premium-step-card:hover .premium-step-icon {
        transform: scale(1.1) rotate(5deg);
        background: var(--ju-gold-gradient);
    }

    .premium-step-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #002789;
        margin-bottom: 12px;
    }

    .premium-step-description {
        color: var(--ju-gray-600);
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0;
    }

    .premium-cta-card {
        background: linear-gradient(145deg, white, var(--ju-gray-50));
        border-radius: var(--radius-2xl);
        padding: 48px;
        box-shadow: var(--shadow);
        border: 1px solid var(--ju-gray-200);
        position: relative;
        overflow: hidden;
    }

    .premium-cta-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #002789, var(--ju-gold));
    }

    .premium-cta-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #002789;
        margin-bottom: 8px;
    }

    .premium-cta-text {
        color: var(--ju-gray-600);
        font-size: 1.1rem;
        margin-bottom: 0;
    }

    .premium-cta-buttons {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
    }

    .premium-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 16px 32px;
        border-radius: var(--radius-full);
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        transition: var(--transition-bounce);
    }

    .premium-cta-btn-primary {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        box-shadow: 0 10px 25px rgba(0,39,137,0.2);
    }

    .premium-cta-btn-primary:hover {
        background: #001a5c;
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0,39,137,0.3);
    }

    .premium-cta-btn-outline {
        background: transparent;
        color: #002789;
        border: 2px solid #002789;
    }

    .premium-cta-btn-outline:hover {
        background: #002789;
        color: white;
        transform: translateY(-3px);
    }

    @media (max-width: 992px) {
        .premium-cta-card .row {
            flex-direction: column;
            text-align: center;
            gap: 24px;
        }
        .premium-cta-buttons {
            justify-content: center;
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

    .hover-rotate-icon i {
        transition: transform var(--transition-elastic);
    }
    .hover-rotate-icon:hover i {
        transform: rotate(360deg);
    }

    .hover-scale {
        transition: transform var(--transition-bounce);
    }
    .hover-scale:hover {
        transform: scale(1.05);
    }

    /* ============================================
           ROYAL BLUE UTILITY CLASSES
        ============================================ */
    .bg-ju-blue {
        background-color: #002789 !important;
    }
    .bg-ju-blue-dark {
        background-color: #001a5c !important;
    }
    .bg-ju-gold {
        background-color: #C4A747 !important;
    }
    .text-ju-blue {
        color: #002789 !important;
    }
    .text-ju-gold {
        color: #C4A747 !important;
    }
    .border-ju-blue {
        border-color: #002789 !important;
    }
    .border-ju-gold {
        border-color: #C4A747 !important;
    }
</style>

<div class="container-fluid px-lg-5">
    <!-- ============================================
           PREMIUM HERO SECTION - Royal Blue Excellence
        ============================================ -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="premium-hero-card" data-aos="fade-up" data-aos-duration="1000">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="premium-hero-badge">
                            <i class="fas fa-star"></i>
                            <span>Jimma University Events Portal</span>
                            <i class="fas fa-star"></i>
                        </div>
                        <h1 class="premium-hero-title">
                            Discover & Register for <br><span class="gold-text">University Events</span>
                        </h1>
                        <p class="premium-hero-subtitle">
                            Join academic, cultural, sports, and social events across Jimma University campuses. 
                            Connect with peers, learn new skills, and enhance your university experience.
                        </p>
                        <div class="premium-hero-buttons">
                            <a href="#events" class="premium-hero-btn premium-hero-btn-primary hover-gold-shine">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Browse Events</span>
                            </a>
                            @guest
                            <a href="{{ route('register') }}" class="premium-hero-btn premium-hero-btn-outline hover-scale">
                                <i class="fas fa-user-plus"></i>
                                <span>Create Account</span>
                            </a>
                            @endguest
                        </div>
                    </div>
                    <div class="col-lg-4 text-center d-none d-lg-block">
                        <img src="{{ asset('images/event-illustration.svg') }}" alt="Events" class="premium-hero-image img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================
           PREMIUM STATISTICS SECTION - Royal Blue Theme
        ============================================ -->
    @auth
    <div class="premium-stats-section" data-aos="fade-up" data-aos-duration="800">
        <div class="premium-section-header">
            <div>
                <h3 class="premium-section-title">
                    <i class="fas fa-chart-pie"></i>
                    Your Event Dashboard
                </h3>
                <p class="premium-section-subtitle">Track your participation and registrations</p>
            </div>
            <div class="premium-stat-trend" style="position: relative; top: 0; right: 0;">
                <i class="fas fa-chart-line"></i> Last 30 days
            </div>
        </div>
        
        <div class="premium-stats-grid">
            <div class="premium-stat-card" data-aos="zoom-in" data-aos-delay="100">
                <div class="premium-stat-icon primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="premium-stat-content">
                    <h4 class="premium-stat-number">{{ auth()->user()->registrations()->confirmed()->count() }}</h4>
                    <p class="premium-stat-label">Confirmed Events</p>
                </div>
            </div>
            
            <div class="premium-stat-card" data-aos="zoom-in" data-aos-delay="200">
                <div class="premium-stat-icon success">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="premium-stat-content">
                    <h4 class="premium-stat-number">{{ auth()->user()->waitlists()->active()->count() }}</h4>
                    <p class="premium-stat-label">Waitlist Positions</p>
                </div>
            </div>
            
            <div class="premium-stat-card" data-aos="zoom-in" data-aos-delay="300">
                <div class="premium-stat-icon warning">
                    <i class="fas fa-history"></i>
                </div>
                <div class="premium-stat-content">
                    <h4 class="premium-stat-number">{{ auth()->user()->registrations()->attended()->count() }}</h4>
                    <p class="premium-stat-label">Events Attended</p>
                </div>
            </div>
            
            <div class="premium-stat-card" data-aos="zoom-in" data-aos-delay="400">
                <div class="premium-stat-icon info">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="premium-stat-content">
                    <h4 class="premium-stat-number">{{ $events->total() }}</h4>
                    <p class="premium-stat-label">Available Events</p>
                </div>
            </div>
        </div>
    </div>
    @endauth

    <!-- ============================================
           PREMIUM FILTER SECTION - Fixed Layout
        ============================================ -->
    <div class="premium-filter-section" id="events" data-aos="fade-up" data-aos-duration="800">
        <div class="premium-section-header">
            <div>
                <h3 class="premium-section-title">
                    <i class="fas fa-filter"></i>
                    Find Your Perfect Event
                </h3>
                <p class="premium-section-subtitle">Filter by type, campus, or search for specific events</p>
            </div>
        </div>
        
        <div class="premium-filter-card">
            <form method="GET" action="{{ route('event-registration.index') }}">
                <div class="premium-filter-grid">
                    <div class="premium-filter-group">
                        <label class="premium-filter-label">
                            <i class="fas fa-search"></i>
                            Search Events
                        </label>
                        <div class="premium-input-wrapper">
                            <i class="fas fa-search premium-input-icon"></i>
                            <input type="text" class="premium-input" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Search by title, description...">
                        </div>
                    </div>
                    
                    <div class="premium-filter-group">
                        <label class="premium-filter-label">
                            <i class="fas fa-tag"></i>
                            Event Type
                        </label>
                        <select class="premium-select" id="event_type" name="event_type">
                            <option value="">All Types</option>
                            <option value="academic" {{ request('event_type') == 'academic' ? 'selected' : '' }}>📚 Academic</option>
                            <option value="cultural" {{ request('event_type') == 'cultural' ? 'selected' : '' }}>🎭 Cultural</option>
                            <option value="sports" {{ request('event_type') == 'sports' ? 'selected' : '' }}>⚽ Sports</option>
                            <option value="conference" {{ request('event_type') == 'conference' ? 'selected' : '' }}>🎤 Conference</option>
                            <option value="workshop" {{ request('event_type') == 'workshop' ? 'selected' : '' }}>🛠️ Workshop</option>
                        </select>
                    </div>
                    
                    <div class="premium-filter-group">
                        <label class="premium-filter-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Campus
                        </label>
                        <select class="premium-select" id="campus" name="campus">
                            <option value="">All Campuses</option>
                            @foreach(\App\Models\Campus::active()->get() as $campus)
                            <option value="{{ $campus->name }}" {{ request('campus') == $campus->name ? 'selected' : '' }}>
                                🏛️ {{ $campus->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="premium-filter-actions">
                        <button type="submit" class="premium-filter-btn premium-filter-apply hover-gold-shine">
                            <i class="fas fa-filter"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('event-registration.index') }}" class="premium-filter-btn premium-filter-reset hover-rotate-icon">
                            <i class="fas fa-redo-alt"></i>
                        </a>
                    </div>
                </div>
            </form>
            
            <!-- Active Filters -->
            @if(request()->hasAny(['search', 'event_type', 'campus']))
            <div class="premium-active-filters">
                <span class="premium-filter-label" style="text-transform: none; color: var(--ju-gray-600);">
                    <i class="fas fa-filter"></i> Active Filters:
                </span>
                @if(request('search'))
                <span class="premium-filter-tag">
                    <i class="fas fa-search"></i> "{{ request('search') }}"
                    <a href="{{ route('event-registration.index', array_merge(request()->except('search', 'page'))) }}" class="remove-filter">×</a>
                </span>
                @endif
                @if(request('event_type'))
                <span class="premium-filter-tag">
                    <i class="fas fa-tag"></i> {{ ucfirst(request('event_type')) }}
                    <a href="{{ route('event-registration.index', array_merge(request()->except('event_type', 'page'))) }}" class="remove-filter">×</a>
                </span>
                @endif
                @if(request('campus'))
                <span class="premium-filter-tag">
                    <i class="fas fa-map-marker-alt"></i> {{ request('campus') }}
                    <a href="{{ route('event-registration.index', array_merge(request()->except('campus', 'page'))) }}" class="remove-filter">×</a>
                </span>
                @endif
                <a href="{{ route('event-registration.index') }}" class="premium-clear-all">
                    Clear All <i class="fas fa-times-circle ms-1"></i>
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- ============================================
           PREMIUM EVENTS GRID - 3 Cards Per Row
           ROYAL BLUE THEME - PERFECT FIT
        ============================================ -->
    <div class="premium-events-section">
        <div class="premium-events-header">
            <div class="premium-header-left">
                <div class="premium-header-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <h3 class="premium-header-title">Upcoming Events</h3>
                    <p class="premium-header-subtitle">
                        <strong>{{ $events->total() }}</strong> events available for registration
                        @if(request()->hasAny(['search', 'event_type', 'campus']))
                            <span>• Filtered results</span>
                        @endif
                    </p>
                </div>
            </div>
            @auth
            <a href="{{ route('my-events.index') }}" class="premium-my-events-btn hover-gold-shine">
                <i class="fas fa-calendar-check"></i>
                <span>My Events</span>
            </a>
            @endauth
        </div>
        
        @if($events->count() > 0)
        <div class="premium-events-grid">
            @foreach($events as $index => $event)
            <div class="premium-event-card" 
                 data-aos="fade-up" 
                 data-aos-delay="{{ ($index % 3) * 100 }}"
                 data-aos-duration="700">
                
                <!-- Card Header with Image -->
                <div class="premium-card-header">
                    @if($event->image)
                        <img src="{{ $event->image_url }}" class="premium-event-image" alt="{{ $event->title }}">
                    @else
                        <div class="premium-image-fallback">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    @endif
                    
                    <!-- Badges -->
                    <div class="premium-card-badges">
                        @if($event->is_featured)
                        <span class="premium-badge premium-badge-featured">
                            <i class="fas fa-star"></i> Featured
                        </span>
                        @endif
                        @if(in_array($event->id, $registeredEventIds))
                        <span class="premium-badge premium-badge-registered">
                            <i class="fas fa-check-circle"></i> Registered
                        </span>
                        @endif
                        @if($event->is_full)
                        <span class="premium-badge premium-badge-full">
                            <i class="fas fa-clock"></i> Full
                        </span>
                        @endif
                    </div>
                    
                    <!-- Category Badge -->
                    <div class="premium-category-badge">
                        @switch($event->event_type)
                            @case('academic') <i class="fas fa-graduation-cap"></i> @break
                            @case('cultural') <i class="fas fa-music"></i> @break
                            @case('sports') <i class="fas fa-futbol"></i> @break
                            @case('conference') <i class="fas fa-microphone"></i> @break
                            @case('workshop') <i class="fas fa-tools"></i> @break
                            @default <i class="fas fa-tag"></i>
                        @endswitch
                        {{ ucfirst($event->event_type) }}
                    </div>
                </div>
                
                <!-- Card Body - Perfect Padding -->
                <div class="premium-card-body">
                    <h5 class="premium-event-title">
                        <a href="{{ route('events.guest.show', $event) }}">
                            {{ Str::limit($event->title, 60) }}
                        </a>
                    </h5>
                    
                    <p class="premium-event-description">
                        {{ Str::limit(strip_tags($event->description), 100) }}
                    </p>
                    
                    <!-- Event Meta - 2x2 Grid -->
                    <div class="premium-event-meta">
                        <div class="premium-meta-item">
                            <div class="premium-meta-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="premium-meta-content">
                                <div class="premium-meta-label">Date</div>
                                <div class="premium-meta-value">{{ $event->start_date->format('M d, Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="premium-meta-item">
                            <div class="premium-meta-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="premium-meta-content">
                                <div class="premium-meta-label">Time</div>
                                <div class="premium-meta-value">{{ $event->start_date->format('h:i A') }}</div>
                            </div>
                        </div>
                        
                        <div class="premium-meta-item">
                            <div class="premium-meta-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="premium-meta-content">
                                <div class="premium-meta-label">Campus</div>
                                <div class="premium-meta-value">{{ Str::limit($event->campus ?? 'Main Campus', 15) }}</div>
                            </div>
                        </div>
                        
                        <div class="premium-meta-item">
                            <div class="premium-meta-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="premium-meta-content">
                                <div class="premium-meta-label">Capacity</div>
                                <div class="premium-meta-value">{{ $event->max_attendees ?? 'Unlimited' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Seat Availability -->
                    @if($event->max_attendees)
                    <div class="premium-availability">
                        <div class="premium-availability-header">
                            <span class="premium-availability-label">Seat Availability</span>
                            <span class="premium-availability-count">
                                @if($event->is_full)
                                    <span style="color: var(--ju-danger);">Full</span>
                                @else
                                    <span class="premium-available-seats">{{ $event->max_attendees - $event->registered_count }}</span> / {{ $event->max_attendees }} left
                                @endif
                            </span>
                        </div>
                        <div class="premium-progress">
                            <div class="premium-progress-bar-container">
                                @php
                                    $percentage = ($event->registered_count / $event->max_attendees) * 100;
                                    $progressClass = $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : 'success');
                                @endphp
                                <div class="premium-progress-bar {{ $progressClass }}" style="width: {{ $percentage }}%">
                                    <span class="premium-progress-tooltip">{{ round($percentage) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Card Footer -->
                <div class="premium-card-footer">
                    <div class="premium-attendance">
                        <div class="premium-attendance-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="premium-attendance-text">
                            <span class="premium-attendance-count">{{ $event->registered_count ?? 0 }}</span>
                            <span class="premium-attendance-label">Attending</span>
                        </div>
                    </div>
                    
                    <div class="premium-action-buttons">
                        @if(auth()->check())
                            @if(in_array($event->id, $registeredEventIds))
                            <a href="{{ route('my-events.index') }}" class="premium-btn premium-btn-success premium-btn-sm">
                                <i class="fas fa-check"></i> Registered
                            </a>
                            @elseif($event->is_full)
                            <a href="{{ route('event-registration.create', $event) }}" class="premium-btn premium-btn-warning premium-btn-sm">
                                <i class="fas fa-clock"></i> Waitlist
                            </a>
                            @else
                            <a href="{{ route('event-registration.create', $event) }}" class="premium-btn premium-btn-primary premium-btn-sm">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                            @endif
                        @else
                        <a href="{{ route('login') }}" class="premium-btn premium-btn-outline premium-btn-sm">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        @endif
                        <a href="{{ route('events.guest.show', $event) }}" class="premium-btn premium-btn-icon premium-btn-sm hover-rotate-icon" title="View Details">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($events->hasPages())
        <div class="premium-pagination-wrapper" data-aos="fade-up" data-aos-duration="600">
            <nav aria-label="Events pagination">
                <ul class="pagination premium-pagination">
                    {{ $events->withQueryString()->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                </ul>
            </nav>
        </div>
        @endif
        
        @else
        <!-- Premium Empty State -->
        <div class="premium-empty-state" data-aos="zoom-in" data-aos-duration="600">
            <div class="premium-empty-icon">
                <div class="empty-circle"></div>
                <i class="fas fa-calendar-times"></i>
            </div>
            <h3 class="premium-empty-title">No Events Available</h3>
            <p class="premium-empty-message">
                @if(request()->hasAny(['search', 'event_type', 'campus']))
                    No events match your current filters. Try adjusting your search criteria.
                @else
                    Check back later for upcoming events or try different filters.
                @endif
            </p>
            @guest
            <a href="{{ route('login') }}" class="premium-empty-btn">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login to View More</span>
            </a>
            @endguest
        </div>
        @endif
    </div>

    <!-- ============================================
           PREMIUM HOW IT WORKS - Royal Blue Theme
        ============================================ -->
    @guest
    <div class="premium-how-it-works" data-aos="fade-up" data-aos-duration="800">
        <div class="premium-section-header text-center" style="border-bottom: none; padding-bottom: 0; margin-bottom: 20px; justify-content: center;">
            <div>
                <h3 class="premium-section-title" style="justify-content: center;">
                    <i class="fas fa-rocket"></i>
                    How to Register for Events
                </h3>
                <p class="premium-section-subtitle">Simple steps to join university events</p>
            </div>
        </div>
        
        <div class="premium-steps-grid">
            <div class="premium-step-card" data-aos="fade-up" data-aos-delay="100">
                <div class="premium-step-number">1</div>
                <div class="premium-step-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h5 class="premium-step-title">Create Account</h5>
                <p class="premium-step-description">Register with your Jimma University credentials for instant access</p>
            </div>
            
            <div class="premium-step-card" data-aos="fade-up" data-aos-delay="200">
                <div class="premium-step-number">2</div>
                <div class="premium-step-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h5 class="premium-step-title">Browse Events</h5>
                <p class="premium-step-description">Find events by type, campus, or date with smart filters</p>
            </div>
            
            <div class="premium-step-card" data-aos="fade-up" data-aos-delay="300">
                <div class="premium-step-number">3</div>
                <div class="premium-step-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h5 class="premium-step-title">Register</h5>
                <p class="premium-step-description">Click register and confirm your details in seconds</p>
            </div>
            
            <div class="premium-step-card" data-aos="fade-up" data-aos-delay="400">
                <div class="premium-step-number">4</div>
                <div class="premium-step-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h5 class="premium-step-title">Get Confirmation</h5>
                <p class="premium-step-description">Receive registration details and event updates instantly</p>
            </div>
        </div>
        
        <div class="premium-cta-card" data-aos="zoom-in" data-aos-duration="800">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h4 class="premium-cta-title">Ready to Join University Events?</h4>
                    <p class="premium-cta-text">Create your account today and start participating in world-class events.</p>
                </div>
                <div class="col-lg-4">
                    <div class="premium-cta-buttons">
                        <a href="{{ route('register') }}" class="premium-cta-btn premium-cta-btn-primary hover-gold-shine">
                            <i class="fas fa-user-plus"></i>
                            <span>Create Account</span>
                        </a>
                        <a href="{{ route('login') }}" class="premium-cta-btn premium-cta-btn-outline hover-scale">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endguest
</div>

<!-- AOS Animation Script -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 50,
            easing: 'ease-in-out-cubic'
        });

        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(event) {
            if (this.hash !== "") {
                event.preventDefault();
                const hash = this.hash;
                $('html, body').animate({
                    scrollTop: $(hash).offset().top - 100
                }, 800, 'easeInOutCubic');
            }
        });
        
        // Animate cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = "1";
                    entry.target.style.transform = "translateY(0)";
                }
            });
        }, observerOptions);
        
        // Observe all event cards
        document.querySelectorAll('.premium-event-card').forEach(card => {
            card.style.opacity = "0";
            card.style.transform = "translateY(30px)";
            card.style.transition = "opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), transform 0.8s cubic-bezier(0.4, 0, 0.2, 1)";
            observer.observe(card);
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
