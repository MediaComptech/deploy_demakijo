<?php
/**
 * Migration: Tambah kolom kategori ke tabel gurus
 * Jalankan sekali: php migrate_add_kategori_to_gurus.php
 */

require 'vendor/autoload.php';
\App\Core\App::boot();

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$schema = Capsule::schema();

// Cek apakah kolom kategori sudah ada
$columns = Capsule::select("SHOW COLUMNS FROM gurus LIKE 'kategori'");

if (!empty($columns)) {
    echo "✅ Kolom 'kategori' sudah ada di tabel gurus. Tidak perlu migrasi.\n";
} else {
    $schema->table('gurus', function (Blueprint $table) {
        $table->string('kategori', 50)->default('kelas')->after('jabatan');
    });
    echo "✅ Kolom 'kategori' berhasil ditambahkan ke tabel gurus.\n";
}

// Update data yang sudah ada berdasarkan jabatan
$gurus = Capsule::table('gurus')->get();
foreach ($gurus as $g) {
    $j = strtolower($g->jabatan ?? '');
    $cat = 'kelas';
    if (str_contains($j, 'mapel') || str_contains($j, 'agama') || str_contains($j, 'pjok') || str_contains($j, 'olahraga') || str_contains($j, 'penjas') || str_contains($j, 'inggris') || str_contains($j, 'seni')) {
        $cat = 'mapel';
    } elseif (str_contains($j, 'pendamping')) {
        $cat = 'pendamping';
    } elseif (str_contains($j, 'tendik') || str_contains($j, 'kependidikan') || str_contains($j, 'tata usaha') || str_contains($j, 'penjaga') || str_contains($j, 'proktor') || str_contains($j, 'bendahara') || str_contains($j, 'kepala sekolah') || str_contains($j, 'kepsek') || str_contains($j, 'administrasi') || str_contains($j, 'pustakawan') || str_contains($j, 'operator')) {
        $cat = 'tendik';
    }
    
    Capsule::table('gurus')->where('id', $g->id)->update(['kategori' => $cat]);
}

$gurusUpdated = Capsule::table('gurus')->get();
echo "\nDaftar Guru saat ini:\n";
foreach ($gurusUpdated as $g) {
    echo "  - ID:{$g->id} | {$g->nama} | Jabatan: {$g->jabatan} | Kategori: {$g->kategori}\n";
}
echo "\nSelesai.\n";
