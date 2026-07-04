@extends('publik.layout', ['title' => 'Pusat Unduhan', 'header_title' => 'Dokumen & Unduhan Publik', 'custom_css' => "
    /* ===== UNDUHAN PAGE ===== */
    .search-filter-bar { display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:18px; }
    .search-input-wrap { position:relative; flex:1; min-width:200px; }
    .search-input-wrap i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#aaa; font-size:.9rem; }
    .search-input { width:100%; padding:9px 12px 9px 36px; border:1.5px solid #dee2e6; border-radius:10px; font-size:.88rem; color:#333; background:#fff; transition:border-color .2s; }
    .search-input:focus { outline:none; border-color:#0056b3; box-shadow:0 0 0 3px rgba(0,86,179,.08); }
    .filter-select { border:1.5px solid #dee2e6; border-radius:10px; padding:9px 14px; font-size:.85rem; font-weight:600; color:#444; background:#fff; cursor:pointer; }
    .view-toggle { width:36px; height:36px; border-radius:8px; border:1.5px solid #dee2e6; background:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:.85rem; color:#666; cursor:pointer; transition:all .2s; }
    .view-toggle.active { background:#0056b3; border-color:#0056b3; color:#fff; }

    /* Tab kategori */
    .tab-cats { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:20px; }
    .tab-cat { display:inline-flex; align-items:center; gap:5px; padding:6px 14px; border-radius:8px; border:1.5px solid #dee2e6; background:#fff; font-size:.82rem; font-weight:600; color:#555; cursor:pointer; text-decoration:none; transition:all .2s; }
    .tab-cat:hover { border-color:#0056b3; color:#0056b3; }
    .tab-cat.active { background:#0056b3; border-color:#0056b3; color:#fff; }
    .tab-cat .cnt { background:rgba(255,255,255,.25); border-radius:20px; padding:1px 7px; font-size:.72rem; margin-left:2px; }
    .tab-cat:not(.active) .cnt { background:#f0f2f5; color:#666; }

    /* Tabel dokumen */
    .doc-table { width:100%; border-collapse:collapse; }
    .doc-table thead th { font-size:.78rem; font-weight:700; color:#888; text-transform:uppercase; letter-spacing:.5px; padding:10px 14px; border-bottom:2px solid #f0f2f5; background:#fff; }
    .doc-table tbody tr { border-bottom:1px solid #f5f6f8; transition:background .15s; }
    .doc-table tbody tr:hover { background:#f8f9fc; }
    .doc-table tbody td { padding:13px 14px; font-size:.88rem; color:#333; vertical-align:middle; }
    .doc-no { color:#aaa; font-weight:600; font-size:.82rem; }
    .doc-icon { width:34px; height:34px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:.85rem; font-weight:700; flex-shrink:0; }
    .doc-icon.pdf { background:#fde8e8; color:#dc3545; }
    .doc-icon.xls { background:#e8f9e8; color:#198754; }
    .doc-icon.doc { background:#e8f0fe; color:#0056b3; }
    .doc-icon.other { background:#f3e8fe; color:#6f42c1; }
    .doc-title { font-weight:700; color:#1a1a1a; font-size:.88rem; margin:0 0 2px; }
    .doc-sub { font-size:.75rem; color:#aaa; }
    .badge-kat { display:inline-block; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:600; }
    .badge-kat.pengumuman { background:#e8f0fe; color:#0056b3; }
    .badge-kat.akademik { background:#e8f9e8; color:#198754; }
    .badge-kat.formulir { background:#fff4e6; color:#fd7e14; }
    .badge-kat.lainnya { background:#f3e8fe; color:#6f42c1; }
    .badge-kat.jadwal { background:#e8f9f9; color:#17a2b8; }
    .btn-unduh { display:inline-flex; align-items:center; gap:6px; border:1.5px solid #dee2e6; border-radius:8px; padding:6px 14px; font-size:.8rem; font-weight:600; color:#333; text-decoration:none; transition:all .2s; background:#fff; }
    .btn-unduh:hover { background:#0056b3; border-color:#0056b3; color:#fff; }
    .load-more-btn { display:flex; align-items:center; gap:8px; justify-content:center; padding:10px 28px; border-radius:30px; border:1.5px solid #dee2e6; background:#fff; color:#555; font-size:.88rem; font-weight:600; cursor:pointer; transition:all .2s; margin-top:16px; }
    .load-more-btn:hover { border-color:#0056b3; color:#0056b3; }

    /* Sidebar kanan */
    .sidebar-card { background:#fff; border-radius:14px; border:1px solid #e8edf3; box-shadow:0 2px 10px rgba(0,0,0,.06); padding:20px; margin-bottom:18px; }
    .sidebar-illus { text-align:center; margin-bottom:14px; }
    .sidebar-illus i { font-size:3.5rem; color:#0056b3; opacity:.75; }
    .sidebar-title { font-size:.95rem; font-weight:700; color:#1a1a1a; margin-bottom:6px; }
    .sidebar-desc { font-size:.8rem; color:#777; line-height:1.6; margin-bottom:14px; }
    .btn-hubungi { display:block; text-align:center; background:#0056b3; color:#fff; border-radius:10px; padding:10px; font-size:.85rem; font-weight:600; text-decoration:none; transition:background .2s; }
    .btn-hubungi:hover { background:#003d8a; color:#fff; }
    .info-card { background:#fff8e1; border:1px solid #ffeaa0; border-radius:12px; padding:14px 16px; display:flex; align-items:flex-start; gap:10px; }
    .info-card i { color:#ffc107; font-size:1.1rem; margin-top:2px; flex-shrink:0; }
    .info-card p { font-size:.78rem; color:#666; margin:0; line-height:1.6; }

    /* Scroll-to-top */
    .scroll-top { position:fixed; bottom:24px; right:24px; width:42px; height:42px; background:#0056b3; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1rem; text-decoration:none; box-shadow:0 4px 15px rgba(0,86,179,.35); z-index:99; transition:all .2s; }
    .scroll-top:hover { background:#003d8a; color:#fff; transform:translateY(-3px); }
"])
@section('content')

@php
function docIconClass($judul, $file = null) {
    $ext = $file ? strtolower(pathinfo($file, PATHINFO_EXTENSION)) : '';
    if ($ext === 'pdf' || str_contains(strtolower($judul ?? ''), 'pdf')) return 'pdf';
    if (in_array($ext, ['xls','xlsx'])) return 'xls';
    if (in_array($ext, ['doc','docx'])) return 'doc';
    return 'other';
}
function docIconLabel($cls) {
    return match($cls) { 'pdf'=>'PDF', 'xls'=>'XLS', 'doc'=>'DOC', default=>'FILE' };
}
function docKategori($judul) {
    $j = strtolower($judul ?? '');
    if (str_contains($j,'pengumuman') || str_contains($j,'libur') || str_contains($j,'kelulusan')) return ['label'=>'Pengumuman','class'=>'pengumuman'];
    if (str_contains($j,'formulir') || str_contains($j,'pendaftaran')) return ['label'=>'Formulir','class'=>'formulir'];
    if (str_contains($j,'jadwal') || str_contains($j,'kalender')) return ['label'=>'Jadwal','class'=>'jadwal'];
    if (str_contains($j,'akademik') || str_contains($j,'panduan') || str_contains($j,'kurikulum')) return ['label'=>'Akademik','class'=>'akademik'];
    return ['label'=>'Lainnya','class'=>'lainnya'];
}
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Search + Filter Bar -->
        <div class="search-filter-bar">
            <div class="search-input-wrap">
                <i class="fas fa-search"></i>
                <input type="text" class="search-input" placeholder="Cari dokumen..." id="docSearch">
            </div>
            <select class="filter-select" id="catSelect">
                <option value="all">Semua Kategori</option>
                <option value="pengumuman">Pengumuman</option>
                <option value="akademik">Akademik</option>
                <option value="formulir">Formulir</option>
                <option value="lainnya">Lainnya</option>
            </select>
            <select class="filter-select" id="sortSelect">
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
            </select>
            <button class="view-toggle active" title="List"><i class="fas fa-list"></i></button>
            <button class="view-toggle" title="Grid"><i class="fas fa-th-large"></i></button>
        </div>

        <!-- Tab Kategori dengan count -->
        @php
            $total = $unduhan->count();
            $countPengumuman = $unduhan->filter(fn($u) => docKategori($u->judul)['class'] === 'pengumuman')->count();
            $countAkademik   = $unduhan->filter(fn($u) => docKategori($u->judul)['class'] === 'akademik')->count();
            $countFormulir   = $unduhan->filter(fn($u) => docKategori($u->judul)['class'] === 'formulir')->count();
            $countLainnya    = $unduhan->filter(fn($u) => !in_array(docKategori($u->judul)['class'], ['pengumuman','akademik','formulir']))->count();
        @endphp
        <div class="tab-cats">
            <a href="javascript:void(0)" class="tab-cat active" data-filter="all"><i class="fas fa-th-large"></i> Semua <span class="cnt">{{ $total }}</span></a>
            <a href="javascript:void(0)" class="tab-cat" data-filter="pengumuman"><i class="fas fa-bullhorn"></i> Pengumuman <span class="cnt">{{ $countPengumuman }}</span></a>
            <a href="javascript:void(0)" class="tab-cat" data-filter="akademik"><i class="fas fa-graduation-cap"></i> Akademik <span class="cnt">{{ $countAkademik }}</span></a>
            <a href="javascript:void(0)" class="tab-cat" data-filter="formulir"><i class="fas fa-file-alt"></i> Formulir <span class="cnt">{{ $countFormulir }}</span></a>
            <a href="javascript:void(0)" class="tab-cat" data-filter="lainnya"><i class="fas fa-ellipsis-h"></i> Lainnya <span class="cnt">{{ $countLainnya }}</span></a>
        </div>

        <!-- Tabel Dokumen -->
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <table class="doc-table" id="docTable">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Judul Dokumen</th>
                        <th width="130">Kategori</th>
                        <th width="150">Tanggal Publikasi</th>
                        <th width="110" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unduhan as $item)
                    @php
                        $cls   = docIconClass($item->judul, $item->file_lampiran ?? '');
                        $label = docIconLabel($cls);
                        $kat   = docKategori($item->judul);
                        $tgl   = $item->created_at ? $item->created_at->format('d M Y') : '-';
                        $fileUrl = $item->file_lampiran ? asset('storage/'.$item->file_lampiran) : '#';
                    @endphp
                    <tr class="doc-row" data-category="{{ $kat['class'] }}" data-timestamp="{{ $item->created_at ? $item->created_at->timestamp : 0 }}">
                        <td class="doc-no">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="doc-icon {{ $cls }}">{{ $label }}</div>
                                <div>
                                    <div class="doc-title">{{ $item->judul }}</div>
                                    <div class="doc-sub">{{ ucfirst($kat['label']) }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge-kat {{ $kat['class'] }}">{{ $kat['label'] }}</span></td>
                        <td><i class="far fa-calendar-alt me-1 text-muted"></i>{{ $tgl }}</td>
                        <td class="text-center">
                            <a href="{{ $fileUrl }}" class="btn-unduh" {{ $item->file_lampiran ? 'download' : '' }}>
                                <i class="fas fa-download"></i> Unduh
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x d-block mb-3" style="opacity:.2;"></i>
                            Belum ada dokumen yang tersedia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($unduhan->count() > 5)
        <div class="text-center">
            <button class="load-more-btn"><i class="fas fa-chevron-down"></i> Muat Lebih Banyak <i class="fas fa-chevron-down"></i></button>
        </div>
        @endif
    </div>

    <!-- SIDEBAR KANAN -->
    <div class="col-lg-4">
        <!-- Ilustrasi + Hubungi -->
        <div class="sidebar-card">
            <div class="sidebar-illus">
                <i class="fas fa-folder-open fa-4x" style="color:#0056b3; opacity:.65;"></i>
            </div>
            <div class="sidebar-title">Butuh dokumen lain?</div>
            <p class="sidebar-desc">Jika dokumen yang Anda cari tidak tersedia, silakan hubungi pihak sekolah.</p>
            <a href="https://wa.me/6282134567890" class="btn-hubungi" target="_blank">
                <i class="fas fa-comments me-1"></i> Hubungi Sekolah
            </a>
        </div>

        <!-- Info Card Kuning -->
        <div class="info-card">
            <i class="fas fa-lightbulb"></i>
            <p><strong>Informasi</strong><br>Semua dokumen di halaman ini dapat diunduh secara gratis dan digunakan sebagaimana mestinya.</p>
        </div>
    </div>
</div>

<!-- Scroll to top -->
<a href="#" class="scroll-top" id="scrollTopBtn" style="display:none;" title="Kembali ke atas">
    <i class="fas fa-arrow-up"></i>
</a>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const docSearch = document.getElementById('docSearch');
    const catSelect = document.getElementById('catSelect');
    const sortSelect = document.getElementById('sortSelect');
    const tabCats = document.querySelectorAll('.tab-cat');
    const tableBody = document.querySelector('#docTable tbody');
    const originalRows = Array.from(tableBody.querySelectorAll('.doc-row'));
    const emptyRow = tableBody.querySelector('tr:not(.doc-row)');

    let currentCategory = 'all';

    function filterAndSortDocs() {
        const searchQuery = docSearch.value.toLowerCase();
        
        // Filter rows
        let visibleCount = 0;
        originalRows.forEach(row => {
            const cat = row.getAttribute('data-category');
            const matchesCategory = (currentCategory === 'all' || cat === currentCategory || 
                                     (currentCategory === 'lainnya' && !['pengumuman', 'akademik', 'formulir'].includes(cat)));
            
            const text = row.textContent.toLowerCase();
            const matchesSearch = text.includes(searchQuery);

            if (matchesCategory && matchesSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Toggle empty message row
        if (emptyRow) {
            if (visibleCount === 0) {
                emptyRow.style.display = '';
            } else {
                emptyRow.style.display = 'none';
            }
        }

        // Sort rows
        const sortedRows = originalRows.filter(row => row.style.display !== 'none');
        const isNewest = sortSelect.value === 'newest';
        sortedRows.sort((a, b) => {
            const timeA = parseInt(a.getAttribute('data-timestamp') || 0);
            const timeB = parseInt(b.getAttribute('data-timestamp') || 0);
            return isNewest ? (timeB - timeA) : (timeA - timeB);
        });

        // Re-append sorted rows in correct order and update visible indexes
        let visibleIdx = 1;
        sortedRows.forEach(row => {
            tableBody.appendChild(row);
            const noCell = row.querySelector('.doc-no');
            if (noCell) {
                noCell.textContent = String(visibleIdx++).padStart(2, '0');
            }
        });

        // Append hidden rows back to the end of the tbody
        originalRows.forEach(row => {
            if (row.style.display === 'none') {
                tableBody.appendChild(row);
            }
        });
    }

    // Tab-cats click events
    tabCats.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            tabCats.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            currentCategory = this.getAttribute('data-filter') || 'all';
            catSelect.value = currentCategory;
            
            filterAndSortDocs();
        });
    });

    // Category select event
    catSelect.addEventListener('change', function() {
        currentCategory = this.value;
        
        tabCats.forEach(tab => {
            if ((tab.getAttribute('data-filter') || 'all') === currentCategory) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
            }
        });

        filterAndSortDocs();
    });

    // Search and Sort events
    docSearch.addEventListener('input', filterAndSortDocs);
    sortSelect.addEventListener('change', filterAndSortDocs);

    // Scroll to top
    window.addEventListener('scroll', function() {
        const btn = document.getElementById('scrollTopBtn');
        if (btn) btn.style.display = window.scrollY > 300 ? 'flex' : 'none';
    });
    document.getElementById('scrollTopBtn').addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
</script>
@endsection