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
    <a href="#" class="filter-chip active"><i class="fas fa-th-large"></i> Semua Prestasi</a>
    <a href="#" class="filter-chip"><i class="fas fa-graduation-cap"></i> Akademik</a>
    <a href="#" class="filter-chip"><i class="fas fa-running"></i> Olahraga</a>
    <a href="#" class="filter-chip"><i class="fas fa-music"></i> Seni & Budaya</a>
    <a href="#" class="filter-chip"><i class="fas fa-ellipsis-h"></i> Lainnya</a>
    <div class="ms-auto">
        <select style="border:1.5px solid #dee2e6;border-radius:8px;padding:6px 14px;font-size:.82rem;font-weight:600;color:#444;background:#fff;">
            <option>Terbaru</option>
            <option>Terlama</option>
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
            <div class="medal-badge" title="Juara 1"></div>

            <div class="d-flex gap-3 align-items-start h-100">
                <!-- Ikon piala -->
                <div class="prestasi-icon-wrap theme-{{ $theme }}">
                    <i class="fas fa-trophy"></i>
                </div>

                <!-- Konten -->
                <div class="prestasi-content">
                    <h5 class="prestasi-title">{{ $item->judul }}</h5>
                    <div class="tingkat-badge theme-{{ $theme }}">Tingkat: {{ $item->tingkat }}</div>
                    @if(!empty($item->deskripsi))
                    <p class="prestasi-desc">{{ Str::limit($item->deskripsi, 120) }}</p>
                    @endif
                    <div class="prestasi-footer mt-auto">
                        <div class="prestasi-date">
                            <i class="far fa-calendar-alt text-warning fs-6"></i>
                            {{ date('d M Y', strtotime($item->tanggal)) }}
                        </div>
                        <a href="#" class="btn-detail-link ms-auto">Lihat Detail <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="fas fa-trophy fa-3x mb-3 d-block" style="color:#dee2e6;"></i>
        <p class="text-muted fs-5">Belum ada data prestasi.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($prestasi->count() > 0)
<div class="custom-pagination">
    <a href="#" class="page-btn arrow"><i class="fas fa-chevron-left"></i></a>
    <a href="#" class="page-btn active">1</a>
    <a href="#" class="page-btn arrow"><i class="fas fa-chevron-right"></i></a>
</div>
@endif

@endsection