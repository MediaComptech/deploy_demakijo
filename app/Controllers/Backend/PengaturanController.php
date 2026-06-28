<?php
namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\SettingWebsite;

class PengaturanController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!Auth::check()) { redirect('/login'); }
    }

    private function checkSchema()
    {
        try {
            $schema = \Illuminate\Database\Capsule\Manager::schema();
            if (!$schema->hasColumn('setting_websites', 'gambar_header')) {
                $schema->table('setting_websites', function ($table) {
                    $table->string('gambar_header')->nullable();
                });
            }
            if (!$schema->hasColumn('setting_websites', 'foto_identitas')) {
                $schema->table('setting_websites', function ($table) {
                    $table->string('foto_identitas')->nullable();
                });
            }
        } catch (\Exception $e) {}
    }

    public function index()
    {
        $this->checkSchema();
        $data = SettingWebsite::first();
        if (!$data) {
            $data = SettingWebsite::create([
                'nama_sekolah' => 'SDN Demakijo 1',
                'alamat'       => 'Jl. Godean, Nogotirto, Gamping, Sleman, Yogyakarta',
                'telepon'      => '0274-123456',
                'email'        => 'info@sdndemakijo1.sch.id',
                'akreditasi'   => 'A',
            ]);
        }
        return view('backend.pengaturan.index', compact('data'));
    }

    public function store(Request $request)
    {
        $this->checkSchema();
        $data    = SettingWebsite::first();
        $section = $request->input('section', 'identitas');

        // Definisi field per segmen — hanya field segmen ini yang akan diupdate
        $sectionFields = [
            'identitas'      => ['nama_sekolah', 'email', 'telepon', 'whatsapp', 'alamat', 'google_maps'],
            'kepsek'         => ['nama_kepsek', 'nip_kepsek', 'sambutan_kepsek_singkat'],
            'statistik'      => ['jumlah_siswa', 'jumlah_guru', 'jumlah_alumni', 'akreditasi'],
            'slider'         => ['slider_1', 'slider_2', 'slider_3', 'slider_4', 'slider_5'],
            'visi_misi'      => ['visi', 'misi', 'sejarah', 'sambutan_kepsek'],
            'sosmed'         => ['facebook', 'instagram', 'youtube', 'youtube_embed'],
            'logo'           => ['logo'],
            'gambar_halaman' => ['gambar_header', 'foto_identitas'],
        ];

        $sectionLabels = [
            'identitas'      => 'Identitas Sekolah',
            'kepsek'         => 'Kepala Sekolah',
            'statistik'      => 'Statistik Sekolah',
            'slider'         => 'Image Slider',
            'visi_misi'      => 'Visi, Misi & Sejarah',
            'sosmed'         => 'Media Sosial',
            'logo'           => 'Logo Sekolah',
            'gambar_halaman' => 'Gambar Halaman',
        ];

        // Ambil hanya field yang termasuk segmen ini dari POST
        $allowedFields = $sectionFields[$section] ?? [];
        $input = [];
        foreach ($allowedFields as $field) {
            if (!in_array($section, ['slider', 'kepsek', 'logo', 'gambar_halaman'])) {
                $val = $request->input($field);
                if ($val !== null) {
                    $input[$field] = $val === '' ? null : $val;
                }
            }
        }

        // === Handle Foto Kepala Sekolah ===
        if ($section === 'kepsek') {
            foreach (['nama_kepsek', 'nip_kepsek', 'sambutan_kepsek_singkat'] as $f) {
                $val = $request->input($f);
                if ($val !== null) $input[$f] = $val === '' ? null : $val;
            }
            if ($request->hasFile('foto_kepsek')) {
                if ($data->foto_kepsek) native_storage_delete($data->foto_kepsek);
                $input['foto_kepsek'] = $request->file('foto_kepsek')->store('uploads', 'public');
            }
        }

        // === Handle Logo ===
        if ($section === 'logo') {
            if ($request->hasFile('logo')) {
                if ($data->logo) native_storage_delete($data->logo);
                $input['logo'] = $request->file('logo')->store('logo', 'public');
            }
        }

        // === Handle Gambar Halaman (Header & Identitas) ===
        if ($section === 'gambar_halaman') {
            if ($request->hasFile('gambar_header')) {
                if ($data->gambar_header) native_storage_delete($data->gambar_header);
                $input['gambar_header'] = $request->file('gambar_header')->store('header', 'public');
            } elseif ($request->input('delete_gambar_header')) {
                if ($data->gambar_header) native_storage_delete($data->gambar_header);
                $input['gambar_header'] = null;
            }

            if ($request->hasFile('foto_identitas')) {
                if ($data->foto_identitas) native_storage_delete($data->foto_identitas);
                $input['foto_identitas'] = $request->file('foto_identitas')->store('identitas', 'public');
            } elseif ($request->input('delete_foto_identitas')) {
                if ($data->foto_identitas) native_storage_delete($data->foto_identitas);
                $input['foto_identitas'] = null;
            }
        }

        // === Handle Slider Images (1–5) ===
        if ($section === 'slider') {
            for ($i = 1; $i <= 5; $i++) {
                $field = 'slider_' . $i;
                if ($request->hasFile($field)) {
                    if ($data->$field) native_storage_delete($data->$field);
                    $input[$field] = $request->file($field)->store('slider', 'public');
                } elseif ($request->input('delete_' . $field)) {
                    if ($data->$field) native_storage_delete($data->$field);
                    $input[$field] = null;
                }
            }
        }

        if (!empty($input)) {
            $data->update($input);
        }

        $label = $sectionLabels[$section] ?? 'Pengaturan';
        redirect('/admin/pengaturan')->with('success', $label . ' berhasil disimpan!');
    }
}
