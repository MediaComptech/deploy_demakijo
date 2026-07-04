<?php
$siteConfig = \App\Models\SettingWebsite::first();
$loginBg = ($siteConfig && $siteConfig->foto_login) ? asset('storage/'.$siteConfig->foto_login) : 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=800&q=80';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | {{ $siteConfig->nama_sekolah ?? 'SDN Demakijo 1' }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #004aad;
            --secondary-blue: #38b6ff;
            --accent-yellow: #ffde59;
            --bg-light: #f8f9fa;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            overflow: hidden;
            width: 100%;
            max-width: 1120px;
            display: flex;
            min-height: 680px;
        }
        .login-image {
            background: url('<?= $loginBg ?>') center/cover;
            width: 55%;
            position: relative;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 1;
        }
        .login-image::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(180deg, rgba(0, 74, 173, 0.88) 0%, rgba(0, 40, 95, 0.95) 100%);
            z-index: -1;
        }
        .login-image-header {
            text-align: center;
            color: white;
        }
        .login-school-logo {
            height: 90px;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.15));
        }
        .login-school-name {
            font-weight: 800;
            font-size: 2.2rem;
            letter-spacing: 0.5px;
            margin-top: 15px;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            color: white;
        }
        .login-school-motto {
            font-size: 1.05rem;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 25px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
            color: #e2e8f0;
        }
        
        /* Badges */
        .login-badges-container {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }
        .login-badge-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 50px;
            color: white;
            font-size: 0.8rem;
            font-weight: 700;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .badge-karakter { background-color: #3b82f6; }
        .badge-mandiri { background-color: #10b981; }
        .badge-prestasi { background-color: #f59e0b; }
        .badge-ilmu { background-color: #8b5cf6; }

        /* Middle illustration / spacing */
        .login-image-mid {
            flex-grow: 1;
            min-height: 120px;
        }

        /* Bottom Info Row */
        .login-info-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        .login-info-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            border-radius: 12px;
            padding: 12px;
            color: white;
            transition: all 0.3s ease;
        }
        .login-info-card:hover {
            background: rgba(255, 255, 255, 0.14);
            transform: translateY(-2px);
        }
        .login-info-card i {
            font-size: 1.1rem;
            color: var(--accent-yellow);
            margin-bottom: 6px;
            display: block;
        }
        .login-info-card h6 {
            font-weight: 700;
            font-size: 0.82rem;
            margin-bottom: 4px;
            color: white;
        }
        .login-info-card p {
            font-size: 0.7rem;
            opacity: 0.85;
            line-height: 1.4;
            margin-bottom: 0;
            color: #cbd5e1;
        }

        /* Footer Strip */
        .login-image-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding-top: 15px;
            display: flex;
            justify-content: space-between;
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.8);
            flex-wrap: wrap;
            gap: 10px;
        }
        .login-image-footer-col {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .login-image-footer-col i {
            color: var(--accent-yellow);
        }

        /* Form Container (Right Side) */
        .login-form-container {
            width: 45%;
            padding: 40px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }
        
        @media (max-width: 992px) {
            .login-card {
                max-width: 500px;
            }
            .login-image {
                display: none !important;
            }
            .login-form-container {
                width: 100%;
                padding: 40px 30px;
            }
        }

        /* Tips Keamanan */
        .tips-keamanan {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 12px 15px;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        .tips-keamanan-title {
            color: #1e3a8a;
            font-weight: 700;
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }
        .tips-keamanan-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }
        .tips-keamanan-list li {
            font-size: 0.75rem;
            color: #1e40af;
            position: relative;
            padding-left: 18px;
            margin-bottom: 4px;
        }
        .tips-keamanan-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #2563eb;
            font-weight: bold;
        }

        /* Style tweaks for form elements */
        .login-header-right {
            text-align: center;
            margin-bottom: 25px;
        }
        .brand-logo-img {
            max-height: 48px;
            object-fit: contain;
            margin-bottom: 12px;
        }
        .login-header-right h4 {
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            font-size: 1.4rem;
        }
        .login-header-right .subtitle {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 4px;
        }
        .login-header-right .desc {
            font-size: 0.78rem;
            color: #94a3b8;
        }

        .form-label { font-weight: 700; font-size: 0.85rem; color: #475569; }
        .input-group-text {
            background: #f8fafc;
            border-right: 0;
            color: #64748b;
            border-color: #cbd5e1;
        }
        .form-control {
            padding: 10px 14px;
            border-left: 0;
            border-color: #cbd5e1;
            font-size: 0.9rem;
            background: #ffffff;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #3b82f6;
        }
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .input-group-text-right {
            border-color: #3b82f6;
        }
        .input-group {
            border-radius: 8px;
            overflow: hidden;
        }
        .input-group:focus-within {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .input-group-text-right {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-left: 0;
            cursor: pointer;
            color: #64748b;
            padding: 0 14px;
            display: flex;
            align-items: center;
        }
        .input-group-text-right:hover { color: #3b82f6; }
        
        .btn-login-blue {
            background-color: #2563eb;
            color: white;
            font-weight: 700;
            border-radius: 8px;
            padding: 12px;
            border: none;
            width: 100%;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15);
        }
        .btn-login-blue:hover {
            background-color: #1d4ed8;
            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.25);
            color: white;
        }
        .btn-login-blue:active {
            transform: scale(0.98);
        }

        .alert-danger-custom {
            background: #fff5f5;
            border: 1px solid #fee2e2;
            border-left: 4px solid #ef4444;
            border-radius: 8px;
            padding: 10px 14px;
            color: #991b1b;
            margin-bottom: 18px;
            font-size: 0.82rem;
        }
        .alert-success-custom {
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            border-left: 4px solid #22c55e;
            border-radius: 8px;
            padding: 10px 14px;
            color: #166534;
            margin-bottom: 18px;
            font-size: 0.82rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <!-- Panel Kiri: Gambar Branding -->
    <div class="login-image">
        <!-- Bagian Atas: Logo & Judul -->
        <div class="login-image-header">
            @if($siteConfig && $siteConfig->logo)
                <img src="{{ asset('storage/'.$siteConfig->logo) }}" alt="Logo" class="login-school-logo">
            @else
                <img src="/logo-192.png" alt="Logo" class="login-school-logo">
            @endif
            <h1 class="login-school-name">{{ strtoupper($siteConfig->nama_sekolah ?? 'SDN DEMAKIJO 1') }}</h1>
            <p class="login-school-motto">Berkarakter, Mandiri, dan Berprestasi</p>
            
            <div class="login-badges-container">
                <div class="login-badge-item badge-karakter">
                    <i class="fas fa-graduation-cap"></i> Berkarakter
                </div>
                <div class="login-badge-item badge-mandiri">
                    <i class="fas fa-user-check"></i> Mandiri
                </div>
                <div class="login-badge-item badge-prestasi">
                    <i class="fas fa-award"></i> Berprestasi
                </div>
                <div class="login-badge-item badge-ilmu">
                    <i class="fas fa-book"></i> Berilmu
                </div>
            </div>
        </div>

        <!-- Spacing Tengah -->
        <div class="login-image-mid"></div>

        <!-- Bagian Bawah: Cards Keunggulan -->
        <div class="login-info-cards">
            <div class="login-info-card">
                <i class="fas fa-shield-alt"></i>
                <h6>Sistem Aman</h6>
                <p>Keamanan data terjamin dengan sistem proteksi terkini.</p>
            </div>
            <div class="login-info-card">
                <i class="fas fa-bolt"></i>
                <h6>Informasi Real-time</h6>
                <p>Data terupdate untuk mendukung keputusan yang cepat & tepat.</p>
            </div>
            <div class="login-info-card">
                <i class="fas fa-mobile-alt"></i>
                <h6>Akses Mudah</h6>
                <p>Dapat diakses kapan saja dan di mana saja melalui perangkat.</p>
            </div>
        </div>

        <!-- Footer Panel Kiri -->
        <div class="login-image-footer">
            <div class="login-image-footer-col">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $siteConfig->alamat ?? 'Nogotirto, Gamping, Sleman, DIY' }}</span>
            </div>
            <div class="login-image-footer-col">
                <i class="fas fa-phone-alt"></i>
                <span>{{ $siteConfig->telepon ?? '(0274) 1234567' }}</span>
            </div>
            <div class="login-image-footer-col">
                <i class="fas fa-globe"></i>
                <span>{{ $siteConfig->email ?? 'info@sdndemakijo1.sch.id' }}</span>
            </div>
        </div>
    </div>

    <!-- Panel Kanan: Form Login -->
    <div class="login-form-container">
        <div class="login-header-right">
            @if($siteConfig && $siteConfig->logo)
                <img src="{{ asset('storage/'.$siteConfig->logo) }}" alt="Logo" class="brand-logo-img">
            @else
                <img src="/logo-192.png" alt="Logo" class="brand-logo-img">
            @endif
            <h4>{{ $siteConfig->nama_sekolah ?? 'SDN Demakijo 1' }}</h4>
            <div class="subtitle">Sistem Informasi &amp; Manajemen Sekolah</div>
            <div class="desc">Silakan masuk untuk mengakses panel administrasi sekolah</div>
        </div>

        <?php
        $flashError   = \App\Core\Session::getFlash('error');
        $flashSuccess = \App\Core\Session::getFlash('success');
        $flashErrors  = \App\Core\Session::getFlash('errors');
        $oldEmail     = \App\Core\Session::getFlash('old_email');
        ?>

        <?php if ($flashError): ?>
        <div class="alert-danger-custom">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= htmlspecialchars($flashError) ?>
        </div>
        <?php endif; ?>

        <?php if ($flashSuccess): ?>
        <div class="alert-success-custom">
            <i class="fas fa-check-circle me-2"></i>
            <?= htmlspecialchars($flashSuccess) ?>
        </div>
        <?php endif; ?>

        <?php if ($flashErrors && is_array($flashErrors)): ?>
        <div class="alert-danger-custom">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Periksa input Anda:</strong>
            <ul class="mb-0 mt-1">
                <?php foreach ($flashErrors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="/login" id="loginForm" autocomplete="off">
            <?= csrf_field() ?>

            <!-- Field Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-at"></i>
                    </span>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($oldEmail ?? '') ?>"
                        required
                        autofocus
                        placeholder="admin@sdndemakijo1.sch.id"
                        autocomplete="username"
                    >
                </div>
            </div>

            <!-- Field Password -->
            <div class="mb-2">
                <label for="password" class="form-label">Kata Sandi</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        required
                        placeholder="Masukkan kata sandi"
                        autocomplete="current-password"
                    >
                    <button
                        type="button"
                        class="input-group-text-right"
                        id="togglePasswordBtn"
                        onclick="togglePassword()"
                        title="Tampilkan/sembunyikan password"
                        aria-label="Toggle show password"
                    >
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Tips Keamanan -->
            <div class="tips-keamanan">
                <div class="tips-keamanan-title">
                    <i class="fas fa-shield-alt"></i>
                    <span>Tips Keamanan</span>
                </div>
                <ul class="tips-keamanan-list">
                    <li>Gunakan password yang kuat dan mudah diingat</li>
                    <li>Jangan bagikan akun Anda kepada orang lain</li>
                    <li>Logout setelah selesai menggunakan sistem</li>
                </ul>
            </div>

            <!-- Remember Me & Lupa Sandi -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-muted small" for="remember">Ingat Saya</label>
                </div>
                <a href="/password-reset" class="text-decoration-none small" style="color: #2563eb;">
                    Lupa Password?
                </a>
            </div>

            <!-- Tombol Login -->
            <button type="submit" class="btn btn-login-blue mb-4" id="loginBtn">
                <i class="fas fa-sign-in-alt me-2"></i>Login ke Dashboard
            </button>

            <div class="text-center">
                <a href="/" class="text-muted text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda Sekolah
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // ===== Toggle Show/Hide Password =====
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const btn = document.getElementById('togglePasswordBtn');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
            btn.title = 'Sembunyikan password';
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
            btn.title = 'Tampilkan password';
        }

        // Kembalikan fokus ke input
        passwordInput.focus();
    }

    // ===== Prevent double submit =====
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('loginBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    });

    // ===== Auto hide alerts setelah 10 detik =====
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-danger-custom, .alert-success-custom');
        alerts.forEach(function(el) {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(function() { el.style.display = 'none'; }, 500);
        });
    }, 10000);
</script>
</body>
</html>
