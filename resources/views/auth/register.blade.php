@extends('layouts.app')

@section('content')
<div class="register-container">
    <div class="register-box">
        <div class="register-header">
            <div class="logo-container">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="skillhub-logo">
                    <path d="M20 40 L50 10 L80 40 L50 70 Z" fill="#4A90E2"/>
                    <path d="M30 50 L50 30 L70 50 L50 70 Z" fill="#5DADE2"/>
                </svg>
                <h2>Skill<span>Hub</span></h2>
            </div>
            <p>Your Gateway to Coding Excellence</p>
        </div>

        <!-- Error Message Handling -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}" class="register-form">
            @csrf

            <!-- Full Name -->
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="@error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="@error('email') is-invalid @enderror">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required class="@error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <!-- Learning Path Selection -->
            <div class="form-group">
                <label>Select Your Learning Path</label>
                <div class="learning-path-selector">
                    <div class="path-option">
                        <input type="radio" id="web-dev" name="learning_path" value="web-development">
                        <label for="web-dev">Web Development</label>
                    </div>
                    <div class="path-option">
                        <input type="radio" id="mobile-dev" name="learning_path" value="mobile-development">
                        <label for="mobile-dev">Mobile Development</label>
                    </div>
                    <div class="path-option">
                        <input type="radio" id="data-science" name="learning_path" value="data-science">
                        <label for="data-science">Data Science</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role">
                    <option selected>Pilih role</option>
                    <option value="admin">Admin</option>
                    <option value="siswa">Siswa</option>
                    <option value="mentor">Mentor</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="register-btn">Start Your Coding Journey</button>

            <div class="form-footer">
                <p>Already have an account? <a href="{{ route('login') }}">Log In</a></p>
                <div class="social-login">
                    <p>Or sign up with:</p>
                    <div class="social-buttons">
                        <button type="button" class="social-btn github">
                            <i class="fab fa-github"></i> GitHub
                        </button>
                        <button type="button" class="social-btn google">
                            <i class="fab fa-google"></i> Google
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    :root {
        /* SkillHub Dark Theme */
        --skillhub-bg-primary: #0F1020;
        --skillhub-bg-secondary: #1A1A2E;
        --skillhub-text-primary: #E0E0E0;
        --skillhub-text-secondary: #8E8E8E;
        --skillhub-accent-blue: #4A90E2;
        --skillhub-accent-green: #2ECC71;
        --skillhub-border-color: #2C2C3E;
        --skillhub-hover-color: #242424;
    }

    body {
        background: linear-gradient(135deg, var(--skillhub-bg-primary), var(--skillhub-bg-secondary));
        min-height: 100vh;
        font-family: 'Inter', 'Fira Code', monospace;
        color: var(--skillhub-text-primary);
        line-height: 1.6;
    }

    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .register-box {
        background: var(--skillhub-bg-secondary);
        padding: 3rem;
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 500px;
        border: 1px solid var(--skillhub-border-color);
    }

    .register-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .logo-container {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .skillhub-logo {
        width: 50px;
        height: 50px;
        margin-right: 15px;
    }

    .register-header h2 {
        font-size: 2.5rem;
        color: var(--skillhub-text-primary);
        margin: 0;
        font-weight: 800;
    }

    .register-header h2 span {
        color: var(--skillhub-accent-blue);
    }

    .register-header p {
        color: var(--skillhub-text-secondary);
        font-size: 1rem;
        margin-top: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.75rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.625rem;
        color: var(--skillhub-text-primary);
        font-size: 0.95rem;
        font-weight: 600;
    }

    .form-group  input{
        width: 100%;
        padding: 0.9rem 1.125rem;
        background-color: var(--skillhub-bg-primary);
        color: var(--skillhub-text-primary);
        border: 1px solid var(--skillhub-border-color);
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    .form-group  select{
        width: 100%;
        padding: 0.9rem 1.125rem;
        background-color: var(--skillhub-bg-primary);
        color: var(--skillhub-text-primary);
        border: 1px solid var(--skillhub-border-color);
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--skillhub-accent-blue);
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
    }
    .form-group select:focus {
        outline: none;
        border-color: var(--skillhub-accent-blue);
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
    }

    .learning-path-selector {
        display: flex;
        justify-content: space-between;
    }

    .path-option {
        display: flex;
        align-items: center;
    }

    .path-option input[type="radio"] {
        display: none;
    }

    .path-option label {
        background: var(--skillhub-bg-primary);
        border: 1px solid var(--skillhub-border-color);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .path-option input[type="radio"]:checked + label {
        background: var(--skillhub-accent-blue);
        color: var(--skillhub-text-primary);
        border-color: var(--skillhub-accent-blue);
    }

    .register-btn {
        width: 100%;
        padding: 1rem;
        background-color: var(--skillhub-accent-blue);
        color: var(--skillhub-text-primary);
        border: none;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .register-btn:hover {
        background-color: var(--skillhub-accent-green);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .form-footer {
        text-align: center;
        color: var(--skillhub-text-secondary);
        font-size: 0.95rem;
    }

    .form-footer a {
        color: var(--skillhub-accent-blue);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .form-footer a:hover {
        color: var(--skillhub-accent-green);
    }

    .social-login {
        margin-top: 1.5rem;
    }

    .social-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 1rem;
    }

    .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        font-size: 0.9rem;
    }

    .social-btn i {
        margin-right: 0.5rem;
        font-size: 1.2rem;
    }

    .social-btn.github {
        background-color: #333;
        color: white;
    }

    .social-btn.github:hover {
        background-color: #4a4a4a;
    }

    .social-btn.google {
        background-color: #DB4437;
        color: white;
    }

    .social-btn.google:hover {
        background-color: #C23321;
    }

    /* Error states */
    .is-invalid {
        border-color: #e74c3c !important;
    }

    .invalid-feedback {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 0.35rem;
    }

    @media (max-width: 600px) {
        .register-box {
            padding: 2rem;
            width: 95%;
            margin: 0 auto;
        }

        .learning-path-selector {
            flex-direction: column;
        }

        .path-option {
            margin-bottom: 0.75rem;
        }

        .path-option label {
            width: 100%;
            text-align: center;
        }

        .social-buttons {
            flex-direction: column;
        }

        .social-btn {
            width: 100%;
            margin-bottom: 0.75rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('.form-group input');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.style.borderColor = 'var(--skillhub-accent-blue)';
            });
            input.addEventListener('blur', () => {
                input.style.borderColor = 'var(--skillhub-border-color)';
            });
        });
    });
</script>
@endpush
