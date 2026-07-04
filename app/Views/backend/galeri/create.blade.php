@extends('layouts.admin')
@section('title', 'Upload Foto Galeri')
@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center py-3"
         style="background:linear-gradient(135deg,#003366,#0056b3);border-radius:.5rem .5rem 0 0;">
        <h5 class="mb-0 text-white fw-bold"><i class="fas fa-cloud-upload-alt me-2"></i>Upload Foto ke Galeri</h5>
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

        <form enctype="multipart/form-data" action="{{ url('/admin/galeri') }}" method="POST" id="form-galeri-create">
            {!! csrf_field() !!}

            <div class="row g-4">
                {{-- Kolom Kiri: Pilihan Album & Judul --}}
                <div class="col-lg-5">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Album <span class="text-danger">*</span></label>
                        <select name="album_id" class="form-select" required>
                            <option value="">-- Pilih Album --</option>
                            @foreach($album as $alb)
                            <option value="{{ $alb->id }}">{{ $alb->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Foto <span class="text-danger">*</span>
                            <small class="text-muted fw-normal">(jika multi-upload, nomor otomatis ditambahkan)</small>
                        </label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Kegiatan Upacara"
                               maxlength="200" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan <small class="text-muted fw-normal">(opsional)</small></label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Deskripsi singkat foto..." maxlength="500"></textarea>
                    </div>

                    {{-- URL Google Drive --}}
                    <div class="card border-0 rounded-3 p-3" style="background:#f0f7ff; border-left:4px solid #0056b3 !important;">
                        <label class="form-label fw-semibold mb-1">
                            <i class="fab fa-google-drive text-primary me-1"></i>URL Google Drive
                            <small class="text-muted fw-normal d-block mt-1">Alternatif jika foto disimpan di Google Drive. Isi salah satu: file upload ATAU URL ini.</small>
                        </label>
                        <input type="url" name="url_gdrive" id="url_gdrive" class="form-control"
                               placeholder="https://drive.google.com/file/d/..."
                               pattern="https?://.*">
                        <small class="text-muted mt-1 d-block"><i class="fas fa-shield-alt text-success me-1"></i>Hanya URL dari Google Drive yang diterima.</small>
                    </div>
                </div>

                {{-- Kolom Kanan: Upload Area --}}
                <div class="col-lg-7">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-images me-1"></i>Upload Foto
                        <span class="badge bg-primary ms-1">Maks. 10 Foto</span>
                    </label>

                    {{-- Drop Zone --}}
                    <div id="drop-zone" class="border rounded-3 p-4 text-center mb-3"
                         style="border: 2px dashed #0056b3 !important; background:#f8faff; cursor:pointer; transition:all 0.2s ease; min-height:160px;"
                         onclick="document.getElementById('files-input').click()">
                        <i class="fas fa-cloud-upload-alt fa-3x text-primary opacity-50 mb-2"></i>
                        <p class="fw-semibold text-primary mb-1">Klik atau seret foto ke sini</p>
                        <p class="text-muted small mb-0">JPG, PNG, WebP, GIF &bull; Maks. <strong>5 MB</strong> per foto &bull; Maks. <strong>10 foto</strong></p>
                        <input type="file" name="files[]" id="files-input" multiple accept="image/jpeg,image/png,image/webp,image/gif" class="d-none">
                    </div>

                    {{-- Counter --}}
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small text-muted">Foto dipilih: <strong id="file-count">0</strong> / 10</span>
                        <button type="button" class="btn btn-outline-danger btn-sm" id="btn-clear-files" style="display:none;" onclick="clearFiles()">
                            <i class="fas fa-times me-1"></i>Hapus Semua
                        </button>
                    </div>

                    {{-- Preview Grid --}}
                    <div id="preview-grid" class="row g-2"></div>

                    {{-- Alert validasi --}}
                    <div id="upload-alert" class="alert alert-warning border-0 rounded-3 mt-2" style="display:none;"></div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ url('/admin/galeri') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-times me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary px-5 fw-semibold" id="btn-submit">
                    <i class="fas fa-save me-2"></i>Simpan Foto
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const MAX_FILES = 10;
const MAX_SIZE  = 5 * 1024 * 1024; // 5MB
const ALLOWED   = ['image/jpeg','image/png','image/webp','image/gif'];
let selectedFiles = [];

const dropZone     = document.getElementById('drop-zone');
const filesInput   = document.getElementById('files-input');
const previewGrid  = document.getElementById('preview-grid');
const fileCount    = document.getElementById('file-count');
const btnClear     = document.getElementById('btn-clear-files');
const uploadAlert  = document.getElementById('upload-alert');
const urlGdriveEl  = document.getElementById('url_gdrive');

// Drag & Drop
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.style.background = '#e0edff';
    dropZone.style.borderColor = '#003366';
});
dropZone.addEventListener('dragleave', () => {
    dropZone.style.background = '#f8faff';
    dropZone.style.borderColor = '#0056b3';
});
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.style.background = '#f8faff';
    dropZone.style.borderColor = '#0056b3';
    addFiles(Array.from(e.dataTransfer.files));
});

filesInput.addEventListener('change', () => {
    addFiles(Array.from(filesInput.files));
    filesInput.value = '';
});

function addFiles(files) {
    uploadAlert.style.display = 'none';
    const warnings = [];

    files.forEach(file => {
        if (selectedFiles.length >= MAX_FILES) {
            warnings.push('Maksimal ' + MAX_FILES + ' foto — beberapa file dilewati.');
            return;
        }
        if (!ALLOWED.includes(file.type)) {
            warnings.push(`"${file.name}" bukan format gambar yang didukung.`);
            return;
        }
        if (file.size > MAX_SIZE) {
            warnings.push(`"${file.name}" terlalu besar (${(file.size/1024/1024).toFixed(1)} MB). Maks. 5 MB.`);
            return;
        }
        // Cegah duplikat (nama + ukuran)
        const exists = selectedFiles.find(f => f.name === file.name && f.size === file.size);
        if (!exists) {
            selectedFiles.push(file);
        }
    });

    if (warnings.length) {
        uploadAlert.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>' + [...new Set(warnings)].join('<br>');
        uploadAlert.style.display = 'block';
    }

    renderPreviews();
    syncFilesToInput();
}

function renderPreviews() {
    previewGrid.innerHTML = '';
    fileCount.textContent = selectedFiles.length;
    btnClear.style.display = selectedFiles.length ? 'inline-block' : 'none';

    selectedFiles.forEach((file, idx) => {
        const col = document.createElement('div');
        col.className = 'col-6 col-md-4 col-lg-3';

        const reader = new FileReader();
        reader.onload = (e) => {
            col.innerHTML = `
                <div class="position-relative rounded-3 overflow-hidden shadow-sm" style="height:100px;">
                    <img src="${e.target.result}" class="w-100 h-100" style="object-fit:cover;">
                    <button type="button" onclick="removeFile(${idx})"
                            class="position-absolute top-0 end-0 btn btn-danger btn-sm m-1 p-0 d-flex align-items-center justify-content-center"
                            style="width:24px;height:24px;border-radius:50%;font-size:11px;" title="Hapus">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="position-absolute bottom-0 start-0 end-0 px-1 py-1"
                         style="background:rgba(0,0,0,0.55);font-size:10px;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        ${file.name}
                    </div>
                </div>`;
        };
        reader.readAsDataURL(file);
        previewGrid.appendChild(col);
    });
}

function removeFile(idx) {
    selectedFiles.splice(idx, 1);
    renderPreviews();
    syncFilesToInput();
}

function clearFiles() {
    selectedFiles = [];
    renderPreviews();
    syncFilesToInput();
    uploadAlert.style.display = 'none';
}

function syncFilesToInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    filesInput.files = dt.files;
}

// Form submit validation
document.getElementById('form-galeri-create').addEventListener('submit', function(e) {
    const urlVal = urlGdriveEl.value.trim();
    const hasFiles = selectedFiles.length > 0;
    const hasUrl = urlVal.length > 0;

    if (!hasFiles && !hasUrl) {
        e.preventDefault();
        uploadAlert.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Upload minimal 1 foto ATAU isi URL Google Drive.';
        uploadAlert.style.display = 'block';
        return;
    }
    if (hasUrl && !urlVal.match(/^https?:\/\/(drive|docs|photos|lh3)\.google(usercontent|\.com)/i)) {
        if (!urlVal.match(/^https?:\/\//)) {
            e.preventDefault();
            uploadAlert.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>URL Google Drive tidak valid.';
            uploadAlert.style.display = 'block';
            return;
        }
    }

    const btn = document.getElementById('btn-submit');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupload...';
    btn.disabled = true;
});

// URL GDrive: jika diisi, disable file input area
urlGdriveEl.addEventListener('input', function() {
    if (this.value.trim()) {
        dropZone.style.opacity = '0.4';
        dropZone.style.pointerEvents = 'none';
        document.querySelector('.badge.bg-primary').textContent = 'Gunakan URL GDrive';
    } else {
        dropZone.style.opacity = '1';
        dropZone.style.pointerEvents = 'auto';
        document.querySelector('.badge.bg-primary').textContent = 'Maks. 10 Foto';
    }
});
</script>
@endpush

@endsection