@extends('layouts.admin')
@section('title', 'Tambah Guru/Tendik')
@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center py-3" style="background:linear-gradient(135deg,#003366,#0056b3);border-radius:.5rem .5rem 0 0;"><h5 class="mb-0 text-white fw-bold"><i class="fas fa-plus me-2"></i>Tambah Guru/Tendik</h5></div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form enctype="multipart/form-data" action="{{ url('admin.guru.store') }}" method="POST">
            {!! csrf_field() !!}
            
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Jabatan / Mapel</label>
            <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Kategori</label>
            <select name="kategori" class="form-select" required>
                <option value="kelas" {{ old('kategori') == 'kelas' ? 'selected' : '' }}>Guru Kelas</option>
                <option value="mapel" {{ old('kategori') == 'mapel' ? 'selected' : '' }}>Guru Mapel</option>
                <option value="pendamping" {{ old('kategori') == 'pendamping' ? 'selected' : '' }}>Guru Pendamping</option>
                <option value="tendik" {{ old('kategori') == 'tendik' ? 'selected' : '' }}>Tenaga Kependidikan</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Pendidikan Terakhir</label>
            <select name="pendidikan" class="form-select">
                <option value="">-- Pilih Pendidikan --</option>
                <option value="SD" {{ old('pendidikan') == 'SD' ? 'selected' : '' }}>SD</option>
                <option value="SMP" {{ old('pendidikan') == 'SMP' ? 'selected' : '' }}>SMP</option>
                <option value="SMA" {{ old('pendidikan') == 'SMA' ? 'selected' : '' }}>SMA</option>
                <option value="S1" {{ old('pendidikan') == 'S1' ? 'selected' : '' }}>S1</option>
                <option value="S2" {{ old('pendidikan') == 'S2' ? 'selected' : '' }}>S2</option>
                <option value="S3" {{ old('pendidikan') == 'S3' ? 'selected' : '' }}>S3</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Biodata / Tentang</label>
            <textarea name="biodata" class="form-control" rows="4" >{{ old('biodata') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Foto Profil</label>
            <input type="file" name="foto" class="form-control">
        </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="{{ url('admin.guru.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection