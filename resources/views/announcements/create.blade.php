{{-- resources/views/announcements/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Announcement - Jimma University')
@section('page-title', 'Create Announcement')
@section('page-subtitle', 'Share important updates with the university community')

@section('content')
<style>
    /* ============================================
           PREMIUM JIMMA UNIVERSITY STYLES
        ============================================ */
    
    :root {
        --ju-blue: #0a2c6e;
        --ju-blue-dark: #041c47;
        --ju-blue-darker: #021230;
        --ju-blue-light: #1e4a8a;
        --ju-blue-lighter: #3a6ab0;
        --ju-blue-soft: rgba(10, 44, 110, 0.08);
        --ju-gold: #c4a747;
        --ju-gold-dark: #a5862e;
        --ju-gold-light: #d8be6e;
        --ju-gold-soft: rgba(196, 167, 71, 0.12);
        
        --ju-success: #10b981;
        --ju-success-dark: #059669;
        --ju-warning: #f59e0b;
        --ju-warning-dark: #d97706;
        --ju-danger: #dc2626;
        --ju-danger-dark: #b91c1c;
        --ju-info: #0a2c6e;
        --ju-info-soft: rgba(10, 44, 110, 0.08);
        
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
        
        --shadow-sm: 0 4px 12px rgba(0,0,0,0.05);
        --shadow: 0 8px 20px rgba(0,0,0,0.08);
        --shadow-lg: 0 16px 32px rgba(0,0,0,0.12);
        
        --radius-sm: 8px;
        --radius: 12px;
        --radius-md: 16px;
        --radius-lg: 20px;
        --radius-xl: 24px;
        --radius-full: 9999px;
        
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-bounce: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    /* ============================================
           WIZARD STEP INDICATOR
        ============================================ */
    .create-wizard {
        margin-bottom: 32px;
    }

    .wizard-steps {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        margin-bottom: 24px;
    }

    .wizard-steps::before {
        content: '';
        position: absolute;
        top: 35px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--ju-gray-200), var(--ju-gray-300));
        z-index: 1;
    }

    .wizard-step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }

    .wizard-step-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--ju-gray-300);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--ju-gray-500);
        transition: var(--transition-bounce);
        box-shadow: var(--shadow-sm);
    }

    .wizard-step.active .wizard-step-circle {
        border-color: var(--ju-blue);
        background: var(--ju-blue);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 10px 20px rgba(10, 44, 110, 0.3);
    }

    .wizard-step.completed .wizard-step-circle {
        border-color: var(--ju-success);
        background: var(--ju-success);
        color: white;
    }

    .wizard-step-label {
        font-weight: 600;
        color: var(--ju-gray-700);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .wizard-step.active .wizard-step-label {
        color: var(--ju-blue);
        font-weight: 700;
    }

    .wizard-step.completed .wizard-step-label {
        color: var(--ju-success);
    }

    /* ============================================
           CANCELLATION TOGGLE - TRULY OPTIONAL
        ============================================ */
    .cancellation-optional {
        margin-bottom: 32px;
        border: 2px dashed var(--ju-gray-300);
        border-radius: var(--radius-xl);
        padding: 20px;
        transition: var(--transition);
        cursor: pointer;
        background: linear-gradient(145deg, white, var(--ju-gray-50));
        position: relative;
        overflow: hidden;
    }

    .cancellation-optional::before {
        content: '✨ OPTIONAL ✨';
        position: absolute;
        top: 10px;
        right: -30px;
        background: var(--ju-gold);
        color: var(--ju-blue-dark);
        font-weight: 800;
        font-size: 0.7rem;
        padding: 4px 40px;
        transform: rotate(45deg);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        letter-spacing: 1px;
        z-index: 10;
    }

    .cancellation-optional:hover {
        border-color: var(--ju-danger);
        background: #fff5f5;
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .cancellation-optional.active {
        border-color: var(--ju-danger);
        background: #fff0f0;
        border-style: solid;
        box-shadow: 0 8px 20px rgba(220, 38, 38, 0.15);
    }

    .cancellation-toggle-content {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .cancellation-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(145deg, #fee2e2, #fecaca);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--ju-danger);
        transition: var(--transition-bounce);
    }

    .cancellation-optional.active .cancellation-icon {
        transform: scale(1.1) rotate(10deg);
        background: linear-gradient(145deg, var(--ju-danger), #b91c1c);
        color: white;
    }

    .cancellation-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--ju-gray-800);
        margin-bottom: 4px;
    }

    .cancellation-optional.active .cancellation-title {
        color: var(--ju-danger);
    }

    .cancellation-subtitle {
        color: var(--ju-gray-600);
        font-size: 0.85rem;
    }

    /* ============================================
           CANCELLATION SECTION (Shows only when toggled)
        ============================================ */
    .cancellation-section {
        margin-top: 24px;
        padding: 24px;
        background: linear-gradient(145deg, #fff5f5, #fff0f0);
        border-radius: var(--radius-xl);
        border: 1px solid #fecaca;
        animation: slideDown 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .cancellation-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 24px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(220, 38, 38, 0.2);
    }

    .cancellation-header-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(145deg, var(--ju-danger), #b91c1c);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .cancellation-header h4 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--ju-danger);
        margin-bottom: 4px;
    }

    .cancellation-header p {
        color: var(--ju-gray-600);
        font-size: 0.85rem;
        margin-bottom: 0;
    }

    .event-search-box {
        position: relative;
        margin-bottom: 20px;
    }

    .event-search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ju-gray-400);
    }

    .event-search-box input {
        padding-left: 45px;
        height: 50px;
        border: 2px solid #fecaca;
        border-radius: var(--radius-full);
        width: 100%;
        transition: var(--transition);
    }

    .event-search-box input:focus {
        border-color: var(--ju-danger);
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
        outline: none;
    }

    .events-list {
        max-height: 300px;
        overflow-y: auto;
        border-radius: var(--radius-lg);
        background: white;
        border: 1px solid #fecaca;
        margin-bottom: 20px;
    }

    .event-select-card {
        padding: 15px;
        border-bottom: 1px solid #fee2e2;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .event-select-card:last-child {
        border-bottom: none;
    }

    .event-select-card:hover {
        background: #fff5f5;
        transform: translateX(5px);
    }

    .event-select-card.selected {
        background: linear-gradient(145deg, #fee2e2, #fecaca);
        border-left: 4px solid var(--ju-danger);
    }

    .event-select-radio {
        width: 24px;
        height: 24px;
        border: 2px solid var(--ju-gray-300);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: var(--transition);
    }

    .event-select-card.selected .event-select-radio {
        border-color: var(--ju-danger);
        background: var(--ju-danger);
    }

    .event-select-card.selected .event-select-radio::after {
        content: '';
        width: 12px;
        height: 12px;
        background: white;
        border-radius: 50%;
    }

    .event-select-image {
        width: 60px;
        height: 60px;
        border-radius: var(--radius);
        object-fit: cover;
        background: var(--ju-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .event-select-info {
        flex: 1;
    }

    .event-select-title {
        font-weight: 700;
        color: var(--ju-gray-800);
        margin-bottom: 6px;
    }

    .event-select-meta {
        display: flex;
        gap: 15px;
        font-size: 0.8rem;
        color: var(--ju-gray-600);
    }

    .event-select-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .event-select-meta i {
        color: var(--ju-gold);
    }

    .cancellation-reason {
        margin-top: 20px;
    }

    .cancellation-reason label {
        font-weight: 700;
        color: var(--ju-danger);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cancellation-reason textarea {
        border: 2px solid #fecaca;
        border-radius: var(--radius-lg);
        width: 100%;
        padding: 15px;
        transition: var(--transition);
    }

    .cancellation-reason textarea:focus {
        border-color: var(--ju-danger);
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
        outline: none;
    }

    /* ============================================
           FORM SECTIONS
        ============================================ */
    .form-section {
        background: white;
        border-radius: var(--radius-xl);
        padding: 28px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--ju-gray-200);
        transition: var(--transition);
    }

    .form-section:hover {
        box-shadow: var(--shadow);
        border-color: var(--ju-blue-soft);
    }

    .form-section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid var(--ju-gray-200);
    }

    .section-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(145deg, var(--ju-blue), var(--ju-blue-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .section-icon.warning {
        background: linear-gradient(145deg, var(--ju-warning), #d97706);
    }

    .section-icon.success {
        background: linear-gradient(145deg, var(--ju-success), #059669);
    }

    .section-title-text {
        flex: 1;
    }

    .section-title-text h4 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--ju-gray-800);
        margin-bottom: 4px;
    }

    .section-title-text p {
        color: var(--ju-gray-500);
        font-size: 0.85rem;
        margin-bottom: 0;
    }

    .section-required {
        font-size: 0.75rem;
        color: var(--ju-danger);
        font-weight: 600;
        background: #fee2e2;
        padding: 4px 10px;
        border-radius: var(--radius-full);
    }

    .section-optional {
        font-size: 0.75rem;
        color: var(--ju-success);
        font-weight: 600;
        background: #d1fae5;
        padding: 4px 10px;
        border-radius: var(--radius-full);
    }

    /* ============================================
           TYPE OPTIONS
        ============================================ */
    .type-options-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 16px;
    }

    .type-option {
        border: 2px solid var(--ju-gray-200);
        border-radius: var(--radius-lg);
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: var(--transition-bounce);
        background: white;
    }

    .type-option:hover {
        border-color: var(--ju-blue);
        background: var(--ju-blue-soft);
        transform: translateY(-4px);
        box-shadow: var(--shadow);
    }

    .type-option.selected {
        border-color: var(--ju-blue);
        background: linear-gradient(145deg, var(--ju-blue-soft), white);
        box-shadow: 0 8px 16px rgba(10,44,110,0.15);
    }

    .type-option input[type="radio"] {
        display: none;
    }

    .type-icon {
        font-size: 2.2rem;
        margin-bottom: 12px;
        transition: var(--transition-bounce);
    }

    .type-option.selected .type-icon {
        transform: scale(1.2);
    }

    .type-option .fw-semibold {
        font-size: 0.9rem;
        color: var(--ju-gray-800);
        margin-bottom: 4px;
    }

    .type-option small {
        font-size: 0.75rem;
        color: var(--ju-gray-500);
    }

    /* ============================================
           AUDIENCE OPTIONS
        ============================================ */
    .audience-options-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .audience-option {
        border: 2px solid var(--ju-gray-200);
        border-radius: var(--radius-lg);
        padding: 20px 12px;
        text-align: center;
        cursor: pointer;
        transition: var(--transition-bounce);
        background: white;
    }

    .audience-option:hover {
        border-color: var(--ju-blue);
        background: var(--ju-blue-soft);
        transform: translateY(-4px);
    }

    .audience-option.selected {
        border-color: var(--ju-blue);
        background: linear-gradient(145deg, var(--ju-blue-soft), white);
        box-shadow: 0 8px 16px rgba(10,44,110,0.1);
    }

    .audience-option input[type="radio"] {
        display: none;
    }

    .audience-icon {
        font-size: 2rem;
        color: var(--ju-blue);
        margin-bottom: 10px;
        transition: var(--transition-bounce);
    }

    .audience-option.selected .audience-icon {
        transform: scale(1.2);
        color: var(--ju-blue-dark);
    }

    .audience-title {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--ju-gray-800);
        margin-bottom: 4px;
    }

    .audience-description {
        font-size: 0.7rem;
        color: var(--ju-gray-500);
    }

    /* ============================================
           USERS LIST
        ============================================ */
    .specific-users-section {
        margin-top: 24px;
        padding: 20px;
        background: var(--ju-gray-50);
        border-radius: var(--radius-lg);
        border: 1px solid var(--ju-gray-200);
        animation: slideDown 0.4s ease;
    }

    .users-search-box {
        position: relative;
        margin-bottom: 16px;
    }

    .users-search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ju-gray-400);
    }

    .users-search-box input {
        padding-left: 45px;
        height: 45px;
        border: 1px solid var(--ju-gray-300);
        border-radius: var(--radius-full);
        width: 100%;
    }

    .users-list-container {
        max-height: 300px;
        overflow-y: auto;
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--ju-gray-200);
        padding: 15px;
    }

    .user-group {
        margin-bottom: 20px;
    }

    .user-group h6 {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--ju-blue);
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .user-group .select-all-btn {
        font-size: 0.75rem;
        padding: 3px 10px;
        background: var(--ju-blue-soft);
        color: var(--ju-blue);
        border: 1px solid var(--ju-blue-lighter);
        border-radius: var(--radius-full);
        cursor: pointer;
        transition: var(--transition);
    }

    .user-group .select-all-btn:hover {
        background: var(--ju-blue);
        color: white;
    }

    .user-item {
        padding: 8px 12px;
        border-bottom: 1px solid var(--ju-gray-200);
        transition: var(--transition);
        display: flex;
        align-items: center;
    }

    .user-item:last-child {
        border-bottom: none;
    }

    .user-item:hover {
        background: var(--ju-blue-soft);
    }

    .user-item .form-check-input:checked {
        background-color: var(--ju-blue);
        border-color: var(--ju-blue);
    }

    /* ============================================
           EDITOR TOOLBAR
        ============================================ */
    .editor-wrapper {
        border: 1px solid var(--ju-gray-300);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: var(--transition);
    }

    .editor-wrapper:focus-within {
        border-color: var(--ju-blue);
        box-shadow: 0 0 0 4px var(--ju-blue-soft);
    }

    .editor-toolbar {
        background: linear-gradient(145deg, var(--ju-gray-100), white);
        padding: 12px;
        border-bottom: 1px solid var(--ju-gray-300);
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .toolbar-group {
        display: flex;
        gap: 4px;
        padding-right: 12px;
        margin-right: 8px;
        border-right: 2px solid var(--ju-gray-300);
    }

    .toolbar-group:last-child {
        border-right: none;
    }

    .toolbar-btn {
        width: 40px;
        height: 40px;
        border-radius: var(--radius);
        border: 1px solid var(--ju-gray-300);
        background: white;
        color: var(--ju-gray-700);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .toolbar-btn:hover {
        background: var(--ju-blue);
        color: white;
        border-color: var(--ju-blue);
        transform: translateY(-2px);
    }

    .editor-content {
        min-height: 300px;
        padding: 20px;
        border: none;
        resize: vertical;
        font-family: inherit;
        line-height: 1.6;
        width: 100%;
    }

    .editor-content:focus {
        outline: none;
    }

    /* ============================================
           NOTIFICATION OPTIONS
        ============================================ */
    .notification-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .notification-option {
        border: 2px solid var(--ju-gray-200);
        border-radius: var(--radius-lg);
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: var(--transition-bounce);
    }

    .notification-option:hover {
        border-color: var(--ju-blue);
        background: var(--ju-blue-soft);
        transform: translateY(-4px);
    }

    .notification-option.selected {
        border-color: var(--ju-blue);
        background: linear-gradient(145deg, var(--ju-blue-soft), white);
        box-shadow: 0 8px 16px rgba(10,44,110,0.1);
    }

    .notification-option input[type="radio"] {
        display: none;
    }

    .notification-icon {
        font-size: 2.5rem;
        margin-bottom: 12px;
        transition: var(--transition-bounce);
    }

    .notification-option.selected .notification-icon {
        transform: scale(1.2);
    }

    /* ============================================
           PREVIEW SIDEBAR
        ============================================ */
    .preview-card {
        position: sticky;
        top: 100px;
    }

    .preview-content {
        min-height: 300px;
    }

    .preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .preview-badge {
        padding: 4px 12px;
        border-radius: var(--radius-full);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .preview-badge.event { background: #dbeafe; color: #1e40af; }
    .preview-badge.campus { background: #d1fae5; color: #065f46; }
    .preview-badge.general { background: #ede9fe; color: #5b21b6; }
    .preview-badge.urgent { background: #fee2e2; color: #991b1b; }

    .preview-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--ju-gray-800);
        margin-bottom: 15px;
    }

    .preview-meta {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        font-size: 0.8rem;
        color: var(--ju-gray-500);
        padding-bottom: 15px;
        border-bottom: 1px solid var(--ju-gray-200);
    }

    .preview-meta i {
        color: var(--ju-gold);
        margin-right: 5px;
    }

    .preview-body {
        line-height: 1.8;
        color: var(--ju-gray-700);
    }

    /* ============================================
           RESPONSIVE
        ============================================ */
    @media (max-width: 1200px) {
        .type-options-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .audience-options-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .type-options-grid,
        .audience-options-grid,
        .notification-options {
            grid-template-columns: 1fr;
        }
        
        .wizard-steps::before {
            top: 25px;
        }
        
        .wizard-step-circle {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }
        
        .wizard-step-label {
            font-size: 0.7rem;
        }
    }

    /* ============================================
           ANIMATIONS
        ============================================ */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.5s ease forwards;
    }

    .char-count {
        font-size: 0.75rem;
        color: var(--ju-gray-500);
    }

    .char-count.warning {
        color: var(--ju-warning);
    }

    .char-count.danger {
        color: var(--ju-danger);
    }

    .required::after {
        content: '*';
        color: var(--ju-danger);
        margin-left: 4px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Wizard Progress -->
            <div class="create-wizard" data-aos="fade-down">
                <div class="wizard-steps">
                    <div class="wizard-step active" id="step1">
                        <div class="wizard-step-circle">1</div>
                        <div class="wizard-step-label">Basic Info</div>
                    </div>
                    <div class="wizard-step" id="step2">
                        <div class="wizard-step-circle">2</div>
                        <div class="wizard-step-label">Content</div>
                    </div>
                    <div class="wizard-step" id="step3">
                        <div class="wizard-step-circle">3</div>
                        <div class="wizard-step-label">Audience</div>
                    </div>
                    <div class="wizard-step" id="step4">
                        <div class="wizard-step-circle">4</div>
                        <div class="wizard-step-label">Settings</div>
                    </div>
                </div>
            </div>

            <form action="{{ route('announcements.store') }}" method="POST" id="announcementForm">
                @csrf
                
                <!-- ============================================
                     OPTIONAL CANCELLATION SECTION - TRULY OPTIONAL
                     Placed outside wizard so it doesn't block navigation
                ============================================ -->
                <div class="form-section mb-4">
                    <div class="form-section-title">
                        <div class="section-icon warning">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div class="section-title-text">
                            <h4>Event Cancellation (Optional)</h4>
                            <p>Only check this if you're creating a cancellation announcement</p>
                        </div>
                        <div class="section-optional">OPTIONAL</div>
                    </div>

                    <!-- Cancellation Toggle (Truly Optional) -->
                    <div class="cancellation-optional {{ old('is_event_cancellation') ? 'active' : '' }}" 
                         onclick="toggleCancellation(this)" id="cancellationToggle">
                        <input type="checkbox" name="is_event_cancellation" id="is_event_cancellation" 
                               value="1" {{ old('is_event_cancellation') ? 'checked' : '' }} 
                               class="d-none">
                        <div class="cancellation-toggle-content">
                            <div class="cancellation-icon">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <div>
                                <div class="cancellation-title">This is an Event Cancellation</div>
                                <div class="cancellation-subtitle">
                                    Check this ONLY if you're cancelling an existing event
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cancellation Section (Shows only when toggled) -->
                    <div id="cancellationSection" style="display: {{ old('is_event_cancellation') ? 'block' : 'none' }};">
                        <div class="cancellation-section">
                            <div class="cancellation-header">
                                <div class="cancellation-header-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h4>Select Event to Cancel</h4>
                                    <p>Choose the event you want to cancel from the list below</p>
                                </div>
                            </div>

                            <!-- Event Search -->
                            <div class="event-search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" id="eventSearch" placeholder="Search events by title, venue, or date..." onkeyup="filterEvents()">
                            </div>

                            <!-- Events List -->
                            <div class="events-list" id="eventsList">
                                @forelse($cancellableEvents as $event)
                                <div class="event-select-card {{ old('cancelled_event_id') == $event->id ? 'selected' : '' }}"
                                     onclick="selectEvent(this, {{ $event->id }})">
                                    <div class="event-select-radio"></div>
                                    <input type="radio" name="cancelled_event_id" value="{{ $event->id }}" 
                                           {{ old('cancelled_event_id') == $event->id ? 'checked' : '' }}
                                           class="d-none">
                                    
                                    @if($event->image)
                                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="event-select-image">
                                    @else
                                    <div class="event-select-image">
                                        <i class="fas fa-calendar-alt fa-2x"></i>
                                    </div>
                                    @endif
                                    
                                    <div class="event-select-info">
                                        <div class="event-select-title">{{ $event->title }}</div>
                                        <div class="event-select-meta">
                                            <span><i class="fas fa-calendar"></i> {{ $event->start_date->format('M d, Y - g:i A') }}</span>
                                            <span><i class="fas fa-map-marker-alt"></i> {{ $event->venue_name ?? 'TBD' }}</span>
                                            @if(method_exists($event, 'registrations'))
                                            <span><i class="fas fa-users"></i> {{ $event->registrations()->count() }} registered</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No upcoming events available for cancellation</p>
                                </div>
                                @endforelse
                            </div>

                            <!-- Cancellation Reason -->
                            <div class="cancellation-reason">
                                <label>
                                    <i class="fas fa-pen"></i> Reason for Cancellation
                                </label>
                                <textarea name="cancellation_reason" 
                                          id="cancellation_reason" 
                                          rows="4"
                                          placeholder="Please explain why this event is being cancelled...">{{ old('cancellation_reason') }}</textarea>
                                @error('cancellation_reason')
                                    <div class="text-danger mt-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info mt-4 mb-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle fa-lg me-3"></i>
                                    <div>
                                        <strong>Note:</strong> When you cancel an event, a cancellation announcement will be created automatically. 
                                        The title will be prefixed with "CANCELLED:" and the type will be set to "Urgent".
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================
                     STEP 1: BASIC INFORMATION
                ============================================ -->
                <div class="form-section step-section" id="section1">
                    <div class="form-section-title">
                        <div class="section-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="section-title-text">
                            <h4>Basic Information</h4>
                            <p>Provide the essential details for your announcement</p>
                        </div>
                        <div class="section-required">REQUIRED</div>
                    </div>

                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold required">
                            <i class="fas fa-heading me-2 text-primary"></i>Announcement Title
                        </label>
                        <input type="text" name="title" id="title" 
                               class="form-control form-control-lg" 
                               placeholder="Enter a clear and descriptive title..." 
                               required maxlength="255"
                               value="{{ old('title') }}">
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Make it clear and concise</small>
                            <span class="char-count" id="titleCharCount">0/255</span>
                        </div>
                        @error('title')
                            <div class="text-danger mt-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Type Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold required">
                            <i class="fas fa-tag me-2 text-primary"></i>Announcement Type
                        </label>
                        <div class="type-options-grid">
                            <label class="type-option {{ old('type', 'general') == 'general' ? 'selected' : '' }}">
                                <input type="radio" name="type" value="general" 
                                       {{ old('type', 'general') == 'general' ? 'checked' : '' }}>
                                <div>
                                    <i class="fas fa-bullhorn type-icon" style="color: #9b59b6;"></i>
                                    <div class="fw-semibold mt-2">General</div>
                                    <small class="text-muted">General updates</small>
                                </div>
                            </label>
                            <label class="type-option {{ old('type') == 'event' ? 'selected' : '' }}">
                                <input type="radio" name="type" value="event" 
                                       {{ old('type') == 'event' ? 'checked' : '' }}>
                                <div>
                                    <i class="fas fa-calendar-alt type-icon" style="color: #3498db;"></i>
                                    <div class="fw-semibold mt-2">Event</div>
                                    <small class="text-muted">Event announcements</small>
                                </div>
                            </label>
                            <label class="type-option {{ old('type') == 'campus' ? 'selected' : '' }}">
                                <input type="radio" name="type" value="campus" 
                                       {{ old('type') == 'campus' ? 'checked' : '' }}>
                                <div>
                                    <i class="fas fa-university type-icon" style="color: #2ecc71;"></i>
                                    <div class="fw-semibold mt-2">Campus</div>
                                    <small class="text-muted">Campus notices</small>
                                </div>
                            </label>
                            <label class="type-option {{ old('type') == 'urgent' ? 'selected' : '' }}">
                                <input type="radio" name="type" value="urgent" 
                                       {{ old('type') == 'urgent' ? 'checked' : '' }}>
                                <div>
                                    <i class="fas fa-exclamation-triangle type-icon" style="color: #e74c3c;"></i>
                                    <div class="fw-semibold mt-2">Urgent</div>
                                    <small class="text-muted">Important alerts</small>
                                </div>
                            </label>
                        </div>
                        @error('type')
                            <div class="text-danger mt-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-outline-primary" onclick="nextStep(2)">
                            Next: Content <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- ============================================
                     STEP 2: CONTENT
                ============================================ -->
                <div class="form-section step-section" id="section2" style="display: none;">
                    <div class="form-section-title">
                        <div class="section-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="section-title-text">
                            <h4>Announcement Content</h4>
                            <p>Write the main content of your announcement</p>
                        </div>
                        <div class="section-required">REQUIRED</div>
                    </div>

                    <!-- Editor -->
                    <div class="mb-4">
                        <label class="form-label fw-bold required">
                            <i class="fas fa-pen me-2 text-primary"></i>Content
                        </label>
                        
                        <div class="editor-wrapper">
                            <div class="editor-toolbar">
                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" onclick="formatText('bold')" title="Bold (Ctrl+B)">
                                        <i class="fas fa-bold"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" onclick="formatText('italic')" title="Italic (Ctrl+I)">
                                        <i class="fas fa-italic"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" onclick="formatText('underline')" title="Underline (Ctrl+U)">
                                        <i class="fas fa-underline"></i>
                                    </button>
                                </div>
                                
                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" onclick="formatText('heading', 2)" title="Heading 2">
                                        <i class="fas fa-heading"></i> H2
                                    </button>
                                    <button type="button" class="toolbar-btn" onclick="formatText('heading', 3)" title="Heading 3">
                                        <i class="fas fa-heading"></i> H3
                                    </button>
                                </div>
                                
                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" onclick="insertList('ul')" title="Bullet List">
                                        <i class="fas fa-list-ul"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" onclick="insertList('ol')" title="Numbered List">
                                        <i class="fas fa-list-ol"></i>
                                    </button>
                                </div>
                                
                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" onclick="insertLink()" title="Insert Link">
                                        <i class="fas fa-link"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" onclick="insertImage()" title="Insert Image">
                                        <i class="fas fa-image"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" onclick="clearFormatting()" title="Clear Formatting">
                                        <i class="fas fa-eraser"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <textarea name="content" id="content" 
                                      class="editor-content" 
                                      rows="15" 
                                      placeholder="Write your announcement content here..." 
                                      required>{{ old('content') }}</textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">You can use HTML formatting tags</small>
                            <span class="char-count" id="contentCharCount">0/5000</span>
                        </div>
                        
                        @error('content')
                            <div class="text-danger mt-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(1)">
                            <i class="fas fa-arrow-left me-2"></i> Previous
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="nextStep(3)">
                            Next: Audience <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- ============================================
                     STEP 3: AUDIENCE
                ============================================ -->
                <div class="form-section step-section" id="section3" style="display: none;">
                    <div class="form-section-title">
                        <div class="section-icon success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="section-title-text">
                            <h4>Target Audience</h4>
                            <p>Select who should see this announcement</p>
                        </div>
                        <div class="section-required">REQUIRED</div>
                    </div>

                    <!-- Audience Selection -->
                    <div class="mb-4">
                        <div class="audience-options-grid">
                            <label class="audience-option {{ old('audience', 'all') == 'all' ? 'selected' : '' }}">
                                <input type="radio" name="audience" value="all" 
                                       {{ old('audience', 'all') == 'all' ? 'checked' : '' }}>
                                <div>
                                    <i class="fas fa-users audience-icon"></i>
                                    <div class="audience-title">Everyone</div>
                                    <div class="audience-description">All university members</div>
                                </div>
                            </label>
                            
                            <label class="audience-option {{ old('audience') == 'students' ? 'selected' : '' }}">
                                <input type="radio" name="audience" value="students" 
                                       {{ old('audience') == 'students' ? 'checked' : '' }}>
                                <div>
                                    <i class="fas fa-user-graduate audience-icon"></i>
                                    <div class="audience-title">Students Only</div>
                                    <div class="audience-description">All registered students</div>
                                </div>
                            </label>
                            
                            <label class="audience-option {{ old('audience') == 'faculty' ? 'selected' : '' }}">
                                <input type="radio" name="audience" value="faculty" 
                                       {{ old('audience') == 'faculty' ? 'checked' : '' }}>
                                <div>
                                    <i class="fas fa-chalkboard-teacher audience-icon"></i>
                                    <div class="audience-title">Faculty Only</div>
                                    <div class="audience-description">Teaching staff</div>
                                </div>
                            </label>
                            
                            <label class="audience-option {{ old('audience') == 'staff' ? 'selected' : '' }}">
                                <input type="radio" name="audience" value="staff" 
                                       {{ old('audience') == 'staff' ? 'checked' : '' }}>
                                <div>
                                    <i class="fas fa-user-tie audience-icon"></i>
                                    <div class="audience-title">Staff Only</div>
                                    <div class="audience-description">Administrative staff</div>
                                </div>
                            </label>

                            <label class="audience-option {{ old('audience') == 'event_managers' ? 'selected' : '' }}">
                                <input type="radio" name="audience" value="event_managers" 
                                       {{ old('audience') == 'event_managers' ? 'checked' : '' }}>
                                <div>
                                    <i class="fas fa-calendar-check audience-icon"></i>
                                    <div class="audience-title">Event Managers</div>
                                    <div class="audience-description">Event management team only</div>
                                </div>
                            </label>
                            
                            <label class="audience-option {{ old('audience') == 'specific' ? 'selected' : '' }}">
                                <input type="radio" name="audience" value="specific" 
                                       {{ old('audience') == 'specific' ? 'checked' : '' }}>
                                <div>
                                    <i class="fas fa-user-friends audience-icon"></i>
                                    <div class="audience-title">Specific Users</div>
                                    <div class="audience-description">Select individuals</div>
                                </div>
                            </label>
                        </div>
                        
                        @error('audience')
                            <div class="text-danger mt-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Specific Users Selection -->
                    <div id="specificUsersSection" style="display: {{ old('audience') == 'specific' ? 'block' : 'none' }};">
                        <div class="specific-users-section">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-user-check me-2"></i>Select Specific Users
                                <small class="text-muted ms-2">(Select one or more users)</small>
                            </h6>
                            
                            <div class="users-search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" id="userSearch" 
                                       class="form-control" 
                                       placeholder="Search users by name or email..."
                                       onkeyup="filterUsers()">
                            </div>
                            
                            <div class="users-list-container" id="usersList">
                                @php
                                    $selectedUsers = old('target_ids', []);
                                @endphp
                                
                                <!-- Students -->
                                @if($users->where('role.slug', 'student')->count() > 0)
                                <div class="user-group" data-role="student">
                                    <h6>
                                        Students
                                        <span class="select-all-btn" onclick="selectGroup('student')">
                                            <i class="fas fa-check-double me-1"></i>Select All
                                        </span>
                                    </h6>
                                    @foreach($users->where('role.slug', 'student') as $user)
                                    <div class="user-item" data-role="student">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="target_ids[]" value="{{ $user->id }}" 
                                                   id="user{{ $user->id }}"
                                                   {{ in_array($user->id, (array)$selectedUsers) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="user{{ $user->id }}">
                                                {{ $user->name }} 
                                                <small class="text-muted">({{ $user->email }})</small>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                                
                                <!-- Faculty -->
                                @if($users->where('role.slug', 'faculty')->count() > 0)
                                <div class="user-group" data-role="faculty">
                                    <h6>
                                        Faculty
                                        <span class="select-all-btn" onclick="selectGroup('faculty')">
                                            <i class="fas fa-check-double me-1"></i>Select All
                                        </span>
                                    </h6>
                                    @foreach($users->where('role.slug', 'faculty') as $user)
                                    <div class="user-item" data-role="faculty">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="target_ids[]" value="{{ $user->id }}" 
                                                   id="user{{ $user->id }}"
                                                   {{ in_array($user->id, (array)$selectedUsers) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="user{{ $user->id }}">
                                                {{ $user->name }} 
                                                <small class="text-muted">({{ $user->email }})</small>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                                
                                <!-- Staff -->
                                @if($users->where('role.slug', 'staff')->count() > 0)
                                <div class="user-group" data-role="staff">
                                    <h6>
                                        Staff
                                        <span class="select-all-btn" onclick="selectGroup('staff')">
                                            <i class="fas fa-check-double me-1"></i>Select All
                                        </span>
                                    </h6>
                                    @foreach($users->where('role.slug', 'staff') as $user)
                                    <div class="user-item" data-role="staff">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="target_ids[]" value="{{ $user->id }}" 
                                                   id="user{{ $user->id }}"
                                                   {{ in_array($user->id, (array)$selectedUsers) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="user{{ $user->id }}">
                                                {{ $user->name }} 
                                                <small class="text-muted">({{ $user->email }})</small>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif

                                <!-- Event Managers -->
                                @if($users->whereIn('role.slug', ['event-manager', 'event_manager', 'eventmanager'])->count() > 0)
                                <div class="user-group" data-role="event-manager">
                                    <h6>
                                        Event Managers
                                        <span class="select-all-btn" onclick="selectGroup('event-manager')">
                                            <i class="fas fa-check-double me-1"></i>Select All
                                        </span>
                                    </h6>
                                    @foreach($users->whereIn('role.slug', ['event-manager', 'event_manager', 'eventmanager']) as $user)
                                    <div class="user-item" data-role="event-manager">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="target_ids[]" value="{{ $user->id }}" 
                                                   id="user{{ $user->id }}"
                                                   {{ in_array($user->id, (array)$selectedUsers) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="user{{ $user->id }}">
                                                {{ $user->name }} 
                                                <small class="text-muted">({{ $user->email }})</small>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            
                            <div class="mt-3">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllUsers()">
                                    <i class="fas fa-check-double me-1"></i>Select All Users
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllUsers()">
                                    <i class="fas fa-times me-1"></i>Deselect All
                                </button>
                            </div>
                            
                            @error('target_ids')
                                <div class="text-danger mt-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                            <i class="fas fa-arrow-left me-2"></i> Previous
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="nextStep(4)">
                            Next: Settings <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- ============================================
                     STEP 4: SETTINGS & PUBLISH
                ============================================ -->
                <div class="form-section step-section" id="section4" style="display: none;">
                    <div class="form-section-title">
                        <div class="section-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="section-title-text">
                            <h4>Settings & Publishing</h4>
                            <p>Configure notification and expiration settings</p>
                        </div>
                        <div class="section-optional">OPTIONAL</div>
                    </div>

                    <!-- Notification Options -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-bell me-2 text-primary"></i>Notification Settings
                        </label>
                        
                        <div class="notification-options">
                            <label class="notification-option {{ old('send_notification', true) ? 'selected' : '' }}">
                                <input type="radio" name="send_notification" value="1" 
                                       {{ old('send_notification', true) ? 'checked' : '' }}>
                                <div>
                                    <i class="fas fa-bell notification-icon" style="color: #10b981;"></i>
                                    <div class="fw-semibold mt-2">Send Notification</div>
                                    <div class="text-muted small">Users will receive alerts</div>
                                </div>
                            </label>
                            <label class="notification-option {{ !old('send_notification', true) ? 'selected' : '' }}">
                                <input type="radio" name="send_notification" value="0" 
                                       {{ !old('send_notification', true) ? 'checked' : '' }}>
                                <div>
                                    <i class="fas fa-bell-slash notification-icon" style="color: #64748b;"></i>
                                    <div class="fw-semibold mt-2">No Notification</div>
                                    <div class="text-muted small">Announcement only</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Advanced Notification Options -->
                    <div id="advancedNotificationOptions" style="display: {{ old('send_notification', true) ? 'block' : 'none' }};">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="notification_priority" class="form-label fw-semibold">
                                    <i class="fas fa-flag me-2"></i>Priority
                                </label>
                                <select name="notification_priority" id="notification_priority" class="form-select">
                                    <option value="normal">Normal</option>
                                    <option value="high">High</option>
                                    <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="notification_type" class="form-label fw-semibold">
                                    <i class="fas fa-tag me-2"></i>Notification Type
                                </label>
                                <select name="notification_type" id="notification_type" class="form-select">
                                    <option value="announcement">Announcement</option>
                                    <option value="alert">Alert</option>
                                    <option value="info">Information</option>
                                    <option value="warning">Warning</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Expiration & Publishing -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="expires_at" class="form-label fw-semibold">
                                <i class="fas fa-clock me-2 text-primary"></i>Expiration Date (Optional)
                            </label>
                            <input type="datetime-local" name="expires_at" id="expires_at" 
                                   class="form-control" 
                                   value="{{ old('expires_at') }}"
                                   min="{{ now()->format('Y-m-d\TH:i') }}">
                            <small class="text-muted">
                                Leave empty if the announcement should not expire
                            </small>
                            @error('expires_at')
                                <div class="text-danger mt-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       name="publish_now" id="publish_now" value="1"
                                       {{ old('publish_now', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="publish_now">
                                    <i class="fas fa-paper-plane me-2"></i>Publish Immediately
                                </label>
                                <div class="text-muted small">
                                    If unchecked, announcement will be saved as draft
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                        <div>
                            <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary" onclick="saveAsDraft()">
                                <i class="fas fa-save me-2"></i>Save as Draft
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>
                                <span id="submitButtonText">
                                    {{ old('send_notification', true) ? 'Publish & Send' : 'Publish' }}
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-start mt-3">
                        <button type="button" class="btn btn-link" onclick="prevStep(3)">
                            <i class="fas fa-arrow-left me-2"></i> Back to Audience
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ============================================
             PREVIEW SIDEBAR
        ============================================ -->
        <div class="col-lg-4">
            <div class="preview-card">
                <div class="form-section">
                    <div class="form-section-title">
                        <div class="section-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="section-title-text">
                            <h4>Live Preview</h4>
                            <p>See how your announcement will look</p>
                        </div>
                    </div>

                    <div id="previewContent" class="preview-content">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-newspaper fa-3x mb-3"></i>
                            <p>Your announcement preview will appear here</p>
                            <small class="d-block">As you type in the form, the preview updates automatically</small>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4 mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-3"></i>
                            <small>The preview updates as you type. This is how your announcement will appear to users.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="form-section mt-4">
                <div class="form-section-title">
                    <div class="section-icon warning">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="section-title-text">
                        <h4>Tips for Effective Announcements</h4>
                    </div>
                </div>
                
                <ul class="list-unstyled mb-0">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Clear Title:</strong> Make it descriptive and concise
                        </div>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Use Formatting:</strong> Break content with headings and lists
                        </div>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Be Specific:</strong> Include dates, times, and locations
                        </div>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Set Audience:</strong> Target the right people
                        </div>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>For Cancellations:</strong> Only use when cancelling an event
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- ============================================
     JAVASCRIPT
============================================ -->
<script>
// ============================================
// WIZARD NAVIGATION
// ============================================
let currentStep = 1;
const totalSteps = 4;

function nextStep(step) {
    // Validate current step if needed
    if (step > currentStep) {
        if (!validateStep(currentStep)) {
            return;
        }
    }
    
    // Hide all sections
    document.querySelectorAll('.step-section').forEach(section => {
        section.style.display = 'none';
    });
    
    // Show target section
    document.getElementById(`section${step}`).style.display = 'block';
    
    // Update wizard steps
    for (let i = 1; i <= totalSteps; i++) {
        const stepElement = document.getElementById(`step${i}`);
        if (i < step) {
            stepElement.className = 'wizard-step completed';
        } else if (i === step) {
            stepElement.className = 'wizard-step active';
        } else {
            stepElement.className = 'wizard-step';
        }
    }
    
    currentStep = step;
    
    // Scroll to top of form
    document.querySelector('.container-fluid').scrollIntoView({ behavior: 'smooth' });
}

function prevStep(step) {
    nextStep(step);
}

function validateStep(step) {
    if (step === 1) {
        const title = document.getElementById('title').value.trim();
        if (!title) {
            alert('Please enter a title for your announcement.');
            document.getElementById('title').focus();
            return false;
        }
    }
    
    if (step === 2) {
        const content = document.getElementById('content').value.trim();
        if (!content) {
            alert('Please enter content for your announcement.');
            document.getElementById('content').focus();
            return false;
        }
    }
    
    return true;
}

// ============================================
// CANCELLATION TOGGLE - TRULY OPTIONAL
// ============================================
function toggleCancellation(element) {
    const checkbox = document.getElementById('is_event_cancellation');
    const section = document.getElementById('cancellationSection');
    const isChecked = !checkbox.checked;
    
    checkbox.checked = isChecked;
    
    if (isChecked) {
        element.classList.add('active');
        section.style.display = 'block';
    } else {
        element.classList.remove('active');
        section.style.display = 'none';
        
        // Clear cancellation fields when unchecked
        document.querySelectorAll('input[name="cancelled_event_id"]').forEach(radio => {
            radio.checked = false;
        });
        document.getElementById('cancellation_reason').value = '';
        
        // Remove selected class from all event cards
        document.querySelectorAll('.event-select-card').forEach(card => {
            card.classList.remove('selected');
        });
    }
}

// ============================================
// EVENT SELECTION
// ============================================
function selectEvent(element, eventId) {
    // Remove selected class from all event cards
    document.querySelectorAll('.event-select-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selected class to clicked card
    element.classList.add('selected');
    
    // Check the radio button
    const radio = element.querySelector('input[type="radio"]');
    radio.checked = true;
    
    // Auto-fill title with cancellation prefix
    const titleInput = document.getElementById('title');
    const eventTitle = element.querySelector('.event-select-title').textContent.trim();
    titleInput.value = `CANCELLED: ${eventTitle}`;
    updateCharCount('title', 'titleCharCount', 255);
    
    // Auto-fill content with cancellation template
    const contentArea = document.getElementById('content');
    const date = element.querySelector('.event-select-meta span:first-child').textContent.trim();
    const location = element.querySelector('.event-select-meta span:nth-child(2)').textContent.trim();
    
    const template = `<p>We regret to inform you that the following event has been cancelled:</p>

<h3>${eventTitle}</h3>

<p><strong>Original Date:</strong> ${date}</p>
<p><strong>Location:</strong> ${location}</p>

<p><strong>Reason:</strong> [Please provide reason in the cancellation reason field]</p>

<p>We apologize for any inconvenience this may cause. If you have any questions, please contact the event organizer.</p>`;
    
    contentArea.value = template;
    
    updateCharCount('content', 'contentCharCount', 5000);
    updatePreview();
}

// ============================================
// EVENT FILTERING
// ============================================
function filterEvents() {
    const searchText = document.getElementById('eventSearch').value.toLowerCase();
    const events = document.querySelectorAll('.event-select-card');
    
    events.forEach(event => {
        const title = event.querySelector('.event-select-title')?.textContent.toLowerCase() || '';
        const meta = event.querySelector('.event-select-meta')?.textContent.toLowerCase() || '';
        
        if (title.includes(searchText) || meta.includes(searchText)) {
            event.style.display = 'flex';
        } else {
            event.style.display = 'none';
        }
    });
}

// ============================================
// PREVIEW UPDATE
// ============================================
function updatePreview() {
    const title = document.getElementById('title').value;
    const content = document.getElementById('content').value;
    const type = document.querySelector('input[name="type"]:checked')?.value || 'general';
    const previewDiv = document.getElementById('previewContent');
    const isCancellation = document.getElementById('is_event_cancellation').checked;
    
    let typeColor = 'general';
    let typeIcon = 'bullhorn';
    let typeBg = '#9b59b6';
    
    if (type === 'event') { 
        typeColor = 'event'; 
        typeIcon = 'calendar-alt';
        typeBg = '#3498db';
    } else if (type === 'campus') { 
        typeColor = 'campus'; 
        typeIcon = 'university';
        typeBg = '#2ecc71';
    } else if (type === 'urgent' || isCancellation) { 
        typeColor = 'urgent'; 
        typeIcon = 'exclamation-triangle';
        typeBg = '#e74c3c';
    }
    
    // If cancellation, override
    if (isCancellation) {
        typeColor = 'urgent';
        typeIcon = 'ban';
        typeBg = '#dc2626';
    }
    
    previewDiv.innerHTML = `
        <div class="announcement-preview">
            <div class="preview-header">
                <span class="preview-badge ${typeColor}" style="background: ${typeBg}20; color: ${typeBg}; border: 1px solid ${typeBg}40;">
                    <i class="fas fa-${typeIcon} me-1"></i>
                    ${type.charAt(0).toUpperCase() + type.slice(1)} ${isCancellation ? 'Cancellation' : ''}
                </span>
            </div>
            <h5 class="preview-title">${title || 'Announcement Title'}</h5>
            <div class="preview-meta">
                <span><i class="fas fa-user"></i> You</span>
                <span><i class="fas fa-calendar"></i> Just now</span>
                <span><i class="fas fa-users"></i> ${getAudienceLabel()}</span>
            </div>
            <div class="preview-body">
                ${content || '<p class="text-muted">Your announcement content will appear here...</p>'}
            </div>
        </div>
    `;
}

function getAudienceLabel() {
    const audience = document.querySelector('input[name="audience"]:checked')?.value || 'all';
    const labels = {
        'all': 'Everyone',
        'students': 'Students Only',
        'faculty': 'Faculty Only',
        'staff': 'Staff Only',
        'event_managers': 'Event Managers Only',
        'specific': 'Specific Users'
    };
    return labels[audience] || 'Everyone';
}

// ============================================
// CHARACTER COUNTS
// ============================================
document.getElementById('title').addEventListener('input', function() {
    updateCharCount('title', 'titleCharCount', 255);
    updatePreview();
});

document.getElementById('content').addEventListener('input', function() {
    updateCharCount('content', 'contentCharCount', 5000);
    updatePreview();
});

function updateCharCount(inputId, countId, maxLength) {
    const input = document.getElementById(inputId);
    const count = document.getElementById(countId);
    const length = input.value.length;
    count.textContent = `${length}/${maxLength}`;
    
    const countElement = count;
    if (length > maxLength * 0.9) {
        countElement.className = 'char-count danger';
    } else if (length > maxLength * 0.7) {
        countElement.className = 'char-count warning';
    } else {
        countElement.className = 'char-count';
    }
}

// ============================================
// TEXT FORMATTING
// ============================================
function formatText(command, level = null) {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    let formattedText = selectedText;
    switch(command) {
        case 'bold':
            formattedText = `<b>${selectedText}</b>`;
            break;
        case 'italic':
            formattedText = `<i>${selectedText}</i>`;
            break;
        case 'underline':
            formattedText = `<u>${selectedText}</u>`;
            break;
        case 'heading':
            formattedText = `<h${level}>${selectedText || 'Heading Text'}</h${level}>`;
            break;
    }
    
    textarea.value = textarea.value.substring(0, start) + formattedText + textarea.value.substring(end);
    textarea.focus();
    updateCharCount('content', 'contentCharCount', 5000);
    updatePreview();
}

function insertList(type) {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const list = type === 'ul' 
        ? '<ul>\n  <li>Item 1</li>\n  <li>Item 2</li>\n  <li>Item 3</li>\n</ul>'
        : '<ol>\n  <li>Item 1</li>\n  <li>Item 2</li>\n  <li>Item 3</li>\n</ol>';
    
    textarea.value = textarea.value.substring(0, start) + '\n' + list + '\n' + textarea.value.substring(start);
    textarea.focus();
    updateCharCount('content', 'contentCharCount', 5000);
    updatePreview();
}

function insertLink() {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end) || 'Link Text';
    const link = `<a href="https://" target="_blank">${selectedText}</a>`;
    
    textarea.value = textarea.value.substring(0, start) + link + textarea.value.substring(end);
    textarea.focus();
    updateCharCount('content', 'contentCharCount', 5000);
    updatePreview();
}

function insertImage() {
    alert('Image upload will be handled in a future update.');
}

function clearFormatting() {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    // Remove HTML tags
    const plainText = selectedText.replace(/<[^>]*>/g, '');
    
    textarea.value = textarea.value.substring(0, start) + plainText + textarea.value.substring(end);
    textarea.focus();
    updateCharCount('content', 'contentCharCount', 5000);
    updatePreview();
}

// ============================================
// AUDIENCE HANDLING
// ============================================
document.querySelectorAll('input[name="audience"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Update UI for selected option
        document.querySelectorAll('.audience-option').forEach(option => {
            option.classList.remove('selected');
        });
        this.closest('.audience-option').classList.add('selected');
        
        // Show/hide specific users section
        const specificUsersSection = document.getElementById('specificUsersSection');
        if (this.value === 'specific') {
            specificUsersSection.style.display = 'block';
        } else {
            specificUsersSection.style.display = 'none';
        }
        
        updatePreview();
    });
});

// ============================================
// USER FILTERING
// ============================================
function filterUsers() {
    const searchText = document.getElementById('userSearch').value.toLowerCase();
    const users = document.querySelectorAll('.user-item');
    
    users.forEach(user => {
        const label = user.querySelector('label').textContent.toLowerCase();
        if (label.includes(searchText)) {
            user.style.display = 'flex';
        } else {
            user.style.display = 'none';
        }
    });
}

function selectGroup(role) {
    const checkboxes = document.querySelectorAll(`.user-group[data-role="${role}"] .user-item input[type="checkbox"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
}

function selectAllUsers() {
    document.querySelectorAll('input[name="target_ids[]"]').forEach(cb => {
        cb.checked = true;
    });
}

function deselectAllUsers() {
    document.querySelectorAll('input[name="target_ids[]"]').forEach(cb => {
        cb.checked = false;
    });
}

// ============================================
// NOTIFICATION OPTIONS
// ============================================
document.querySelectorAll('input[name="send_notification"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.notification-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        this.closest('.notification-option').classList.add('selected');
        
        const advancedOptions = document.getElementById('advancedNotificationOptions');
        const submitBtnText = document.getElementById('submitButtonText');
        
        if (this.value === '1') {
            advancedOptions.style.display = 'block';
            submitBtnText.textContent = 'Publish & Send';
        } else {
            advancedOptions.style.display = 'none';
            submitBtnText.textContent = 'Publish';
        }
    });
});

// ============================================
// TYPE SELECTION FOR PRIORITY
// ============================================
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Update UI for selected option
        document.querySelectorAll('.type-option').forEach(option => {
            option.classList.remove('selected');
        });
        this.closest('.type-option').classList.add('selected');
        
        // Set notification priority if urgent
        if (this.value === 'urgent') {
            document.getElementById('notification_priority').value = 'urgent';
        }
        
        updatePreview();
    });
});

// ============================================
// FORM VALIDATION - FIXED FOR OPTIONAL CANCELLATION
// ============================================
document.getElementById('announcementForm').addEventListener('submit', function(e) {
    const isCancellation = document.getElementById('is_event_cancellation').checked;
    
    // Only validate cancellation fields if the cancellation option IS checked
    if (isCancellation) {
        const eventSelected = document.querySelector('input[name="cancelled_event_id"]:checked');
        const reason = document.getElementById('cancellation_reason').value.trim();
        
        if (!eventSelected) {
            e.preventDefault();
            alert('Please select an event to cancel.');
            document.getElementById('cancellationSection').scrollIntoView({ behavior: 'smooth' });
            return;
        }
        
        if (!reason) {
            e.preventDefault();
            alert('Please provide a reason for cancellation.');
            document.getElementById('cancellation_reason').focus();
            return;
        }
    }
    
    // Always validate title and content
    const title = document.getElementById('title').value.trim();
    const content = document.getElementById('content').value.trim();
    
    if (!title) {
        e.preventDefault();
        alert('Please enter a title.');
        document.getElementById('title').focus();
        return;
    }
    
    if (!content) {
        e.preventDefault();
        alert('Please enter content.');
        document.getElementById('content').focus();
        return;
    }
    
    // If specific audience is selected, check if users are selected
    const specificAudience = document.querySelector('input[name="audience"]:checked')?.value === 'specific';
    if (specificAudience) {
        const selectedUsers = document.querySelectorAll('input[name="target_ids[]"]:checked').length;
        if (selectedUsers === 0) {
            e.preventDefault();
            alert('Please select at least one user for specific audience.');
            document.getElementById('specificUsersSection').scrollIntoView({ behavior: 'smooth' });
            return;
        }
    }
});

function saveAsDraft() {
    document.getElementById('publish_now').checked = false;
    document.getElementById('announcementForm').submit();
}

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Set min date for expiration
    document.getElementById('expires_at').min = new Date().toISOString().slice(0, 16);
    
    // Initialize character counts
    updateCharCount('title', 'titleCharCount', 255);
    updateCharCount('content', 'contentCharCount', 5000);
    
    // Initialize preview
    updatePreview();
    
    // Check if coming from cancellation
    const isCancellation = document.getElementById('is_event_cancellation').checked;
    if (isCancellation) {
        document.getElementById('cancellationSection').style.display = 'block';
    }
});
</script>
@endsection
