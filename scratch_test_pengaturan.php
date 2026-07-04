<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
\App\Core\App::boot();

// Simulate session/auth
$_SESSION['user_id'] = 1;

try {
    // Test the blade view compilation
    $data = \App\Models\SettingWebsite::first();
    if (!$data) {
        echo "WARN: No SettingWebsite record found!\n";
        $data = new \App\Models\SettingWebsite();
    }
    echo "SettingWebsite fetched OK. Columns: ";
    echo implode(', ', array_keys((array)$data->getAttributes())) . "\n";
    
    // Check for problematic columns
    $check = ['npsn','status_sekolah','bentuk_pendidikan','kode_pos','tahun_berdiri','luas_tanah','jumlah_kelas','jumlah_tendik','akreditasi_no_sertifikat','akreditasi_tahun','akreditasi_peringkat','akreditasi_berlaku'];
    foreach ($check as $col) {
        echo "  $col: " . ($data->$col ?? 'NULL') . "\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
