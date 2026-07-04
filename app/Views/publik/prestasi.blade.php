@extends('publik.layout', ['title' => 'Prestasi Sekolah', 'header_title' => 'Prestasi Membanggakan', 'custom_css' => "
    /* ===== PRESTASI PAGE ===== */
    .filter-chip { display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:50px; border:1.5px solid #dee2e6; background:#fff; font-size:.875rem; font-weight:600; color:#555; cursor:pointer; transition:all .2s; text-decoration:none; }
    .filter-chip:hover { border-color:#0056b3; color:#0056b3; background:#f0f6ff; }
    .filter-chip.active { background:#0056b3; border-color:#0056b3; color:#fff; }

    /* Prestasi Card */
    .prestasi-card { background:#fff; border-radius:16px; border:1px solid #e8edf3; box-shadow:0 4px 20px rgba(0,0,0,.05); padding:24px; position:relative; overflow:hidden; transition:transform .25s, box-shadow .25s; display:flex; flex-direction:column; height:100%; }
    .prestasi-card:hover { transform:translateY(-4px); box-shadow:0 12px 30px rgba(0,0,0,.1); }

    /* Medali badge (Gold medal with red ribbon) */
    .medal-badge { position:absolute; top:12px; right:16px; width:44px; height:56px; background:url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 50 65\"><polygon points=\"12,28 18,62 26,48 34,62 40,28\" fill=\"%23dc3545\"/><ellipse cx=\"26\" cy=\"22\" rx=\"20\" ry=\"20\" fill=\"%23ffc107\" stroke=\"%23e0a800\" stroke-width=\"2\"/><ellipse cx=\"26\" cy=\"22\" rx=\"15\" ry=\"15\" fill=\"none\" stroke=\"%23fff\" stroke-width=\"1.5\" stroke-dasharray=\"3,2\"/><text x=\"50%%\" y=\"41%%\" text-anchor=\"middle\" dominant-baseline=\"middle\" font-size=\"18\" font-weight=\"bold\" fill=\"white\" font-family=\"sans-serif\">1</text></svg>') center/contain no-repeat; z-index:2; }

    .prestasi-icon-wrap { width:68px; height:68px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; position:relative; }
    .prestasi-icon-wrap.theme-blue { background:#eef2ff; border:2px solid #0056b3; color:#0056b3; }
    .prestasi-icon-wrap.theme-green { background:#e8f8f0; border:2px solid #28a745; color:#28a745; }
    .prestasi-icon-wrap i { font-size:1.75rem; }

    .prestasi-content { flex:1; display:flex; flex-direction:column; }
    .prestasi-title { font-size:1.1rem; font-weight:800; color:#1a1a1a; margin:0 0 6px; line-height:1.3; padding-right:35px; }
    .tingkat-badge { display:inline-block; font-size:.75rem; font-weight:600; padding:3px 12px; border-radius:50px; margin-bottom:10px; width:fit-content; }
    .tingkat-badge.theme-blue { background:#eef2ff; color:#0056b3; }
    .tingkat-badge.theme-green { background:#e8f8f0; color:#28a745; }
    .prestasi-desc { font-size:.84rem; color:#666; line-height:1.6; margin-bottom:16px; }
    .prestasi-footer { margin-top:auto; padding-top:10px; border-top:1px solid #f1f5f9; display:flex; align-items:center; justify-content:between; width:100%; }
    .prestasi-date { font-size:.78rem; color:#888; display:flex; align-items:center; gap:6px; font-weight:500; }
    .btn-detail-link { display:inline-flex; align-items:center; gap:6px; border:1.5px solid #0056b3; border-radius:50px; padding:5px 16px; font-size:.8rem; font-weight:700; color:#0056b3; text-decoration:none; transition:all .2s; background:#fff; }
    .btn-detail-link:hover { border-color:#0056b3; color:#fff; background:#0056b3; }

    /* Pagination */
    .custom-pagination { display:flex; justify-content:center; align-items:center; gap:6px; margin-top:36px; }
    .page-btn { width:36px; height:36px; border-radius:8px; border:1.5px solid #dee2e6; background:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:.85rem; font-weight:600; color:#555; cursor:pointer; text-decoration:none; transition:all .2s; }
    .page-btn:hover { border-color:#0056b3; color:#0056b3; }
    .page-btn.active { background:#0056b3; border-color:#0056b3; color:#fff; }
    .page-btn.arrow { color:#888; }
"])
@section('content')

<!-- Filter Chips + Sort -->
<div class="d-flex flex-wrap align-items-center gap-2 mb-4">
    <a href="?" class="filter-chip {{ empty($kategori) ? 'active' : '' }}"><i class="fas fa-th-large"></i> Semua Prestasi</a>
    <a href="?kategori=Akademik&sort={{ $sort }}" class="filter-chip {{ $kategori === 'Akademik' ? 'active' : '' }}"><i class="fas fa-graduation-cap"></i> Akademik</a>
    <a href="?kategori=Olahraga&sort={{ $sort }}" class="filter-chip {{ $kategori === 'Olahraga' ? 'active' : '' }}"><i class="fas fa-running"></i> Olahraga</a>
    <a href="?kategori=Seni%20%26%20Budaya&sort={{ $sort }}" class="filter-chip {{ $kategori === 'Seni & Budaya' ? 'active' : '' }}"><i class="fas fa-music"></i> Seni & Budaya</a>
    <a href="?kategori=Lainnya&sort={{ $sort }}" class="filter-chip {{ $kategori === 'Lainnya' ? 'active' : '' }}"><i class="fas fa-ellipsis-h"></i> Lainnya</a>
    <div class="ms-auto">
        <select onchange="location.href = '?kategori={{ urlencode($kategori ?? '') }}&sort=' + this.value" style="border:1.5px solid #dee2e6;border-radius:8px;padding:6px 14px;font-size:.82rem;font-weight:600;color:#444;background:#fff;outline:none;">
            <option value="Terbaru" {{ $sort === 'Terbaru' ? 'selected' : '' }}>Terbaru</option>
            <option value="Terlama" {{ $sort === 'Terlama' ? 'selected' : '' }}>Terlama</option>
        </select>
    </div>
</div>

<!-- Prestasi Grid 2-kolom -->
<div class="row g-4">
    @forelse($prestasi as $index => $item)
    @php
        $theme = ($index % 2 == 0) ? 'blue' : 'green';
    @endphp
    <div class="col-md-6" data-aos="fade-up">
        <div class="prestasi-card">
            <!-- Badge medali -->
            <div class="medal-badge" title="Juara"></div>

            <div class="d-flex gap-3 align-items-start h-100">
                <!-- Ikon piala / Foto kecil -->
                @if($item->foto)
                    <div class="position-relative flex-shrink-0" style="width:68px; height:68px;">
                        <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->judul }}" class="rounded-circle border border-2 border-primary" style="width:68px; height:68px; object-fit:cover;" loading="lazy">
                    </div>
                @else
                    <div class="prestasi-icon-wrap theme-{{ $theme }}">
                        <i class="fas fa-trophy"></i>
                    </div>
                @endif

                <!-- Konten -->
                <div class="prestasi-content">
                    <h5 class="prestasi-title">{{ $item->judul }}</h5>
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="tingkat-badge theme-{{ $theme }} mb-0">Tingkat: {{ $item->tingkat }}</span>
                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill" style="font-size: 0.72rem; font-weight: 600;">{{ $item->kategori ?? 'Lainnya' }}</span>
                    </div>
                    @if(!empty($item->deskripsi))
                    <p class="prestasi-desc mb-3">{{ Str::limit($item->deskripsi, 120) }}</p>
                    @endif
                    <div class="prestasi-footer mt-auto">
                        <div class="prestasi-date">
                            <i class="far fa-calendar-alt text-warning fs-6"></i>
                            {{ date('d M Y', strtotime($item->tanggal)) }}
                        </div>
                        <a href="javascript:void(0)" 
                           class="btn-detail-link ms-auto"
                           onclick="showPrestasiDetail('{{ addslashes($item->judul) }}', '{{ $item->kategori ?? 'Lainnya' }}', '{{ $item->tingkat }}', '{{ date('d M Y', strtotime($item->tanggal)) }}', '{{ addslashes($item->deskripsi ?? '') }}', '{{ $item->foto ? asset('storage/'.$item->foto) : '' }}')">
                           Lihat Detail <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="fas fa-trophy fa-3x mb-3 d-block text-muted opacity-50"></i>
        <p class="text-muted fs-5">Belum ada data prestasi untuk kategori ini.</p>
    </div>
    @endforelse
</div>

<!-- Modal Detail Prestasi -->
<div class="modal fade" id="prestasiDetailModal" tabindex="-1" aria-labelledby="prestasiDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0 px-4 pb-4">
                <div class="text-center mb-3">
                    <div class="d-inline-flex p-3 bg-warning-subtle text-warning rounded-circle mb-3">
                        <i class="fas fa-award fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1" id="m-judul">Detail Prestasi</h4>
                    <span class="badge bg-primary px-3 py-2 rounded-pill fw-bold" id="m-kategori">Kategori</span>
                </div>
                
                <div class="mb-4 text-center d-none" id="m-img-container">
                    <img src="" id="m-foto" class="img-fluid rounded-3 shadow-sm border" style="max-height: 250px; object-fit: cover;" loading="lazy">
                </div>

                <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                    <div class="row g-2 text-start">
                        <div class="col-6">
                            <span class="text-muted small d-block">Tingkat Prestasi</span>
                            <strong class="text-dark" id="m-tingkat">-</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-muted small d-block">Tanggal Diperoleh</span>
                            <strong class="text-dark" id="m-tanggal">-</strong>
                        </div>
                    </div>
                </div>

                <div class="text-start">
                    <h6 class="fw-bold text-dark mb-2">Deskripsi Prestasi</h6>
                    <p class="text-muted mb-0" style="font-size:0.9rem; line-height:1.6;" id="m-deskripsi">-</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showPrestasiDetail(judul, kategori, tingkat, tanggal, deskripsi, fotoUrl) {
    document.getElementById('m-judul').innerText = judul;
    document.getElementById('m-kategori').innerText = kategori;
    document.getElementById('m-tingkat').innerText = tingkat;
    document.getElementById('m-tanggal').innerText = tanggal;
    document.getElementById('m-deskripsi').innerText = deskripsi || 'Tidak ada deskripsi tambahan.';
    
    const imgContainer = document.getElementById('m-img-container');
    const foto = document.getElementById('m-foto');
    if (fotoUrl) {
        foto.src = fotoUrl;
        imgContainer.classList.remove('d-none');
    } else {
        foto.src = '';
        imgContainer.classList.add('d-none');
    }
    
    const modal = new bootstrap.Modal(document.getElementById('prestasiDetailModal'));
    modal.show();
}
</script>
@endpush

@endsection