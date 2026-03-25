<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - JU Event Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --ju-navy: #0a1929;
            --ju-blue: #0d2b4b;
            --ju-sky: #2c4b73;
            --ju-soft: #edf4fb;
            --ju-mint: #e5f6ec;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at 10% 20%, #e4f1ff 0%, transparent 35%),
                radial-gradient(circle at 90% 15%, #dff2f5 0%, transparent 35%),
                linear-gradient(145deg, #f8fbff 0%, #edf3fa 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 12px;
        }

        .auth-shell {
            width: 100%;
            max-width: 1080px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 18px 45px rgba(10, 25, 41, 0.18);
            background: #fff;
        }

        .auth-side {
            background: linear-gradient(165deg, var(--ju-navy), var(--ju-blue));
            color: #fff;
            padding: 34px 26px;
            height: 100%;
        }

        .auth-logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
            background: #fff;
            border-radius: 50%;
            padding: 8px;
            margin-bottom: 14px;
        }

        .feature-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .feature-list li {
            margin-bottom: 10px;
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .feature-list i {
            width: 20px;
        }

        .auth-form {
            padding: 34px 26px;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
            animation: fadeIn 0.25s ease;
        }

        .step-indicator {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
        }

        .step-pill {
            flex: 1;
            text-align: center;
            padding: 8px 10px;
            border-radius: 999px;
            font-size: 0.86rem;
            font-weight: 600;
            background: var(--ju-soft);
            color: #4a617a;
            border: 1px solid #d6e1ee;
        }

        .step-pill.active {
            background: #d9e8fb;
            color: var(--ju-blue);
            border-color: #b6cdee;
        }

        .form-label {
            font-weight: 600;
            color: var(--ju-navy);
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            border-right: 0;
            background: #f8fafc;
        }

        .form-control {
            border-left: 0;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 43, 75, 0.14);
            border-color: #86a3c4;
        }

        .input-group:focus-within .input-group-text {
            border-color: #86a3c4;
        }

        .btn-main {
            background: linear-gradient(135deg, var(--ju-navy), var(--ju-blue));
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.72rem 1rem;
            border-radius: 10px;
        }

        .btn-main:hover {
            color: #fff;
            background: linear-gradient(135deg, var(--ju-blue), var(--ju-sky));
        }

        .btn-muted {
            border-radius: 10px;
        }

        .hint-card {
            background: var(--ju-mint);
            border: 1px solid #ccead8;
            color: #1e5632;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.9rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 767px) {
            .auth-side {
                padding: 22px 18px;
            }

            .auth-form {
                padding: 22px 18px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-shell">
        <div class="row g-0">
            <div class="col-md-5 d-none d-md-block">
                <div class="auth-side d-flex flex-column justify-content-between">
                    <div>
                        <img src="{{ asset('images/jimma-logo.png') }}" alt="Jimma University" class="auth-logo">
                        <h3 class="fw-bold mb-2">Create your account</h3>
                        <p class="mb-3 text-white-50">All new signups start with Guest access. Role assignment is done by Super Administrator.</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> Browse and register for events</li>
                            <li><i class="fas fa-check-circle"></i> Submit feedback and requests</li>
                            <li><i class="fas fa-check-circle"></i> Profile and notification access</li>
                        </ul>
                    </div>
                    <small class="text-white-50">JU Event Management Platform</small>
                </div>
            </div>

            <div class="col-md-7">
                <div class="auth-form">
                    <div class="d-md-none text-center mb-3">
                        <img src="{{ asset('images/jimma-logo.png') }}" alt="Jimma University" class="auth-logo" style="margin-bottom:8px;">
                        <h5 class="fw-bold mb-0" style="color:#0a1929;">JU Event Management</h5>
                    </div>

                    <h4 class="fw-bold mb-1" style="color:#0a1929;">Register</h4>
                    <p class="text-muted mb-3">Fill in your details to create an account.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="step-indicator">
                        <div class="step-pill active" id="pill1">1. Basic Info</div>
                        <div class="step-pill" id="pill2">2. Security</div>
                    </div>

                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <div class="form-step active" id="step1">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user text-secondary"></i></span>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Enter your full name"
                                        required
                                    >
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope text-secondary"></i></span>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        value="{{ old('email') }}"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="you@example.com"
                                        required
                                    >
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-main" id="nextStepBtn">
                                    Next <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-step" id="step2">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock text-secondary"></i></span>
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Create a strong password"
                                        required
                                    >
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Minimum 8 characters.</small>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock text-secondary"></i></span>
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        class="form-control"
                                        placeholder="Repeat your password"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label" for="terms">I agree to the platform terms and privacy policy.</label>
                            </div>

                            <div class="hint-card mb-3">
                                After signup, you will log in as <strong>Guest</strong>. Super Administrator can later assign Student, Faculty, Event Manager, or another role.
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary btn-muted" id="prevStepBtn">
                                    <i class="fas fa-arrow-left me-1"></i>Back
                                </button>
                                <button type="submit" class="btn btn-main">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
                            </div>
                        </div>
                    </form>

                    <p class="mb-0 text-muted text-center mt-3">
                        Already registered?
                        <a href="{{ route('login') }}" class="fw-semibold" style="color:#0d2b4b;">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var step1 = document.getElementById('step1');
            var step2 = document.getElementById('step2');
            var pill1 = document.getElementById('pill1');
            var pill2 = document.getElementById('pill2');
            var nextBtn = document.getElementById('nextStepBtn');
            var prevBtn = document.getElementById('prevStepBtn');

            function showStep(step) {
                if (step === 1) {
                    step1.classList.add('active');
                    step2.classList.remove('active');
                    pill1.classList.add('active');
                    pill2.classList.remove('active');
                } else {
                    step2.classList.add('active');
                    step1.classList.remove('active');
                    pill2.classList.add('active');
                    pill1.classList.remove('active');
                }
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    var name = document.getElementById('name');
                    var email = document.getElementById('email');
                    if (!name.value.trim() || !email.value.trim()) {
                        alert('Please complete full name and email first.');
                        return;
                    }
                    showStep(2);
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', function () {
                    showStep(1);
                });
            }

            var toggle = document.getElementById('togglePassword');
            var password = document.getElementById('password');
            if (toggle && password) {
                toggle.addEventListener('click', function () {
                    var icon = this.querySelector('i');
                    var isHidden = password.type === 'password';
                    password.type = isHidden ? 'text' : 'password';
                    icon.classList.toggle('fa-eye', !isHidden);
                    icon.classList.toggle('fa-eye-slash', isHidden);
                });
            }

            var invalidField = document.querySelector('.is-invalid');
            if (invalidField) {
                if (step1.contains(invalidField)) {
                    showStep(1);
                } else {
                    showStep(2);
                }
            }
        });
    </script>
</body>
</html>
