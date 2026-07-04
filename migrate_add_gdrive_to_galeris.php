<?php
/**
 * Migration: Tambah kolom url_gdrive ke tabel galeris
 * Jalankan sekali: php migrate_add_gdrive_to_galeris.php
 */

require 'vendor/autoload.php';
\App\Core\App::boot();

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$schema = Capsule::schema();

$columnsToAdd = [
    'url_gdrive'  => 'text',    // Link Google Drive / URL eksternal
    'keterangan'  => 'text',    // Deskripsi opsional
    'urutan'      => 'integer', // Urutan tampil
];

$existing = array_map(fn($c) => $c->Field, Capsule::select('SHOW COLUMNS FROM galeris'));

$added = [];
$schema->table('galeris', function (Blueprint $table) use ($columnsToAdd, $existing, &$added) {
    foreach ($columnsToAdd as $col => $type) {
        if (!in_array($col, $existing)) {
            if ($type === 'text')    $table->text($col)->nullable();
            if ($type === 'integer') $table->integer($col)->default(0)->nullable();
            $added[] = $col;
        }
    }
});

if (empty($added)) {
    echo "✅ Semua kolom sudah ada di tabel galeris.\n";
} else {
    echo "✅ Kolom baru ditambahkan: " . implode(', ', $added) . "\n";
}
echo "✅ Selesai.\n";
