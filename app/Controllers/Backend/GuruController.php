<?php
namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Guru;

class GuruController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!Auth::check()) { redirect('/login'); }
    }

    public function index()
    {
        $data = Guru::latest()->get();
        return view('backend.guru.index', compact('data'));
    }

    public function create()
    {
        return view('backend.guru.create');
    }

    public function store(Request $request)
    {
        $input = $request->except('_token');
        // Selalu set NIP ke null demi privasi
        $input['nip'] = null;
        if ($request->hasFile('foto')) {
            $input['foto'] = $request->file('foto')->store('uploads', 'public');
        }
        $guru = Guru::create($input);
        log_activity("Menambahkan data guru baru '{$guru->nama}' dengan jabatan '{$guru->jabatan}'", 'guru', $guru);
        redirect('/admin/guru')->with('success', 'Data Guru berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = Guru::findOrFail($id);
        return view('backend.guru.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $model = Guru::findOrFail($id);
        $input = $request->except('_token', '_method');
        // Selalu set NIP ke null demi privasi
        $input['nip'] = null;
        if ($request->hasFile('foto')) {
            if ($model->foto) native_storage_delete($model->foto);
            $input['foto'] = $request->file('foto')->store('uploads', 'public');
        }
        $oldName = $model->nama;
        $model->update($input);
        log_activity("Mengubah data guru '{$oldName}' (Jabatan: {$model->jabatan})", 'guru', $model);
        redirect('/admin/guru')->with('success', 'Data Guru berhasil diubah');
    }

    public function destroy($id)
    {
        $model = Guru::findOrFail($id);
        if ($model->foto) native_storage_delete($model->foto);
        $nama = $model->nama;
        $model->delete();
        log_activity("Menghapus data guru '{$nama}'", 'guru');
        redirect('/admin/guru')->with('success', 'Data Guru berhasil dihapus');
    }
}
