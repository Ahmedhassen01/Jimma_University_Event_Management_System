<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - JU Event Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --ju-navy: #0a1929;
            --ju-blue: #0d2b4b;
            --ju-sky: #2c4b73;
            --ju-mint: #dff2f5;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at 15% 20%, #eaf6ff 0%, transparent 35%),
                radial-gradient(circle at 85% 10%, #dff2f5 0%, transparent 40%),
                linear-gradient(140deg, #f7fbff 0%, #eef3f9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 12px;
        }

        .auth-shell {
            width: 100%;
            max-width: 980px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 18px 45px rgba(10, 25, 41, 0.18);
            background: #fff;
        }

        .auth-side {
            background: linear-gradient(155deg, var(--ju-navy), var(--ju-blue));
            color: #fff;
            padding: 36px 28px;
            height: 100%;
        }

        .auth-logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
            background: #fff;
            border-radius: 50%;
            padding: 8px;
            margin-bottom: 16px;
        }

        .auth-form {
            padding: 36px 28px;
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

        .auth-note {
            background: var(--ju-mint);
            border: 1px solid #c8e8ee;
            color: #0b3d4a;
            border-radius: 10px;
            font-size: 0.9rem;
            padding: 10px 12px;
        }

        @media (max-width: 767px) {
            .auth-side {
                padding: 24px 20px;
            }

            .auth-form {
                padding: 24px 20px;
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
                        <h3 class="fw-bold mb-2">JU Event Management</h3>
                        <p class="mb-0 text-white-50">Sign in to manage events, requests, notifications, and your profile.</p>
                    </div>
                    <small class="text-white-50">Secure campus event platform</small>
                </div>
            </div>

            <div class="col-md-7">
                <div class="auth-form">
                    <div class="d-md-none text-center mb-3">
                        <img src="{{ asset('images/jimma-logo.png') }}" alt="Jimma University" class="auth-logo" style="margin-bottom:8px;">
                        <h5 class="fw-bold mb-0" style="color:#0a1929;">JU Event Management</h5>
                    </div>

                    <h4 class="fw-bold mb-1" style="color:#0a1929;">Welcome back</h4>
                    <p class="text-muted mb-4">Login with your account credentials.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
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
                                    autofocus
                                >
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock text-secondary"></i></span>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter your password"
                                    required
                                >
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword" aria-label="Toggle password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <button type="submit" class="btn btn-main w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </form>

                    <div class="auth-note mb-3">
                        New accounts start as <strong>Guest</strong>. A Super Administrator can assign Student, Faculty, Event Manager, or other roles later.
                    </div>

                    <p class="mb-0 text-muted text-center">
                        No account yet?
                        <a href="{{ route('register') }}" class="fw-semibold" style="color:#0d2b4b;">Create one</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
        });
    </script>
</body>
</html>
