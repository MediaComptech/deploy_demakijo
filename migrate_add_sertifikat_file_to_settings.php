<?php
/**
 * Migration: Tambah kolom akreditasi_sertifikat_file ke tabel setting_websites
 * Jalankan sekali: php migrate_add_sertifikat_file_to_settings.php
 */

require 'vendor/autoload.php';
\App\Core\App::boot();

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$schema = Capsule::schema();

if (!$schema->hasColumn('setting_websites', 'akreditasi_sertifikat_file')) {
    $schema->table('setting_websites', function (Blueprint $table) {
        $table->string('akreditasi_sertifikat_file', 255)->nullable()->after('akreditasi');
    });
    echo "✅ Kolom 'akreditasi_sertifikat_file' berhasil ditambahkan ke tabel setting_websites.\n";
} else {
    echo "✅ Kolom 'akreditasi_sertifikat_file' sudah ada di tabel setting_websites.\n";
}
echo "✅ Selesai.\n";
