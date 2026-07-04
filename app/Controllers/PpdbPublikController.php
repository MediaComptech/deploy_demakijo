<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Auth;
use App\Models\Ppdb;

class PpdbPublikController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'nik'            => 'required|digits:16|unique:ppdbs,nik',
            'jenis_kelamin'  => 'required|in:L,P',
            'tempat_lahir'   => 'required|string',
            'tanggal_lahir'  => 'required|date',
            'agama'          => 'required|string',
            'alamat'         => 'required|string',
            'nama_ayah'      => 'required|string',
            'nama_ibu'       => 'required|string',
            'no_telp'        => 'required|string',
        ], [
            'nik.unique'        => 'NIK ini sudah terdaftar. Setiap anak hanya dapat mendaftar satu kali.',
            'nik.digits'        => 'NIK harus terdiri dari 16 digit angka.',
            'jenis_kelamin.in'  => 'Jenis kelamin tidak valid.',
        ]);

        // Generate unique no_pendaftaran: PPDB-YYYY-XXXXX
        $lastId        = Ppdb::max('id') ?? 0;
        $noPendaftaran = 'PPDB-' . date('Y') . '-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        $input = $request->except('_token');
        foreach ($input as $key => $val) {
            if (is_string($val)) {
                $input[$key] = htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES, 'UTF-8');
            }
        }
        $input['no_pendaftaran'] = $noPendaftaran;
        $input['status']         = 'pending';

        // Handle file uploads
        $fileFields = ['berkas_kk', 'berkas_akta', 'berkas_pasfoto'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $input[$field] = $request->file($field)->store('ppdb/' . date('Y'), 'public');
            }
        }

        Ppdb::create($input);

        \App\Core\Session::setFlash('success', "Pendaftaran berhasil! No. Pendaftaran Anda: <strong>{$noPendaftaran}</strong>. Simpan nomor ini untuk mengecek status pendaftaran.");
        header('Location: /ppdb-online');
        exit;
    }

    public function cek(Request $request)
    {
        $no   = $request->input('no');
        $ppdb = Ppdb::where('no_pendaftaran', $no)->first();

        header('Content-Type: application/json; charset=utf-8');

        if (!$ppdb) {
            echo json_encode(['found' => false]);
            exit;
        }

        echo json_encode([
            'found'   => true,
            'nama'    => $ppdb->nama_lengkap,
            'status'  => $ppdb->status,
            'catatan' => $ppdb->catatan ?? 'Tidak ada catatan dari panitia.',
        ]);
        exit;
    }
}
