<?php
/**
 * Migration: Tambah kolom kategori ke tabel prestasis
 * Jalankan sekali: php migrate_add_kategori_to_prestasis.php
 */

require 'vendor/autoload.php';
\App\Core\App::boot();

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$schema = Capsule::schema();

// Cek apakah kolom kategori sudah ada
$columns = Capsule::select("SHOW COLUMNS FROM prestasis LIKE 'kategori'");

if (!empty($columns)) {
    echo "✅ Kolom 'kategori' sudah ada di tabel prestasis. Tidak perlu migrasi.\n";
} else {
    $schema->table('prestasis', function (Blueprint $table) {
        $table->string('kategori', 50)->default('Lainnya')->after('tingkat');
    });
    echo "✅ Kolom 'kategori' berhasil ditambahkan ke tabel prestasis.\n";
}

// Update data yang sudah ada (misal Juara 1 Lari Karung -> Olahraga, FL2SN Menyanyi -> Seni & Budaya)
Capsule::table('prestasis')->where('judul', 'like', '%Lari%')->update(['kategori' => 'Olahraga']);
Capsule::table('prestasis')->where('judul', 'like', '%Menyanyi%')->orWhere('judul', 'like', '%FL2SN%')->update(['kategori' => 'Seni & Budaya']);

$prestasi = Capsule::table('prestasis')->get();
echo "\nDaftar Prestasi saat ini:\n";
foreach ($prestasi as $p) {
    echo "  - ID:{$p->id} | {$p->judul} | kategori: {$p->kategori}\n";
}
echo "\nSelesai.\n";
