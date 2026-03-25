{{-- resources/views/events/guest/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Events Dashboard | Jimma University')
@section('page-title', 'University Events Dashboard')
@section('page-subtitle', 'Discover Campus Activities, Conferences & Cultural Events')

@section('breadcrumb-items')
<li class="breadcrumb-item">
    <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none hover-lift">
        <i class="fas fa-home me-2" style="color: var(--ju-gold);"></i>Home
    </a>
</li>
<li class="breadcrumb-item active">
    <span class="fw-semibold" style="color: var(--ju-blue-dark);">Events Dashboard</span>
</li>
@endsection

@section('content')
<style>
/* ============================================
           PREMIUM JIMMA UNIVERSITY DASHBOARD
           ROYAL BLUE (#002789) & GOLD (#C4A747) - OFFICIAL
           Perfectly Fitted Cards | Fixed Search
        ============================================ */

:root {
    /* ROYAL BLUE - Official Jimma University Primary Color - MATCHING LAYOUT */
    --ju-blue: #002789;
    /* Official Royal Blue - Exact match */
    --ju-blue-dark: #001a5c;
    /* Darker shade for gradients */
    --ju-blue-darker: #021230;
    --ju-blue-light: #1a3a9a;
    /* Lighter shade for accents */
    --ju-blue-lighter: #3a6ab0;
    --ju-blue-soft: rgba(0, 39, 137, 0.08);
    --ju-blue-glow: rgba(0, 39, 137, 0.2);
    --ju-blue-gradient: linear-gradient(145deg, #002789, #001a5c);
    --ju-blue-gradient-light: linear-gradient(145deg, #002789, #1a3a9a);

    /* Ethiopian Gold Accents - Official - MATCHING LAYOUT */
    --ju-gold: #C4A747;
    /* Official Gold accent - Exact match */
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
    --shadow-xs: 0 2px 4px rgba(0, 39, 137, 0.02);
    --shadow-sm: 0 4px 6px rgba(0, 39, 137, 0.04);
    --shadow: 0 6px 12px rgba(0, 39, 137, 0.06);
    --shadow-md: 0 8px 24px rgba(0, 39, 137, 0.08);
    --shadow-lg: 0 16px 32px rgba(0, 39, 137, 0.1);
    --shadow-xl: 0 24px 48px rgba(0, 39, 137, 0.12);
    --shadow-2xl: 0 32px 64px rgba(0, 39, 137, 0.15);
    --shadow-gold: 0 8px 20px rgba(196, 167, 71, 0.2);
    --shadow-gold-lg: 0 16px 32px rgba(196, 167, 71, 0.25);

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

/* ============================================
           RESET & BASE - Fix All Spacing Issues
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

.dashboard-container {
    background: var(--ju-offwhite);
    min-height: 100vh;
}

/* ============================================
           PREMIUM HEADER - Royal Blue Excellence
        ============================================ */
.ju-premium-header {
    background: linear-gradient(145deg, #002789, #001a5c);
    position: relative;
    padding: 60px 0 100px;
    margin-bottom: 0;
    overflow: hidden;
    border-bottom: 4px solid var(--ju-gold);
}

.ju-premium-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 40%),
        radial-gradient(circle at 90% 70%, rgba(196, 167, 71, 0.15) 0%, transparent 40%),
        radial-gradient(circle at 30% 80%, rgba(255, 255, 255, 0.08) 0%, transparent 35%),
        radial-gradient(circle at 70% 30%, rgba(196, 167, 71, 0.12) 0%, transparent 45%);
    pointer-events: none;
}

.header-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 32px;
    position: relative;
    z-index: 10;
}

.premium-badge {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 8px 24px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: var(--radius-full);
    color: white;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 24px;
}

.premium-badge i {
    color: var(--ju-gold);
}

.premium-title {
    font-size: 3.2rem;
    font-weight: 800;
    color: white;
    margin-bottom: 16px;
    line-height: 1.1;
    letter-spacing: -1.5px;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.gold-text {
    color: var(--ju-gold);
    position: relative;
    display: inline-block;
    text-shadow: 0 2px 10px rgba(196, 167, 71, 0.3);
}

.premium-subtitle {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.95);
    max-width: 700px;
    margin-bottom: 40px;
    line-height: 1.6;
}

/* ============================================
           FIXED STATS GRID - Royal Blue Cards
        ============================================ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-top: 20px;
}

@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .premium-title {
        font-size: 2.8rem;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .premium-title {
        font-size: 2.2rem;
    }

    .header-content {
        padding: 0 20px;
    }

    .ju-premium-header {
        padding: 40px 0 80px;
    }
}

.premium-stat-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: var(--radius-lg);
    padding: 24px;
    transition: var(--transition-bounce);
    position: relative;
    overflow: hidden;
    height: 100%;
    width: 100%;
}

.premium-stat-card:hover {
    transform: translateY(-6px);
    background: rgba(255, 255, 255, 0.12);
    border-color: var(--ju-gold);
    box-shadow: var(--shadow-gold);
}

.stat-icon-wrapper {
    width: 56px;
    height: 56px;
    background: rgba(255, 255, 255, 0.12);
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-bottom: 16px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: var(--transition-bounce);
}

.premium-stat-card:hover .stat-icon-wrapper {
    background: var(--ju-gold);
    color: var(--ju-blue-dark);
    transform: scale(1.1) rotate(5deg);
    border-color: var(--ju-gold);
}

.stat-number {
    font-size: 2.4rem;
    font-weight: 800;
    color: white;
    line-height: 1;
    margin-bottom: 6px;
    font-family: 'Montserrat', sans-serif;
}

.stat-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.stat-trend {
    position: absolute;
    top: 20px;
    right: 20px;
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--ju-gold);
    background: rgba(196, 167, 71, 0.15);
    padding: 4px 12px;
    border-radius: var(--radius-full);
    border: 1px solid rgba(196, 167, 71, 0.3);
}

/* ============================================
           FIXED FILTER SECTION - Royal Blue Theme
        ============================================ */
.premium-filter-section {
    max-width: 1400px;
    margin: -50px auto 50px;
    padding: 0 24px;
    position: relative;
    z-index: 100;
}

.filter-glass-card {
    background: var(--ju-white);
    backdrop-filter: blur(20px);
    border-radius: var(--radius-xl);
    padding: 32px;
    box-shadow: var(--shadow);
    border: 1px solid var(--ju-gray-200);
    position: relative;
}

.filter-glass-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #002789, var(--ju-gold), #002789);
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
}

/* FIXED SEARCH WRAPPER - No Overlap */
.premium-search-wrapper {
    position: relative;
    margin-bottom: 28px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.premium-search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #002789;
    font-size: 1.1rem;
    z-index: 10;
}

.premium-search-input {
    flex: 1;
    padding: 16px 24px 16px 56px;
    border: 2px solid var(--ju-gray-200);
    border-radius: var(--radius-lg);
    font-size: 1rem;
    background: var(--ju-white);
    color: var(--ju-gray-800);
    transition: var(--transition);
    height: 60px;
    box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.02);
}

.premium-search-input:focus {
    outline: none;
    border-color: #002789;
    box-shadow: 0 0 0 4px rgba(0, 39, 137, 0.1);
}

.premium-search-input:focus+.premium-search-icon {
    color: #002789;
    transform: translateY(-50%) scale(1.1);
}

/* FIXED SEARCH BUTTON - Royal Blue */
.premium-search-btn {
    background: linear-gradient(145deg, #002789, #001a5c);
    color: white;
    border: none;
    border-radius: var(--radius-lg);
    padding: 0 32px;
    height: 60px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: var(--transition-bounce);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    box-shadow: 0 8px 20px rgba(0, 39, 137, 0.25);
    white-space: nowrap;
    flex-shrink: 0;
}

.premium-search-btn:hover {
    background: #001a5c;
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(0, 39, 137, 0.35);
}

.premium-search-btn i {
    font-size: 1rem;
    transition: var(--transition);
}

.premium-search-btn:hover i {
    transform: rotate(90deg);
}

@media (max-width: 768px) {
    .premium-search-wrapper {
        flex-direction: column;
    }

    .premium-search-btn {
        width: 100%;
        padding: 0 24px;
    }
}

/* Premium Filter Tabs - Royal Blue */
.premium-filter-tabs {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.premium-filter-tab {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: var(--ju-gray-100);
    border: 1px solid var(--ju-gray-200);
    border-radius: var(--radius-full);
    color: var(--ju-gray-700);
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: var(--transition-bounce);
}

.premium-filter-tab:hover {
    background: #002789;
    color: white;
    border-color: #002789;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 39, 137, 0.2);
}

.premium-filter-tab:hover i {
    color: var(--ju-gold);
}

.premium-filter-tab.active {
    background: #002789;
    color: white;
    border-color: #002789;
    box-shadow: 0 8px 16px rgba(0, 39, 137, 0.25);
}

.premium-filter-tab.active i {
    color: var(--ju-gold);
}

/* ============================================
           FIXED EVENTS SECTION - Royal Blue Theme
        ============================================ */
.events-section {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 24px 60px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    padding-bottom: 20px;
    border-bottom: 2px solid rgba(0, 39, 137, 0.1);
}

.section-title-wrapper {
    display: flex;
    align-items: center;
    gap: 16px;
}

.section-icon {
    width: 56px;
    height: 56px;
    background: rgba(0, 39, 137, 0.08);
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #002789;
    border: 1px solid rgba(0, 39, 137, 0.15);
}

.section-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: #002789;
    margin: 0;
    letter-spacing: -1px;
}

.section-subtitle {
    color: var(--ju-gray-600);
    margin: 4px 0 0;
    font-size: 1rem;
}

.premium-sort-select {
    padding: 12px 32px 12px 20px;
    border: 2px solid var(--ju-gray-200);
    border-radius: var(--radius-lg);
    background: white;
    color: var(--ju-gray-800);
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23002789' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    background-size: 16px;
}

.premium-sort-select:focus {
    outline: none;
    border-color: #002789;
    box-shadow: 0 0 0 4px rgba(0, 39, 137, 0.1);
}

/* ============================================
           FIXED CARDS - 3 PER ROW, PERFECT FIT
           ROYAL BLUE THEME - NO CONTENT CUT OFF
        ============================================ */
.premium-events-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
    margin-bottom: 48px;
}

@media (max-width: 1200px) {
    .premium-events-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .premium-events-grid {
        grid-template-columns: 1fr;
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .premium-sort-select {
        width: 100%;
    }
}

/* FIXED CARD - Royal Blue Theme */
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

/* FIXED CARD HEADER - Proper Height */
.premium-card-header {
    position: relative;
    height: 180px;
    width: 100%;
    overflow: hidden;
    flex-shrink: 0;
}

.premium-card-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.premium-event-card:hover .premium-card-image {
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
    filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.2));
}

/* FIXED BADGES - Gold Accents */
.premium-badges {
    position: absolute;
    top: 16px;
    left: 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    z-index: 20;
    max-width: calc(100% - 32px);
}

.premium-type-badge {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    color: #002789;
    padding: 6px 16px;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    width: fit-content;
}

.premium-status-badge {
    padding: 6px 16px;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 700;
    color: white;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    width: fit-content;
}

.premium-featured-badge {
    background: linear-gradient(145deg, #C4A747, #a5862e);
}

.premium-live-badge {
    background: linear-gradient(145deg, #dc2626, #b91c1c);
}

/* FIXED CARD BODY - Perfect Padding */
.premium-card-body {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.premium-event-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--ju-gray-800);
    line-height: 1.4;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 52px;
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
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 0;
    min-height: 42px;
}

/* FIXED DETAILS GRID - 2x2 Layout */
.premium-details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin: 8px 0 0;
}

.premium-detail-item {
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

.premium-detail-icon {
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
}

.premium-event-card:hover .premium-detail-icon {
    background: #002789;
    color: white;
    border-color: #002789;
}

.premium-detail-content {
    flex: 1;
    min-width: 0;
}

.premium-detail-label {
    font-size: 0.6rem;
    font-weight: 700;
    color: var(--ju-gray-500);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 2px;
}

.premium-detail-value {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--ju-gray-800);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* FIXED CARD FOOTER - Clean */
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
    background: rgba(0, 39, 137, 0.08);
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #002789;
    font-size: 1rem;
    border: 2px solid white;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    flex-shrink: 0;
}

.premium-event-card:hover .premium-attendance-icon {
    background: var(--ju-gold);
    color: #001a5c;
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

.premium-view-btn {
    padding: 12px 24px;
    background: linear-gradient(145deg, #002789, #001a5c);
    color: white;
    border: none;
    border-radius: var(--radius-lg);
    font-weight: 700;
    font-size: 0.9rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition-bounce);
    box-shadow: 0 8px 16px rgba(0, 39, 137, 0.2);
    white-space: nowrap;
    flex-shrink: 0;
}

.premium-view-btn:hover {
    background: #001a5c;
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(0, 39, 137, 0.3);
}

.premium-view-btn i {
    transition: transform 0.3s ease;
}

.premium-view-btn:hover i {
    transform: translateX(6px);
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
    border: 2px dashed rgba(0, 39, 137, 0.2);
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
    background: rgba(0, 39, 137, 0.08);
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

    0%,
    100% {
        transform: scale(1);
        opacity: 0.3;
    }

    50% {
        transform: scale(1.1);
        opacity: 0.6;
    }
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
    box-shadow: 0 8px 20px rgba(0, 39, 137, 0.25);
}

.premium-empty-btn:hover {
    background: #001a5c;
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(0, 39, 137, 0.35);
}

/* ============================================
           PREMIUM RESULTS BAR & PAGINATION
        ============================================ */
.premium-results-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    background: white;
    border-radius: var(--radius-lg);
    border: 1px solid var(--ju-gray-200);
    margin: 32px 0;
}

.results-info {
    display: flex;
    align-items: center;
    gap: 12px;
    color: var(--ju-gray-600);
}

.results-info i {
    color: var(--ju-gold);
}

.results-info strong {
    color: #002789;
}

.premium-export-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 24px;
    background: white;
    color: #002789;
    border: 2px solid #002789;
    border-radius: var(--radius-lg);
    font-weight: 700;
    font-size: 0.9rem;
    text-decoration: none;
    transition: var(--transition-bounce);
}

.premium-export-btn:hover {
    background: #002789;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 39, 137, 0.2);
}

.premium-pagination-wrapper {
    margin-top: 48px;
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
    box-shadow: 0 8px 16px rgba(0, 39, 137, 0.2);
}

.premium-pagination .page-link:hover:not(.active) {
    background: rgba(0, 39, 137, 0.08);
    border-color: #002789;
    color: #002789;
    transform: translateY(-2px);
}

/* ============================================
           PREMIUM FLOATING ACTION BUTTON
        ============================================ */
.premium-fab {
    position: fixed;
    bottom: 32px;
    right: 32px;
    z-index: 1000;
}

.premium-fab-btn {
    width: 60px;
    height: 60px;
    background: linear-gradient(145deg, #002789, #001a5c);
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    cursor: pointer;
    transition: var(--transition-bounce);
    box-shadow: 0 10px 25px rgba(0, 39, 137, 0.3);
    border: 2px solid white;
}

.premium-fab-btn:hover {
    background: #001a5c;
    transform: translateY(-5px) scale(1.1);
    box-shadow: 0 15px 35px rgba(0, 39, 137, 0.4);
}

/* ============================================
           PREMIUM SCROLL PROGRESS
        ============================================ */
.premium-scroll-progress {
    position: fixed;
    top: 0;
    left: 0;
    width: 0%;
    height: 4px;
    background: linear-gradient(90deg, #002789, var(--ju-gold));
    z-index: 9999;
    box-shadow: 0 0 20px rgba(196, 167, 71, 0.3);
    transition: width 0.1s ease;
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
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
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
    transition: transform var(--transition);
}

.hover-rotate-icon:hover i {
    transform: rotate(360deg);
}

/* ============================================
           ROYAL BLUE UTILITY CLASSES
        ============================================ */
.text-ju-blue {
    color: #002789;
}

.text-ju-gold {
    color: #C4A747;
}

.bg-ju-blue {
    background: #002789;
}

.bg-ju-gold {
    background: #C4A747;
}

.border-ju-blue {
    border-color: #002789;
}

.border-ju-gold {
    border-color: #C4A747;
}
</style>

<!-- Premium Scroll Progress Bar -->
<div class="premium-scroll-progress" id="premiumScrollProgress"></div>

<div class="dashboard-container">
    <!-- ============================================
           PREMIUM HEADER - Royal Blue Excellence
        ============================================ -->
    <div class="ju-premium-header">
        <div class="header-content">
            <div class="premium-badge" data-aos="fade-down" data-aos-duration="800">
                <i class="fas fa-star"></i>
                <span>Official Jimma University Events Portal</span>
                <i class="fas fa-star"></i>
            </div>

            <h1 class="premium-title">
                Jimma University
                <span class="gold-text">Events</span>
            </h1>

            <p class="premium-subtitle">
                Discover, engage, and participate in campus activities, academic conferences,
                and cultural events across all our campuses. Experience excellence in every gathering.
            </p>

            <!-- Premium Stats Grid - Royal Blue -->
            <div class="stats-grid">
                <div class="premium-stat-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-number">{{ $upcomingCount ?? 0 }}</div>
                    <div class="stat-label">Upcoming Events</div>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i> +12%
                    </div>
                </div>

                <div class="premium-stat-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="stat-number">{{ $ongoingCount ?? 0 }}</div>
                    <div class="stat-label">Live Now</div>
                    <div class="stat-trend">
                        <i class="fas fa-circle" style="color: #10b981;"></i> Active
                    </div>
                </div>

                <div class="premium-stat-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="stat-number">{{ $campusCount ?? 12 }}</div>
                    <div class="stat-label">Campuses</div>
                    <div class="stat-trend">
                        <i class="fas fa-globe-africa"></i> Nationwide
                    </div>
                </div>

                <div class="premium-stat-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">{{ $totalAttendees ?? '2.5k' }}</div>
                    <div class="stat-label">Total Attendees</div>
                    <div class="stat-trend">
                        <i class="fas fa-chart-line"></i> +25%
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================
           FIXED FILTER SECTION - Royal Blue Theme
        ============================================ -->
    <div class="premium-filter-section">
        <div class="filter-glass-card" data-aos="fade-up" data-aos-duration="800">
            <!-- FIXED: Search wrapper with proper flex layout -->
            <div class="premium-search-wrapper">
                <i class="fas fa-search premium-search-icon"></i>
                <input type="text" id="premiumSearchInput" class="premium-search-input"
                    placeholder="Search events by title, department, speaker, or keywords..."
                    value="{{ request('search') }}">
                <button class="premium-search-btn" id="premiumSearchBtn">
                    <i class="fas fa-search"></i>
                    <span>Search Events</span>
                </button>
            </div>

            <!-- Premium Filter Tabs - Royal Blue -->
            <div class="premium-filter-tabs">
                <a href="{{ route('events.guest.dashboard') }}"
                    class="premium-filter-tab {{ !request()->hasAny(['status', 'featured', 'event_type']) ? 'active' : '' }}">
                    <i class="fas fa-globe-africa"></i>
                    <span>All Events</span>
                </a>

                <a href="?status=upcoming"
                    class="premium-filter-tab {{ request('status') == 'upcoming' ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    <span>Upcoming</span>
                </a>

                <a href="?featured=1" class="premium-filter-tab {{ request('featured') == '1' ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>Featured</span>
                </a>

                <a href="?status=ongoing"
                    class="premium-filter-tab {{ request('status') == 'ongoing' ? 'active' : '' }}">
                    <i class="fas fa-play-circle"></i>
                    <span>Live Now</span>
                </a>

                <a href="?event_type=academic"
                    class="premium-filter-tab {{ request('event_type') == 'academic' ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Academic</span>
                </a>

                <a href="?event_type=cultural"
                    class="premium-filter-tab {{ request('event_type') == 'cultural' ? 'active' : '' }}">
                    <i class="fas fa-music"></i>
                    <span>Cultural</span>
                </a>

                <a href="?event_type=sports"
                    class="premium-filter-tab {{ request('event_type') == 'sports' ? 'active' : '' }}">
                    <i class="fas fa-futbol"></i>
                    <span>Sports</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ============================================
           FIXED EVENTS SECTION - Perfect Card Fit
           ROYAL BLUE THEME - 3 Cards Per Row
        ============================================ -->
    <div class="events-section">
        <!-- Section Header -->
        <div class="section-header">
            <div class="section-title-wrapper">
                <div class="section-icon" data-aos="zoom-in" data-aos-duration="600">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <h2 class="section-title">Featured & Upcoming Events</h2>
                    <p class="section-subtitle">
                        <span style="color: #002789; font-weight: 700;">{{ $events->total() }}</span> events available
                        @if(request()->hasAny(['search', 'status', 'featured', 'event_type']))
                        <span style="color: var(--ju-gold);">• Filtered results</span>
                        @endif
                    </p>
                </div>
            </div>

            <select class="premium-sort-select" id="premiumSortSelect">
                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>📅 Latest First
                </option>
                <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>📅 Earliest First
                </option>
                <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>🔥 Most Popular
                </option>
                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>📝 Title A-Z</option>
            </select>
        </div>

        <!-- FIXED: Premium Events Grid - 3 Cards Per Row, Perfect Fit -->
        <div class="premium-events-grid" id="premiumEventsContainer">
            @forelse($events as $index => $event)
            <div class="premium-event-card" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}"
                data-aos-duration="700">

                <!-- FIXED: Card Header with Proper Image Fit -->
                <div class="premium-card-header">
                    @if($event->image)
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="premium-card-image"
                        loading="lazy"
                        onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'premium-image-fallback\'><i class=\'fas fa-calendar-alt\'></i></div>';">
                    @else
                    <div class="premium-image-fallback">
                        <i class="fas fa-{{ $event->event_type_icon ?? 'calendar-alt' }}"></i>
                    </div>
                    @endif

                    <!-- Premium Badges - Gold Accents -->
                    <div class="premium-badges">
                        <span class="premium-type-badge">
                            @switch($event->event_type)
                            @case('academic') <i class="fas fa-graduation-cap"></i> @break
                            @case('cultural') <i class="fas fa-music"></i> @break
                            @case('sports') <i class="fas fa-futbol"></i> @break
                            @case('conference') <i class="fas fa-microphone"></i> @break
                            @case('workshop') <i class="fas fa-tools"></i> @break
                            @case('seminar') <i class="fas fa-chalkboard-teacher"></i> @break
                            @default <i class="fas fa-tag"></i>
                            @endswitch
                            {{ Str::limit(ucfirst($event->event_type), 12) }}
                        </span>

                        @if($event->is_featured)
                        <span class="premium-status-badge premium-featured-badge">
                            <i class="fas fa-star"></i> Featured
                        </span>
                        @endif

                        @if($event->status == 'ongoing')
                        <span class="premium-status-badge premium-live-badge">
                            <i class="fas fa-circle"></i> Live Now
                        </span>
                        @endif
                    </div>
                </div>

                <!-- FIXED: Card Body - Perfect Padding, No Cut Off -->
                <div class="premium-card-body">
                    <h5 class="premium-event-title">
                        <a href="{{ route('events.guest.show', $event->slug) }}">
                            {{ Str::limit($event->title, 60) }}
                        </a>
                    </h5>

                    <p class="premium-event-description">
                        {{ Str::limit(strip_tags($event->short_description ?? $event->description), 90) }}
                    </p>

                    <!-- FIXED: Details Grid - 2x2 Layout -->
                    <div class="premium-details-grid">
                        <div class="premium-detail-item">
                            <div class="premium-detail-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="premium-detail-content">
                                <div class="premium-detail-label">Date</div>
                                <div class="premium-detail-value">{{ $event->start_date->format('M d, Y') }}</div>
                            </div>
                        </div>

                        <div class="premium-detail-item">
                            <div class="premium-detail-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="premium-detail-content">
                                <div class="premium-detail-label">Time</div>
                                <div class="premium-detail-value">{{ $event->start_date->format('h:i A') }}</div>
                            </div>
                        </div>

                        <div class="premium-detail-item">
                            <div class="premium-detail-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="premium-detail-content">
                                <div class="premium-detail-label">Campus</div>
                                <div class="premium-detail-value">
                                    {{ Str::limit($event->campus_name ?? 'Main Campus', 15) }}</div>
                            </div>
                        </div>

                        <div class="premium-detail-item">
                            <div class="premium-detail-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="premium-detail-content">
                                <div class="premium-detail-label">Capacity</div>
                                <div class="premium-detail-value">{{ $event->max_attendees ?? 'Unlimited' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FIXED: Card Footer - Clean -->
                <div class="premium-card-footer">
                    <div class="premium-attendance">
                        <div class="premium-attendance-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="premium-attendance-text">
                            <span class="premium-attendance-count">{{ $event->registered_attendees ?? 0 }}</span>
                            <span class="premium-attendance-label">Attending</span>
                        </div>
                    </div>

                    <a href="{{ route('events.guest.show', $event->slug) }}" class="premium-view-btn">
                        <span>View</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            @empty
            <!-- Premium Empty State - Royal Blue -->
            <div class="premium-empty-state" data-aos="zoom-in" data-aos-duration="600">
                <div class="premium-empty-icon">
                    <div class="empty-circle"></div>
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3 class="premium-empty-title">No Events Found</h3>
                <p class="premium-empty-message">
                    @if(request()->hasAny(['search', 'status', 'featured', 'event_type']))
                    No events match your current filters. Try adjusting your search criteria.
                    @else
                    There are no upcoming events at the moment. Check back soon!
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status', 'featured', 'event_type']))
                <a href="{{ route('events.guest.dashboard') }}" class="premium-empty-btn">
                    <i class="fas fa-times-circle"></i>
                    <span>Clear All Filters</span>
                </a>
                @else
                <a href="#" class="premium-empty-btn">
                    <i class="fas fa-bell"></i>
                    <span>Get Notified</span>
                </a>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Premium Results Bar -->
        @if($events->count() > 0)
        <div class="premium-results-bar" data-aos="fade-up" data-aos-duration="600">
            <div class="results-info">
                <i class="fas fa-info-circle" style="color: var(--ju-gold);"></i>
                <span>
                    Showing <strong>{{ $events->firstItem() }}-{{ $events->lastItem() }}</strong>
                    of <strong>{{ $events->total() }}</strong> events
                </span>
            </div>
            <div>
                <a href="?export=1" class="premium-export-btn">
                    <i class="fas fa-download"></i>
                    <span>Export</span>
                </a>
            </div>
        </div>
        @endif

        <!-- Premium Pagination -->
        @if($events->hasPages())
        <div class="premium-pagination-wrapper" data-aos="fade-up" data-aos-duration="600">
            <nav aria-label="Events pagination">
                <ul class="pagination premium-pagination">
                    {{-- Use default Laravel pagination which always exists --}}
                    {{ $events->withQueryString()->onEachSide(1)->links() }}
                </ul>
            </nav>
        </div>
        @endif
    </div>

    <!-- Premium Floating Action Button -->
    <div class="premium-fab">
        <button class="premium-fab-btn" id="premiumScrollToTopBtn" title="Scroll to top" style="display: none;">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>
</div>

<!-- Premium JavaScript -->
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

    // Scroll Progress Bar
    const scrollProgress = document.getElementById('premiumScrollProgress');
    window.addEventListener('scroll', function() {
        const windowHeight = document.documentElement.scrollHeight - document.documentElement
            .clientHeight;
        const scrolled = (window.scrollY / windowHeight) * 100;
        scrollProgress.style.width = scrolled + '%';
    });

    // FIXED: Search Functionality
    const searchInput = document.getElementById('premiumSearchInput');
    const searchBtn = document.getElementById('premiumSearchBtn');

    function performPremiumSearch() {
        const params = new URLSearchParams(window.location.search);
        const searchTerm = searchInput.value.trim();

        if (searchTerm) {
            params.set('search', searchTerm);
        } else {
            params.delete('search');
        }
        params.delete('page');

        window.location.href = window.location.pathname + '?' + params.toString();
    }

    searchBtn.addEventListener('click', performPremiumSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') performPremiumSearch();
    });

    // Sort Functionality
    const sortSelect = document.getElementById('premiumSortSelect');
    sortSelect.addEventListener('change', function() {
        const params = new URLSearchParams(window.location.search);
        params.set('sort', this.value);
        params.delete('page');
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Scroll to Top Button
    const scrollToTopBtn = document.getElementById('premiumScrollToTopBtn');

    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.style.display = 'flex';
            scrollToTopBtn.style.animation = 'fadeInUp 0.5s ease';
        } else {
            scrollToTopBtn.style.display = 'none';
        }
    });

    scrollToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>

<!-- Add AOS CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
@endsection