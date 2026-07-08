@extends('layouts.admin')
@section('title', 'Log Aktivitas Sistem')
@section('content')

<div class="card shadow-sm border-0 rounded-4">
    <!-- Header Card -->
    <div class="card-header d-flex justify-content-between align-items-center py-3"
         style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border-radius: .75rem .75rem 0 0;">
        <h5 class="mb-0 text-white fw-bold"><i class="fas fa-history me-2 text-warning"></i>Log Aktivitas Pengguna</h5>
        <span class="badge bg-light text-dark fw-semibold px-3 py-2 rounded-pill shadow-sm">Total: {{ $total }} Log</span>
    </div>

    <!-- Filter & Search Card Body -->
    <div class="card-body bg-light border-bottom">
        <form method="GET" action="{{ url('/admin/log') }}" id="filterForm">
            <div class="row g-3">
                <!-- Search input -->
                <div class="col-md-5">
                    <div class="input-group shadow-sm rounded-3 overflow-hidden">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-1" 
                               placeholder="Cari deskripsi, modul, atau nama user..." value="{{ $search }}">
                        @if($search !== '')
                            <a href="{{ url('/admin/log') }}" class="btn btn-outline-secondary border-start-0" title="Clear Search"><i class="fas fa-times"></i></a>
                        @endif
                    </div>
                </div>

                <!-- Module Filter -->
                <div class="col-md-3">
                    <div class="input-group shadow-sm rounded-3">
                        <span class="input-group-text bg-white text-muted"><i class="fas fa-filter"></i></span>
                        <select name="log_name" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="all" {{ $logName === 'all' ? 'selected' : '' }}>Semua Modul</option>
                            @foreach($logNames as $ln)
                                <option value="{{ $ln }}" {{ $logName === $ln ? 'selected' : '' }}>{{ ucfirst($ln) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Sort Order -->
                <div class="col-md-3">
                    <div class="input-group shadow-sm rounded-3">
                        <span class="input-group-text bg-white text-muted"><i class="fas fa-sort-amount-down"></i></span>
                        <select name="sort" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="description" {{ $sort === 'description' ? 'selected' : '' }}>Abjad Deskripsi</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm rounded-3" title="Terapkan Filter">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Card Body -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="5%" class="ps-4">No</th>
                        <th width="15%">Waktu</th>
                        <th width="15%">Modul</th>
                        <th width="45%">Deskripsi Kegiatan</th>
                        <th width="20%" class="pe-4">Pengguna (Operator)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $idx => $item)
                    @php
                        // Hitung nomor urut baris di semua halaman
                        $rowNum = $idx + 1 + ($page - 1) * $limit;
                        
                        // Badge color mapping
                        $badgeColor = 'bg-secondary';
                        if ($item->log_name === 'auth') $badgeColor = 'bg-purple text-white';
                        elseif ($item->log_name === 'guru') $badgeColor = 'bg-success';
                        elseif ($item->log_name === 'siswa') $badgeColor = 'bg-info text-dark';
                        elseif ($item->log_name === 'system') $badgeColor = 'bg-danger';
                    @endphp
                    <tr>
                        <td class="ps-4 font-weight-bold text-muted">{{ $rowNum }}</td>
                        <td class="small text-dark fw-semibold">
                            <i class="far fa-clock me-1 text-muted"></i>
                            {{ date('d-m-Y H:i:s', strtotime($item->created_at)) }}
                        </td>
                        <td>
                            <span class="badge {{ $badgeColor }} rounded-pill px-3 py-1.5 font-weight-bold text-uppercase" style="font-size: 0.725rem; letter-spacing: 0.5px;">
                                {{ $item->log_name ?: 'Default' }}
                            </span>
                        </td>
                        <td class="text-dark fw-semibold text-wrap">{{ $item->description }}</td>
                        <td class="pe-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center text-dark fw-bold me-2" 
                                     style="width: 32px; height: 32px; font-size: 0.85rem;">
                                    {{ strtoupper(substr($item->causer->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $item->causer->name ?? 'System' }}</div>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $item->causer->email ?? 'system_auto@sdndemakijo1.id' }}</small>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="fas fa-history fa-3x mb-3 text-muted opacity-50"></i>
                            <h5 class="fw-bold text-muted">Tidak Ada Log Ditemukan</h5>
                            <p class="small text-muted mb-0">Coba ubah kata kunci pencarian atau bersihkan filter Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Footer -->
    @if($pages > 1)
    <div class="card-footer bg-white border-top py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
        <div class="small text-muted fw-semibold">
            Menampilkan data {{ ($page - 1) * $limit + 1 }} sampai {{ min($page * $limit, $total) }} dari {{ $total }} entri
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm justify-content-center mb-0">
                <!-- First Page -->
                <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $page > 1 ? '?' . http_build_query(array_merge($_GET, ['page' => 1])) : '#' }}" title="First Page">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                </li>
                
                <!-- Previous Page -->
                <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $page > 1 ? '?' . http_build_query(array_merge($_GET, ['page' => $page - 1])) : '#' }}" title="Previous">
                        <i class="fas fa-angle-left"></i>
                    </a>
                </li>
                
                <!-- Page Range Calculation -->
                @php
                    $start = max(1, $page - 2);
                    $end = min($pages, $page + 2);
                @endphp
                
                @for($i = $start; $i <= $end; $i++)
                <li class="page-item {{ $page == $i ? 'active' : '' }}">
                    <a class="page-link px-3" href="?{{ http_build_query(array_merge($_GET, ['page' => $i])) }}">{{ $i }}</a>
                </li>
                @endfor
                
                <!-- Next Page -->
                <li class="page-item {{ $page >= $pages ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $page < $pages ? '?' . http_build_query(array_merge($_GET, ['page' => $page + 1])) : '#' }}" title="Next">
                        <i class="fas fa-angle-right"></i>
                    </a>
                </li>

                <!-- Last Page -->
                <li class="page-item {{ $page >= $pages ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $page < $pages ? '?' . http_build_query(array_merge($_GET, ['page' => $pages])) : '#' }}" title="Last Page">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    @endif
</div>

@endsection
