@extends('layouts.admin')
@section('title', 'Pengaturan Website')
@section('content')

<div class="row g-4">

    {{-- ===== SEGMEN 1: IDENTITAS SEKOLAH ===== --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex align-items-center justify-content-between py-3"
                 style="background:linear-gradient(135deg,#003366,#0056b3); border-radius: .5rem .5rem 0 0;">
                <h6 class="mb-0 text-white fw-bold">
                    <i class="fas fa-school me-2"></i>Identitas Sekolah
                </h6>
                <span class="badge bg-white text-primary small">Segmen 1 / 6</span>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/pengaturan') }}" method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="section" value="identitas">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="nama_sekolah" class="form-control" value="{{ $data->nama_sekolah }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $data->email }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telepon</label>
                            <input type="text" name="telepon" class="form-control" value="{{ $data->telepon }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">WhatsApp <small class="text-muted">(Format: 628xxx)</small></label>
                            <input type="text" name="whatsapp" class="form-control" value="{{ $data->whatsapp }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="2">{{ $data->alamat }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Embed Google Maps <small class="text-muted">(src URL dari iframe)</small></label>
                            <textarea name="google_maps" class="form-control" rows="2" placeholder="https://maps.google.com/maps?q=...&output=embed">{{ $data->google_maps }}</textarea>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Simpan Identitas
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== SEGMEN 2: KEPALA SEKOLAH ===== --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex align-items-center justify-content-between py-3"
                 style="background: linear-gradient(135deg, #0e7490, #06b6d4); border-radius: .5rem .5rem 0 0;">
                <h6 class="mb-0 text-white fw-bold">
                    <i class="fas fa-user-tie me-2"></i>Kepala Sekolah
                </h6>
                <span class="badge bg-white text-info small">Segmen 2 / 6</span>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/pengaturan') }}" method="POST" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" name="section" value="kepsek">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Kepala Sekolah</label>
                            <input type="text" name="nama_kepsek" class="form-control" value="{{ $data->nama_kepsek ?? '' }}" placeholder="Sukanto, S.Pd.">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIP</label>
                            <input type="text" name="nip_kepsek" class="form-control" value="{{ $data->nip_kepsek ?? '' }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Sambutan Singkat <small class="text-muted">(tampil di homepage)</small></label>
                            <textarea name="sambutan_kepsek_singkat" class="form-control" rows="3" placeholder="Teks untuk ditampilkan di bagian sambutan homepage...">{{ $data->sambutan_kepsek_singkat ?? '' }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Foto Kepala Sekolah</label>
                            @if($data->foto_kepsek)
                                <div class="mb-2 text-center">
                                    <img src="{{ asset('storage/'.$data->foto_kepsek) }}"
                                         class="img-thumbnail rounded-circle shadow-sm"
                                         style="height:80px;width:80px;object-fit:cover;">
                                    <div class="small text-muted mt-1">Foto saat ini</div>
                                </div>
                            @endif
                            <input type="file" name="foto_kepsek" class="form-control" accept="image/*">
                            <small class="text-muted"><i class="fas fa-info-circle me-1 text-primary"></i>Format JPG/PNG, maks. 2MB</small>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-info text-white px-4">
                                <i class="fas fa-save me-2"></i>Simpan Data Kepala Sekolah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== SEGMEN 3: STATISTIK SEKOLAH ===== --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex align-items-center justify-content-between py-3"
                 style="background: linear-gradient(135deg, #166534, #16a34a); border-radius: .5rem .5rem 0 0;">
                <h6 class="mb-0 text-white fw-bold">
                    <i class="fas fa-chart-bar me-2"></i>Statistik Sekolah
                    <small class="ms-2 opacity-75">(Angka di Homepage)</small>
                </h6>
                <span class="badge bg-white text-success small">Segmen 3 / 6</span>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/pengaturan') }}" method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="section" value="statistik">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Jumlah Siswa Aktif</label>
                            <input type="number" name="jumlah_siswa" class="form-control" value="{{ $data->jumlah_siswa ?? '' }}" placeholder="350">
                            <small class="text-muted"><i class="fas fa-robot me-1 text-success"></i>Kosongkan = hitung otomatis</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Jumlah Guru & Tendik</label>
                            <input type="number" name="jumlah_guru" class="form-control" value="{{ $data->jumlah_guru ?? '' }}" placeholder="24">
                            <small class="text-muted"><i class="fas fa-robot me-1 text-success"></i>Kosongkan = hitung otomatis</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Jumlah Alumni</label>
                            <input type="number" name="jumlah_alumni" class="form-control" value="{{ $data->jumlah_alumni ?? '' }}" placeholder="1500">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Akreditasi</label>
                            <select name="akreditasi" class="form-select">
                                @foreach(['A', 'B', 'C', 'Unggul', 'Baik Sekali', 'Baik'] as $ak)
                                <option value="{{ $ak }}" {{ ($data->akreditasi ?? 'A') == $ak ? 'selected' : '' }}>{{ $ak }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save me-2"></i>Simpan Statistik
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== SEGMEN 4: IMAGE SLIDER ===== --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex align-items-center justify-content-between py-3"
                 style="background: linear-gradient(135deg, #92400e, #d97706); border-radius: .5rem .5rem 0 0;">
                <h6 class="mb-0 text-white fw-bold">
                    <i class="fas fa-images me-2"></i>Image Slider Hero
                    <small class="ms-2 opacity-75">(Maks. 5 Foto)</small>
                </h6>
                <span class="badge bg-white text-warning small">Segmen 4 / 6</span>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    <i class="fas fa-info-circle text-info me-1"></i>
                    Upload foto untuk slider homepage. Ukuran ideal: <strong>1920×700px</strong>. Format JPG/PNG, maks. 2MB per foto.
                </p>
                <form action="{{ url('/admin/pengaturan') }}" method="POST" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" name="section" value="slider">
                    <div class="row g-3 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                        <div class="col-md-4 col-lg-2" style="min-width:200px;">
                            <div class="card border h-100 shadow-sm">
                                <div class="card-body p-3">
                                    <label class="form-label fw-semibold small">
                                        <i class="fas fa-image me-1 text-warning"></i>Foto Slider {{ $i }}
                                    </label>
                                    @php $field = 'slider_'.$i; @endphp
                                    @if($data->$field)
                                        <div class="mb-2 position-relative">
                                            <img src="{{ asset('storage/'.$data->$field) }}"
                                                 class="img-fluid rounded shadow-sm"
                                                 style="height:100px; width:100%; object-fit:cover;">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox"
                                                       name="delete_{{ $field }}" id="del_{{ $field }}">
                                                <label class="form-check-label text-danger small" for="del_{{ $field }}">
                                                    <i class="fas fa-trash-alt me-1"></i>Hapus foto ini
                                                </label>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mb-2 bg-light rounded d-flex align-items-center justify-content-center border-dashed"
                                             style="height:100px; border:2px dashed #dee2e6;">
                                            <div class="text-center">
                                                <i class="fas fa-cloud-upload-alt text-muted fa-2x"></i>
                                                <div class="small text-muted mt-1">Belum ada foto</div>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" name="{{ $field }}" class="form-control form-control-sm" accept="image/*">
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning text-dark px-4">
                            <i class="fas fa-save me-2"></i>Simpan Image Slider
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== SEGMEN 5: VISI MISI & SEJARAH ===== --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex align-items-center justify-content-between py-3"
                 style="background: linear-gradient(135deg, #5b21b6, #7c3aed); border-radius: .5rem .5rem 0 0;">
                <h6 class="mb-0 text-white fw-bold">
                    <i class="fas fa-eye me-2"></i>Visi, Misi & Sejarah
                </h6>
                <span class="badge bg-white text-purple small" style="color:#7c3aed!important;">Segmen 5 / 6</span>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/pengaturan') }}" method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="section" value="visi_misi">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Visi Sekolah</label>
                            <textarea name="visi" class="form-control" rows="4">{{ $data->visi }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Misi Sekolah</label>
                            <textarea name="misi" class="form-control" rows="4">{{ $data->misi }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Sejarah Sekolah</label>
                            <textarea name="sejarah" class="form-control" rows="5">{{ $data->sejarah }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Sambutan Kepala Sekolah <small class="text-muted">(Teks Lengkap)</small></label>
                            <textarea name="sambutan_kepsek" class="form-control" rows="5">{{ $data->sambutan_kepsek }}</textarea>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn px-4 text-white" style="background:#7c3aed;">
                                <i class="fas fa-save me-2"></i>Simpan Visi, Misi & Sejarah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== SEGMEN 6: MEDIA SOSIAL & LOGO ===== --}}
    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header d-flex align-items-center justify-content-between py-3"
                 style="background: linear-gradient(135deg, #be185d, #ec4899); border-radius: .5rem .5rem 0 0;">
                <h6 class="mb-0 text-white fw-bold">
                    <i class="fas fa-share-alt me-2"></i>Media Sosial & YouTube
                </h6>
                <span class="badge bg-white small" style="color:#be185d!important;">Segmen 6a</span>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/pengaturan') }}" method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="section" value="sosmed">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fab fa-facebook text-primary me-1"></i>Facebook URL
                        </label>
                        <input type="url" name="facebook" class="form-control" value="{{ $data->facebook }}" placeholder="https://facebook.com/...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fab fa-instagram text-danger me-1"></i>Instagram URL
                        </label>
                        <input type="url" name="instagram" class="form-control" value="{{ $data->instagram }}" placeholder="https://instagram.com/...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fab fa-youtube text-danger me-1"></i>YouTube URL
                        </label>
                        <input type="url" name="youtube" class="form-control" value="{{ $data->youtube }}" placeholder="https://youtube.com/...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fab fa-youtube text-danger me-1"></i>YouTube Embed ID
                            <small class="text-muted">(untuk video profil)</small>
                        </label>
                        <input type="text" name="youtube_embed" class="form-control" value="{{ $data->youtube_embed ?? '' }}" placeholder="dQw4w9WgXcQ">
                        <small class="text-muted">Isi hanya ID video YouTube (bagian setelah watch?v=)</small>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn px-4 text-white" style="background:#be185d;">
                            <i class="fas fa-save me-2"></i>Simpan Media Sosial
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== SEGMEN 6b: LOGO ===== --}}
    <div class="col-md-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header d-flex align-items-center justify-content-between py-3"
                 style="background: linear-gradient(135deg, #78350f, #b45309); border-radius: .5rem .5rem 0 0;">
                <h6 class="mb-0 text-white fw-bold">
                    <i class="fas fa-image me-2"></i>Logo Sekolah
                </h6>
                <span class="badge bg-white small" style="color:#b45309!important;">Segmen 6b</span>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/pengaturan') }}" method="POST" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" name="section" value="logo">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Logo</label>
                        @if($data->logo)
                            <div class="mb-3 text-center">
                                <img src="{{ asset('storage/'.$data->logo) }}"
                                     class="img-thumbnail shadow-sm"
                                     style="max-height:90px; background:#f8f9fa; padding:8px; border-radius:8px;">
                                <div class="small text-muted mt-1">Logo saat ini</div>
                            </div>
                        @endif
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small class="text-muted">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            Format PNG transparan direkomendasikan. Maks. 1MB.
                        </small>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn px-4 text-white" style="background:#b45309;">
                            <i class="fas fa-save me-2"></i>Simpan Logo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== SEGMEN 7: GAMBAR HALAMAN (HEADER & IDENTITAS) ===== --}}
    <div class="col-12 mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex align-items-center justify-content-between py-3"
                 style="background: linear-gradient(135deg, #1e293b, #334155); border-radius: .5rem .5rem 0 0;">
                <h6 class="mb-0 text-white fw-bold">
                    <i class="fas fa-panorama me-2"></i>Gambar Halaman Sub-Page & Identitas Sekolah
                    <small class="ms-2 opacity-75">(Agar Tampilan Tidak Monoton)</small>
                </h6>
                <span class="badge bg-white text-dark small">Segmen 7</span>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/pengaturan') }}" method="POST" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" name="section" value="gambar_halaman">
                    <div class="row g-4">
                        <!-- Gambar Header Subpage -->
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="fas fa-image text-primary me-1"></i>Gambar Header Sub-Page
                                </label>
                                <p class="small text-muted mb-2">Ditampilkan sebagai latar belakang header judul di semua halaman publik (Berita, Agenda, Galeri, dll).</p>
                                @if($data->gambar_header)
                                    <div class="mb-3 position-relative">
                                        <img src="{{ asset('storage/'.$data->gambar_header) }}" class="img-fluid rounded shadow-sm" style="max-height:140px; width:100%; object-fit:cover;">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="delete_gambar_header" id="del_gh">
                                            <label class="form-check-label text-danger small" for="del_gh"><i class="fas fa-trash-alt me-1"></i>Hapus & gunakan gambar default</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-2 bg-white rounded d-flex align-items-center justify-content-center border-dashed" style="height:100px; border:2px dashed #cbd5e1;">
                                        <span class="small text-muted"><i class="fas fa-info-circle me-1"></i>Menggunakan Gambar Default</span>
                                    </div>
                                @endif
                                <input type="file" name="gambar_header" class="form-control form-control-sm" accept="image/*">
                            </div>
                        </div>

                        <!-- Gambar Identitas Sekolah -->
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="fas fa-id-card text-success me-1"></i>Foto Halaman Identitas Sekolah
                                </label>
                                <p class="small text-muted mb-2">Foto kegiatan / gedung yang ditampilkan di bagian kanan atas halaman Identitas Sekolah.</p>
                                @if($data->foto_identitas)
                                    <div class="mb-3 position-relative">
                                        <img src="{{ asset('storage/'.$data->foto_identitas) }}" class="img-fluid rounded shadow-sm" style="max-height:140px; width:100%; object-fit:cover;">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="delete_foto_identitas" id="del_fi">
                                            <label class="form-check-label text-danger small" for="del_fi"><i class="fas fa-trash-alt me-1"></i>Hapus & gunakan foto default</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-2 bg-white rounded d-flex align-items-center justify-content-center border-dashed" style="height:100px; border:2px dashed #cbd5e1;">
                                        <span class="small text-muted"><i class="fas fa-info-circle me-1"></i>Menggunakan Foto Default</span>
                                    </div>
                                @endif
                                <input type="file" name="foto_identitas" class="form-control form-control-sm" accept="image/*">
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-dark px-4">
                                <i class="fas fa-save me-2"></i>Simpan Gambar Halaman
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection