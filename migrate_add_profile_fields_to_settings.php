<?php
/**
 * Migration: Tambah kolom profil sekolah lengkap ke tabel setting_websites
 * Jalankan sekali: php migrate_add_profile_fields_to_settings.php
 */

require 'vendor/autoload.php';
\App\Core\App::boot();

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$schema = Capsule::schema();

// Kolom yang akan ditambahkan jika belum ada
$columnsToAdd = [
    'npsn' => ['type' => 'string', 'length' => 50, 'default' => '20401066'],
    'status_sekolah' => ['type' => 'string', 'length' => 50, 'default' => 'Negeri'],
    'bentuk_pendidikan' => ['type' => 'string', 'length' => 50, 'default' => 'SD'],
    'kode_pos' => ['type' => 'string', 'length' => 10, 'default' => '55294'],
    'tahun_berdiri' => ['type' => 'string', 'length' => 10, 'default' => '1985'],
    'luas_tanah' => ['type' => 'string', 'length' => 50, 'default' => '2.450 m²'],
    'jumlah_kelas' => ['type' => 'integer', 'default' => 24],
    'jumlah_tendik' => ['type' => 'integer', 'default' => 7],
    'timeline_sejarah' => ['type' => 'text', 'nullable' => true],
    'counter_kepala_sekolah' => ['type' => 'string', 'length' => 50, 'default' => '7'],
    'counter_alumni' => ['type' => 'string', 'length' => 50, 'default' => 'Ribuan'],
    'akreditasi_no_sertifikat' => ['type' => 'string', 'length' => 255, 'default' => '1234/BAN-SM/AK/XII/2022'],
    'akreditasi_tahun' => ['type' => 'string', 'length' => 10, 'default' => '2022'],
    'akreditasi_peringkat' => ['type' => 'string', 'length' => 50, 'default' => 'A (Unggul)'],
    'akreditasi_berlaku' => ['type' => 'string', 'length' => 10, 'default' => '2027'],
    'akreditasi_standar_isi' => ['type' => 'integer', 'default' => 93],
    'akreditasi_standar_proses' => ['type' => 'integer', 'default' => 92],
    'akreditasi_standar_skl' => ['type' => 'integer', 'default' => 95],
    'akreditasi_standar_ptk' => ['type' => 'integer', 'default' => 93],
    'akreditasi_standar_sarpras' => ['type' => 'integer', 'default' => 90],
    'akreditasi_standar_pengelolaan' => ['type' => 'integer', 'default' => 94],
    'akreditasi_standar_pembiayaan' => ['type' => 'integer', 'default' => 91],
    'akreditasi_standar_penilaian' => ['type' => 'integer', 'default' => 92],
];

// Cek kolom yang belum ada
$existingColumns = Capsule::select("SHOW COLUMNS FROM setting_websites");
$existingNames = array_map(fn($col) => $col->Field, $existingColumns);

$neededColumns = [];
foreach ($columnsToAdd as $name => $opts) {
    if (!in_array($name, $existingNames)) {
        $neededColumns[$name] = $opts;
    }
}

if (empty($neededColumns)) {
    echo "✅ Semua kolom profil sekolah sudah ada di tabel setting_websites.\n";
} else {
    $schema->table('setting_websites', function (Blueprint $table) use ($neededColumns) {
        foreach ($neededColumns as $name => $opts) {
            $type = $opts['type'];
            if ($type === 'string') {
                $table->string($name, $opts['length'] ?? 255)->default($opts['default'] ?? null)->nullable();
            } elseif ($type === 'integer') {
                $table->integer($name)->default($opts['default'] ?? 0)->nullable();
            } elseif ($type === 'text') {
                $table->text($name)->nullable();
            }
        }
    });
    echo "✅ Kolom profil sekolah baru berhasil ditambahkan ke tabel setting_websites.\n";
}

// Inisialisasi timeline sejarah bawaan jika kosong
$defaultTimeline = json_encode([
    ['tahun' => '1985', 'judul' => 'Didirikan', 'deskripsi' => 'Sekolah didirikan di atas tanah wakaf seluas 2.450 m².'],
    ['tahun' => '1986', 'judul' => 'Mulai Operasi', 'deskripsi' => 'Tahun ajaran pertama dimulai dengan 3 rombongan belajar.'],
    ['tahun' => '1995', 'judul' => 'Pembangunan Fisik', 'deskripsi' => 'Pembangunan ruang kelas baru dan ruang perpustakaan.'],
    ['tahun' => '2005', 'judul' => 'Perluasan Lab', 'deskripsi' => 'Perluasan sarana dan prasarana serta laboratorium komputer.'],
    ['tahun' => '2015', 'judul' => 'Akreditasi A', 'deskripsi' => 'Akreditasi sekolah meningkat menjadi A.'],
    ['tahun' => '2020', 'judul' => 'Kurikulum 2013', 'deskripsi' => 'Implementasi Kurikulum 2013 dan pembelajaran berbasis IT.'],
    ['tahun' => '2024', 'judul' => 'Digitalisasi', 'deskripsi' => 'Pengembangan digitalisasi sekolah dan program inovasi.'],
]);

Capsule::table('setting_websites')
    ->whereNull('timeline_sejarah')
    ->orWhere('timeline_sejarah', '')
    ->update(['timeline_sejarah' => $defaultTimeline]);

// Berikan nilai bawaan untuk data yang kosong
$settings = Capsule::table('setting_websites')->first();
if ($settings) {
    $updateData = [];
    foreach ($columnsToAdd as $name => $opts) {
        if ($name === 'timeline_sejarah') continue;
        if (empty($settings->$name)) {
            $updateData[$name] = $opts['default'] ?? '';
        }
    }
    if (!empty($updateData)) {
        Capsule::table('setting_websites')->where('id', $settings->id)->update($updateData);
    }
}

echo "✅ Selesai.\n";
