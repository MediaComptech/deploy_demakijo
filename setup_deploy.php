<?php
/**
 * SETUP HELPER - SDN Demakijo 1
 * 
 * Upload file ini ke: /home/sdnc1967/public_html/setup_deploy.php
 * Akses via browser: https://sdndemakijo1.sch.id/setup_deploy.php?token=sdndemakijo2026
 * 
 * PENTING: Hapus file ini setelah selesai digunakan!
 */

// ========================
// SECURITY TOKEN
// ========================
$SECRET_TOKEN = 'sdndemakijo2026';
if (!isset($_GET['token']) || $_GET['token'] !== $SECRET_TOKEN) {
    http_response_code(403);
    die('<h2 style="color:red">403 Forbidden - Token salah atau tidak ditemukan.</h2>');
}

// ========================
// KONFIGURASI PATH
// ========================
// Sesuaikan nama folder ini dengan nama repo yang terdaftar di cPanel Git Version Control
// Cek di cPanel: Git™ Version Control → Basic Information → Repository Path
$REPO_PATH    = '/home/sdnc1967/repositories/deploy_demakijo';
$PUBLIC_HTML  = '/home/sdnc1967/public_html';
$ENV_CONTENT  = <<<ENV
APP_NAME="SDN Demakijo 1"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sdndemakijo1.sch.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sdnc1967_sdndemakijo1
DB_USERNAME=sdnc1967_demakijo1
DB_PASSWORD=Deploy2026!@
ENV;

// ========================
// FUNCTIONS
// ========================
function deleteDir($dir) {
    if (!is_dir($dir)) return true;
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            deleteDir($path);
        } else {
            @unlink($path);
        }
    }
    return @rmdir($dir);
}

function copyDir($src, $dst, $excludes = []) {
    $count = 0;
    if (!is_dir($dst)) mkdir($dst, 0755, true);
    $items = scandir($src);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        if (in_array($item, $excludes)) continue;
        $srcPath = $src . '/' . $item;
        $dstPath = $dst . '/' . $item;
        if (is_dir($srcPath)) {
            $count += copyDir($srcPath, $dstPath, $excludes);
        } else {
            copy($srcPath, $dstPath);
            $count++;
        }
    }
    return $count;
}

function log_line($msg, $ok = true) {
    $color = $ok ? 'green' : 'red';
    $icon  = $ok ? '✅' : '❌';
    echo "<div style='font-family:monospace;font-size:13px;padding:3px 0;color:{$color}'>{$icon} {$msg}</div>\n";
    ob_flush();
    flush();
}

// ========================
// MAIN PROCESS
// ========================
header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Deploy Setup</title>';
echo '<style>body{font-family:sans-serif;max-width:900px;margin:30px auto;padding:20px;background:#f4f7fa;}';
echo 'h2{color:#003366;} .box{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.1);margin-bottom:20px;}</style>';
echo '</head><body>';
echo '<h2>🚀 SDN Demakijo 1 — Deploy Setup Helper</h2>';

$action = $_GET['action'] ?? 'check';

// ==== ACTION: CEK STATUS ====
if ($action === 'check') {
    echo '<div class="box"><h3>📊 Status Cek</h3>';
    log_line("Repository path: {$REPO_PATH} — " . (is_dir($REPO_PATH) ? 'ADA ✅' : 'TIDAK ADA ❌'), is_dir($REPO_PATH));
    log_line("public_html path: {$PUBLIC_HTML} — " . (is_dir($PUBLIC_HTML) ? 'ADA ✅' : 'TIDAK ADA ❌'), is_dir($PUBLIC_HTML));
    log_line(".git di public_html: " . (is_dir($PUBLIC_HTML.'/.git') ? 'MASIH ADA (perlu dihapus)' : 'Tidak ada'), !is_dir($PUBLIC_HTML.'/.git'));
    log_line(".env di public_html: " . (file_exists($PUBLIC_HTML.'/.env') ? 'ADA ✅' : 'Belum ada'), file_exists($PUBLIC_HTML.'/.env'));
    log_line("App folder tersync: " . (is_dir($PUBLIC_HTML.'/app') ? 'YA ✅' : 'BELUM'), is_dir($PUBLIC_HTML.'/app'));
    
    $vendor_ok = is_dir($PUBLIC_HTML.'/vendor');
    log_line("Vendor folder: " . ($vendor_ok ? 'ADA ✅' : 'Belum ada (perlu composer install)'), $vendor_ok);
    
    echo '<hr><h4>⚡ Pilih Tindakan:</h4>';
    echo '<p><a href="?token='.$SECRET_TOKEN.'&action=delete_git" style="background:#dc3545;color:white;padding:10px 20px;border-radius:6px;text-decoration:none;margin-right:10px;">🗑️ 1. Hapus .git dari public_html</a></p>';
    echo '<p><a href="?token='.$SECRET_TOKEN.'&action=sync_files" style="background:#0056b3;color:white;padding:10px 20px;border-radius:6px;text-decoration:none;margin-right:10px;">📂 2. Sync File dari Repositori ke public_html</a></p>';
    echo '<p><a href="?token='.$SECRET_TOKEN.'&action=create_env" style="background:#28a745;color:white;padding:10px 20px;border-radius:6px;text-decoration:none;margin-right:10px;">⚙️ 3. Buat file .env production</a></p>';
    echo '<p><a href="?token='.$SECRET_TOKEN.'&action=fix_perms" style="background:#fd7e14;color:white;padding:10px 20px;border-radius:6px;text-decoration:none;margin-right:10px;">🔐 4. Fix Permission Folder</a></p>';
    echo '<p><a href="?token='.$SECRET_TOKEN.'&action=seed_calendar" style="background:#7c3aed;color:white;padding:10px 20px;border-radius:6px;text-decoration:none;margin-right:10px;">📅 5. Update Kalender Akademik Sleman</a></p>';
    echo '<p><a href="?token='.$SECRET_TOKEN.'&action=run_migrations" style="background:#20c997;color:white;padding:10px 20px;border-radius:6px;text-decoration:none;margin-right:10px;">🗄️ 6. Jalankan Migrasi Database (Kategori, Profil Sekolah, dsb)</a></p>';
    echo '<p><a href="?token='.$SECRET_TOKEN.'&action=all" style="background:#003366;color:white;padding:10px 20px;border-radius:6px;text-decoration:none;font-weight:bold;">🚀 LAKUKAN SEMUA LANGKAH SEKALIGUS</a></p>';
    echo '</div>';
}

// ==== ACTION: HAPUS .GIT ====
if ($action === 'delete_git' || $action === 'all') {
    echo '<div class="box"><h3>🗑️ Menghapus folder .git dari public_html...</h3>';
    $gitPath = $PUBLIC_HTML . '/.git';
    if (is_dir($gitPath)) {
        $result = deleteDir($gitPath);
        log_line("Folder .git dihapus: " . ($result ? 'BERHASIL' : 'GAGAL'), $result);
    } else {
        log_line("Folder .git tidak ditemukan (sudah bersih)");
    }
    echo '</div>';
}

// ==== ACTION: SYNC FILES ====
if ($action === 'sync_files' || $action === 'all') {
    echo '<div class="box"><h3>📂 Menyalin file dari repositori ke public_html...</h3>';
    if (!is_dir($REPO_PATH)) {
        log_line("ERROR: Folder repositori tidak ditemukan di {$REPO_PATH}", false);
    } else {
        $excludes = ['.git', '.env', '.env.local', 'node_modules', 'vendor'];
        $count = copyDir($REPO_PATH, $PUBLIC_HTML, $excludes);
        log_line("File disalin: {$count} file berhasil dicopy ke public_html");
        log_line("File yang di-exclude: .git, .env, .env.local, node_modules, vendor");
    }
    echo '</div>';
}

// ==== ACTION: BUAT .ENV ====
if ($action === 'create_env' || $action === 'all') {
    echo '<div class="box"><h3>⚙️ Membuat file .env production...</h3>';
    $envPath = $PUBLIC_HTML . '/.env';
    if (file_exists($envPath)) {
        log_line(".env sudah ada — tidak ditimpa (untuk keamanan). Hapus manual jika ingin diperbarui.");
    } else {
        $result = file_put_contents($envPath, $ENV_CONTENT);
        log_line(".env production dibuat: " . ($result !== false ? 'BERHASIL' : 'GAGAL'), $result !== false);
        @chmod($envPath, 0644);
    }
    echo '</div>';
}

// ==== ACTION: FIX PERMISSIONS ====
if ($action === 'fix_perms' || $action === 'all') {
    echo '<div class="box"><h3>🔐 Memperbaiki permission folder...</h3>';
    
    $dirs = [
        $PUBLIC_HTML . '/storage'             => 0755,
        $PUBLIC_HTML . '/storage/cache'       => 0755,
        $PUBLIC_HTML . '/public/storage'      => 0755,
        $PUBLIC_HTML . '/public/storage/uploads' => 0755,
    ];
    
    foreach ($dirs as $dir => $perm) {
        if (!is_dir($dir)) mkdir($dir, $perm, true);
        chmod($dir, $perm);
        log_line("Permission {$perm}: {$dir}");
    }
    
    // Hapus cache lama
    $cacheDir = $PUBLIC_HTML . '/storage/cache';
    if (is_dir($cacheDir)) {
        $caches = glob($cacheDir . '/*.{bladec,php}', GLOB_BRACE);
        $deleted = 0;
        foreach ($caches ?? [] as $f) {
            if (basename($f) !== '.gitkeep') { unlink($f); $deleted++; }
        }
        log_line("Cache lama dihapus: {$deleted} file");
    }
    echo '</div>';
}

// ==== ACTION: SEED ACADEMIC CALENDAR ====
if ($action === 'seed_calendar' || $action === 'all') {
    echo '<div class="box"><h3>📅 Sinkronisasi Kalender Akademik Sleman...</h3>';
    $seedFile = $PUBLIC_HTML . '/public/seed_academic_calendar.php';
    if (file_exists($seedFile)) {
        ob_start();
        include $seedFile;
        $output = ob_get_clean();
        echo "<pre style='background:#f8fafc; padding:10px; border-radius:6px; border:1px solid #e2e8f0; font-family:monospace; color:#333;'>" . htmlspecialchars($output) . "</pre>";
        log_line("Kalender Akademik berhasil diperbarui!");
    } else {
        log_line("ERROR: File seed_academic_calendar.php tidak ditemukan di {$seedFile}", false);
    }
    echo '</div>';
}

// ==== ACTION: RUN MIGRATIONS ====
if ($action === 'run_migrations' || $action === 'all') {
    echo '<div class="box"><h3>🗄️ Menjalankan Migrasi Database...</h3>';
    
    // 1. Jalankan migrasi Tambah Role Users
    $m1 = $PUBLIC_HTML . '/migrate_add_role_to_users.php';
    if (file_exists($m1)) {
        ob_start();
        include $m1;
        $output = ob_get_clean();
        echo "<pre style='background:#f8fafc; padding:10px; border-radius:6px; border:1px solid #e2e8f0; font-family:monospace; color:#333;'>" . htmlspecialchars($output) . "</pre>";
        log_line("Migrasi 1 (Role Users) selesai!");
    } else {
        log_line("Migrasi 1 tidak ditemukan di {$m1}, melewati...");
    }

    // 2. Jalankan migrasi Tambah Kategori Prestasi
    $m2 = $PUBLIC_HTML . '/migrate_add_kategori_to_prestasis.php';
    if (file_exists($m2)) {
        ob_start();
        include $m2;
        $output = ob_get_clean();
        echo "<pre style='background:#f8fafc; padding:10px; border-radius:6px; border:1px solid #e2e8f0; font-family:monospace; color:#333;'>" . htmlspecialchars($output) . "</pre>";
        log_line("Migrasi 2 (Kategori Prestasi) selesai!");
    } else {
        log_line("Migrasi 2 tidak ditemukan di {$m2}, melewati...");
    }

    // 3. Jalankan migrasi Tambah Profil Sekolah Settings
    $m3 = $PUBLIC_HTML . '/migrate_add_profile_fields_to_settings.php';
    if (file_exists($m3)) {
        ob_start();
        include $m3;
        $output = ob_get_clean();
        echo "<pre style='background:#f8fafc; padding:10px; border-radius:6px; border:1px solid #e2e8f0; font-family:monospace; color:#333;'>" . htmlspecialchars($output) . "</pre>";
        log_line("Migrasi 3 (Kolom Profil Sekolah) selesai!");
    } else {
        log_line("Migrasi 3 tidak ditemukan di {$m3}, melewati...");
    }

    // 4. Jalankan migrasi Tambah URL GDrive Galeri
    $m4 = $PUBLIC_HTML . '/migrate_add_gdrive_to_galeris.php';
    if (file_exists($m4)) {
        ob_start();
        include $m4;
        $output = ob_get_clean();
        echo "<pre style='background:#f8fafc; padding:10px; border-radius:6px; border:1px solid #e2e8f0; font-family:monospace; color:#333;'>" . htmlspecialchars($output) . "</pre>";
        log_line("Migrasi 4 (Kolom Google Drive Galeri) selesai!");
    } else {
        log_line("Migrasi 4 tidak ditemukan di {$m4}, melewati...");
    }

    echo '</div>';
}

// ==== FINISH ====
if ($action === 'all') {
    echo '<div class="box" style="border-left:5px solid green;">';
    echo '<h3 style="color:green">✅ Semua langkah selesai!</h3>';
    echo '<p>Silakan buka <a href="https://sdndemakijo1.sch.id" target="_blank">https://sdndemakijo1.sch.id</a> untuk mengecek website.</p>';
    echo '<p style="color:red"><strong>⚠️ PENTING: Hapus file setup_deploy.php dari public_html setelah selesai!</strong></p>';
    echo '</div>';
}

echo '<p style="margin-top:20px;font-size:12px;color:#aaa;"><a href="?token='.$SECRET_TOKEN.'">← Kembali ke menu utama</a></p>';
echo '</body></html>';
