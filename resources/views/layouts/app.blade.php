<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Jimma University | Event Management System')</title>

    <!-- Meta Tags -->
    <meta name="description"
        content="Jimma University Event Management System - Professional platform for managing university events, registrations, and venue bookings.">
    <meta name="author" content="Jimma University">
    <meta name="keywords" content="Jimma University, Events, Management System, Ethiopia, Higher Education">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts - Montserrat & Open Sans -->
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Open+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
    /* ============================================
           OFFICIAL JIMMA UNIVERSITY DESIGN SYSTEM
           PRIMARY COLOR: ROYAL BLUE #002789
           ACCENT COLOR: GOLD #C4A747
        ============================================ */
    :root {
        /* OFFICIAL JIMMA UNIVERSITY COLORS - UPDATED */
        --ju-blue: #002789;
        /* Official Royal Blue */
        --ju-blue-dark: #001a5c;
        /* Darker shade for gradients */
        --ju-blue-light: #1a3a9a;
        /* Lighter shade for accents */
        --ju-blue-soft: #e6ebf7;
        /* Soft blue for backgrounds */

        /* Gold accent colors */
        --ju-gold: #C4A747;
        /* Official Gold accent */
        --ju-gold-light: #e5d6a6;
        --ju-gold-dark: #b8960f;

        /* Secondary colors */
        --ju-green: #006838;
        --ju-green-dark: #004d2b;
        --ju-green-light: #1a8c4a;
        --ju-green-soft: #e8f3e9;
        --ju-white: #ffffff;
        --ju-offwhite: #f9f9f9;
        --ju-gray: #f0f0f0;
        --ju-gray-dark: #333333;
        --ju-black: #1a1a1a;
        --ju-red: #dc3545;

        /* SEMANTIC COLORS */
        --success: #28a745;
        --success-light: #d4edda;
        --info: var(--ju-blue);
        --info-light: var(--ju-blue-soft);
        --warning: #ffc107;
        --warning-light: #fff3cd;
        --danger: #dc3545;
        --danger-light: #f8d7da;

        /* GRADIENTS - UPDATED WITH ROYAL BLUE */
        --gradient-primary: linear-gradient(145deg, #002789 0%, #001a5c 100%);
        --gradient-primary-light: linear-gradient(145deg, #1a3a9a 0%, #002789 100%);
        --gradient-green: linear-gradient(145deg, var(--ju-green) 0%, var(--ju-green-dark) 100%);
        --gradient-gold: linear-gradient(145deg, var(--ju-gold) 0%, #b8960f 100%);
        --gradient-sidebar: linear-gradient(180deg, #002789 0%, #001a4d 100%);

        /* SHADOWS - UPDATED WITH ROYAL BLUE */
        --shadow-xs: 0 2px 4px rgba(0, 39, 137, 0.02);
        --shadow-sm: 0 4px 6px rgba(0, 39, 137, 0.04);
        --shadow: 0 6px 12px rgba(0, 39, 137, 0.06);
        --shadow-md: 0 8px 24px rgba(0, 39, 137, 0.08);
        --shadow-lg: 0 16px 32px rgba(0, 39, 137, 0.1);
        --shadow-xl: 0 24px 48px rgba(0, 39, 137, 0.12);
        --shadow-2xl: 0 32px 64px rgba(0, 39, 137, 0.15);
        --shadow-sidebar: 8px 0 25px rgba(0, 0, 0, 0.15);

        /* FONTS */
        --font-primary: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        --font-secondary: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;

        /* SPACING */
        --space-1: 0.25rem;
        --space-2: 0.5rem;
        --space-3: 0.75rem;
        --space-4: 1rem;
        --space-5: 1.25rem;
        --space-6: 1.5rem;
        --space-8: 2rem;
        --space-10: 2.5rem;
        --space-12: 3rem;
        --space-16: 4rem;

        /* BORDER RADIUS */
        --radius-sm: 0.25rem;
        --radius: 0.375rem;
        --radius-md: 0.5rem;
        --radius-lg: 0.75rem;
        --radius-xl: 1rem;
        --radius-2xl: 1.25rem;
        --radius-full: 9999px;

        /* TRANSITIONS */
        --transition-fast: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-slow: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-bounce: 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        --transition-elastic: 0.6s cubic-bezier(0.68, -0.6, 0.32, 1.6);

        /* Z-INDEX */
        --z-header: 1000;
        --z-sidebar: 999;
        --z-modal: 1050;
        --z-dropdown: 1020;
        --z-toast: 1060;
        --z-sidebar-toggle: 1001;
    }

    /* ============================================
           BASE STYLES
        ============================================ */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html {
        font-size: 16px;
        scroll-behavior: smooth;
        height: 100%;
    }

    body {
        font-family: var(--font-primary);
        font-weight: 400;
        line-height: 1.6;
        color: var(--ju-gray-dark);
        background: var(--ju-offwhite);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        overflow-x: hidden;
    }

    /* ============================================
           HOVER ANIMATIONS - FULLY PRESERVED
        ============================================ */
    .hover-effect {
        transition: all var(--transition);
        position: relative;
        overflow: hidden;
    }

    .hover-scale {
        transition: transform var(--transition-bounce), box-shadow var(--transition);
    }

    .hover-scale:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-lg);
        z-index: 10;
    }

    .hover-lift {
        transition: transform var(--transition-bounce), box-shadow var(--transition);
    }

    .hover-lift:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-xl);
    }

    .hover-glow {
        transition: box-shadow var(--transition);
    }

    .hover-glow:hover {
        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.2), 0 0 20px rgba(255, 255, 255, 0.3);
    }

    .hover-border {
        position: relative;
    }

    .hover-border::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--ju-white);
        transition: width var(--transition);
    }

    .hover-border:hover::after {
        width: 100%;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .hover-pulse:hover {
        animation: pulse 1.5s infinite;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-5px);
        }

        75% {
            transform: translateX(5px);
        }
    }

    .hover-shake:hover {
        animation: shake 0.5s ease-in-out;
    }

    .hover-rotate {
        transition: transform var(--transition-elastic);
    }

    .hover-rotate:hover {
        transform: rotate(8deg);
    }

    .hover-flip {
        transition: transform var(--transition-elastic);
    }

    .hover-flip:hover {
        transform: perspective(400px) rotateY(10deg);
    }

    .hover-shine {
        position: relative;
        overflow: hidden;
    }

    .hover-shine::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s ease;
    }

    .hover-shine:hover::before {
        left: 100%;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .hover-bounce:hover {
        animation: bounce 0.8s ease;
    }

    @keyframes shadowPulse {
        0% {
            box-shadow: 0 4px 10px rgba(0, 39, 137, 0.1);
        }

        50% {
            box-shadow: 0 8px 25px rgba(0, 39, 137, 0.3);
        }

        100% {
            box-shadow: 0 4px 10px rgba(0, 39, 137, 0.1);
        }
    }

    .hover-shadow-pulse:hover {
        animation: shadowPulse 1.5s infinite;
    }

    .hover-slide-up {
        transition: transform var(--transition-bounce);
    }

    .hover-slide-up:hover {
        transform: translateY(-8px);
    }

    .hover-grow-shadow {
        transition: transform var(--transition), box-shadow var(--transition);
    }

    .hover-grow-shadow:hover {
        transform: scale(1.03);
        box-shadow: 0 20px 40px rgba(0, 39, 137, 0.15);
    }

    .hover-icon-spin i {
        transition: transform var(--transition-bounce);
    }

    .hover-icon-spin:hover i {
        transform: rotate(360deg);
    }

    .hover-underline-center {
        position: relative;
    }

    .hover-underline-center::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        width: 0;
        height: 2px;
        background: var(--ju-white);
        transition: all var(--transition);
        transform: translateX(-50%);
    }

    .hover-underline-center:hover::after {
        width: 80%;
    }

    .hover-bg-highlight {
        transition: background-color var(--transition);
    }

    .hover-bg-highlight:hover {
        background-color: var(--ju-blue-soft);
    }

    .hover-border-color {
        transition: border-color var(--transition);
    }

    .hover-border-color:hover {
        border-color: var(--ju-blue);
    }

    .hover-text-color {
        transition: color var(--transition);
    }

    .hover-text-color:hover {
        color: var(--ju-blue);
    }

    .hover-zoom {
        transition: transform var(--transition-elastic);
    }

    .hover-zoom:hover {
        transform: scale(1.1);
    }

    .hover-fade {
        transition: opacity var(--transition);
        opacity: 0.9;
    }

    .hover-fade:hover {
        opacity: 1;
    }

    /* ============================================
           HEADER - ROYAL BLUE BACKGROUND
        ============================================ */
    .ju-header {
        background: #002789;
        /* Official Royal Blue */
        border-bottom: 3px solid var(--ju-gold);
        box-shadow: var(--shadow-lg);
        position: sticky;
        top: 0;
        z-index: var(--z-header);
        height: 80px;
    }

    .header-container {
        max-width: 1440px;
        margin: 0 auto;
        padding: 0 var(--space-6);
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--space-6);
    }

    .ju-brand {
        display: flex;
        align-items: center;
        gap: var(--space-3);
        text-decoration: none;
        padding: var(--space-2);
        border-radius: var(--radius);
        min-height: 48px;
        flex-shrink: 0;
    }

    .ju-logo {
        width: 50px;
        height: 50px;
        background: var(--ju-white);
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #002789;
        /* Royal Blue */
        font-size: 1.6rem;
        font-weight: 800;
        box-shadow: var(--shadow);
        transition: all var(--transition-bounce);
    }

    .ju-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: var(--radius);
    }

    .ju-brand:hover .ju-logo {
        transform: rotate(-5deg) scale(1.1);
        box-shadow: var(--shadow-xl);
    }

    .ju-title-container {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .ju-main-title {
        font-family: var(--font-secondary);
        font-size: 1.4rem;
        font-weight: 800;
        margin: 0;
        color: var(--ju-white);
        letter-spacing: -0.5px;
    }

    .ju-subtitle {
        font-size: 0.7rem;
        color: var(--ju-white);
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin: 0;
        opacity: 0.9;
    }

    /* Mobile Menu Toggle */
    .mobile-menu-toggle {
        display: none;
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: var(--ju-white);
        font-size: 1.5rem;
        cursor: pointer;
        padding: var(--space-2);
        border-radius: var(--radius);
        width: 44px;
        height: 44px;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
        transition: all var(--transition);
    }

    .mobile-menu-toggle:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    /* Search */
    .header-search {
        flex: 1;
        max-width: 500px;
        position: relative;
    }

    .header-search input {
        width: 100%;
        padding: var(--space-3) var(--space-4) var(--space-3) var(--space-12);
        border: none;
        border-radius: 40px;
        font-size: 0.9rem;
        color: var(--ju-gray-dark);
        background: var(--ju-white);
        transition: all var(--transition);
        box-shadow: var(--shadow-md);
        min-height: 48px;
    }

    .header-search input:focus {
        outline: none;
        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.3);
        transform: translateY(-1px);
    }

    .header-search i {
        position: absolute;
        left: var(--space-4);
        top: 50%;
        transform: translateY(-50%);
        color: var(--ju-gray-dark);
        font-size: 1.1rem;
        pointer-events: none;
    }

    /* User Menu */
    .user-menu-container {
        display: flex;
        align-items: center;
        gap: var(--space-4);
        flex-shrink: 0;
    }

    .notification-bell {
        position: relative;
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: var(--ju-white);
        font-size: 1.25rem;
        cursor: pointer;
        padding: var(--space-2);
        border-radius: 50%;
        transition: all var(--transition);
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }

    .notification-bell:hover {
        transform: rotate(15deg);
        background: rgba(255, 255, 255, 0.25);
    }

    .notification-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        background: var(--danger);
        color: var(--ju-white);
        font-size: 0.65rem;
        font-weight: 700;
        min-width: 20px;
        height: 20px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        border: 2px solid var(--ju-white);
        box-shadow: var(--shadow-sm);
        animation: pulse 2s infinite;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: var(--space-3);
        padding: var(--space-2) var(--space-3);
        border-radius: 40px;
        transition: all var(--transition-bounce);
        cursor: pointer;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
        min-height: 48px;
        backdrop-filter: blur(5px);
    }

    .user-profile:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: var(--ju-white);
        transform: translateY(-3px);
        box-shadow: var(--shadow-xl);
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        background: var(--ju-white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #002789;
        /* Royal Blue */
        font-weight: 700;
        font-size: 1rem;
        box-shadow: var(--shadow-sm);
        transition: all var(--transition);
    }

    .user-profile:hover .user-avatar {
        transform: scale(1.15);
        box-shadow: var(--shadow-md);
    }

    .user-details {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--ju-white);
        white-space: nowrap;
    }

    .user-role {
        font-size: 0.7rem;
        color: var(--ju-white);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.9;
    }

    .logout-btn {
        background: rgba(255, 255, 255, 0.15);
        color: var(--ju-white);
        border: 2px solid rgba(255, 255, 255, 0.3);
        padding: var(--space-2) var(--space-5);
        border-radius: 40px;
        font-size: 0.9rem;
        font-weight: 600;
        min-height: 44px;
        transition: all var(--transition-bounce);
        backdrop-filter: blur(5px);
    }

    .logout-btn:hover {
        background: var(--danger);
        border-color: var(--danger);
        color: var(--ju-white);
        animation: shake 0.5s ease-in-out;
    }

    .guest-nav {
        display: flex;
        align-items: center;
        gap: var(--space-4);
    }

    .auth-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-2);
        padding: var(--space-3) var(--space-6);
        border-radius: 40px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        transition: all var(--transition-bounce);
        border: none;
        cursor: pointer;
        min-height: 48px;
        min-width: 120px;
        box-shadow: var(--shadow-md);
    }

    .auth-btn-login {
        background: var(--ju-white);
        color: #002789;
        /* Royal Blue */
    }

    .auth-btn-login:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-xl);
    }

    .auth-btn-register {
        background: transparent;
        color: var(--ju-white);
        border: 2px solid var(--ju-white);
    }

    .auth-btn-register:hover {
        background: var(--ju-white);
        color: #002789;
        /* Royal Blue */
        transform: scale(1.05);
    }

    /* ============================================
           DISTINCT SIDEBAR - ROYAL BLUE WITH GOLD ACCENTS
        ============================================ */
    .main-layout {
        display: flex;
        flex: 1;
        min-height: calc(100vh - 80px);
        position: relative;
    }

    .ju-sidebar {
        width: 300px;
        background: linear-gradient(180deg, #002789 0%, #001a5c 100%);
        border-right: 3px solid var(--ju-gold);
        position: sticky;
        top: 80px;
        height: calc(100vh - 80px);
        overflow-y: auto;
        flex-shrink: 0;
        padding: var(--space-6) 0;
        box-shadow: var(--shadow-sidebar);
        z-index: var(--z-sidebar);
        transition: left var(--transition);
    }

    /* Custom Scrollbar */
    .ju-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .ju-sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    .ju-sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        transition: all var(--transition);
    }

    .ju-sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.4);
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .menu-header {
        padding: var(--space-4) var(--space-6) var(--space-2);
        color: var(--ju-gold);
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-top: var(--space-6);
        position: relative;
        display: flex;
        align-items: center;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .menu-header::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 16px;
        background: var(--ju-gold);
        margin-right: var(--space-2);
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(196, 167, 71, 0.4);
    }

    .menu-item {
        margin: 4px var(--space-3);
        position: relative;
    }

    .menu-link {
        display: flex;
        align-items: center;
        gap: var(--space-3);
        padding: var(--space-3) var(--space-4);
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.25s ease;
        border-radius: var(--radius);
        position: relative;
        overflow: hidden;
        min-height: 48px;
        border-left: 3px solid transparent;
        background: transparent;
    }

    .menu-link:hover {
        color: var(--ju-white);
        background: rgba(255, 255, 255, 0.1);
        border-left-color: var(--ju-gold);
        transform: translateX(5px);
    }

    .menu-link.active {
        color: var(--ju-white);
        background: rgba(196, 167, 71, 0.15);
        border-left: 3px solid var(--ju-gold);
        font-weight: 600;
        box-shadow: inset 0 0 20px rgba(196, 167, 71, 0.1);
    }

    .menu-icon {
        width: 24px;
        text-align: center;
        font-size: 1.1rem;
        color: var(--ju-gold);
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }

    .menu-link:hover .menu-icon,
    .menu-link.active .menu-icon {
        transform: scale(1.15);
        color: var(--ju-gold-light);
    }

    .menu-title {
        flex: 1;
        position: relative;
        z-index: 1;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .menu-badge {
        background: var(--danger);
        color: var(--ju-white);
        font-size: 0.65rem;
        padding: 3px 8px;
        border-radius: 20px;
        font-weight: 700;
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
        position: relative;
        z-index: 1;
        border: 1px solid rgba(255, 255, 255, 0.2);
        letter-spacing: 0.5px;
    }

    .menu-arrow {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.6);
        transition: all 0.3s ease;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        z-index: 1;
    }

    .menu-link:hover .menu-arrow,
    .menu-collapse[aria-expanded="true"] .menu-arrow {
        transform: rotate(90deg);
        color: var(--ju-gold);
        background: rgba(255, 255, 255, 0.1);
    }

    .sub-menu {
        list-style: none;
        padding: 0.5rem 0;
        margin: 0.25rem 0 0.5rem calc(var(--space-6) + 10px);
        background: rgba(0, 0, 0, 0.25);
        border-left: 2px solid var(--ju-gold);
        border-radius: 0 var(--radius) var(--radius) 0;
        position: relative;
        backdrop-filter: blur(2px);
    }

    .sub-menu .nav-link {
        display: flex;
        align-items: center;
        gap: var(--space-3);
        padding: 0.6rem var(--space-4) 0.6rem var(--space-6);
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 400;
        transition: all 0.25s ease;
        border-left: 2px solid transparent;
        min-height: 40px;
        position: relative;
    }

    .sub-menu .nav-link i {
        color: var(--ju-gold);
        font-size: 0.85rem;
        width: 18px;
        text-align: center;
        filter: drop-shadow(0 2px 2px rgba(0, 0, 0, 0.2));
    }

    .sub-menu .nav-link:hover {
        color: var(--ju-white);
        background: rgba(255, 255, 255, 0.08);
        border-left-color: var(--ju-gold);
        transform: translateX(3px);
    }

    .sub-menu .nav-link.active {
        color: var(--ju-white);
        background: rgba(196, 167, 71, 0.12);
        border-left-color: var(--ju-gold);
        font-weight: 600;
    }

    /* Sidebar Overlay */
    .ju-sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 998;
        backdrop-filter: blur(3px);
        opacity: 0;
        transition: opacity var(--transition);
    }

    .ju-sidebar-overlay.active {
        display: block;
        opacity: 1;
    }

    /* ============================================
           MAIN CONTENT AREA - LIGHT BACKGROUND
        ============================================ */
    .ju-main-content {
        flex: 1;
        padding: var(--space-8);
        background: var(--ju-offwhite);
        min-height: calc(100vh - 80px);
        overflow-y: auto;
        position: relative;
        z-index: 1;
        width: 100%;
    }

    .content-header {
        margin-bottom: var(--space-8);
        padding-bottom: var(--space-6);
        border-bottom: 2px solid #002789;
        /* Royal Blue */
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: var(--space-4);
        background: transparent;
    }

    .page-title {
        font-family: var(--font-secondary);
        font-size: 2.2rem;
        font-weight: 800;
        color: #002789;
        /* Royal Blue */
        margin-bottom: var(--space-2);
        letter-spacing: -1px;
        line-height: 1.2;
    }

    .page-subtitle {
        color: var(--ju-gray-dark);
        font-size: 1.1rem;
        font-weight: 400;
        max-width: 700px;
        opacity: 0.8;
    }

    .breadcrumb-nav {
        padding: var(--space-2) var(--space-4);
        background: var(--ju-white);
        border-radius: 40px;
        box-shadow: var(--shadow-sm);
    }

    .breadcrumb-item a {
        color: #002789;
        /* Royal Blue */
        text-decoration: none;
        font-weight: 500;
        transition: all var(--transition);
    }

    .breadcrumb-item a:hover {
        color: var(--ju-blue-dark);
    }

    /* ============================================
           CARDS & COMPONENTS - FULLY PRESERVED
        ============================================ */
    .ju-card {
        background: var(--ju-white);
        border: 1px solid var(--ju-gray);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        transition: all var(--transition-bounce);
        overflow: hidden;
        position: relative;
        margin-bottom: var(--space-6);
    }

    .ju-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-2xl);
        border-color: #002789;
        /* Royal Blue */
    }

    .ju-card-header {
        padding: var(--space-6);
        border-bottom: 1px solid var(--ju-gray);
        background: linear-gradient(180deg, var(--ju-white) 0%, var(--ju-offwhite) 100%);
    }

    .ju-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #002789;
        /* Royal Blue */
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-2);
    }

    .ju-card-body {
        padding: var(--space-6);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: var(--space-6);
        margin-bottom: var(--space-8);
    }

    .stat-card {
        color: var(--ju-white);
        border-radius: var(--radius-lg);
        padding: var(--space-8);
        text-align: center;
        transition: all var(--transition-bounce);
        border: none;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .stat-card-primary {
        background: linear-gradient(145deg, #002789 0%, #001a5c 100%);
    }

    .stat-card-green {
        background: var(--gradient-green);
    }

    .stat-card-gold {
        background: var(--gradient-gold);
    }

    .stat-card-success {
        background: var(--success);
    }

    .stat-card-warning {
        background: var(--warning);
        color: var(--ju-black);
    }

    .stat-card-danger {
        background: var(--danger);
    }

    .stat-icon {
        font-size: 2.8rem;
        margin-bottom: var(--space-4);
        opacity: 0.9;
        filter: drop-shadow(0 8px 12px rgba(0, 0, 0, 0.2));
        transition: all var(--transition);
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.2) rotate(5deg);
    }

    .stat-number {
        font-size: 3.2rem;
        font-weight: 800;
        margin-bottom: var(--space-2);
        font-family: var(--font-secondary);
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        line-height: 1;
    }

    .stat-label {
        font-size: 1rem;
        opacity: 0.95;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .ju-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-2);
        padding: var(--space-3) var(--space-6);
        border-radius: 40px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        transition: all var(--transition-bounce);
        border: none;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        min-height: 44px;
        box-shadow: var(--shadow-sm);
    }

    .ju-btn-primary {
        background: linear-gradient(145deg, #002789 0%, #001a5c 100%);
        color: var(--ju-white);
    }

    .ju-btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-xl);
    }

    .ju-btn-outline {
        background: transparent;
        color: #002789;
        /* Royal Blue */
        border: 2px solid #002789;
    }

    .ju-btn-outline:hover {
        background: #002789;
        color: var(--ju-white);
        transform: scale(1.05);
    }

    .action-btn {
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all var(--transition-bounce);
        box-shadow: var(--shadow-sm);
        color: var(--ju-white);
        margin: 0 2px;
    }

    .action-btn-view {
        background: #002789;
    }

    /* Royal Blue */
    .action-btn-edit {
        background: var(--warning);
        color: var(--ju-black);
    }

    .action-btn-delete {
        background: var(--danger);
    }

    .action-btn-approve {
        background: var(--success);
    }

    .action-btn-reject {
        background: var(--danger);
    }

    .action-btn:hover {
        transform: rotate(15deg) scale(1.15);
        box-shadow: var(--shadow-lg);
    }

    .ju-table {
        width: 100%;
        margin-bottom: 0;
    }

    .ju-table thead th {
        background: #e6ebf7;
        /* Soft blue */
        color: #001a5c;
        /* Dark Royal Blue */
        font-weight: 700;
        padding: var(--space-4);
        border-bottom: 2px solid #002789;
        /* Royal Blue */
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .ju-table tbody td {
        padding: var(--space-4);
        border-bottom: 1px solid var(--ju-gray);
        color: var(--ju-gray-dark);
        vertical-align: middle;
    }

    .ju-table tbody tr:hover td {
        background: #e6ebf7;
        /* Soft blue */
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* ============================================
           FOOTER
        ============================================ */
    .ju-footer {
        background: #001a5c;
        /* Dark Royal Blue */
        color: var(--ju-white);
        padding: var(--space-8) 0;
        margin-top: auto;
        position: relative;
        border-top: 3px solid var(--ju-gold);
    }

    .footer-container {
        max-width: 1440px;
        margin: 0 auto;
        padding: 0 var(--space-6);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: var(--space-6);
    }

    .footer-copyright {
        color: var(--ju-white);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: var(--space-2);
        opacity: 0.9;
    }

    .footer-links {
        display: flex;
        gap: var(--space-6);
        flex-wrap: wrap;
    }

    .footer-link {
        color: var(--ju-white);
        text-decoration: none;
        font-size: 0.9rem;
        transition: all var(--transition);
        position: relative;
        padding: var(--space-1) 0;
        opacity: 0.9;
    }

    .footer-link:hover {
        color: var(--ju-gold);
    }

    /* ============================================
           TOASTS
        ============================================ */
    .toast-container {
        position: fixed;
        top: 90px;
        right: var(--space-6);
        z-index: var(--z-toast);
        max-width: 400px;
        width: 100%;
    }

    .ju-toast {
        background: var(--ju-white);
        border-left: 4px solid;
        border-radius: var(--radius);
        box-shadow: var(--shadow-xl);
        padding: var(--space-4);
        margin-bottom: var(--space-3);
        animation: slideInRight 0.3s ease-out;
        transition: all var(--transition);
    }

    .ju-toast-success {
        border-left-color: var(--success);
    }

    .ju-toast-error {
        border-left-color: var(--danger);
    }

    .ju-toast-warning {
        border-left-color: var(--warning);
    }

    .ju-toast-info {
        border-left-color: #002789;
    }

    /* Royal Blue */

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
           RESPONSIVE DESIGN - PROFESSIONAL BREAKPOINTS
           ALL FUNCTIONALITIES PRESERVED
        ============================================ */

    /* Large Desktop (1400px and up) */
    @media (min-width: 1400px) {
        .ju-sidebar {
            width: 300px;
        }

        .ju-main-content {
            padding: var(--space-8);
        }

        .page-title {
            font-size: 2.2rem;
        }
    }

    /* Desktop (1200px to 1399px) */
    @media (min-width: 1200px) and (max-width: 1399px) {
        .ju-sidebar {
            width: 280px;
        }

        .ju-main-content {
            padding: var(--space-6);
        }

        .page-title {
            font-size: 2rem;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }
    }

    /* Laptop (992px to 1199px) */
    @media (min-width: 992px) and (max-width: 1199px) {
        .ju-sidebar {
            width: 260px;
        }

        .ju-main-content {
            padding: var(--space-6);
        }

        .page-title {
            font-size: 1.8rem;
        }

        .ju-main-title {
            font-size: 1.2rem;
        }

        .ju-subtitle {
            font-size: 0.65rem;
        }

        .user-details {
            display: none;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .stat-number {
            font-size: 2.5rem;
        }
    }

    /* Tablet Landscape (768px to 991px) */
    @media (min-width: 768px) and (max-width: 991px) {
        .ju-header {
            height: auto;
            min-height: 80px;
        }

        .header-container {
            flex-wrap: wrap;
            padding: var(--space-4);
            gap: var(--space-3);
        }

        .ju-brand {
            order: 1;
        }

        .mobile-menu-toggle {
            display: flex;
            order: 2;
        }

        .user-menu-container {
            order: 3;
            width: auto;
        }

        .header-search {
            order: 4;
            max-width: 100%;
            width: 100%;
            margin-top: var(--space-2);
        }

        .ju-sidebar {
            position: fixed;
            left: -300px;
            top: 80px;
            width: 280px;
            height: calc(100vh - 80px);
            z-index: var(--z-sidebar);
            transition: left var(--transition);
            border-right: 3px solid var(--ju-gold);
            box-shadow: none;
        }

        .ju-sidebar.mobile-open {
            left: 0;
            box-shadow: var(--shadow-sidebar);
        }

        .ju-sidebar-overlay {
            display: block;
        }

        .ju-sidebar-overlay.active {
            display: block;
        }

        .ju-main-content {
            padding: var(--space-5);
            width: 100%;
        }

        .page-title {
            font-size: 1.6rem;
        }

        .page-subtitle {
            font-size: 1rem;
        }

        .content-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .breadcrumb-nav {
            align-self: flex-start;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: var(--space-4);
        }

        .stat-card {
            min-height: 180px;
            padding: var(--space-6);
        }

        .stat-number {
            font-size: 2.2rem;
        }

        .stat-icon {
            font-size: 2.2rem;
        }

        .user-details {
            display: none;
        }

        .guest-nav {
            gap: var(--space-2);
        }

        .auth-btn {
            min-width: 100px;
            padding: var(--space-2) var(--space-4);
        }
    }

    /* Tablet Portrait (576px to 767px) */
    @media (min-width: 576px) and (max-width: 767px) {
        .ju-header {
            height: auto;
            min-height: 70px;
        }

        .header-container {
            flex-wrap: wrap;
            padding: var(--space-3);
            gap: var(--space-2);
        }

        .ju-brand {
            order: 1;
        }

        .ju-logo {
            width: 45px;
            height: 45px;
            font-size: 1.4rem;
        }

        .ju-main-title {
            font-size: 1.1rem;
        }

        .ju-subtitle {
            font-size: 0.6rem;
        }

        .mobile-menu-toggle {
            display: flex;
            order: 2;
        }

        .user-menu-container {
            order: 3;
            width: auto;
        }

        .header-search {
            order: 4;
            max-width: 100%;
            width: 100%;
            margin-top: var(--space-2);
        }

        .ju-sidebar {
            position: fixed;
            left: -300px;
            top: 70px;
            width: 280px;
            height: calc(100vh - 70px);
            z-index: var(--z-sidebar);
            transition: left var(--transition);
            border-right: 3px solid var(--ju-gold);
        }

        .ju-sidebar.mobile-open {
            left: 0;
            box-shadow: var(--shadow-sidebar);
        }

        .ju-sidebar-overlay {
            display: block;
        }

        .ju-sidebar-overlay.active {
            display: block;
        }

        .ju-main-content {
            padding: var(--space-4);
            width: 100%;
        }

        .page-title {
            font-size: 1.4rem;
        }

        .page-subtitle {
            font-size: 0.9rem;
        }

        .content-header {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-3);
            margin-bottom: var(--space-6);
            padding-bottom: var(--space-4);
        }

        .breadcrumb-nav {
            align-self: flex-start;
            width: 100%;
        }

        .breadcrumb {
            font-size: 0.8rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: var(--space-4);
        }

        .stat-card {
            min-height: 160px;
            padding: var(--space-5);
        }

        .stat-number {
            font-size: 2rem;
        }

        .stat-icon {
            font-size: 2rem;
        }

        .stat-label {
            font-size: 0.9rem;
        }

        .user-details {
            display: none;
        }

        .notification-bell {
            display: none;
        }

        .logout-btn span {
            display: none;
        }

        .logout-btn i {
            margin: 0;
        }

        .logout-btn {
            padding: var(--space-2) var(--space-3);
        }

        .guest-nav {
            flex-direction: column;
            width: 100%;
            gap: var(--space-2);
        }

        .auth-btn {
            width: 100%;
            min-width: auto;
            padding: var(--space-2);
        }

        .footer-container {
            flex-direction: column;
            text-align: center;
            gap: var(--space-4);
        }

        .footer-links {
            justify-content: center;
            gap: var(--space-4);
        }
    }

    /* Mobile (575px and below) */
    @media (max-width: 575px) {
        .ju-header {
            height: auto;
            min-height: 60px;
        }

        .header-container {
            flex-wrap: wrap;
            padding: var(--space-2);
            gap: var(--space-2);
        }

        .ju-brand {
            order: 1;
            gap: var(--space-2);
        }

        .ju-logo {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }

        .ju-main-title {
            font-size: 0.95rem;
            letter-spacing: -0.3px;
        }

        .ju-subtitle {
            font-size: 0.55rem;
            letter-spacing: 0.5px;
        }

        .mobile-menu-toggle {
            display: flex;
            order: 2;
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }

        .user-menu-container {
            order: 3;
            width: auto;
            gap: var(--space-2);
        }

        .header-search {
            order: 4;
            max-width: 100%;
            width: 100%;
            margin-top: var(--space-1);
        }

        .header-search input {
            min-height: 40px;
            font-size: 0.85rem;
        }

        .ju-sidebar {
            position: fixed;
            left: -100%;
            top: 60px;
            width: 85%;
            max-width: 280px;
            height: calc(100vh - 60px);
            z-index: var(--z-sidebar);
            transition: left var(--transition);
            border-right: 3px solid var(--ju-gold);
            padding: var(--space-4) 0;
        }

        .ju-sidebar.mobile-open {
            left: 0;
            box-shadow: var(--shadow-sidebar);
        }

        .ju-sidebar-overlay {
            display: block;
        }

        .ju-sidebar-overlay.active {
            display: block;
        }

        .menu-header {
            padding: var(--space-3) var(--space-4) var(--space-1);
            font-size: 0.7rem;
            margin-top: var(--space-4);
        }

        .menu-link {
            padding: var(--space-2) var(--space-3);
            min-height: 44px;
            font-size: 0.85rem;
        }

        .menu-icon {
            width: 22px;
            font-size: 0.95rem;
        }

        .menu-badge {
            font-size: 0.6rem;
            padding: 2px 6px;
        }

        .sub-menu {
            margin-left: calc(var(--space-5) + 15px);
        }

        .sub-menu .nav-link {
            padding: 0.5rem var(--space-3) 0.5rem var(--space-4);
            font-size: 0.8rem;
        }

        .ju-main-content {
            padding: var(--space-3);
            width: 100%;
        }

        .page-title {
            font-size: 1.2rem;
        }

        .page-subtitle {
            font-size: 0.85rem;
        }

        .content-header {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-3);
            margin-bottom: var(--space-4);
            padding-bottom: var(--space-3);
        }

        .breadcrumb-nav {
            align-self: flex-start;
            width: 100%;
            padding: var(--space-2) var(--space-3);
        }

        .breadcrumb {
            font-size: 0.75rem;
        }

        .breadcrumb-item a {
            font-size: 0.75rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: var(--space-3);
            margin-bottom: var(--space-5);
        }

        .stat-card {
            padding: var(--space-4);
            min-height: 140px;
        }

        .stat-icon {
            font-size: 1.8rem;
            margin-bottom: var(--space-2);
        }

        .stat-number {
            font-size: 1.8rem;
            margin-bottom: var(--space-1);
        }

        .stat-label {
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .ju-card-header {
            padding: var(--space-4);
        }

        .ju-card-body {
            padding: var(--space-4);
        }

        .ju-card-title {
            font-size: 1.1rem;
        }

        .ju-btn {
            padding: var(--space-2) var(--space-4);
            min-height: 40px;
            font-size: 0.85rem;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            font-size: 0.85rem;
        }

        .ju-table thead th {
            padding: var(--space-2) var(--space-3);
            font-size: 0.75rem;
        }

        .ju-table tbody td {
            padding: var(--space-2) var(--space-3);
            font-size: 0.8rem;
        }

        .user-details {
            display: none;
        }

        .user-profile {
            padding: var(--space-1);
            min-height: 40px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            font-size: 0.9rem;
        }

        .notification-bell {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }

        .notification-badge {
            min-width: 18px;
            height: 18px;
            font-size: 0.55rem;
        }

        .logout-btn {
            padding: var(--space-1) var(--space-3);
            min-height: 40px;
        }

        .logout-btn span {
            display: none;
        }

        .logout-btn i {
            margin: 0;
        }

        .guest-nav {
            flex-direction: column;
            width: 100%;
            gap: var(--space-2);
        }

        .auth-btn {
            width: 100%;
            min-width: auto;
            padding: var(--space-1) var(--space-3);
            min-height: 40px;
            font-size: 0.85rem;
        }

        .footer-container {
            flex-direction: column;
            text-align: center;
            padding: 0 var(--space-4);
            gap: var(--space-3);
        }

        .footer-copyright {
            font-size: 0.8rem;
            flex-direction: column;
            gap: var(--space-1);
        }

        .footer-links {
            justify-content: center;
            gap: var(--space-3);
        }

        .footer-link {
            font-size: 0.8rem;
        }

        .toast-container {
            top: 70px;
            right: var(--space-3);
            max-width: calc(100% - var(--space-6));
        }

        .ju-toast {
            padding: var(--space-3);
            font-size: 0.85rem;
        }
    }

    /* Small Mobile (375px and below) */
    @media (max-width: 375px) {
        .ju-main-title {
            font-size: 0.85rem;
        }

        .ju-logo {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }

        .ju-subtitle {
            font-size: 0.5rem;
        }

        .page-title {
            font-size: 1.1rem;
        }

        .page-subtitle {
            font-size: 0.8rem;
        }

        .ju-sidebar {
            width: 90%;
        }

        .user-profile {
            display: none;
        }

        .notification-bell {
            display: none;
        }

        .logout-btn {
            padding: var(--space-1) var(--space-2);
        }

        .stat-number {
            font-size: 1.6rem;
        }
    }

    /* Print Styles */
    @media print {

        .ju-header,
        .ju-sidebar,
        .ju-footer,
        .toast-container,
        .mobile-menu-toggle,
        .ju-sidebar-overlay {
            display: none !important;
        }

        .main-layout {
            display: block;
        }

        .ju-main-content {
            padding: 0 !important;
            margin: 0 !important;
        }

        .ju-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            break-inside: avoid;
        }
    }

    .user-profile-btn {
        background: transparent;
        border: none;
        padding: 0;
    }

    .user-profile-btn:hover {
        background: transparent;
    }

    .user-profile-btn::after {
        display: none;
    }

    .dropdown-item {
        padding: 0.6rem 1.2rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f0f7ff;
        color: #002789;
        transform: translateX(5px);
    }

    .dropdown-item.active {
        background: linear-gradient(135deg, #002789 0%, #001a5c 100%);
        color: white;
    }

    .dropdown-item i {
        color: #002789;
    }

    .dropdown-item:hover i {
        color: #002789;
    }

    .dropdown-item.active i {
        color: white;
    }

    .dropdown-header {
        border-radius: 0.375rem 0.375rem 0 0;
    }

    .text-white-50 {
        color: rgba(255, 255, 255, 0.7) !important;
    }


    .notification-dropdown {
        animation: notificationSlide 0.3s ease;
    }

    @keyframes notificationSlide {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .notification-body::-webkit-scrollbar {
        width: 6px;
    }

    .notification-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .notification-body::-webkit-scrollbar-thumb {
        background: #002789;
        border-radius: 10px;
    }

    .notification-body::-webkit-scrollbar-thumb:hover {
        background: #001a5c;
    }

    .notification-footer .btn:hover {
        background: #002789 !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 39, 137, 0.2);
    }

    .text-white-50 {
        color: rgba(255, 255, 255, 0.7) !important;
    }


    .nav-section-title {
        padding: 0.5rem 1rem 0.25rem 2rem;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6c757d;
        border-bottom: 1px solid rgba(0, 39, 137, 0.1);
        margin-top: 0.5rem;
    }

    .nav-section-title:first-of-type {
        margin-top: 0;
    }

    .nav-section-text {
        font-weight: 600;
    }

    .menu-badge {
        float: right;
        padding: 0.2rem 0.5rem;
        border-radius: 10px;
        font-size: 0.7rem;
        font-weight: 600;
        color: white;
        background-color: #002789;
        margin-top: 0.15rem;
    }

    .hover-pulse:hover {
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .nav-link {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .nav-link:hover {
        background-color: rgba(0, 39, 137, 0.05);
        border-left-color: #002789;
    }

    .nav-link.active {
        background-color: rgba(0, 39, 137, 0.1);
        border-left-color: #002789;
        font-weight: 500;
    }

    .nav-link i {
        width: 20px;
        text-align: center;
    }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Header -->
    <header class="ju-header">
        <div class="header-container">
            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle" type="button" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Brand -->
            <a href="{{ route('home') }}" class="ju-brand hover-scale">
                <div class="ju-logo">
                    <img src="{{ asset('images/jimma-logo.png') }}" alt="Jimma University Logo">
                </div>
                <div class="ju-title-container">
                    <h1 class="ju-main-title">Jimma University</h1>
                    <p class="ju-subtitle">Event Management System</p>
                </div>
            </a>

            <!-- Search -->
            <div class="header-search d-none d-lg-block">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search events, announcements, or users..." id="globalSearch"
                    class="hover-glow">
            </div>

            <!-- User Menu -->
            <div class="user-menu-container">
                @auth
                <!-- Notification Bell -->
                <div class="dropdown">
                    <button class="notification-bell hover-rotate dropdown-toggle" type="button"
                        id="notificationDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @php
                        $unreadCount = auth()->user()->unread_notifications_count ?? 0;
                        @endphp
                        @if($unreadCount > 0)
                        <span class="notification-badge">{{ $unreadCount }}</span>
                        @endif
                    </button>

                    <!-- Notifications Dropdown -->
                    <div class="dropdown-menu notification-dropdown dropdown-menu-end"
                        aria-labelledby="notificationDropdown"
                        style="width: 360px; padding: 0; border: none; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                        <!-- Header -->
                        <div class="notification-header"
                            style="background: linear-gradient(135deg, #002789 0%, #001a5c 100%); padding: 1.25rem; border-radius: 12px 12px 0 0;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 text-white fw-semibold">
                                        <i class="fas fa-bell me-2"></i>Notifications
                                    </h6>
                                    <small class="text-white-50">Latest 3 notifications</small>
                                </div>
                            </div>
                        </div>

                        <!-- Notification List -->
                        <div class="notification-body" id="notificationList"
                            style="max-height: 380px; overflow-y: auto; background: #fff;">
                            <!-- Loading State -->
                            <div class="text-center py-5" id="loadingNotifications">
                                <div class="spinner-border" style="color: #002789; width: 2.5rem; height: 2.5rem;"
                                    role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-3 text-muted mb-0">Fetching your notifications...</p>
                            </div>

                            <!-- Empty State (hidden by default) -->
                            <div class="text-center py-5 d-none" id="emptyNotifications">
                                <div
                                    style="width: 80px; height: 80px; background: #f0f7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                    <i class="fas fa-bell-slash fa-3x" style="color: #002789; opacity: 0.5;"></i>
                                </div>
                                <h6 class="fw-semibold mb-2">No New Notifications Yet</h6>
                                <p class="text-muted small mb-0 px-4">You're all caught up! Check back later for
                                    updates.</p>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="notification-footer p-3"
                            style="background: #f8f9fa; border-top: 1px solid #e9ecef; border-radius: 0 0 12px 12px;">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn flex-grow-1" id="showMoreNotificationsBtn"
                                    style="background: white; border: 1px solid #002789; color: #002789; border-radius: 8px; padding: 0.6rem; font-weight: 500; transition: all 0.3s ease;">
                                    <i class="fas fa-chevron-down me-2"></i>Show More
                                </button>
                                <button type="button" class="btn flex-grow-1" id="showLessNotificationsBtn"
                                    style="display: none; background: white; border: 1px solid #002789; color: #002789; border-radius: 8px; padding: 0.6rem; font-weight: 500; transition: all 0.3s ease;">
                                    <i class="fas fa-chevron-up me-2"></i>Show Less
                                </button>
                                <button type="button" class="btn flex-grow-1" id="clearAllNotificationsBtn"
                                    style="display: none; background: #fff5f5; border: 1px solid #dc3545; color: #dc3545; border-radius: 8px; padding: 0.6rem; font-weight: 500; transition: all 0.3s ease;">
                                    <i class="fas fa-trash-alt me-2"></i>Clear All
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="user-profile-btn dropdown-toggle" type="button" id="profileDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-profile hover-lift">
                            <div class="user-avatar">
                                @if(auth()->user()->profile_photo_url)
                                <img src="{{ auth()->user()->profile_photo_url }}"
                                    alt="{{ auth()->user()->name }}"
                                    style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="user-details">
                                <div class="user-name">{{ auth()->user()->name }}</div>
                                <div class="user-role">
                                    @if(auth()->user()->role)
                                    {{ auth()->user()->role->name }}
                                    @else
                                    User
                                    @endif
                                </div>
                            </div>
                            <i class="fas fa-chevron-down ml-2" style="font-size: 0.75rem; color: var(--ju-white);"></i>
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown"
                        style="min-width: 250px; padding: 0.5rem 0;">
                        <!-- Profile Header -->
                        <li class="dropdown-header"
                            style="background: linear-gradient(135deg, #002789 0%, #001a5c 100%); color: white; padding: 1rem;">
                            <div class="d-flex align-items-center gap-3">
                                <div
                                    style="width: 50px; height: 50px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #002789; font-size: 1.5rem;">
                                    @if(auth()->user()->profile_photo_url)
                                    <img src="{{ auth()->user()->profile_photo_url }}"
                                        alt="{{ auth()->user()->name }}"
                                        style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                    @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-0 text-white">{{ auth()->user()->name }}</h6>
                                    <small class="text-white-50">{{ auth()->user()->email }}</small>
                                </div>
                            </div>
                        </li>

                        <!-- Menu Items -->
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('profile.show') ? 'active' : '' }}"
                                href="{{ route('profile.show') }}">
                                <i class="fas fa-user-circle me-2" style="width: 20px;"></i> My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
                                href="{{ route('profile.edit') }}">
                                <i class="fas fa-edit me-2" style="width: 20px;"></i> Edit Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('my-events.index') }}">
                                <i class="fas fa-calendar-alt me-2" style="width: 20px;"></i> My Events
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}#account-security">
                                <i class="fas fa-key me-2" style="width: 20px;"></i> Change Password
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2" style="width: 20px;"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="d-inline" id="logoutForm">
                    @csrf
                    <button type="submit" class="btn logout-btn hover-shake">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
                @else
                <!-- Guest Navigation -->
                <div class="guest-nav">
                    <a href="{{ route('login') }}" class="auth-btn auth-btn-login hover-shine">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="auth-btn auth-btn-register hover-scale">
                        <i class="fas fa-user-plus"></i>
                        <span>Register</span>
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Toast Container -->
    <div class="toast-container">
        @if(session('success'))
        <div class="ju-toast ju-toast-success animate__animated animate__fadeInRight" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle fa-lg text-success"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="fw-bold mb-1">Success!</div>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" onclick="this.closest('.ju-toast').remove()"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="ju-toast ju-toast-error animate__animated animate__fadeInRight" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle fa-lg text-danger"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="fw-bold mb-1">Error!</div>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" onclick="this.closest('.ju-toast').remove()"></button>
            </div>
        </div>
        @endif

        @if(session('info'))
        <div class="ju-toast ju-toast-info animate__animated animate__fadeInRight" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle fa-lg text-info"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="fw-bold mb-1">Information</div>
                    <div>{{ session('info') }}</div>
                </div>
                <button type="button" class="btn-close" onclick="this.closest('.ju-toast').remove()"></button>
            </div>
        </div>
        @endif
    </div>

    <!-- Main Layout -->
    <div class="main-layout">
        @auth
        @php
        $sidebarUser = auth()->user();
        $isStudentUser = $sidebarUser && $sidebarUser->hasRole('student');
        $isFacultyUser = $sidebarUser && (
            $sidebarUser->hasRole('faculty') ||
            $sidebarUser->hasRole('faculty-member')
        );
        $isApproverUser = $sidebarUser && (
            $sidebarUser->hasRole('super-admin') ||
            $sidebarUser->hasRole('super-administrator') ||
            $sidebarUser->hasRole('administrator') ||
            $sidebarUser->hasRole('admin') ||
            $sidebarUser->isAdmin()
        );
        $isEventManagerUser = $sidebarUser && $sidebarUser->hasRole('event-manager');
        $showTopEventRequestLinks = $sidebarUser && !$isApproverUser && !$isEventManagerUser;
        @endphp
        <!-- Sidebar - ROYAL BLUE WITH GOLD ACCENTS -->
        <nav class="ju-sidebar" id="mainSidebar">
            <ul class="sidebar-menu">
                <!-- DASHBOARD -->
                @if(!$isStudentUser && !$isFacultyUser && !auth()->user()->hasRole('event-manager') && auth()->user()->hasPermission('view_dashboard'))
                <li class="menu-header">Dashboard</li>
                <li class="menu-item">
                    <a href="{{ route('dashboard') }}"
                        class="menu-link hover-slide-up {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-tachometer-alt"></i>
                        <span class="menu-title">Admin Dashboard</span>
                    </a>
                </li>
                @endif

                <!-- EVENT MANAGEMENT -->
                <li class="menu-header">Events</li>

                <!-- Browse Events -->
                <li class="menu-item">
                    <a href="{{ route('home') }}"
                        class="menu-link hover-slide-up {{ request()->routeIs('home') || request()->routeIs('events.guest.*') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-calendar-alt"></i>
                        <span class="menu-title">Browse Events</span>
                    </a>
                </li>

                <!-- Register for Events -->
                <li class="menu-item">
                    <a href="{{ route('event-registration.index') }}"
                        class="menu-link hover-slide-up {{ request()->routeIs('event-registration.*') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-user-plus"></i>
                        <span class="menu-title">Register for Events</span>
                    </a>
                </li>

                <!-- My Registrations -->
                <li class="menu-item">
                    <a href="{{ route('my-events.index') }}"
                        class="menu-link hover-slide-up {{ request()->routeIs('my-events.*') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-calendar-check"></i>
                        <span class="menu-title">My Registrations</span>
                        @php
                        $myRegistrationsCount = auth()->user()
                        ? \App\Models\EventRegistration::where('user_id', auth()->id())
                        ->whereIn('status', ['confirmed', 'pending'])
                        ->count()
                        : 0;
                        @endphp
                        @if(!$isStudentUser && $myRegistrationsCount > 0)
                        <span class="menu-badge hover-pulse">{{ $myRegistrationsCount }}</span>
                        @endif
                    </a>
                </li>

                @if($isApproverUser)
                <!-- Event Requests Review -->
                <li class="menu-item">
                    <a href="{{ route('event-requests.index') }}"
                        class="menu-link hover-slide-up {{ request()->routeIs('event-requests.index') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-clipboard-check"></i>
                        <span class="menu-title">Event Requests Review</span>
                        @php
                        $pendingRequestsCount = \App\Models\EventRequest::where('status', 'pending')->count();
                        @endphp
                        @if($pendingRequestsCount > 0)
                        <span class="menu-badge hover-pulse">{{ $pendingRequestsCount }}</span>
                        @endif
                    </a>
                </li>
                @elseif($isEventManagerUser)
                <!-- Event Scheduling Queue -->
                <li class="menu-item">
                    <a href="{{ route('event-requests.my-requests') }}"
                        class="menu-link hover-slide-up {{ request()->routeIs('event-requests.my-requests') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-clipboard-list"></i>
                        <span class="menu-title">Event Scheduling Queue</span>
                        @php
                        $newManagerQueueCount = \App\Models\EventRequest::where('status', 'manager_review')
                            ->whereNull('manager_viewed_at')
                            ->count();
                        @endphp
                        @if($newManagerQueueCount > 0)
                        <span class="menu-badge hover-pulse">{{ $newManagerQueueCount }}</span>
                        @endif
                    </a>
                </li>
                @elseif($showTopEventRequestLinks)
                <!-- Create Event Request -->
                <li class="menu-item">
                    <a href="{{ route('event-requests.create') }}"
                        class="menu-link hover-slide-up {{ request()->routeIs('event-requests.create') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-plus-circle"></i>
                        <span class="menu-title">Create Event Request</span>
                    </a>
                </li>

                <!-- My Event Requests -->
                <li class="menu-item">
                    <a href="{{ route('event-requests.my-requests') }}"
                        class="menu-link hover-slide-up {{ request()->routeIs('event-requests.my-requests') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-clipboard-list"></i>
                        <span class="menu-title">My Event Requests</span>
                        @php
                        $myPendingRequestsCount = auth()->user()
                            ? \App\Models\EventRequest::where('user_id', auth()->id())
                                ->whereIn('status', ['manager_review', 'pending'])
                                ->count()
                            : 0;
                        @endphp
                        @if($myPendingRequestsCount > 0)
                        <span class="menu-badge hover-pulse">{{ $myPendingRequestsCount }}</span>
                        @endif
                    </a>
                </li>
                @endif

                <!-- FEEDBACK SECTION -->
                <li class="menu-header">Feedback</li>

                <!-- Submit Feedback -->
                <li class="menu-item">
                    <a href="{{ route('feedback.create') }}"
                        class="menu-link hover-slide-up {{ request()->routeIs('feedback.create') || request()->routeIs('feedback.store') || request()->routeIs('feedback.thankyou') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-comment-dots"></i>
                        <span class="menu-title">Submit Feedback</span>
                    </a>
                </li>

                <!-- View Testimonials -->
                <li class="menu-item">
                    <a href="{{ route('feedback.testimonials') }}"
                        class="menu-link hover-slide-up {{ request()->routeIs('feedback.testimonials') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-star"></i>
                        <span class="menu-title">View Testimonials</span>
                    </a>
                </li>

                @if(auth()->user()->hasAnyPermission(['view_feedback', 'manage_feedback', 'update_feedback', 'respond_feedback']))
                <li class="menu-item">
                    <a href="{{ route('feedback.index') }}"
                        class="menu-link hover-slide-up {{ request()->routeIs('feedback.index') || request()->routeIs('feedback.show') || request()->routeIs('feedback.update-status') || request()->routeIs('feedback.add-response') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-comments"></i>
                        <span class="menu-title">Feedback Management</span>
                        @php
                        $feedbackQueueCount = auth()->user()->hasPermission('manage_feedback')
                            ? \App\Models\Feedback::whereIn('status', ['pending', 'reviewed'])->count()
                            : \App\Models\Feedback::where('assigned_to', auth()->id())
                                ->whereIn('status', ['pending', 'reviewed'])
                                ->count();
                        @endphp
                        @if($feedbackQueueCount > 0)
                        <span class="menu-badge hover-pulse">{{ $feedbackQueueCount }}</span>
                        @endif
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasPermission('view_feedback_analytics'))
                <li class="menu-item">
                    <a href="{{ route('feedback.analytics') }}"
                        class="menu-link hover-slide-up {{ request()->routeIs('feedback.analytics') || request()->routeIs('feedback.admin.analytics') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-chart-line"></i>
                        <span class="menu-title">Feedback Analytics</span>
                    </a>
                </li>
                @endif

                <!-- ANNOUNCEMENTS -->
                <li class="menu-header">Announcements</li>
                <li class="menu-item">
                    <a href="{{ route('announcements.index') }}"
                        class="menu-link hover-slide-up {{ request()->routeIs('announcements.index') && !request()->routeIs('announcements.create') && !request()->routeIs('announcements.edit') && !request()->routeIs('announcements.statistics') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-bullhorn"></i>
                        <span class="menu-title">Announcements</span>
                    </a>
                </li>

                <!-- ADMINISTRATION SECTION -->
                @if(
                    !$isStudentUser &&
                    !$isFacultyUser &&
                    (
                        auth()->user()->hasAnyPermission(['manage_events', 'manage_venues', 'manage_users',
                        'view_event_requests', 'manage_feedback', 'manage_announcements']) ||
                        auth()->user()->hasRole('event-manager')
                    )
                )
                <li class="menu-header">Administration</li>

                <!-- VENUE MANAGEMENT -->
                @if(auth()->user()->hasPermission('manage_venues'))
                <li class="menu-item">
                    <a class="menu-link menu-collapse hover-slide-up {{ request()->routeIs('admin.campuses.*') || request()->routeIs('admin.buildings.*') || request()->routeIs('admin.venues.*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#venueManagement" role="button"
                        aria-expanded="{{ request()->routeIs('admin.campuses.*') || request()->routeIs('admin.buildings.*') || request()->routeIs('admin.venues.*') ? 'true' : 'false' }}">
                        <i class="menu-icon fas fa-map-marked-alt"></i>
                        <span class="menu-title">Venue Management</span>
                        <i class="menu-arrow fas fa-chevron-right"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.campuses.*') || request()->routeIs('admin.buildings.*') || request()->routeIs('admin.venues.*') ? 'show' : '' }}"
                        id="venueManagement">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('admin.campuses.*') ? 'active' : '' }}"
                                    href="{{ route('admin.campuses.index') }}">
                                    <i class="fas fa-university me-2"></i>
                                    <span>Campuses</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('admin.buildings.*') ? 'active' : '' }}"
                                    href="{{ route('admin.buildings.index') }}">
                                    <i class="fas fa-building me-2"></i>
                                    <span>Buildings</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('admin.venues.*') ? 'active' : '' }}"
                                    href="{{ route('admin.venues.index') }}">
                                    <i class="fas fa-door-closed me-2"></i>
                                    <span>Venues & Halls</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                <!-- EVENT ADMINISTRATION -->
                @if(auth()->user()->hasPermission('manage_events') ||
                auth()->user()->hasPermission('view_event_requests') ||
                auth()->user()->hasPermission('manage_speakers') ||
                auth()->user()->hasRole('event-manager') ||
                $isApproverUser)
                <li class="menu-item">
                    <a class="menu-link menu-collapse hover-slide-up {{ request()->routeIs('admin.events.*') || request()->routeIs('event-requests.index') || request()->routeIs('speakers.*') || request()->routeIs('admin.events.speakers.*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#eventAdmin" role="button"
                        aria-expanded="{{ request()->routeIs('admin.events.*') || request()->routeIs('event-requests.index') || request()->routeIs('speakers.*') || request()->routeIs('admin.events.speakers.*') ? 'true' : 'false' }}">
                        <i class="menu-icon fas fa-calendar-plus"></i>
                        <span class="menu-title">Event Administration</span>
                        <i class="menu-arrow fas fa-chevron-right"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.events.*') || request()->routeIs('event-requests.index') || request()->routeIs('speakers.*') || request()->routeIs('admin.events.speakers.*') ? 'show' : '' }}"
                        id="eventAdmin">
                        <ul class="nav flex-column sub-menu">

                            <!-- Events Management Section -->
                            @if(auth()->user()->hasPermission('manage_events') ||
                            auth()->user()->hasPermission('create_events') ||
                            auth()->user()->hasRole('event-manager'))
                            <li class="nav-item nav-section-title">
                                <span class="nav-section-text">Events</span>
                            </li>

                            @if(auth()->user()->hasPermission('manage_events'))
                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('admin.events.index') ? 'active' : '' }}"
                                    href="{{ route('admin.events.index') }}">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    <span>All Events</span>
                                    @php
                                    $upcomingEvents = \App\Models\Event::upcoming()->count();
                                    @endphp
                                    @if($upcomingEvents > 0)
                                    <span class="menu-badge hover-pulse"
                                        style="background-color: #002789;">{{ $upcomingEvents }}</span>
                                    @endif
                                </a>
                            </li>
                            @endif

                            @if(
                                auth()->user()->hasPermission('create_events') ||
                                auth()->user()->hasRole('event-manager') ||
                                $isApproverUser
                            )
                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('admin.events.create') ? 'active' : '' }}"
                                    href="{{ route('admin.events.create') }}">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    <span>Create Event</span>
                                </a>
                            </li>
                            @endif
                            @endif

                            <!-- Speakers Management Section -->
                            @if(class_exists('App\Models\Speaker') && auth()->user()->hasPermission('manage_speakers'))
                            <li class="nav-item nav-section-title">
                                <span class="nav-section-text">Speakers</span>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('speakers.index') ? 'active' : '' }}"
                                    href="{{ route('speakers.index') }}">
                                    <i class="fas fa-microphone-alt me-2"></i>
                                    <span>All Speakers</span>
                                    @php
                                    $activeSpeakers = \App\Models\Speaker::active()->count();
                                    @endphp
                                    @if($activeSpeakers > 0)
                                    <span class="menu-badge"
                                        style="background-color: #28a745;">{{ $activeSpeakers }}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('speakers.create') ? 'active' : '' }}"
                                    href="{{ route('speakers.create') }}">
                                    <i class="fas fa-user-plus me-2"></i>
                                    <span>Add Speaker</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('speakers.featured') ? 'active' : '' }}"
                                    href="{{ route('speakers.index', ['featured' => 1]) }}">
                                    <i class="fas fa-star me-2" style="color: #ffc107;"></i>
                                    <span>Featured Speakers</span>
                                    @php
                                    $featuredSpeakers = \App\Models\Speaker::featured()->count();
                                    @endphp
                                    @if($featuredSpeakers > 0)
                                    <span class="menu-badge"
                                        style="background-color: #ffc107; color: #000;">{{ $featuredSpeakers }}</span>
                                    @endif
                                </a>
                            </li>
                            @endif

                            <!-- Event Requests Section -->
                            @if($isApproverUser)
                            <li class="nav-item nav-section-title">
                                <span class="nav-section-text">Requests</span>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('event-requests.index') ? 'active' : '' }}"
                                    href="{{ route('event-requests.index') }}">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    <span>All Requests</span>
                                    @php
                                    $pendingRequests = \App\Models\EventRequest::where('status', 'pending')->count();
                                    @endphp
                                    @if($pendingRequests > 0)
                                    <span class="menu-badge hover-pulse"
                                        style="background-color: #ffc107; color: #000;">{{ $pendingRequests }}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('event-requests.pending') ? 'active' : '' }}"
                                    href="{{ route('event-requests.index', ['status' => 'pending']) }}">
                                    <i class="fas fa-hourglass-half me-2" style="color: #ffc107;"></i>
                                    <span>Pending</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('event-requests.approved') ? 'active' : '' }}"
                                    href="{{ route('event-requests.index', ['status' => 'approved']) }}">
                                    <i class="fas fa-check-circle me-2" style="color: #28a745;"></i>
                                    <span>Approved</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                <!-- USER ADMINISTRATION -->
                @if(auth()->user()->hasAnyPermission(['view_users', 'create_users', 'view_roles', 'view_permissions']))
                <li class="menu-item">
                    <a class="menu-link menu-collapse hover-slide-up {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#userAdmin" role="button"
                        aria-expanded="{{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'true' : 'false' }}">
                        <i class="menu-icon fas fa-users-cog"></i>
                        <span class="menu-title">User Administration</span>
                        <i class="menu-arrow fas fa-chevron-right"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'show' : '' }}"
                        id="userAdmin">
                        <ul class="nav flex-column sub-menu">
                            @if(auth()->user()->hasPermission('view_users'))
                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('users.index') ? 'active' : '' }}"
                                    href="{{ route('users.index') }}">
                                    <i class="fas fa-users me-2"></i>
                                    <span>All Users</span>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->hasPermission('create_users'))
                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('users.create') ? 'active' : '' }}"
                                    href="{{ route('users.create') }}">
                                    <i class="fas fa-user-plus me-2"></i>
                                    <span>Add User</span>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->hasPermission('view_roles'))
                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                                    href="{{ route('roles.index') }}">
                                    <i class="fas fa-user-tag me-2"></i>
                                    <span>Roles</span>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->hasPermission('view_permissions'))
                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('permissions.*') ? 'active' : '' }}"
                                    href="{{ route('permissions.index') }}">
                                    <i class="fas fa-key me-2"></i>
                                    <span>Permissions</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                <!-- ANNOUNCEMENT ADMINISTRATION -->
                @if(auth()->user()->hasAnyPermission(['manage_announcements', 'create_announcements',
                'view_announcement_stats']))
                <li class="menu-item">
                    <a class="menu-link menu-collapse hover-slide-up {{ request()->routeIs('announcements.create') || request()->routeIs('announcements.edit') || request()->routeIs('announcements.statistics') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#announcementAdmin" role="button"
                        aria-expanded="{{ request()->routeIs('announcements.create') || request()->routeIs('announcements.edit') || request()->routeIs('announcements.statistics') ? 'true' : 'false' }}">
                        <i class="menu-icon fas fa-bullhorn"></i>
                        <span class="menu-title">Announcement Admin</span>
                        <i class="menu-arrow fas fa-chevron-right"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('announcements.create') || request()->routeIs('announcements.edit') || request()->routeIs('announcements.statistics') ? 'show' : '' }}"
                        id="announcementAdmin">
                        <ul class="nav flex-column sub-menu">
                            @if(auth()->user()->hasPermission('view_announcements'))
                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('announcements.index') && !request()->routeIs('announcements.create') && !request()->routeIs('announcements.edit') && !request()->routeIs('announcements.statistics') ? 'active' : '' }}"
                                    href="{{ route('announcements.index') }}">
                                    <i class="fas fa-list me-2"></i>
                                    <span>All Announcements</span>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->hasPermission('create_announcements'))
                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('announcements.create') ? 'active' : '' }}"
                                    href="{{ route('announcements.create') }}">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    <span>Create New</span>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->hasPermission('view_announcement_stats'))
                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('announcements.statistics') ? 'active' : '' }}"
                                    href="{{ route('announcements.statistics') }}">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    <span>Statistics</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif
                @endif


                <!-- In your layouts/app.blade.php, find the Administration section and add: -->

                <!-- REGISTRATION ADMINISTRATION -->
                @if(!$isStudentUser && !$isFacultyUser && auth()->user()->hasAnyPermission(['manage_event_registrations', 'view_registrations',
                'confirm_registrations']))
                <li class="menu-item">
                    <a class="menu-link menu-collapse hover-slide-up {{ request()->routeIs('admin.registrations.*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#registrationAdmin" role="button"
                        aria-expanded="{{ request()->routeIs('admin.registrations.*') ? 'true' : 'false' }}">
                        <i class="menu-icon fas fa-ticket-alt"></i>
                        <span class="menu-title">Registration Management</span>
                        @php
                        $pendingRegistrationsCount = \App\Models\EventRegistration::where('status', 'pending')->count();
                        @endphp
                        @if($pendingRegistrationsCount > 0)
                        <span class="menu-badge hover-pulse"
                            style="background-color: #ffc107; color: #000;">{{ $pendingRegistrationsCount }}</span>
                        @else
                        <i class="menu-arrow fas fa-chevron-right"></i>
                        @endif
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.registrations.*') ? 'show' : '' }}"
                        id="registrationAdmin">
                        <ul class="nav flex-column sub-menu">

                            <!-- Overview Section -->
                            <li class="nav-item nav-section-title">
                                <span class="nav-section-text">Overview</span>
                            </li>

                            @if(auth()->user()->hasPermission('view_registrations'))
                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('admin.registrations.index') && !request()->routeIs('admin.registrations.pending') && !request()->routeIs('admin.registrations.statistics') ? 'active' : '' }}"
                                    href="{{ route('admin.registrations.index') }}">
                                    <i class="fas fa-list me-2"></i>
                                    <span>All Registrations</span>
                                    @php
                                    $totalRegistrations = \App\Models\EventRegistration::count();
                                    @endphp
                                    @if($totalRegistrations > 0)
                                    <span class="menu-badge"
                                        style="background-color: #002789;">{{ $totalRegistrations }}</span>
                                    @endif
                                </a>
                            </li>
                            @endif

                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('admin.registrations.pending') ? 'active' : '' }}"
                                    href="{{ route('admin.registrations.pending') }}">
                                    <i class="fas fa-clock me-2" style="color: #ffc107;"></i>
                                    <span>Pending Confirmation</span>
                                    @php
                                    $pendingCount = \App\Models\EventRegistration::where('status', 'pending')->count();
                                    @endphp
                                    @if($pendingCount > 0)
                                    <span class="menu-badge hover-pulse"
                                        style="background-color: #ffc107; color: #000;">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                            </li>

                            @if(auth()->user()->hasPermission('view_registrations'))
                            <li class="nav-item">
                                <a class="nav-link hover-border {{ request()->routeIs('admin.registrations.statistics') ? 'active' : '' }}"
                                    href="{{ route('admin.registrations.statistics') }}">
                                    <i class="fas fa-chart-pie me-2"></i>
                                    <span>Statistics</span>
                                </a>
                            </li>
                            @endif

                            <!-- Status Views -->
                            <li class="nav-item nav-section-title">
                                <span class="nav-section-text">By Status</span>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link hover-border"
                                    href="{{ route('admin.registrations.index', ['status' => 'confirmed']) }}">
                                    <i class="fas fa-check-circle me-2" style="color: #28a745;"></i>
                                    <span>Confirmed</span>
                                    @php
                                    $confirmedCount = \App\Models\EventRegistration::where('status',
                                    'confirmed')->count();
                                    @endphp
                                    @if($confirmedCount > 0)
                                    <span class="menu-badge"
                                        style="background-color: #28a745;">{{ $confirmedCount }}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link hover-border"
                                    href="{{ route('admin.registrations.index', ['status' => 'cancelled']) }}">
                                    <i class="fas fa-times-circle me-2" style="color: #dc3545;"></i>
                                    <span>Cancelled</span>
                                    @php
                                    $cancelledCount = \App\Models\EventRegistration::where('status',
                                    'cancelled')->count();
                                    @endphp
                                    @if($cancelledCount > 0)
                                    <span class="menu-badge"
                                        style="background-color: #dc3545;">{{ $cancelledCount }}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link hover-border"
                                    href="{{ route('admin.registrations.index', ['status' => 'waitlisted']) }}">
                                    <i class="fas fa-clock me-2" style="color: #17a2b8;"></i>
                                    <span>Waitlisted</span>
                                    @php
                                    $waitlistedCount = \App\Models\EventRegistration::where('status',
                                    'waitlisted')->count();
                                    @endphp
                                    @if($waitlistedCount > 0)
                                    <span class="menu-badge"
                                        style="background-color: #17a2b8;">{{ $waitlistedCount }}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link hover-border"
                                    href="{{ route('admin.registrations.index', ['attended' => 1]) }}">
                                    <i class="fas fa-user-check me-2" style="color: #28a745;"></i>
                                    <span>Attended</span>
                                    @php
                                    $attendedCount = \App\Models\EventRegistration::where('attended', true)->count();
                                    @endphp
                                    @if($attendedCount > 0)
                                    <span class="menu-badge"
                                        style="background-color: #28a745;">{{ $attendedCount }}</span>
                                    @endif
                                </a>
                            </li>

                            
                        </ul>
                    </div>
                </li>
                @endif


                <!-- SYSTEM SECTION -->
                <li class="menu-header">System</li>
                <li class="menu-item">
                    <a href="#" class="menu-link hover-slide-up">
                        <i class="menu-icon fas fa-cog"></i>
                        <span class="menu-title">System Settings</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link hover-slide-up">
                        <i class="menu-icon fas fa-question-circle"></i>
                        <span class="menu-title">Help Center</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link hover-slide-up">
                        <i class="menu-icon fas fa-book"></i>
                        <span class="menu-title">Documentation</span>
                    </a>
                </li>
            </ul>

            <!-- Add this after the notifications or in the user dropdown -->
            <li class="menu-item">
                <a href="{{ route('profile.show') }}"
                    class="menu-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="menu-icon fas fa-user-circle"></i>
                    <span class="menu-title">My Profile</span>
                </a>
            </li>

        </nav>
        @endauth

        <!-- Main Content -->
        <main class="ju-main-content">
            <div class="content-header">
                <div>
                    <h1 class="page-title">@yield('page-title', 'Jimma University Events')</h1>
                    <p class="page-subtitle">@yield('page-subtitle', 'We are in the Community!')</p>
                </div>
                @auth
                <nav class="breadcrumb-nav">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="hover-underline-center"><i
                                    class="fas fa-home me-1"></i>Dashboard</a></li>
                        @yield('breadcrumb-items')
                    </ol>
                </nav>
                @endauth
            </div>
            <div class="content-area">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="ju-footer">
        <div class="footer-container">
            <div class="footer-copyright">
                <i class="fas fa-copyright me-1"></i> {{ date('Y') }} Jimma University. All rights reserved.
                <span class="mx-2 d-none d-sm-inline">|</span>
                <span class="d-block d-sm-inline mt-2 mt-sm-0 fw-bold">"We are in the Community!"</span>
            </div>
            <div class="footer-links">
                <a href="#" class="footer-link hover-underline-center">About</a>
                <a href="#" class="footer-link hover-underline-center">Contact</a>
                <a href="#" class="footer-link hover-underline-center">Privacy</a>
                <a href="#" class="footer-link hover-underline-center">Terms</a>
                <a href="#" class="footer-link hover-underline-center">Support</a>
            </div>
        </div>
    </footer>

    <!-- Approve/Reject Modal -->
    <div class="modal fade" id="approveRejectModal" tabindex="-1" aria-labelledby="approveRejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(145deg, #002789 0%, #001a5c 100%); color: white;">
                    <h5 class="modal-title" id="approveRejectModalLabel">
                        <i class="fas fa-check-circle me-2"></i>
                        <span id="modalActionText">Approve Request</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="approveRejectForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Event Request:</label>
                            <div id="requestTitle" class="p-3 bg-light rounded">
                                <strong id="modalEventTitle">Loading...</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="reviewNotes" class="form-label fw-bold">
                                <i class="fas fa-edit me-1"></i>Review Notes
                            </label>
                            <textarea class="form-control" id="reviewNotes" name="review_notes" rows="4"
                                placeholder="Add any comments or notes about this decision..."></textarea>
                            <div class="form-text">These notes will be visible to the requester.</div>
                        </div>
                        <input type="hidden" id="modalRequestId" name="request_id">
                        <input type="hidden" id="modalAction" name="action" value="approve">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-success" id="modalSubmitBtn">
                            <i class="fas fa-check me-1"></i>
                            <span id="submitBtnText">Approve Request</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="ju-sidebar-overlay" id="sidebarOverlay"></div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });

    // Set CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        cleanupStuckModalBackdrop();
        initMobileSidebar();
        initNotificationSystem();
        initSidebar();
        initSearch();
        initTooltips();
        initSelect2();
        initDataTables();
        initModals();
        autoHideToasts();
    });

    function cleanupStuckModalBackdrop() {
        // Defensive cleanup for pages where a modal target is missing or interrupted.
        if (!document.querySelector('.modal.show')) {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
    }

    // Mobile Sidebar
    function initMobileSidebar() {
        const sidebar = document.getElementById('mainSidebar');
        const toggleBtn = document.getElementById('mobileMenuToggle');
        const overlay = document.getElementById('sidebarOverlay');

        if (toggleBtn && sidebar && overlay) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('mobile-open') ? 'hidden' : '';
            });

            overlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('mobile-open')) {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth > 991) {
                if (sidebar) sidebar.classList.remove('mobile-open');
                if (overlay) overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    // Notification System
    let notificationsExpanded = false;

    function initNotificationSystem() {
        const dropdownTrigger = document.getElementById('notificationDropdown');
        const showMoreBtn = document.getElementById('showMoreNotificationsBtn');
        const showLessBtn = document.getElementById('showLessNotificationsBtn');
        const clearAllBtn = document.getElementById('clearAllNotificationsBtn');

        if (!dropdownTrigger) return;

        checkNewNotifications();
        setInterval(checkNewNotifications, 30000);

        dropdownTrigger.addEventListener('click', function() {
            setTimeout(() => {
                notificationsExpanded = false;
                loadNotifications(false);
            }, 120);
        });

        if (showMoreBtn) {
            showMoreBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                loadNotifications(true);
            });
        }

        if (showLessBtn) {
            showLessBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                loadNotifications(false);
            });
        }

        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                clearAllNotifications();
            });
        }
    }

    function loadNotifications(showAll = false) {
        const list = document.getElementById('notificationList');
        const showMoreBtn = document.getElementById('showMoreNotificationsBtn');
        const showLessBtn = document.getElementById('showLessNotificationsBtn');
        const clearAllBtn = document.getElementById('clearAllNotificationsBtn');

        if (!list) return;

        notificationsExpanded = showAll;
        list.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border" style="color: #002789; width: 2.5rem; height: 2.5rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted mb-0">Fetching your notifications...</p>
            </div>
        `;

        const recentNotificationsUrl = '{{ route("notifications.recent") }}';
        fetch(`${recentNotificationsUrl}${showAll ? '?all=1' : ''}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const notifications = data.notifications || [];
                if (notifications.length === 0) {
                    list.innerHTML = `
                        <div class="text-center py-5">
                            <div style="width: 80px; height: 80px; background: #f0f7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                <i class="fas fa-bell-slash fa-3x" style="color: #002789; opacity: 0.5;"></i>
                            </div>
                            <h6 class="fw-semibold mb-2">No New Notifications Yet</h6>
                            <p class="text-muted small mb-0 px-4">You're all caught up! Check back later for updates.</p>
                        </div>
                    `;
                    if (showMoreBtn) showMoreBtn.style.display = 'none';
                    if (showLessBtn) showLessBtn.style.display = 'none';
                    if (clearAllBtn) clearAllBtn.style.display = 'none';
                    return;
                }

                list.innerHTML = notifications.map(notif => {
                    const isRead = !!(notif.pivot && notif.pivot.read_at);
                    const iconBg = isRead ? '#f8f9fa' : '#e3f2fd';
                    const dot = isRead ? '' :
                        '<span style="width: 8px; height: 8px; background: #002789; border-radius: 50%; margin-top: 4px;"></span>';
                    return `
                        <a href="${notif.open_url}" class="dropdown-item notification-item ${isRead ? '' : 'unread'}" style="padding: 1rem; border-bottom: 1px solid #e9ecef; white-space: normal;">
                            <div class="d-flex align-items-start gap-3">
                                <div style="width: 40px; height: 40px; background: ${iconBg}; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-${notif.type_icon || 'bell'}" style="color: #002789;"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <h6 class="mb-1 fw-semibold" style="font-size: 0.9rem;">${escapeHtml(notif.title || 'Notification')}</h6>
                                    <p class="mb-1 text-muted" style="font-size: 0.8rem;">${escapeHtml(notif.excerpt || '')}</p>
                                    <small class="text-muted" style="font-size: 0.72rem;">${escapeHtml(notif.pivot?.created_at || '')}</small>
                                </div>
                                ${dot}
                            </div>
                        </a>
                    `;
                }).join('');

                if (showMoreBtn) showMoreBtn.style.display = (!showAll && data.has_more) ? 'inline-block' : 'none';
                if (showLessBtn) showLessBtn.style.display = showAll ? 'inline-block' : 'none';
                if (clearAllBtn) {
                    clearAllBtn.style.display = 'inline-block';
                }

                updateNotificationBadge(data.unread_count || 0);
            })
            .catch(() => {
                list.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-circle fa-2x text-danger mb-2"></i>
                        <p class="text-muted mb-2">Failed to load notifications</p>
                        <button class="btn btn-sm btn-outline-primary" onclick="loadNotifications(${showAll ? 'true' : 'false'})">
                            <i class="fas fa-sync-alt me-1"></i>Retry
                        </button>
                    </div>
                `;
            });
    }

    function clearAllNotifications() {
        fetch('{{ route("notifications.clear-all") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(() => {
                const list = document.getElementById('notificationList');
                const showMoreBtn = document.getElementById('showMoreNotificationsBtn');
                const showLessBtn = document.getElementById('showLessNotificationsBtn');
                const clearAllBtn = document.getElementById('clearAllNotificationsBtn');

                if (list) {
                    list.innerHTML = `
                        <div class="text-center py-5">
                            <div style="width: 80px; height: 80px; background: #f0f7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                <i class="fas fa-bell-slash fa-3x" style="color: #002789; opacity: 0.5;"></i>
                            </div>
                            <h6 class="fw-semibold mb-2">No New Notifications Yet</h6>
                            <p class="text-muted small mb-0 px-4">You're all caught up! Check back later for updates.</p>
                        </div>
                    `;
                }
                if (showMoreBtn) showMoreBtn.style.display = 'none';
                if (showLessBtn) showLessBtn.style.display = 'none';
                if (clearAllBtn) clearAllBtn.style.display = 'none';
                updateNotificationBadge(0);
            })
            .catch(() => {});
    }

    function checkNewNotifications() {
        fetch('{{ route("notifications.unread-count") }}', {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => updateNotificationBadge(data.count || 0))
            .catch(() => {});
    }

    function updateNotificationBadge(count) {
        const bell = document.querySelector('.notification-bell');
        if (!bell) return;

        let badge = bell.querySelector('.notification-badge');
        if (count > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'notification-badge';
                bell.appendChild(badge);
            }
            badge.textContent = count;
        } else if (badge) {
            badge.remove();
        }
    }

    function escapeHtml(value) {
        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    // Sidebar
    function initSidebar() {
        document.querySelectorAll('.menu-link.active').forEach(link => {
            const collapse = link.closest('.collapse');
            if (collapse) {
                const parentMenu = document.querySelector(`[href="#${collapse.id}"]`);
                if (parentMenu) {
                    parentMenu.classList.remove('collapsed');
                    parentMenu.setAttribute('aria-expanded', 'true');
                    collapse.classList.add('show');
                }
            }
        });
    }

    // Search
    function initSearch() {
        const searchInput = document.getElementById('globalSearch');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const query = this.value.trim();
                    if (query) {
                        window.location.href = `{{ route('home') }}?search=${encodeURIComponent(query)}`;
                    }
                }
            });
        }
    }

    // Tooltips
    function initTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Select2
    function initSelect2() {
        if ($.fn.select2) {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Select an option',
                allowClear: true
            });
        }
    }

    // DataTables
    function initDataTables() {
        if ($.fn.DataTable) {
            $('.data-table').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>',
                        next: '<i class="fas fa-chevron-right"></i>',
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                }
            });
        }
    }

    // Modals
    function initModals() {
        window.openApproveModal = function(requestId, title) {
            document.getElementById('modalRequestId').value = requestId;
            document.getElementById('modalEventTitle').textContent = title;
            document.getElementById('modalActionText').textContent = 'Approve Request';
            document.getElementById('submitBtnText').textContent = 'Approve Request';
            document.getElementById('modalSubmitBtn').className = 'btn btn-success';
            document.getElementById('modalAction').value = 'approve';
            const modal = new bootstrap.Modal(document.getElementById('approveRejectModal'));
            modal.show();
        };

        window.openRejectModal = function(requestId, title) {
            document.getElementById('modalRequestId').value = requestId;
            document.getElementById('modalEventTitle').textContent = title;
            document.getElementById('modalActionText').textContent = 'Reject Request';
            document.getElementById('submitBtnText').textContent = 'Reject Request';
            document.getElementById('modalSubmitBtn').className = 'btn btn-danger';
            document.getElementById('modalAction').value = 'reject';
            const modal = new bootstrap.Modal(document.getElementById('approveRejectModal'));
            modal.show();
        };
    }

    // Auto-hide toasts
    function autoHideToasts() {
        setTimeout(function() {
            document.querySelectorAll('.ju-toast').forEach(toast => {
                toast.style.transition = 'opacity 0.5s ease';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            });
        }, 5000);
    }


    </script>

    @stack('scripts')
</body>

</html>

