@extends('publik.layout', ['title' => 'Guru dan Tendik', 'header_title' => 'Direktori Guru & Tenaga Kependidikan', 'custom_css' => "
    /* ===== GURU TENDIK PAGE ===== */
    .filter-chip { display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:50px; border:1.5px solid #dee2e6; background:#fff; font-size:.875rem; font-weight:600; color:#555; cursor:pointer; transition:all .2s; text-decoration:none; }
    .filter-chip:hover { border-color:#0056b3; color:#0056b3; background:#f0f6ff; }
    .filter-chip.active { background:#0056b3; border-color:#0056b3; color:#fff; }

    /* Guru Card */
    .guru-card { border-radius:14px; background:#fff; border:1px solid #e8edf3; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,.07); transition:transform .25s, box-shadow .25s; position:relative; text-align:center; }
    .guru-card:hover { transform:translateY(-5px); box-shadow:0 10px 28px rgba(0,0,0,.14); }
    .guru-card-top {
        height:80px;
        background: linear-gradient(135deg, #c8daf7 0%, #dce8fb 60%, #eef4fd 100%);
        border-radius:14px 14px 0 0;
        position:relative;
    }
    .guru-card-top::before {
        content:'';
        position:absolute;
        bottom:-20px;
        left:50%;
        transform:translateX(-50%);
        width:140px;
        height:40px;
        background:inherit;
        border-radius:0 0 70px 70px;
        opacity:.4;
    }
    .badge-aktif { position:absolute; top:10px; right:10px; background:#28a745; color:#fff; font-size:.7rem; font-weight:700; padding:3px 10px; border-radius:12px; z-index:3; }
    .badge-icon-tl { position:absolute; top:10px; left:12px; font-size:.9rem; }
    .guru-avatar { width:80px; height:80px; border-radius:50%; border:3.5px solid #fff; object-fit:cover; margin-top:-40px; position:relative; z-index:2; box-shadow:0 4px 12px rgba(0,0,0,.12); }
    .guru-card-body { padding:8px 16px 18px; }
    .guru-name { font-size:.92rem; font-weight:700; color:#1a1a1a; margin:8px 0 2px; }
    .guru-subtitle { font-size:.78rem; font-weight:700; color:#0056b3; margin-bottom:8px; }
    .guru-jabatan-badge { display:inline-flex; align-items:center; gap:5px; background:#f0f4ff; border-radius:20px; padding:4px 12px; font-size:.75rem; font-weight:600; color:#0056b3; margin-bottom:12px; }
    .guru-jabatan-badge.mapel { background:#f0fff4; color:#198754; }
    .guru-jabatan-badge.pendamping { background:#fff8e1; color:#d4a017; }
    .guru-jabatan-badge.tendik { background:#f3e8fe; color:#6f42c1; }
    .btn-detail { display:inline-flex; align-items:center; gap:6px; background:#0056b3; color:#fff; border-radius:25px; padding:7px 20px; font-size:.82rem; font-weight:600; text-decoration:none; transition:all .2s; }
    .btn-detail:hover { background:#003d8a; color:#fff; }

    /* Apresiasi Banner */
    .apresiasi-banner { background:#eef4ff; border:1px solid #c8daf7; border-radius:14px; padding:20px 24px; display:flex; align-items:center; gap:18px; }
    .apresiasi-icon { width:56px; height:56px; background:#0056b3; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.3rem; flex-shrink:0; }
    .apresiasi-title { font-size:1rem; font-weight:700; color:#0056b3; margin:0 0 4px; }
    .apresiasi-desc { font-size:.82rem; color:#555; margin:0; }
    .apresiasi-line { width:36px; height:3px; background:#ffc107; border-radius:2px; margin-top:6px; }
"])
@section('content')

@php
function guruKategoriClass($kategori) {
    if ($kategori === 'kelas') return '';
    return $kategori ?? '';
}
function guruKategoriIcon($kategori) {
    if ($kategori === 'mapel') return 'fas fa-book-open';
    if ($kategori === 'pendamping') return 'fas fa-hands-helping';
    if ($kategori === 'tendik') return 'fas fa-id-badge';
    return 'fas fa-chalkboard-teacher';
}
function guruCardIcon($idx) {
    $icons = ['fas fa-star','fas fa-chart-line','fas fa-heart','fas fa-gem','fas fa-medal','fas fa-award','fas fa-bolt','fas fa-leaf'];
    $colors = ['#ffc107','#0056b3','#e53935','#8e24aa','#fd7e14','#28a745','#17a2b8','#6f42c1'];
    return ['icon'=>$icons[$idx % count($icons)], 'color'=>$colors[$idx % count($colors)]];
}
@endphp

<!-- Filter Chips -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="#" class="filter-chip active" data-filter="all"><i class="fas fa-users"></i> Semua</a>
    <a href="#" class="filter-chip" data-filter="kelas"><i class="fas fa-chalkboard-teacher"></i> Guru Kelas</a>
    <a href="#" class="filter-chip" data-filter="mapel"><i class="fas fa-book-open"></i> Guru Mapel</a>
    <a href="#" class="filter-chip" data-filter="pendamping"><i class="fas fa-hands-helping"></i> Guru Pendamping</a>
    <a href="#" class="filter-chip" data-filter="tendik"><i class="fas fa-id-badge"></i> Tenaga Kependidikan</a>
</div>

<!-- Guru Grid -->
<div class="row g-3 mb-4">
    @forelse($guru as $idx => $item)
    @php
        $jabatanClass = guruKategoriClass($item->kategori);
        $jabatanIcon  = guruKategoriIcon($item->kategori);
        $cardIcon     = guruCardIcon($idx);
        $initials     = collect(explode(' ', $item->nama))->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->implode('');
    @endphp
    <div class="col-6 col-md-4 col-lg-3 guru-item-col" data-kategori="{{ $item->kategori ?: 'kelas' }}" data-aos="fade-up" data-aos-delay="{{ $idx * 50 }}">
        <div class="guru-card">
            <div class="guru-card-top">
                <span class="badge-aktif">Aktif</span>
                <span class="badge-icon-tl" style="color:{{ $cardIcon['color'] }};"><i class="{{ $cardIcon['icon'] }}"></i></span>
            </div>

            @if($item->foto)
                <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->nama }}" class="guru-avatar mx-auto d-block" loading="lazy">
            @else
                <div class="guru-avatar mx-auto d-flex align-items-center justify-content-center"
                     style="background:#003d8a; color:#fff; font-size:1.3rem; font-weight:800;">{{ $initials }}</div>
            @endif

            <div class="guru-card-body">
                <div class="guru-name">{{ $item->nama }}</div>
                <div class="guru-subtitle">GTK</div>
                <div class="guru-jabatan-badge {{ $jabatanClass }}">
                    <i class="{{ $jabatanIcon }}"></i>
                    {{ $item->jabatan ?: 'Guru Kelas' }}
                </div>
                <br>
                <a href="#" class="btn-detail" data-bs-toggle="modal" data-bs-target="#modalGuru{{ $item->id }}">
                    <i class="fas fa-eye"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>

    <!-- Modal Detail Guru -->
    <div class="modal fade" id="modalGuru{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close bg-light rounded-circle p-2" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 p-md-5 pt-0">
                    <div class="row align-items-center">
                        <div class="col-md-7 mb-4 mb-md-0">
                            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                                @if($item->foto)
                                    <img src="{{ asset('storage/'.$item->foto) }}" alt="Avatar" class="rounded-circle me-3" style="width:70px;height:70px;object-fit:cover;" loading="lazy">
                                @else
                                    <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                         style="width:70px;height:70px;background:#003d8a;color:#fff;font-size:1.2rem;font-weight:800;">{{ $initials }}</div>
                                @endif
                                <div>
                                    <h4 class="fw-bold text-dark mb-1">{{ $item->nama }}</h4>
                                    <p class="text-primary fw-bold mb-0">GTK • Aktif</p>
                                </div>
                            </div>
                            <h6 class="fw-bold text-muted mb-3" style="font-size:.85rem;letter-spacing:1px;">INFORMASI PRIBADI</h6>
                            <div class="row mb-2"><div class="col-5 text-muted small">Jabatan</div><div class="col-7 small fw-bold text-dark">{{ $item->jabatan }}</div></div>
                            @if($item->pendidikan)
                            <div class="row mb-2"><div class="col-5 text-muted small">Pendidikan</div><div class="col-7 small fw-bold text-dark">{{ $item->pendidikan }}</div></div>
                            @endif
                            <div class="row mb-2"><div class="col-5 text-muted small">Status</div><div class="col-7 small fw-bold text-success">Aktif</div></div>
                            @if($item->biodata)
                            <p class="text-muted small mt-3">{{ $item->biodata }}</p>
                            @endif
                        </div>
                        <div class="col-md-5 text-center">
                            <div class="bg-light p-3 rounded-4">
                                @if($item->foto)
                                    <img src="{{ asset('storage/'.$item->foto) }}" class="img-fluid mb-3 rounded-3 border bg-white" style="max-height:200px;object-fit:cover;">
                                @else
                                    <div class="rounded-3 mb-3 d-flex align-items-center justify-content-center mx-auto"
                                         style="width:160px;height:180px;background:#003d8a;color:#fff;font-size:3rem;font-weight:800;">{{ $initials }}</div>
                                @endif
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 w-100">Status: Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5"><p class="text-muted fs-5">Belum ada data Guru & Tendik.</p></div>
    @endforelse
</div>

<!-- Banner Apresiasi -->
<div class="apresiasi-banner">
    <div class="apresiasi-icon"><i class="fas fa-hands-helping"></i></div>
    <div>
        <div class="apresiasi-title">Terima kasih guru & tendik hebat!</div>
        <p class="apresiasi-desc">Dedikasi dan kerja keras Bapak/Ibu adalah kunci keberhasilan pendidikan di SDN Demakijo 1.</p>
        <div class="apresiasi-line"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chips = document.querySelectorAll('.filter-chip');
    const cols = document.querySelectorAll('.guru-item-col');

    chips.forEach(function(chip) {
        chip.addEventListener('click', function(e) {
            e.preventDefault();
            chips.forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');
            cols.forEach(function(col) {
                const cat = col.getAttribute('data-kategori');
                if (filterValue === 'all' || cat === filterValue) {
                    col.style.display = '';
                } else {
                    col.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endsection