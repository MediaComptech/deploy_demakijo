<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Core/App.php';
\App\Core\App::boot();

use App\Models\Agenda;

// Kalender Akademik Sleman TA 2026/2027
$agendas = [
    [
        'judul' => 'Masa Pengenalan Lingkungan Sekolah (MPLS)',
        'slug' => 'masa-pengenalan-lingkungan-sekolah-mpls',
        'deskripsi' => 'Pengenalan lingkungan sekolah, program, sarana prasarana, tata tertib, serta pembiasaan karakter bagi siswa baru kelas 1.',
        'tanggal_mulai' => '2026-07-07',
        'tanggal_selesai' => '2026-07-10',
        'lokasi' => 'SDN Demakijo 1',
        'kategori' => 'Akademik'
    ],
    [
        'judul' => 'Hari Pertama Masuk Sekolah Semester 1',
        'slug' => 'hari-pertama-masuk-sekolah-semester-1',
        'deskripsi' => 'Permulaan kegiatan belajar mengajar (KBM) efektif Semester Ganjil Tahun Ajaran 2026/2027.',
        'tanggal_mulai' => '2026-07-07',
        'tanggal_selesai' => '2026-07-07',
        'lokasi' => 'SDN Demakijo 1',
        'kategori' => 'Akademik'
    ],
    [
        'judul' => 'Upacara HUT RI ke-81 & Lomba Kemerdekaan',
        'slug' => 'upacara-hut-ri-ke-81-lomba-kemerdekaan',
        'deskripsi' => 'Upacara bendera peringatan Hari Kemerdekaan RI ke-81 dilanjutkan dengan berbagai lomba antar-kelas untuk melatih sportivitas dan rasa nasionalisme.',
        'tanggal_mulai' => '2026-08-17',
        'tanggal_selesai' => '2026-08-18',
        'lokasi' => 'Halaman Sekolah SDN Demakijo 1',
        'kategori' => 'Lomba'
    ],
    [
        'judul' => 'Asesmen Nasional (ANBK) SD',
        'slug' => 'asesmen-nasional-anbk-sd',
        'deskripsi' => 'Pelaksanaan Asesmen Nasional Berbasis Komputer (ANBK) untuk memetakan mutu pendidikan dasar.',
        'tanggal_mulai' => '2026-10-19',
        'tanggal_selesai' => '2026-10-22',
        'lokasi' => 'Laboratorium Komputer',
        'kategori' => 'Akademik'
    ]
];

foreach ($agendas as $a) {
    // Cari apakah sudah ada dengan slug yang sama, jika belum buat baru
    $existing = Agenda::where('slug', $a['slug'])->first();
    if (!$existing) {
        Agenda::create($a);
        echo "Agenda dibuat: " . $a['judul'] . "\n";
    } else {
        $existing->update($a);
        echo "Agenda diperbarui: " . $a['judul'] . "\n";
    }
}
echo "Populasi Agenda Selesai!\n";
