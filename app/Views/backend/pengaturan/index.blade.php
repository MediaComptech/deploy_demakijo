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
            <div class="card-header py-3"
                 style="background: linear-gradient(135deg, #1e293b, #334155); border-radius: .5rem .5rem 0 0;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-0 text-white fw-bold">
                            <i class="fas fa-panorama me-2"></i>Gambar Halaman Publik
                        </h6>
                        <small class="text-white opacity-60">Ganti foto Hero, Banner halaman & Foto Identitas Sekolah</small>
                    </div>
                    <span class="badge bg-white text-dark small">Segmen 7</span>
                </div>
            </div>
            <div class="card-body p-4">

                {{-- Info Banner --}}
                <div class="alert alert-info border-0 rounded-3 d-flex align-items-start gap-3 mb-4" style="background:#eff6ff;">
                    <i class="fas fa-lightbulb text-info fs-5 mt-1"></i>
                    <div class="small">
                        <strong class="d-block mb-1">Panduan Upload Gambar</strong>
                        <ul class="mb-0 ps-3">
                            <li><strong>Gambar Header Sub-Page</strong> — latar belakang biru/foto di atas halaman Guru, Berita, Agenda, Galeri, dll. Ukuran ideal: <strong>1920×700px</strong></li>
                            <li><strong>Foto Identitas Sekolah</strong> — foto gedung/kegiatan yang tampil di halaman Identitas Sekolah, sebelah kanan tabel data. Ukuran ideal: <strong>800×500px</strong></li>
                            <li><strong>Gambar Latar Belakang Login</strong> — foto ilustrasi halaman login sebelah kiri. Ukuran ideal: <strong>800×800px</strong> (Square/Portrait)</li>
                        </ul>
                        Format: <strong>JPG / PNG / WebP</strong> &bull; Maks. <strong>2 MB</strong> per gambar
                    </div>
                </div>

                <form action="{{ url('/admin/pengaturan') }}" method="POST" enctype="multipart/form-data" id="form-gambar-halaman">
                    {!! csrf_field() !!}
                    <input type="hidden" name="section" value="gambar_halaman">

                    <div class="row g-4">

                        {{-- ===== SLOT 1: Gambar Header Sub-Page ===== --}}
                        <div class="col-md-4">
                            <div class="card border h-100 rounded-3 overflow-hidden shadow-sm">
                                <div class="card-header d-flex align-items-center gap-2 py-2 px-3"
                                     style="background:#f1f5ff; border-bottom:2px solid #0d6efd;">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                         style="width:30px;height:30px;background:#0d6efd;flex-shrink:0;">
                                        <i class="fas fa-images" style="font-size:0.75rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold small text-dark">Gambar Header Sub-Page</div>
                                        <div class="text-muted" style="font-size:0.7rem;">Ditampilkan di semua halaman selain beranda</div>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    {{-- Preview Gambar Saat Ini --}}
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold text-muted text-uppercase" style="letter-spacing:.5px;">Gambar Saat Ini</label>
                                        @if($data->gambar_header)
                                            <div class="position-relative rounded-3 overflow-hidden" style="height:140px;">
                                                <img src="{{ asset('storage/'.$data->gambar_header) }}"
                                                     class="w-100 h-100" style="object-fit:cover;" alt="Header saat ini">
                                                <div class="position-absolute bottom-0 start-0 end-0 px-2 py-1"
                                                     style="background:rgba(0,0,0,0.5);">
                                                    <span class="text-white" style="font-size:0.7rem;">
                                                        <i class="fas fa-check-circle text-success me-1"></i>Gambar custom terpasang
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="delete_gambar_header" id="del_gh">
                                                <label class="form-check-label text-danger small" for="del_gh">
                                                    <i class="fas fa-trash-alt me-1"></i>Hapus &amp; kembali ke gambar default
                                                </label>
                                            </div>
                                        @else
                                            <div class="rounded-3 d-flex flex-column align-items-center justify-content-center text-center"
                                                 style="height:140px; border:2px dashed #cbd5e1; background:#f8fafc;">
                                                <i class="fas fa-image text-muted fa-2x mb-2 opacity-50"></i>
                                                <span class="small text-muted">Menggunakan Gambar Default</span>
                                                <span class="text-muted" style="font-size:0.7rem;">(foto sekolah dari Unsplash)</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Preview Live --}}
                                    <div id="preview-wrap-gambar_header" class="mb-3" style="display:none;">
                                        <label class="form-label small fw-semibold text-primary text-uppercase" style="letter-spacing:.5px;">
                                            <i class="fas fa-eye me-1"></i>Preview Gambar Baru
                                        </label>
                                        <div class="rounded-3 overflow-hidden" style="height:100px;">
                                            <img id="preview-gambar_header" src="" class="w-100 h-100" style="object-fit:cover;" alt="Preview">
                                        </div>
                                        <div id="info-gambar_header" class="small text-success mt-1"></div>
                                    </div>

                                    {{-- Input File --}}
                                    <label class="form-label small fw-semibold text-dark">
                                        <i class="fas fa-upload me-1 text-primary"></i>Pilih Gambar Baru
                                    </label>
                                    <input type="file" name="gambar_header" id="file-gambar_header"
                                           class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp"
                                           onchange="previewImage(this, 'gambar_header')">
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-info-circle me-1 text-primary"></i>Rekomendasi: <strong>1920×700px</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ===== SLOT 2: Foto Identitas Sekolah ===== --}}
                        <div class="col-md-4">
                            <div class="card border h-100 rounded-3 overflow-hidden shadow-sm">
                                <div class="card-header d-flex align-items-center gap-2 py-2 px-3"
                                     style="background:#f0fdf4; border-bottom:2px solid #16a34a;">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                         style="width:30px;height:30px;background:#16a34a;flex-shrink:0;">
                                        <i class="fas fa-id-card" style="font-size:0.75rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold small text-dark">Foto Identitas Sekolah</div>
                                        <div class="text-muted" style="font-size:0.7rem;">Tampil di halaman Identitas Sekolah</div>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    {{-- Preview Gambar Saat Ini --}}
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold text-muted text-uppercase" style="letter-spacing:.5px;">Foto Saat Ini</label>
                                        @if($data->foto_identitas)
                                            <div class="position-relative rounded-3 overflow-hidden" style="height:140px;">
                                                <img src="{{ asset('storage/'.$data->foto_identitas) }}"
                                                     class="w-100 h-100" style="object-fit:cover;" alt="Foto identitas saat ini">
                                                <div class="position-absolute bottom-0 start-0 end-0 px-2 py-1"
                                                     style="background:rgba(0,0,0,0.5);">
                                                    <span class="text-white" style="font-size:0.7rem;">
                                                        <i class="fas fa-check-circle text-success me-1"></i>Foto custom terpasang
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="delete_foto_identitas" id="del_fi">
                                                <label class="form-check-label text-danger small" for="del_fi">
                                                    <i class="fas fa-trash-alt me-1"></i>Hapus &amp; kembali ke foto default
                                                </label>
                                            </div>
                                        @else
                                            <div class="rounded-3 d-flex flex-column align-items-center justify-content-center text-center"
                                                 style="height:140px; border:2px dashed #cbd5e1; background:#f8fafc;">
                                                <i class="fas fa-id-card text-muted fa-2x mb-2 opacity-50"></i>
                                                <span class="small text-muted">Menggunakan Foto Default</span>
                                                <span class="text-muted" style="font-size:0.7rem;">(foto wisuda dari Unsplash)</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Preview Live --}}
                                    <div id="preview-wrap-foto_identitas" class="mb-3" style="display:none;">
                                        <label class="form-label small fw-semibold text-success text-uppercase" style="letter-spacing:.5px;">
                                            <i class="fas fa-eye me-1"></i>Preview Foto Baru
                                        </label>
                                        <div class="rounded-3 overflow-hidden" style="height:100px;">
                                            <img id="preview-foto_identitas" src="" class="w-100 h-100" style="object-fit:cover;" alt="Preview">
                                        </div>
                                        <div id="info-foto_identitas" class="small text-success mt-1"></div>
                                    </div>

                                    {{-- Input File --}}
                                    <label class="form-label small fw-semibold text-dark">
                                        <i class="fas fa-upload me-1 text-success"></i>Pilih Foto Baru
                                    </label>
                                    <input type="file" name="foto_identitas" id="file-foto_identitas"
                                           class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp"
                                           onchange="previewImage(this, 'foto_identitas')">
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-info-circle me-1 text-success"></i>Rekomendasi: <strong>800×500px</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ===== SLOT 3: Foto Latar Belakang Login ===== --}}
                        <div class="col-md-4">
                            <div class="card border h-100 rounded-3 overflow-hidden shadow-sm">
                                <div class="card-header d-flex align-items-center gap-2 py-2 px-3"
                                     style="background:#fff7ed; border-bottom:2px solid #ea580c;">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                         style="width:30px;height:30px;background:#ea580c;flex-shrink:0;">
                                        <i class="fas fa-sign-in-alt" style="font-size:0.75rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold small text-dark">Latar Belakang Login</div>
                                        <div class="text-muted" style="font-size:0.7rem;">Ilustrasi sebelah kiri halaman login</div>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    {{-- Preview Gambar Saat Ini --}}
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold text-muted text-uppercase" style="letter-spacing:.5px;">Foto Saat Ini</label>
                                        @if($data->foto_login)
                                            <div class="position-relative rounded-3 overflow-hidden" style="height:140px;">
                                                <img src="{{ asset('storage/'.$data->foto_login) }}"
                                                     class="w-100 h-100" style="object-fit:cover;" alt="Foto login saat ini">
                                                <div class="position-absolute bottom-0 start-0 end-0 px-2 py-1"
                                                     style="background:rgba(0,0,0,0.5);">
                                                    <span class="text-white" style="font-size:0.7rem;">
                                                        <i class="fas fa-check-circle text-success me-1"></i>Foto custom terpasang
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="delete_foto_login" id="del_fl">
                                                <label class="form-check-label text-danger small" for="del_fl">
                                                    <i class="fas fa-trash-alt me-1"></i>Hapus &amp; kembali ke foto default
                                                </label>
                                            </div>
                                        @else
                                            <div class="rounded-3 d-flex flex-column align-items-center justify-content-center text-center"
                                                 style="height:140px; border:2px dashed #cbd5e1; background:#f8fafc;">
                                                <i class="fas fa-sign-in-alt text-muted fa-2x mb-2 opacity-50"></i>
                                                <span class="small text-muted">Menggunakan Foto Default</span>
                                                <span class="text-muted" style="font-size:0.7rem;">(foto wisuda dari Unsplash)</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Preview Live --}}
                                    <div id="preview-wrap-foto_login" class="mb-3" style="display:none;">
                                        <label class="form-label small fw-semibold text-warning text-uppercase" style="letter-spacing:.5px;">
                                            <i class="fas fa-eye me-1"></i>Preview Foto Baru
                                        </label>
                                        <div class="rounded-3 overflow-hidden" style="height:100px;">
                                            <img id="preview-foto_login" src="" class="w-100 h-100" style="object-fit:cover;" alt="Preview">
                                        </div>
                                        <div id="info-foto_login" class="small text-success mt-1"></div>
                                    </div>

                                    {{-- Input File --}}
                                    <label class="form-label small fw-semibold text-dark">
                                        <i class="fas fa-upload me-1 text-warning"></i>Pilih Foto Baru
                                    </label>
                                    <input type="file" name="foto_login" id="file-foto_login"
                                           class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp"
                                           onchange="previewImage(this, 'foto_login')">
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-info-circle me-1 text-warning"></i>Rekomendasi: <strong>800×800px</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                                 style="background:#f8fafc; border:1px dashed #cbd5e1;">
                                <div class="small text-muted">
                                    <i class="fas fa-circle-info me-1 text-primary"></i>
                                    Perubahan akan langsung tampil di website publik setelah disimpan.
                                </div>
                                <button type="submit" class="btn btn-dark px-5 fw-bold" id="btn-simpan-seg7">
                                    <i class="fas fa-save me-2"></i>Simpan Gambar Halaman
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== SEGMEN 8: PROFIL DETAIL & AKREDITASI ===== --}}
    <div class="col-12 mt-4">
        <div class="card shadow-sm border-0" style="border: 2px solid #dc3545 !important;">
            <div class="card-header d-flex align-items-center justify-content-between py-3"
                 style="background: linear-gradient(135deg, #7f1d1d, #dc2626); border-radius: .5rem .5rem 0 0;">
                <h6 class="mb-0 text-white fw-bold">
                    <i class="fas fa-id-badge me-2"></i>Profil Sekolah & Akreditasi
                    <small class="ms-2 opacity-75">(Halaman Identitas & Akreditasi Publik)</small>
                </h6>
                <span class="badge bg-white text-danger small">Segmen 8</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ url('/admin/pengaturan') }}" method="POST" id="form-profil-detail" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" name="section" value="profil_detail">

                    {{-- === Data Profil Sekolah === --}}
                    <h6 class="fw-bold text-danger mb-3 border-bottom pb-2">
                        <i class="fas fa-school me-2"></i>Data Profil Sekolah
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">NPSN</label>
                            <input type="text" name="npsn" class="form-control" value="{{ $data->npsn ?? '20401066' }}" placeholder="20401066">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Status Sekolah</label>
                            <select name="status_sekolah" class="form-select">
                                @foreach(['Negeri', 'Swasta'] as $s)
                                <option value="{{ $s }}" {{ ($data->status_sekolah ?? 'Negeri') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Bentuk Pendidikan</label>
                            <select name="bentuk_pendidikan" class="form-select">
                                @foreach(['SD', 'SMP', 'SMA', 'SMK', 'MI', 'MTs', 'MA'] as $b)
                                <option value="{{ $b }}" {{ ($data->bentuk_pendidikan ?? 'SD') == $b ? 'selected' : '' }}>{{ $b }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Kode Pos</label>
                            <input type="text" name="kode_pos" class="form-control" value="{{ $data->kode_pos ?? '55294' }}" placeholder="55294">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tahun Berdiri</label>
                            <input type="text" name="tahun_berdiri" class="form-control" value="{{ $data->tahun_berdiri ?? '1985' }}" placeholder="1985">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Luas Tanah</label>
                            <input type="text" name="luas_tanah" class="form-control" value="{{ $data->luas_tanah ?? '2.450 m²' }}" placeholder="2.450 m²">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Jumlah Ruang Kelas</label>
                            <input type="number" name="jumlah_kelas" class="form-control" value="{{ $data->jumlah_kelas ?? 24 }}" placeholder="24">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Jumlah Tendik</label>
                            <input type="number" name="jumlah_tendik" class="form-control" value="{{ $data->jumlah_tendik ?? 7 }}" placeholder="7">
                        </div>
                    </div>

                    {{-- === Standar Akreditasi === --}}
                    <h6 class="fw-bold text-danger mb-3 border-bottom pb-2">
                        <i class="fas fa-award me-2"></i>Data Akreditasi Sekolah
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">No. Sertifikat Akreditasi</label>
                            <input type="text" name="akreditasi_no_sertifikat" class="form-control" value="{{ $data->akreditasi_no_sertifikat ?? '' }}" placeholder="1234/BAN-SM/AK/XII/2022">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tahun Akreditasi</label>
                            <input type="text" name="akreditasi_tahun" class="form-control" value="{{ $data->akreditasi_tahun ?? '' }}" placeholder="2022">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Peringkat Akreditasi</label>
                            <input type="text" name="akreditasi_peringkat" class="form-control" value="{{ $data->akreditasi_peringkat ?? 'A (Unggul)' }}" placeholder="A (Unggul)">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Berlaku Hingga</label>
                            <input type="text" name="akreditasi_berlaku" class="form-control" value="{{ $data->akreditasi_berlaku ?? '' }}" placeholder="2027">
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="form-label fw-semibold">File Sertifikat Akreditasi <small class="text-muted fw-normal">(PDF atau Gambar)</small></label>
                            @if(!empty($data->akreditasi_sertifikat_file))
                                <div class="mb-2 d-flex align-items-center gap-3">
                                    <a href="{{ asset('storage/' . $data->akreditasi_sertifikat_file) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-file-pdf me-1"></i>Lihat Sertifikat Saat Ini
                                    </a>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="delete_akreditasi_sertifikat_file" id="del_sertifikat_file">
                                        <label class="form-check-label text-danger small" for="del_sertifikat_file">
                                            <i class="fas fa-trash me-1"></i>Hapus sertifikat ini
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="akreditasi_sertifikat_file" class="form-control" accept=".pdf,image/*">
                            <small class="text-muted"><i class="fas fa-info-circle text-primary me-1"></i>Maks. 2MB. Format: PDF, JPG, PNG, WebP.</small>
                        </div>
                    </div>

                    <p class="text-muted small mb-2"><i class="fas fa-info-circle text-primary me-1"></i>Nilai standar akreditasi (0–100):</p>
                    <div class="row g-3 mb-4">
                        @php
                        $standarAkreditasi = [
                            ['akreditasi_standar_isi', 'Standar Isi', 93],
                            ['akreditasi_standar_proses', 'Standar Proses', 92],
                            ['akreditasi_standar_skl', 'Standar SKL', 95],
                            ['akreditasi_standar_ptk', 'Standar PTK', 93],
                            ['akreditasi_standar_sarpras', 'Standar Sarpras', 90],
                            ['akreditasi_standar_pengelolaan', 'Standar Pengelolaan', 94],
                            ['akreditasi_standar_pembiayaan', 'Standar Pembiayaan', 91],
                            ['akreditasi_standar_penilaian', 'Standar Penilaian', 92],
                        ];
                        @endphp
                        @foreach($standarAkreditasi as $standarItem)
                        @php $sField = $standarItem[0]; $sLabel = $standarItem[1]; $sDefault = $standarItem[2]; @endphp
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-semibold small">{{ $sLabel }}</label>
                            <input type="number" name="{{ $sField }}" min="0" max="100" class="form-control form-control-sm"
                                   value="{{ $data->$sField ?? $sDefault }}" placeholder="{{ $sDefault }}">
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-danger px-5 fw-bold">
                            <i class="fas fa-save me-2"></i>Simpan Profil & Akreditasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
/**
 * Live preview gambar saat file dipilih (Segmen 7)
 */
function previewImage(input, fieldName) {
    const file = input.files[0];
    if (!file) return;

    // Validasi tipe file
    const allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!allowed.includes(file.type)) {
        alert('Format file tidak didukung. Gunakan JPG, PNG, atau WebP.');
        input.value = '';
        return;
    }

    // Validasi ukuran (maks. 2MB)
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran file terlalu besar. Maks. 2MB.\nUkuran file Anda: ' + (file.size / 1024 / 1024).toFixed(2) + ' MB');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const previewWrap = document.getElementById('preview-wrap-' + fieldName);
        const previewImg  = document.getElementById('preview-' + fieldName);
        const infoEl      = document.getElementById('info-' + fieldName);

        if (previewWrap) previewWrap.style.display = 'block';
        if (previewImg)  previewImg.src = e.target.result;
        if (infoEl) {
            const sizeMB = (file.size / 1024 / 1024).toFixed(2);
            infoEl.innerHTML = '<i class="fas fa-check-circle me-1"></i><strong>' + file.name + '</strong> &bull; ' + sizeMB + ' MB';
        }
    };
    reader.readAsDataURL(file);
}

// Konfirmasi sebelum submit jika ada checkbox delete yang tercentang
document.getElementById('form-gambar-halaman').addEventListener('submit', function(e) {
    const delGH = document.getElementById('del_gh');
    const delFI = document.getElementById('del_fi');
    const delFL = document.getElementById('del_fl');
    const toDelete = [];
    if (delGH && delGH.checked) toDelete.push('Gambar Header Sub-Page');
    if (delFI && delFI.checked) toDelete.push('Foto Identitas Sekolah');
    if (delFL && delFL.checked) toDelete.push('Gambar Latar Belakang Login');
    if (toDelete.length > 0) {
        if (!confirm('Anda akan menghapus:\n• ' + toDelete.join('\n• ') + '\n\nLanjutkan?')) {
            e.preventDefault();
        }
    }
});
</script>
@endpush

@endsection