<?php
$siteConfig = \App\Models\SettingWebsite::first();
$loginBg = ($siteConfig && $siteConfig->gambar_header) ? asset('storage/'.$siteConfig->gambar_header) : 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=800&q=80';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SDN Demakijo 1</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Fredoka+One&display=swap" rel="stylesheet">
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
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
        }
        .login-image {
            background: url('<?= $loginBg ?>') center/cover;
            width: 50%;
            display: none;
            position: relative;
        }
        .login-image::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 74, 173, 0.7);
        }
        .login-image-content {
            position: absolute;
            z-index: 1;
            color: white;
            bottom: 40px;
            left: 30px;
            right: 30px;
        }
        @media (min-width: 768px) {
            .login-image { display: block; }
        }
        .login-form-container {
            width: 100%;
            padding: 50px 40px;
        }
        @media (min-width: 768px) {
            .login-form-container { width: 50%; }
        }
        .brand-logo {
            font-family: 'Fredoka One', cursive;
            color: var(--primary-blue);
            font-size: 1.8rem;
            margin-bottom: 8px;
            text-align: center;
        }
        .brand-subtitle {
            text-align: center;
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 30px;
        }
        .form-label { font-weight: 700; font-size: 0.9rem; color: #333; }
        .input-group-text {
            background: white;
            border-right: 0;
            color: #6c757d;
        }
        .form-control {
            border-radius: 0;
            padding: 12px 15px;
            border-left: 0;
            border-right: 0;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: var(--secondary-blue);
        }
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .input-group-text-right {
            border-color: var(--secondary-blue);
        }
        .input-group {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
        }
        .input-group .form-control {
            border: none;
        }
        .input-group:focus-within {
            border-color: var(--secondary-blue);
            box-shadow: 0 0 0 3px rgba(56, 182, 255, 0.2);
        }
        .input-group-text-right {
            background: white;
            border: none;
            cursor: pointer;
            color: #6c757d;
            padding: 0 15px;
            display: flex;
            align-items: center;
        }
        .input-group-text-right:hover { color: var(--primary-blue); }
        .btn-login {
            background: linear-gradient(135deg, var(--accent-yellow), #ffc107);
            color: var(--primary-blue);
            font-weight: 800;
            border-radius: 10px;
            padding: 13px;
            border: none;
            width: 100%;
            font-size: 1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 222, 89, 0.5);
            color: var(--primary-blue);
        }
        .btn-login:active { transform: translateY(0); }
        .alert-danger-custom {
            background: #fff3f3;
            border: 1px solid #f5c6cb;
            border-left: 4px solid #dc3545;
            border-radius: 10px;
            padding: 12px 15px;
            color: #721c24;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .alert-success-custom {
            background: #f0fff4;
            border: 1px solid #c3e6cb;
            border-left: 4px solid #28a745;
            border-radius: 10px;
            padding: 12px 15px;
            color: #155724;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .password-rules {
            background: #f8f9ff;
            border: 1px solid #e3e8ff;
            border-radius: 8px;
            padding: 10px 14px;
            margin-top: 10px;
            font-size: 0.8rem;
            color: #555;
        }
        .password-rules ul { margin: 5px 0 0; padding-left: 18px; }
        .password-rules li { margin-bottom: 2px; }
        .divider {
            text-align: center;
            color: #aaa;
            font-size: 0.8rem;
            margin: 20px 0;
            position: relative;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #ddd;
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }
    </style>
</head>
<body>

<div class="container px-3 py-4">
    <div class="login-card mx-auto">
        <!-- Panel Kiri: Gambar -->
        <div class="login-image">
            <div class="login-image-content">
                <h2 style="font-family: 'Fredoka One', cursive; font-size: 1.8rem;">Smart School</h2>
                <p style="font-size: 0.95rem; opacity: 0.9;">Sistem Informasi &amp; Manajemen Pendidikan Berbasis Digital.</p>
                <div style="margin-top: 20px; background: rgba(255,255,255,0.15); border-radius: 10px; padding: 12px;">
                    <small>🏫 SDN Demakijo 1 Gamping Sleman</small><br>
                    <small>📚 Portal Admin &amp; Manajemen Sekolah</small>
                </div>
            </div>
        </div>

        <!-- Panel Kanan: Form Login -->
        <div class="login-form-container">
            <div class="brand-logo">
                <i class="fas fa-graduation-cap text-warning me-2"></i>SDN Demakijo 1
            </div>
            <p class="brand-subtitle">Masuk ke Panel Administrasi Sekolah</p>

            <?php
            // Tampilkan pesan flash (error/success)
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
                <div class="mb-4">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-1" style="color: var(--secondary-blue);"></i>
                        Alamat Email
                    </label>
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
                <div class="mb-1">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-1" style="color: var(--secondary-blue);"></i>
                        Kata Sandi
                    </label>
                    <div class="input-group" id="passwordGroup">
                        <span class="input-group-text">
                            <i class="fas fa-key"></i>
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

                <!-- Password Rules Info -->
                <div class="password-rules mb-3">
                    <strong>ℹ️ Aturan Password:</strong>
                    <ul>
                        <li>Masukkan password persis seperti yang ditetapkan (huruf besar/kecil sensitif)</li>
                        <li>Karakter khusus seperti <code>!</code>, <code>@</code>, <code>#</code> diperbolehkan</li>
                        <li>Gunakan tombol 👁️ untuk memeriksa input password Anda</li>
                    </ul>
                </div>

                <!-- Remember Me & Lupa Sandi -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted small" for="remember">Ingat Saya</label>
                    </div>
                    <a href="/password-reset" class="text-decoration-none small" style="color: var(--primary-blue);">
                        <i class="fas fa-question-circle me-1"></i>Lupa Sandi?
                    </a>
                </div>

                <!-- Tombol Login -->
                <button type="submit" class="btn btn-login mb-3" id="loginBtn">
                    <i class="fas fa-sign-in-alt me-2"></i>Login ke Dashboard
                </button>

                <div class="divider">atau</div>

                <div class="text-center">
                    <a href="/" class="text-muted text-decoration-none small">
                        <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda Sekolah
                    </a>
                </div>
            </form>
        </div>
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
