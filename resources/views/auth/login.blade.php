<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Monitor-ing</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --accent: #22d3ee;
            --surface: rgba(255,255,255,0.07);
            --surface-hover: rgba(255,255,255,0.12);
            --border: rgba(255,255,255,0.15);
            --text: #f1f5f9;
            --text-muted: #94a3b8;
            --error: #f87171;
            --success: #34d399;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #0f0c29;
            background: linear-gradient(135deg, #0f0c29 0%, #1a1060 35%, #24243e 70%, #0d1b3e 100%);
            overflow: hidden;
            position: relative;
        }

        /* Animated blobs */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.35;
            animation: blobFloat 8s ease-in-out infinite;
            pointer-events: none;
        }
        .blob-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, #6366f1, #4f46e5);
            top: -150px; left: -150px;
            animation-delay: 0s;
        }
        .blob-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, #22d3ee, #0891b2);
            bottom: -100px; right: -100px;
            animation-delay: 3s;
        }
        .blob-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, #a855f7, #7c3aed);
            top: 50%; left: 60%;
            animation-delay: 1.5s;
        }

        @keyframes blobFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(20px, -20px) scale(1.05); }
            66% { transform: translate(-15px, 15px) scale(0.95); }
        }

        /* Grid dots background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
        }

        /* Card */
        .login-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 24px;
            box-shadow:
                0 0 0 1px rgba(99,102,241,0.1),
                0 32px 64px rgba(0,0,0,0.4),
                inset 0 1px 0 rgba(255,255,255,0.1);
            animation: cardIn 0.6s cubic-bezier(0.16,1,0.3,1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(32px) scale(0.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Logo / Brand */
        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px; height: 56px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 16px;
            margin-bottom: 1rem;
            box-shadow: 0 8px 24px rgba(99,102,241,0.4);
        }
        .brand-icon svg { width: 28px; height: 28px; fill: #fff; }
        .brand h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.02em;
        }
        .brand p {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        /* Form */
        .form-group { margin-bottom: 1.25rem; }

        label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            letter-spacing: 0.01em;
        }

        .input-wrap {
            position: relative;
        }
        .input-wrap .icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            display: flex;
            pointer-events: none;
        }
        .input-wrap .icon svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 12px;
            color: var(--text);
            font-size: 0.9375rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }
        input::placeholder { color: rgba(148,163,184,0.5); }
        input:focus {
            border-color: var(--primary-light);
            background: rgba(255,255,255,0.1);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
        }

        /* Password toggle */
        .eye-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            display: flex;
            padding: 4px;
            border-radius: 6px;
            transition: color 0.2s;
        }
        .eye-toggle:hover { color: var(--text); }
        .eye-toggle svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

        /* Options row */
        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        .remember-me input[type="checkbox"] {
            width: 16px; height: 16px;
            padding: 0;
            accent-color: var(--primary);
            border-radius: 4px;
            cursor: pointer;
        }
        .remember-me span {
            font-size: 0.8125rem;
            color: var(--text-muted);
        }

        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 0.9375rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(99,102,241,0.4);
            letter-spacing: 0.01em;
        }
        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0;
            transition: opacity 0.2s;
        }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(99,102,241,0.5); }
        .btn-login:hover::before { opacity: 1; }
        .btn-login:active { transform: translateY(0); }

        /* Spinner inside button */
        .btn-login .spinner {
            display: none;
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .btn-login.loading .btn-text { display: none; }
        .btn-login.loading .spinner { display: block; }

        /* Error alert */
        .alert-error {
            background: rgba(248,113,113,0.12);
            border: 1px solid rgba(248,113,113,0.3);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            color: var(--error);
            font-size: 0.8125rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            animation: fadeIn 0.3s ease;
        }
        .alert-error svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

        /* Field error */
        .field-error {
            font-size: 0.75rem;
            color: var(--error);
            margin-top: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.5rem 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.1);
        }
        .divider span { font-size: 0.75rem; color: var(--text-muted); white-space: nowrap; }

        /* Footer text */
        .card-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Floating animation for input focus ring */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Badge versi */
        .version-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            background: rgba(99,102,241,0.15);
            border: 1px solid rgba(99,102,241,0.3);
            border-radius: 100px;
            padding: 0.2rem 0.625rem;
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--primary-light);
            letter-spacing: 0.04em;
            margin-bottom: 0.75rem;
        }
        .version-badge::before {
            content: '';
            width: 6px; height: 6px;
            background: var(--accent);
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <!-- Animated BG blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="login-card">
        <!-- Brand -->
        <div class="brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 3h18v4H3V3zm0 6h8v4H3V9zm0 6h8v4H3v-4zm10-6h8v10h-8V9z"/>
                </svg>
            </div>
            <div class="version-badge">Monitor-ing System</div>
            <h1>Selamat Datang</h1>
            <p>Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <!-- Error dari Laravel -->
        @if ($errors->any())
            <div class="alert-error">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert-error">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <div class="input-wrap">
                    <span class="icon">
                        <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="22,4 12,13 2,4"/></svg>
                    </span>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="nama@perusahaan.com"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                    >
                </div>
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <span class="icon">
                        <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                    >
                    <button type="button" class="eye-toggle" id="eyeToggle" aria-label="Tampilkan password">
                        <svg id="eyeIcon" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Options -->
            <div class="options-row">
                <label class="remember-me">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Ingat saya</span>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-login" id="submitBtn">
                <span class="btn-text">Masuk</span>
                <div class="spinner"></div>
            </button>
        </form>

        <div class="card-footer">
            &copy; {{ date('Y') }} Monitor-ing. Sistem Monitoring Internal.
        </div>
    </div>

    <script>
        // Toggle password visibility
        const eyeToggle = document.getElementById('eyeToggle');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        const eyeOpenSVG = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
        const eyeClosedSVG = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`;

        eyeToggle.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeIcon.innerHTML = isPassword ? eyeClosedSVG : eyeOpenSVG;
        });

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });
    </script>
</body>
</html>
