@extends('layouts.admin')
@section('title', 'Edit Foto Galeri')
@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center py-3"
         style="background:linear-gradient(135deg,#003366,#0056b3);border-radius:.5rem .5rem 0 0;">
        <h5 class="mb-0 text-white fw-bold"><i class="fas fa-edit me-2"></i>Edit Foto Galeri</h5>
        <a href="{{ url('/admin/galeri') }}" class="btn btn-light btn-sm fw-semibold">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
    <div class="card-body p-4">

        @if(session('error'))
            <div class="alert alert-danger border-0 rounded-3">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form enctype="multipart/form-data" action="{{ url('/admin/galeri/' . $data->id . '/update') }}" method="POST">
            {!! csrf_field() !!}
            <input type="hidden" name="_method" value="POST">

            <div class="row g-4">
                {{-- Kolom Kiri --}}
                <div class="col-lg-5">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Album <span class="text-danger">*</span></label>
                        <select name="album_id" class="form-select" required>
                            <option value="">-- Pilih Album --</option>
                            @foreach($album as $alb)
                            <option value="{{ $alb->id }}" {{ $data->album_id == $alb->id ? 'selected' : '' }}>
                                {{ $alb->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Foto <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control"
                               value="{{ old('judul', $data->judul) }}" maxlength="200" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan <small class="text-muted fw-normal">(opsional)</small></label>
                        <textarea name="keterangan" class="form-control" rows="2" maxlength="500">{{ old('keterangan', $data->keterangan ?? '') }}</textarea>
                    </div>

                    {{-- URL Google Drive --}}
                    <div class="card border-0 rounded-3 p-3" style="background:#f0f7ff; border-left:4px solid #0056b3 !important;">
                        <label class="form-label fw-semibold mb-1">
                            <i class="fab fa-google-drive text-primary me-1"></i>URL Google Drive
                            <small class="text-muted fw-normal d-block mt-1">Kosongkan jika menggunakan upload file biasa.</small>
                        </label>
                        <input type="url" name="url_gdrive" class="form-control"
                               value="{{ old('url_gdrive', $data->url_gdrive ?? '') }}"
                               placeholder="https://drive.google.com/file/d/...">
                        <small class="text-muted mt-1 d-block"><i class="fas fa-shield-alt text-success me-1"></i>Hanya URL dari Google Drive yang diterima.</small>
                    </div>
                </div>

                {{-- Kolom Kanan: Foto --}}
                <div class="col-lg-7">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-image me-1"></i>Ganti Foto
                        <small class="text-muted fw-normal">(kosongkan jika tidak ingin mengubah)</small>
                    </label>

                    {{-- Preview foto saat ini --}}
                    <div class="mb-3">
                        @if($data->file)
                            <div class="rounded-3 overflow-hidden shadow-sm d-inline-block position-relative mb-2" style="max-width:100%;">
                                <img src="{{ asset('storage/' . $data->file) }}" id="preview-current"
                                     style="max-height:200px; max-width:100%; object-fit:cover; border-radius:8px; display:block;">
                                <span class="badge bg-success position-absolute top-0 start-0 m-2">
                                    <i class="fas fa-check me-1"></i>Foto Saat Ini
                                </span>
                            </div>
                        @elseif(!empty($data->url_gdrive))
                            <div class="alert alert-info border-0 rounded-3 d-flex align-items-center gap-2 p-3 mb-2">
                                <i class="fab fa-google-drive fa-lg text-primary"></i>
                                <div>
                                    <div class="fw-semibold small">Foto dari Google Drive</div>
                                    <a href="{{ $data->url_gdrive }}" target="_blank" rel="noopener noreferrer"
                                       class="text-decoration-none small">{{ Str::limit($data->url_gdrive, 60) }}</a>
                                </div>
                            </div>
                        @else
                            <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center"
                                 style="height:120px;">
                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-2x mb-1 opacity-50"></i>
                                    <div class="small">Belum ada foto</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Input file baru --}}
                    <div class="border rounded-3 p-3" style="background:#f8faff;">
                        <input type="file" name="file" id="file-new" class="form-control"
                               accept="image/jpeg,image/png,image/webp,image/gif"
                               onchange="previewNewFile(this)">
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            Format: JPG, PNG, WebP, GIF &bull; Maks. 5 MB
                        </small>
                    </div>

                    {{-- Preview foto baru --}}
                    <div id="new-preview-wrap" class="mt-3" style="display:none;">
                        <div class="small text-muted mb-1 fw-semibold">Preview Foto Baru:</div>
                        <img id="new-preview-img" src="" alt=""
                             style="max-height:160px; max-width:100%; object-fit:cover; border-radius:8px;" class="shadow-sm border">
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ url('/admin/galeri') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-times me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary px-5 fw-semibold">
                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewNewFile(input) {
    const file = input.files[0];
    if (!file) return;

    const allowed = ['image/jpeg','image/png','image/webp','image/gif'];
    if (!allowed.includes(file.type)) {
        alert('Format tidak didukung. Gunakan JPG, PNG, WebP, atau GIF.');
        input.value = '';
        return;
    }
    if (file.size > 5 * 1024 * 1024) {
        alert('File terlalu besar (' + (file.size/1024/1024).toFixed(1) + ' MB). Maks. 5 MB.');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        document.getElementById('new-preview-img').src = e.target.result;
        document.getElementById('new-preview-wrap').style.display = 'block';
    };
    reader.readAsDataURL(file);
}
</script>
@endpush

@endsection