@extends('layouts.app')

@section('title', 'Notifications - Jimma University')
@section('page-title', 'Notifications')
@section('page-subtitle', 'Your Notifications & Alerts')

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
            <i class="fas fa-home me-2" style="color: var(--ju-gold);"></i>Home
        </a>
    </li>
    <li class="breadcrumb-item active">
        <span class="fw-semibold" style="color: var(--ju-blue-dark);">Notifications</span>
    </li>
@endsection

@section('content')
<style>
    /* ============================================
           PREMIUM JIMMA UNIVERSITY NOTIFICATIONS
           ROYAL BLUE (#002789) & GOLD (#C4A747) - OFFICIAL
           COLORFUL STAT CARDS | HEADER FILTERS
        ============================================ */
    
    :root {
        /* ROYAL BLUE - Official Jimma University Primary Color - MATCHING LAYOUT */
        --ju-blue: #002789;
        --ju-blue-dark: #001a5c;
        --ju-blue-darker: #021230;
        --ju-blue-light: #1a3a9a;
        --ju-blue-lighter: #3a6ab0;
        --ju-blue-soft: #e6ebf7;
        --ju-blue-muted: #d4e0f0;
        --ju-blue-glow: rgba(0, 39, 137, 0.2);
        --ju-blue-gradient: linear-gradient(145deg, #002789, #001a5c);
        --ju-blue-gradient-light: linear-gradient(145deg, #002789, #1a3a9a);
        
        /* Ethiopian Gold Accents - Official - MATCHING LAYOUT */
        --ju-gold: #C4A747;
        --ju-gold-dark: #a5862e;
        --ju-gold-light: #e5d6a6;
        --ju-gold-soft: rgba(196, 167, 71, 0.12);
        --ju-gold-glow: rgba(196, 167, 71, 0.25);
        --ju-gold-gradient: linear-gradient(145deg, #C4A747, #a5862e);
        
        /* VIBRANT COLORS for Stat Cards */
        --ju-total: #002789;
        --ju-total-dark: #001a5c;
        --ju-total-light: #e6ebf7;
        --ju-read: #28a745;
        --ju-read-dark: #1e7e34;
        --ju-read-light: #d4edda;
        --ju-unread: #ffc107;
        --ju-unread-dark: #d39e00;
        --ju-unread-light: #fff3cd;
        --ju-month: #C4A747;
        --ju-month-dark: #a5862e;
        --ju-month-light: #e5d6a6;
        
        /* Semantic Colors */
        --ju-success: #28a745;
        --ju-success-dark: #1e7e34;
        --ju-success-soft: rgba(40, 167, 69, 0.12);
        --ju-warning: #ffc107;
        --ju-warning-dark: #d39e00;
        --ju-warning-soft: rgba(255, 193, 7, 0.12);
        --ju-danger: #dc3545;
        --ju-danger-dark: #bd2130;
        --ju-danger-soft: rgba(220, 53, 69, 0.12);
        --ju-info: #002789;
        --ju-info-soft: rgba(0, 39, 137, 0.08);
        
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
        
        /* Shadows - Royal Blue Tinted */
        --shadow-xs: 0 2px 4px rgba(0,39,137,0.02);
        --shadow-sm: 0 4px 6px rgba(0,39,137,0.04);
        --shadow: 0 6px 12px rgba(0,39,137,0.06);
        --shadow-md: 0 8px 24px rgba(0,39,137,0.08);
        --shadow-lg: 0 16px 32px rgba(0,39,137,0.1);
        --shadow-xl: 0 24px 48px rgba(0,39,137,0.12);
        --shadow-2xl: 0 32px 64px rgba(0,39,137,0.15);
        --shadow-gold: 0 8px 20px rgba(196,167,71,0.2);
        
        /* Border Radius - Matching Layout */
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
        max-width: 1600px;
        padding: 2rem 1.5rem;
    }

    /* ============================================
           COLORFUL STAT CARDS - Vibrant & Premium
        ============================================ */
    .premium-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 32px;
    }

    @media (max-width: 1200px) {
        .premium-stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .premium-stats-grid { grid-template-columns: 1fr; }
    }

    .premium-stat-card {
        border-radius: var(--radius-xl);
        padding: 28px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition-bounce);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        height: 100%;
        color: white;
    }

    .premium-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
        opacity: 0;
        transition: var(--transition);
        pointer-events: none;
    }

    .premium-stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-2xl);
    }

    .premium-stat-card:hover::before {
        opacity: 1;
    }

    /* VIBRANT CARD COLORS */
    .premium-stat-card.total {
        background: linear-gradient(145deg, #002789, #001a5c);
    }
    .premium-stat-card.read {
        background: linear-gradient(145deg, #28a745, #1e7e34);
    }
    .premium-stat-card.unread {
        background: linear-gradient(145deg, #ffc107, #d39e00);
    }
    .premium-stat-card.month {
        background: linear-gradient(145deg, #C4A747, #a5862e);
    }

    .premium-stat-icon {
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
        margin-bottom: 16px;
        transition: var(--transition-bounce);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .premium-stat-card:hover .premium-stat-icon {
        transform: scale(1.1) rotate(8deg);
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
    }

    .premium-stat-number {
        font-size: 2.8rem;
        font-weight: 800;
        color: white;
        margin-bottom: 4px;
        line-height: 1;
        font-family: 'Montserrat', sans-serif;
        text-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .premium-stat-label {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.95);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }

    .premium-stat-subtext {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
    }

    /* ============================================
           PREMIUM HEADER FILTERS - Clean & Compact
        ============================================ */
    .premium-filters-header {
        background: white;
        border-radius: var(--radius-xl);
        padding: 20px 28px;
        margin-bottom: 32px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--ju-gray-200);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        position: relative;
    }

    .premium-filters-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #002789, var(--ju-gold), #002789);
        border-radius: var(--radius-xl) var(--radius-xl) 0 0;
    }

    .premium-filter-groups {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 32px;
    }

    .premium-filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .premium-filter-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--ju-gray-700);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .premium-filter-label i {
        color: var(--ju-gold);
        font-size: 0.85rem;
    }

    .premium-filter-badges {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
    }

    .premium-filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        background: var(--ju-gray-100);
        border: 1px solid var(--ju-gray-200);
        border-radius: var(--radius-full);
        color: var(--ju-gray-700);
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: var(--transition-bounce);
    }

    .premium-filter-badge:hover {
        background: #002789;
        color: white;
        border-color: #002789;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,39,137,0.2);
    }

    .premium-filter-badge.active {
        background: #002789;
        color: white;
        border-color: #002789;
        box-shadow: 0 8px 16px rgba(0,39,137,0.2);
    }

    .premium-filter-badge.active i {
        color: var(--ju-gold);
    }

    .premium-filter-badge i {
        font-size: 0.8rem;
        transition: var(--transition);
    }

    .premium-header-actions-compact {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .premium-header-btn-sm {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 24px;
        border-radius: var(--radius-full);
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: var(--transition-bounce);
        border: none;
        cursor: pointer;
    }

    .premium-header-btn-sm-primary {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        box-shadow: 0 8px 16px rgba(0,39,137,0.2);
    }

    .premium-header-btn-sm-primary:hover {
        background: #001a5c;
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(0,39,137,0.3);
    }

    .premium-header-btn-sm-outline {
        background: transparent;
        color: #002789;
        border: 2px solid #002789;
    }

    .premium-header-btn-sm-outline:hover {
        background: #002789;
        color: white;
        transform: translateY(-2px);
    }

    .premium-header-btn-sm-outline:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .premium-header-btn-sm i {
        transition: var(--transition-elastic);
    }

    .premium-header-btn-sm:hover i {
        transform: rotate(180deg);
    }

    /* ============================================
           PREMIUM NOTIFICATIONS HEADER
        ============================================ */
    .premium-notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .premium-header-title h3 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #002789;
        margin-bottom: 6px;
    }

    .premium-header-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--ju-gray-600);
        font-size: 0.95rem;
        flex-wrap: wrap;
    }

    .premium-unread-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        background: var(--ju-unread-light);
        color: #d39e00;
        border-radius: var(--radius-full);
        font-weight: 700;
        font-size: 0.8rem;
        border: 1px solid rgba(255,193,7,0.3);
    }

    /* ============================================
           PREMIUM NOTIFICATION CARDS
        ============================================ */
    .premium-notifications-card {
        background: var(--ju-white);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--ju-gray-200);
        transition: var(--transition);
    }

    .premium-notifications-card:hover {
        box-shadow: var(--shadow);
        border-color: var(--ju-blue-soft);
    }

    .premium-notification-item {
        position: relative;
        padding: 24px 28px;
        border-bottom: 1px solid var(--ju-gray-200);
        transition: var(--transition);
        border-left: 4px solid transparent;
    }

    .premium-notification-item:last-child {
        border-bottom: none;
    }

    .premium-notification-item:hover {
        background: linear-gradient(90deg, var(--ju-gray-50), white);
        border-left-width: 6px;
    }

    /* Notification Type Border Colors */
    .premium-notification-item.announcement { border-left-color: #002789; }
    .premium-notification-item.event { border-left-color: #1a3a9a; }
    .premium-notification-item.system { border-left-color: var(--ju-gray-500); }
    .premium-notification-item.alert { border-left-color: var(--ju-danger); }
    .premium-notification-item.info { border-left-color: var(--ju-info); }
    .premium-notification-item.warning { border-left-color: var(--ju-warning); }
    .premium-notification-item.success { border-left-color: var(--ju-success); }

    .premium-notification-item.unread {
        background: linear-gradient(90deg, #e6ebf7, white);
        border-left-width: 6px;
    }

    .premium-notification-item.unread .premium-notification-title {
        color: #002789;
        font-weight: 700;
    }

    .premium-notification-row {
        display: flex;
        gap: 20px;
    }

    .premium-notification-icon {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: white;
        flex-shrink: 0;
        transition: var(--transition-bounce);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .premium-notification-item:hover .premium-notification-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .premium-notification-icon.blue-gradient { background: linear-gradient(145deg, #002789, #001a5c); }
    .premium-notification-icon.green-gradient { background: linear-gradient(145deg, #28a745, #1e7e34); }
    .premium-notification-icon.gold-gradient { background: linear-gradient(145deg, #C4A747, #a5862e); }
    .premium-notification-icon.purple-gradient { background: linear-gradient(145deg, #1a3a9a, #3a6ab0); }
    .premium-notification-icon.red-gradient { background: linear-gradient(145deg, #dc3545, #bd2130); }

    .premium-notification-content {
        flex: 1;
    }

    .premium-notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .premium-notification-title-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .premium-notification-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--ju-gray-800);
        margin: 0;
    }

    .premium-notification-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: var(--radius-full);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .premium-notification-badge.priority-1 {
        background: var(--ju-warning-soft);
        color: var(--ju-warning-dark);
        border: 1px solid rgba(255,193,7,0.3);
    }

    .premium-notification-badge.priority-2 {
        background: var(--ju-danger-soft);
        color: var(--ju-danger-dark);
        border: 1px solid rgba(220,53,69,0.3);
    }

    .premium-notification-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        color: var(--ju-gray-500);
        font-size: 0.85rem;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .premium-notification-time {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .premium-notification-time i {
        color: var(--ju-gold);
    }

    .premium-notification-message {
        color: var(--ju-gray-600);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .premium-notification-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        background: var(--ju-gray-100);
        border: 1px solid var(--ju-gray-200);
        border-radius: var(--radius-full);
        color: var(--ju-gray-700);
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: var(--transition-bounce);
        margin-bottom: 16px;
    }

    .premium-notification-action-btn:hover {
        background: #002789;
        color: white;
        border-color: #002789;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,39,137,0.2);
    }

    .premium-notification-action-btn i {
        transition: var(--transition-elastic);
    }

    .premium-notification-action-btn:hover i {
        transform: translateX(6px);
    }

    .premium-notification-actions {
        display: flex;
        gap: 12px;
        opacity: 0.6;
        transition: var(--transition);
    }

    .premium-notification-item:hover .premium-notification-actions {
        opacity: 1;
    }

    .premium-action-icon-btn {
        width: 36px;
        height: 36px;
        border-radius: var(--radius);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: 1px solid var(--ju-gray-200);
        color: var(--ju-gray-600);
        transition: var(--transition-bounce);
        cursor: pointer;
    }

    .premium-action-icon-btn:hover {
        background: #002789;
        color: white;
        border-color: #002789;
        transform: scale(1.1);
    }

    .premium-action-icon-btn.success:hover {
        background: #28a745;
        border-color: #28a745;
    }

    .premium-action-icon-btn.danger:hover {
        background: #dc3545;
        border-color: #dc3545;
    }

    .premium-unread-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #ffc107;
        position: absolute;
        top: 24px;
        right: 28px;
        box-shadow: 0 0 0 2px white, 0 0 0 4px rgba(255,193,7,0.2);
        animation: pulse-gold 2s infinite;
    }

    @keyframes pulse-gold {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.8; }
    }

    /* ============================================
           PREMIUM EMPTY STATE
        ============================================ */
    .premium-empty-state {
        text-align: center;
        padding: 60px 40px;
        background: linear-gradient(145deg, white, var(--ju-gray-50));
        border-radius: var(--radius-xl);
        border: 2px dashed #e6ebf7;
    }

    .premium-empty-icon {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 24px;
    }

    .premium-empty-circle {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(0,39,137,0.08);
        animation: pulse-circle 2s infinite;
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

    @keyframes pulse-circle {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.1); opacity: 0.6; }
    }

    .premium-empty-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #002789;
        margin-bottom: 12px;
    }

    .premium-empty-message {
        color: var(--ju-gray-600);
        font-size: 1rem;
        max-width: 400px;
        margin: 0 auto 24px;
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
           PREMIUM PAGINATION
        ============================================ */
    .premium-pagination {
        padding: 24px 28px;
        border-top: 1px solid var(--ju-gray-200);
        background: linear-gradient(180deg, var(--ju-gray-50), white);
    }

    .premium-pagination .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin: 0;
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
           PREMIUM TOAST NOTIFICATIONS
        ============================================ */
    .premium-toast {
        position: fixed;
        bottom: 32px;
        right: 32px;
        padding: 16px 24px;
        background: white;
        border-left: 4px solid;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 16px;
        z-index: 9999;
        animation: slideInRight 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        border: 1px solid var(--ju-gray-200);
    }

    .premium-toast-success {
        border-left-color: #28a745;
    }

    .premium-toast-info {
        border-left-color: #002789;
    }

    .premium-toast-error {
        border-left-color: #dc3545;
    }

    .premium-toast-icon {
        font-size: 1.5rem;
    }

    .premium-toast-content {
        font-weight: 600;
        color: var(--ju-gray-800);
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
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
           RESPONSIVE ADJUSTMENTS
        ============================================ */
    @media (max-width: 1200px) {
        .premium-filter-groups {
            gap: 24px;
        }
    }

    @media (max-width: 992px) {
        .premium-filters-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .premium-filter-groups {
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
            gap: 16px;
        }
        
        .premium-filter-group {
            width: 100%;
        }
        
        .premium-filter-badges {
            width: 100%;
        }
        
        .premium-header-actions-compact {
            width: 100%;
            justify-content: flex-start;
        }
        
        .premium-notification-row {
            flex-direction: column;
        }
        
        .premium-notification-icon {
            margin-bottom: 8px;
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }
        
        .premium-stat-number {
            font-size: 2.2rem;
        }
        
        .premium-header-actions-compact {
            flex-wrap: wrap;
        }
        
        .premium-header-btn-sm {
            width: 100%;
            justify-content: center;
        }
        
        .premium-notification-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .premium-notification-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        
        .premium-unread-dot {
            top: 16px;
            right: 16px;
        }
    }
</style>

<div class="container-fluid">
    <!-- ============================================
           COLORFUL STAT CARDS - Vibrant & Premium
        ============================================ -->
    <div class="premium-stats-grid" data-aos="fade-up" data-aos-duration="800">
        <!-- Total Notifications - Royal Blue -->
        <div class="premium-stat-card total" data-aos="zoom-in" data-aos-delay="100">
            <div class="premium-stat-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="premium-stat-number">{{ $notifications->total() }}</div>
            <div class="premium-stat-label">Total Notifications</div>
            <div class="premium-stat-subtext">All time</div>
        </div>
        
        <!-- Read - Green -->
        <div class="premium-stat-card read" data-aos="zoom-in" data-aos-delay="200">
            <div class="premium-stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="premium-stat-number">{{ $notifications->total() - ($unreadCount ?? 0) }}</div>
            <div class="premium-stat-label">Read</div>
            <div class="premium-stat-subtext">{{ $notifications->total() > 0 ? round((($notifications->total() - ($unreadCount ?? 0)) / $notifications->total()) * 100) : 0 }}% complete</div>
        </div>
        
        <!-- Unread - Gold -->
        <div class="premium-stat-card unread" data-aos="zoom-in" data-aos-delay="300">
            <div class="premium-stat-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="premium-stat-number">{{ $unreadCount ?? 0 }}</div>
            <div class="premium-stat-label">Unread</div>
            <div class="premium-stat-subtext">Awaiting your attention</div>
        </div>
        
        <!-- This Month - Gold Gradient -->
        <div class="premium-stat-card month" data-aos="zoom-in" data-aos-delay="400">
            <div class="premium-stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="premium-stat-number">{{ now()->format('M Y') }}</div>
            <div class="premium-stat-label">This Month</div>
            <div class="premium-stat-subtext">{{ now()->format('F') }} notifications</div>
        </div>
    </div>

    <!-- ============================================
           PREMIUM HEADER FILTERS - Clean & Compact
        ============================================ -->
    <div class="premium-filters-header" data-aos="fade-up" data-aos-duration="800">
        <div class="premium-filter-groups">
            <!-- Status Filter -->
            <div class="premium-filter-group">
                <span class="premium-filter-label">
                    <i class="fas fa-flag"></i>
                    Status
                </span>
                <div class="premium-filter-badges">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'all', 'page' => 1]) }}" 
                       class="premium-filter-badge {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">
                        <i class="fas fa-globe-africa"></i> All
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'unread', 'page' => 1]) }}" 
                       class="premium-filter-badge {{ request('status') == 'unread' ? 'active' : '' }}">
                        <i class="fas fa-envelope"></i> Unread
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'read', 'page' => 1]) }}" 
                       class="premium-filter-badge {{ request('status') == 'read' ? 'active' : '' }}">
                        <i class="fas fa-check-circle"></i> Read
                    </a>
                </div>
            </div>

            <!-- Type Filter -->
            <div class="premium-filter-group">
                <span class="premium-filter-label">
                    <i class="fas fa-tag"></i>
                    Type
                </span>
                <div class="premium-filter-badges">
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'all', 'page' => 1]) }}" 
                       class="premium-filter-badge {{ !request('type') || request('type') == 'all' ? 'active' : '' }}">
                        All
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'announcement', 'page' => 1]) }}" 
                       class="premium-filter-badge {{ request('type') == 'announcement' ? 'active' : '' }}">
                        <i class="fas fa-bullhorn"></i> Announcement
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'event', 'page' => 1]) }}" 
                       class="premium-filter-badge {{ request('type') == 'event' ? 'active' : '' }}">
                        <i class="fas fa-calendar"></i> Event
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'system', 'page' => 1]) }}" 
                       class="premium-filter-badge {{ request('type') == 'system' ? 'active' : '' }}">
                        <i class="fas fa-cog"></i> System
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'alert', 'page' => 1]) }}" 
                       class="premium-filter-badge {{ request('type') == 'alert' ? 'active' : '' }}">
                        <i class="fas fa-exclamation-triangle"></i> Alert
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'info', 'page' => 1]) }}" 
                       class="premium-filter-badge {{ request('type') == 'info' ? 'active' : '' }}">
                        <i class="fas fa-info-circle"></i> Info
                    </a>
                </div>
            </div>

            <!-- Time Period Filter - Compact -->
            <div class="premium-filter-group">
                <span class="premium-filter-label">
                    <i class="fas fa-clock"></i>
                    Time
                </span>
                <div class="premium-filter-badges">
                    <a href="{{ request()->fullUrlWithQuery(['period' => 'today', 'page' => 1]) }}" 
                       class="premium-filter-badge {{ request('period') == 'today' ? 'active' : '' }}">
                        Today
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['period' => 'week', 'page' => 1]) }}" 
                       class="premium-filter-badge {{ request('period') == 'week' ? 'active' : '' }}">
                        Week
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['period' => 'month', 'page' => 1]) }}" 
                       class="premium-filter-badge {{ request('period') == 'month' ? 'active' : '' }}">
                        Month
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['period' => 'year', 'page' => 1]) }}" 
                       class="premium-filter-badge {{ request('period') == 'year' ? 'active' : '' }}">
                        Year
                    </a>
                </div>
            </div>
        </div>

        <!-- Compact Action Buttons -->
        <div class="premium-header-actions-compact">
            <!-- Use PATCH method for mark all read -->
            <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="display: inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="premium-header-btn-sm premium-header-btn-sm-outline hover-gold-shine" 
                        {{ ($unreadCount ?? 0) == 0 ? 'disabled' : '' }}>
                    <i class="fas fa-check-double"></i>
                    <span>Mark All Read</span>
                </button>
            </form>
            
            <!-- Refresh button -->
            <a href="{{ route('notifications.index') }}" class="premium-header-btn-sm premium-header-btn-sm-outline hover-rotate-icon">
                <i class="fas fa-sync-alt"></i>
                <span>Refresh</span>
            </a>
            
            @can('manage_notifications')
            <a href="{{ route('notifications.create') }}" class="premium-header-btn-sm premium-header-btn-sm-primary hover-gold-shine">
                <i class="fas fa-paper-plane"></i>
                <span>Send</span>
            </a>
            @endcan
        </div>
    </div>

    <!-- ============================================
           PREMIUM NOTIFICATIONS LIST
        ============================================ -->
    <div class="row">
        <div class="col-12">
            <!-- Premium Notifications Header -->
            <div class="premium-notifications-header" data-aos="fade-left" data-aos-duration="800">
                <div class="premium-header-title">
                    <h3>Your Notifications</h3>
                    <div class="premium-header-meta">
                        <span>Showing <strong>{{ $notifications->firstItem() }}-{{ $notifications->lastItem() }}</strong> of <strong>{{ $notifications->total() }}</strong> notifications</span>
                        @if(($unreadCount ?? 0) > 0)
                        <span class="premium-unread-badge">
                            <i class="fas fa-circle"></i> {{ $unreadCount }} unread
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Premium Notifications Card -->
            <div class="premium-notifications-card" data-aos="fade-up" data-aos-duration="800">
                @forelse($notifications as $notification)
                @php
                    $userNotification = $notification->pivot;
                    $isUnread = is_null($userNotification->read_at);
                    $typeColor = $notification->type_color ?? 'info';
                    $typeIcon = $notification->type_icon ?? 'bell';
                    
                    // Determine icon gradient class
                    $iconGradient = 'blue-gradient';
                    if ($notification->type == 'success') $iconGradient = 'green-gradient';
                    else if ($notification->type == 'warning') $iconGradient = 'gold-gradient';
                    else if ($notification->type == 'alert' || $notification->type == 'danger') $iconGradient = 'red-gradient';
                    else if ($notification->type == 'event') $iconGradient = 'purple-gradient';
                    else if ($notification->type == 'announcement') $iconGradient = 'blue-gradient';
                    else if ($notification->type == 'info') $iconGradient = 'blue-gradient';
                    else $iconGradient = 'blue-gradient';
                @endphp
                <div class="premium-notification-item {{ $notification->type }} {{ $isUnread ? 'unread' : '' }}"
                     id="notification-{{ $notification->id }}">
                    
                    @if($isUnread)
                    <div class="premium-unread-dot"></div>
                    @endif
                    
                    <div class="premium-notification-row">
                        <!-- Notification Icon - Colorful Gradients -->
                        <div class="premium-notification-icon {{ $iconGradient }}">
                            <i class="fas fa-{{ $typeIcon }}"></i>
                        </div>
                        
                        <!-- Notification Content -->
                        <div class="premium-notification-content">
                            <div class="premium-notification-header">
                                <div class="premium-notification-title-wrapper">
                                    <h5 class="premium-notification-title">{{ $notification->title }}</h5>
                                    
                                    @if(!empty($notification->priority) && $notification->priority > 0)
                                    <span class="premium-notification-badge priority-{{ $notification->priority }}">
                                        <i class="fas fa-flag"></i>
                                        {{ $notification->priority == 1 ? 'High' : 'Urgent' }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="premium-notification-meta">
                                <span class="premium-notification-time">
                                    <i class="far fa-clock" style="color: var(--ju-gold);"></i>
                                    {{ $userNotification->created_at->diffForHumans() }}
                                </span>
                                @if(!empty($notification->creator))
                                <span>
                                    <i class="fas fa-user" style="color: var(--ju-gold);"></i>
                                    {{ $notification->creator->name }}
                                </span>
                                @endif
                            </div>
                            
                            <div class="premium-notification-message">
                                {{ $notification->message }}
                            </div>
                            
                            @if(!empty($notification->action_url))
                            <a href="{{ $notification->action_url }}" class="premium-notification-action-btn">
                                <span>{{ $notification->action_text ?? 'View Details' }}</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                            @endif
                            
                            <!-- Notification Actions -->
                            <div class="premium-notification-actions">
                                @if($isUnread)
                                <button type="button" class="premium-action-icon-btn success" 
                                        onclick="markAsRead({{ $notification->id }})"
                                        title="Mark as Read">
                                    <i class="fas fa-check"></i>
                                </button>
                                @else
                                <button type="button" class="premium-action-icon-btn" 
                                        onclick="markAsUnread({{ $notification->id }})"
                                        title="Mark as Unread">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                @endif
                                
                                <button type="button" class="premium-action-icon-btn danger" 
                                        onclick="dismissNotification({{ $notification->id }})"
                                        title="Dismiss">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Premium Empty State -->
                <div class="premium-empty-state">
                    <div class="premium-empty-icon">
                        <div class="premium-empty-circle"></div>
                        <i class="fas fa-bell-slash"></i>
                    </div>
                    <h4 class="premium-empty-title">No Notifications</h4>
                    <p class="premium-empty-message">You don't have any notifications yet. We'll notify you when something important arrives.</p>
                    @can('manage_notifications')
                    <a href="{{ route('notifications.create') }}" class="premium-empty-btn hover-gold-shine">
                        <i class="fas fa-paper-plane"></i>
                        <span>Send Your First Notification</span>
                    </a>
                    @endcan
                </div>
                @endforelse
                
                <!-- Premium Pagination -->
                @if($notifications->hasPages())
                <div class="premium-pagination">
                    {{ $notifications->withQueryString()->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for clearing all notifications -->
<form id="clearAllForm" action="{{ route('notifications.clear-all') }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<!-- Premium Toast Container -->
<div id="premiumToastContainer" style="position: fixed; bottom: 32px; right: 32px; z-index: 9999;"></div>

<!-- AOS Animation Script -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // ============================================
    // PREMIUM NOTIFICATION FUNCTIONS
    // ============================================
    
    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        offset: 20,
        easing: 'ease-in-out-cubic'
    });
    
    // Mark as Read
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/read`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notification = document.getElementById(`notification-${notificationId}`);
                
                // Remove unread class and dot
                notification.classList.remove('unread');
                const dot = notification.querySelector('.premium-unread-dot');
                if (dot) dot.remove();
                
                // Update title styling
                const title = notification.querySelector('.premium-notification-title');
                if (title) {
                    title.style.fontWeight = '600';
                    title.style.color = 'var(--ju-gray-800)';
                }
                
                // Replace action button
                const actionsDiv = notification.querySelector('.premium-notification-actions');
                const markReadBtn = actionsDiv.querySelector('button[onclick*="markAsRead"]');
                if (markReadBtn) {
                    markReadBtn.outerHTML = `
                        <button type="button" class="premium-action-icon-btn" 
                                onclick="markAsUnread(${notificationId})"
                                title="Mark as Unread">
                            <i class="fas fa-envelope"></i>
                        </button>
                    `;
                }
                
                // Update unread count
                updateUnreadCount(-1);
                
                showToast('Notification marked as read', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to mark notification as read', 'error');
        });
    }
    
    // Mark as Unread
    function markAsUnread(notificationId) {
        fetch(`/notifications/${notificationId}/unread`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notification = document.getElementById(`notification-${notificationId}`);
                
                // Add unread class and dot
                notification.classList.add('unread');
                
                // Add dot if not exists
                if (!notification.querySelector('.premium-unread-dot')) {
                    const dot = document.createElement('div');
                    dot.className = 'premium-unread-dot';
                    notification.appendChild(dot);
                }
                
                // Update title styling
                const title = notification.querySelector('.premium-notification-title');
                if (title) {
                    title.style.fontWeight = '700';
                    title.style.color = '#002789';
                }
                
                // Replace action button
                const actionsDiv = notification.querySelector('.premium-notification-actions');
                const markUnreadBtn = actionsDiv.querySelector('button[onclick*="markAsUnread"]');
                if (markUnreadBtn) {
                    markUnreadBtn.outerHTML = `
                        <button type="button" class="premium-action-icon-btn success" 
                                onclick="markAsRead(${notificationId})"
                                title="Mark as Read">
                            <i class="fas fa-check"></i>
                        </button>
                    `;
                }
                
                // Update unread count
                updateUnreadCount(1);
                
                showToast('Notification marked as unread', 'info');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to mark notification as unread', 'error');
        });
    }
    
    // Dismiss Notification
    function dismissNotification(notificationId) {
        if (confirm('Are you sure you want to dismiss this notification?')) {
            fetch(`/notifications/${notificationId}/dismiss`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notification = document.getElementById(`notification-${notificationId}`);
                    const wasUnread = notification.classList.contains('unread');
                    
                    // Remove notification with animation
                    notification.style.transition = 'all 0.3s ease';
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(20px)';
                    
                    setTimeout(() => {
                        notification.remove();
                        
                        // Update unread count if needed
                        if (wasUnread) {
                            updateUnreadCount(-1);
                        }
                        
                        // Check if no notifications left
                        const notificationsContainer = document.querySelector('.premium-notifications-card');
                        if (!notificationsContainer.querySelector('.premium-notification-item')) {
                            location.reload(); // Reload to show empty state
                        }
                    }, 300);
                    
                    showToast('Notification dismissed', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to dismiss notification', 'error');
            });
        }
    }
    
    // Update Unread Count
    function updateUnreadCount(change) {
        const unreadBadges = document.querySelectorAll('.premium-unread-badge');
        const statCard = document.querySelector('.premium-stat-card.unread');
        const unreadStatNumber = statCard?.querySelector('.premium-stat-number');
        const readStatCard = document.querySelector('.premium-stat-card.read');
        const readStatNumber = readStatCard?.querySelector('.premium-stat-number');
        const totalStatCard = document.querySelector('.premium-stat-card.total');
        const totalStatNumber = totalStatCard?.querySelector('.premium-stat-number');
        
        unreadBadges.forEach(badge => {
            let count = parseInt(badge.textContent.match(/\d+/)?.[0] || 0);
            count += change;
            
            if (count > 0) {
                badge.innerHTML = `<i class="fas fa-circle"></i> ${count} unread`;
                badge.style.display = 'inline-flex';
            } else {
                badge.style.display = 'none';
            }
        });
        
        // Update stat cards
        if (unreadStatNumber) {
            let count = parseInt(unreadStatNumber.textContent) + change;
            unreadStatNumber.textContent = Math.max(0, count);
        }
        
        if (readStatNumber && totalStatNumber) {
            let total = parseInt(totalStatNumber.textContent);
            let unread = parseInt(unreadStatNumber?.textContent || 0);
            readStatNumber.textContent = total - unread;
            
            // Update read percentage
            const readSubtext = readStatCard?.querySelector('.premium-stat-subtext');
            if (readSubtext && total > 0) {
                const percentage = Math.round(((total - unread) / total) * 100);
                readSubtext.textContent = `${percentage}% complete`;
            }
        }
        
        // Update mark all read button state
        const markAllBtn = document.querySelector('form[action*="mark-all-read"] button');
        if (markAllBtn) {
            const unread = parseInt(unreadStatNumber?.textContent || 0);
            markAllBtn.disabled = unread === 0;
        }
    }
    
    // Show Toast
    function showToast(message, type = 'success') {
        const container = document.getElementById('premiumToastContainer');
        const toast = document.createElement('div');
        toast.className = `premium-toast premium-toast-${type}`;
        
        let icon = 'check-circle';
        if (type === 'info') icon = 'info-circle';
        if (type === 'error') icon = 'exclamation-circle';
        
        toast.innerHTML = `
            <div class="premium-toast-icon" style="color: ${type === 'success' ? '#28a745' : (type === 'info' ? '#002789' : '#dc3545')}">
                <i class="fas fa-${icon}"></i>
            </div>
            <div class="premium-toast-content">
                ${message}
            </div>
        `;
        
        container.appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.transition = 'all 0.3s ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Clear All Notifications
    function clearAllNotifications() {
        if (confirm('Are you sure you want to clear all notifications? This action cannot be undone.')) {
            document.getElementById('clearAllForm').submit();
        }
    }
    
    // Keyboard Shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+R to refresh
        if ((e.ctrlKey || e.metaKey) && e.key === 'r' && !e.shiftKey) {
            e.preventDefault();
            window.location.href = '{{ route("notifications.index") }}';
        }
        
        // Ctrl+M to mark all as read
        if ((e.ctrlKey || e.metaKey) && e.key === 'm') {
            e.preventDefault();
            const markAllBtn = document.querySelector('form[action*="mark-all-read"] button');
            if (markAllBtn && !markAllBtn.disabled) {
                markAllBtn.click();
            }
        }
        
        // Ctrl+D to dismiss all (clear all)
        if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
            e.preventDefault();
            clearAllNotifications();
        }
    });
    
    // Filter button active state persistence
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.premium-filter-badge');
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Remove active class from siblings in same filter group
                const parent = this.closest('.premium-filter-badges');
                if (parent) {
                    parent.querySelectorAll('.premium-filter-badge').forEach(b => {
                        b.classList.remove('active');
                    });
                }
                this.classList.add('active');
            });
        });
    });
</script>

<!-- Add AOS CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
@endsection