@extends('publik.layout', ['title' => 'Agenda Kegiatan', 'header_title' => 'Jadwal Kegiatan Sekolah'])
@section('content')

<!-- Styling Khusus untuk Agenda Modern -->
<style>
    .agenda-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .agenda-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 51, 102, 0.08);
        border-color: rgba(0, 51, 102, 0.1);
    }
    .date-badge {
        width: 90px;
        background: #f4f6f9;
        border-radius: 0.75rem;
        text-align: center;
        padding: 0.75rem;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }
    .date-badge .day-num {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        color: #003366;
    }
    .date-badge .month-yr {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6c757d;
        letter-spacing: 0.5px;
    }
    .date-badge .day-name {
        font-size: 0.75rem;
        font-weight: 600;
        color: #ffc107;
        margin-top: 2px;
    }
    .filter-tab {
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.5rem 1.25rem;
        border: 1px solid rgba(0,0,0,0.06);
        background: #ffffff;
        color: #495057;
        transition: all 0.2s ease;
    }
    .filter-tab:hover {
        background: rgba(0, 51, 102, 0.04);
        color: #003366;
    }
    .filter-tab.active {
        background: #003366 !important;
        color: #ffffff !important;
        border-color: #003366 !important;
        box-shadow: 0 4px 10px rgba(0, 51, 102, 0.15);
    }
    /* Calendar Widget style */
    .mini-calendar {
        background: linear-gradient(135deg, #003366, #0056b3);
        border-radius: 1.25rem;
        color: #ffffff;
        padding: 1.5rem;
        box-shadow: 0 8px 20px rgba(0, 51, 102, 0.15);
    }
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
        text-align: center;
    }
    .calendar-day-name {
        font-size: 0.7rem;
        font-weight: 700;
        opacity: 0.7;
        text-transform: uppercase;
        padding-bottom: 4px;
    }
    .calendar-cell {
        font-size: 0.85rem;
        font-weight: 600;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .calendar-cell:hover:not(.empty) {
        background: rgba(255, 255, 255, 0.2);
    }
    .calendar-cell.active-date {
        background: #ffc107;
        color: #003366;
        font-weight: 800;
        box-shadow: 0 2px 6px rgba(255, 193, 7, 0.4);
    }
    .calendar-cell.today-date {
        border: 2px solid #ffffff;
    }
    /* Category counts sidebar styling */
    .sidebar-category-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        color: #495057;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    .sidebar-category-link:hover {
        background: rgba(0, 51, 102, 0.04);
        color: #003366;
    }
    .sidebar-category-link.active {
        background: rgba(0, 51, 102, 0.08);
        color: #003366;
        border-left: 4px solid #ffc107;
    }
</style>

<div class="row g-4 justify-content-center">
    
    <!-- SIDEBAR KIRI: Calendar & Category link list -->
    <div class="col-lg-4 col-xl-3" data-aos="fade-right">
        <!-- Calendar Widget -->
        @php
            // Deteksi bulan & tahun aktif untuk kalender
            $currentMonthVal = (int)date('m');
            $currentYearVal = (int)date('Y');
            
            $calMonth = isset($_GET['cal_month']) ? (int)$_GET['cal_month'] : null;
            $calYear = isset($_GET['cal_year']) ? (int)$_GET['cal_year'] : null;
            
            if (!$calMonth || !$calYear) {
                // Periksa apakah ada event di bulan berjalan saat ini
                $hasCurrentMonthEvents = false;
                $currentMonthYearStr = date('Y-m');
                foreach ($agenda as $item) {
                    if (strpos($item->tanggal_mulai, $currentMonthYearStr) === 0) {
                        $hasCurrentMonthEvents = true;
                        break;
                    }
                }
                
                if ($hasCurrentMonthEvents) {
                    $calMonth = $currentMonthVal;
                    $calYear = $currentYearVal;
                } else {
                    // Default ke bulan dengan agenda mendatang terdekat
                    $upcomingEvent = null;
                    $todayStr = date('Y-m-d');
                    foreach ($agenda as $item) {
                        if ($item->tanggal_mulai >= $todayStr) {
                            if (!$upcomingEvent || $item->tanggal_mulai < $upcomingEvent->tanggal_mulai) {
                                $upcomingEvent = $item;
                            }
                        }
                    }
                    if ($upcomingEvent) {
                        $calMonth = (int)date('m', strtotime($upcomingEvent->tanggal_mulai));
                        $calYear = (int)date('Y', strtotime($upcomingEvent->tanggal_mulai));
                    } else {
                        $calMonth = $currentMonthVal;
                        $calYear = $currentYearVal;
                    }
                }
            }
            
            $calTime = mktime(0, 0, 0, $calMonth, 1, $calYear);
            $monthName = date('F', $calTime);
            $firstDay = date('w', $calTime);
            $daysInMonth = (int)date('t', $calTime);
            
            $idMonths = [
                'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
            ];
            $displayMonthName = $idMonths[$monthName] ?? $monthName;

            // Navigasi bulan
            $prevMonth = $calMonth - 1;
            $prevYear = $calYear;
            if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }
            
            $nextMonth = $calMonth + 1;
            $nextYear = $calYear;
            if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }

            $todayNum = (int)date('d');
            $todayMonthYear = date('m-Y');
            $calMonthYear = sprintf('%02d-%d', $calMonth, $calYear);
        @endphp

        <div class="mini-calendar mb-4">
            <div class="calendar-header d-flex justify-content-between align-items-center">
                <a href="?cal_month={{ $prevMonth }}&cal_year={{ $prevYear }}" class="text-white text-decoration-none px-2"><i class="fas fa-chevron-left"></i></a>
                <span class="fw-bold"><i class="fas fa-calendar-alt me-2"></i>{{ $displayMonthName }} {{ $calYear }}</span>
                <a href="?cal_month={{ $nextMonth }}&cal_year={{ $nextYear }}" class="text-white text-decoration-none px-2"><i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="calendar-grid">
                @php
                    $days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                @endphp
                @foreach($days as $d)
                    <div class="calendar-day-name">{{ $d }}</div>
                @endforeach
                
                @for($i = 0; $i < $firstDay; $i++)
                    <div class="calendar-cell empty"></div>
                @endfor
                
                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        // Check if this day has any agenda events scheduled
                        $hasEvent = false;
                        foreach($agenda as $item) {
                            $startDay = (int)date('d', strtotime($item->tanggal_mulai));
                            $endDay = (int)date('d', strtotime($item->tanggal_selesai));
                            $eventMonthYear = date('m-Y', strtotime($item->tanggal_mulai));
                            if ($eventMonthYear == $calMonthYear && $day >= $startDay && $day <= $endDay) {
                                $hasEvent = true;
                                break;
                            }
                        }
                    @endphp
                    <div class="calendar-cell {{ ($day == $todayNum && $todayMonthYear == $calMonthYear) ? 'today-date' : '' }} {{ $hasEvent ? 'active-date' : '' }}" 
                         title="{{ $hasEvent ? 'Ada Kegiatan Sekolah' : '' }}">
                        {{ $day }}
                    </div>
                @endfor
            </div>
        </div>

        <!-- Sidebar Category Counts List -->
        <div class="card border-0 rounded-4 shadow-sm p-3">
            <h6 class="fw-bold text-dark mb-3 px-2"><i class="fas fa-tags text-primary me-2"></i>Kategori Kegiatan</h6>
            @php
                $catCounts = [
                    'Semua' => count($agenda),
                    'Akademik' => 0,
                    'Olahraga' => 0,
                    'Seni & Budaya' => 0,
                    'Lomba' => 0,
                    'Lainnya' => 0
                ];
                foreach($agenda as $item) {
                    $c = $item->kategori ?? 'Akademik';
                    if (isset($catCounts[$c])) {
                        $catCounts[$c]++;
                    } else {
                        $catCounts['Lainnya']++;
                    }
                }
            @endphp
            <div class="d-flex flex-column gap-1">
                <a href="#" class="sidebar-category-link active" onclick="filterCategory('Semua', this)">
                    <span class="d-flex align-items-center"><i class="fas fa-list-ul me-3 text-muted"></i>Semua Kegiatan</span>
                    <span class="badge bg-light text-dark border">{{ $catCounts['Semua'] }}</span>
                </a>
                <a href="#" class="sidebar-category-link" onclick="filterCategory('Akademik', this)">
                    <span class="d-flex align-items-center"><i class="fas fa-book me-3 text-primary"></i>Akademik</span>
                    <span class="badge bg-light text-dark border">{{ $catCounts['Akademik'] }}</span>
                </a>
                <a href="#" class="sidebar-category-link" onclick="filterCategory('Olahraga', this)">
                    <span class="d-flex align-items-center"><i class="fas fa-basketball-ball me-3 text-success"></i>Olahraga</span>
                    <span class="badge bg-light text-dark border">{{ $catCounts['Olahraga'] }}</span>
                </a>
                <a href="#" class="sidebar-category-link" onclick="filterCategory('Seni & Budaya', this)">
                    <span class="d-flex align-items-center"><i class="fas fa-music me-3 text-purple" style="color: #6f42c1;"></i>Seni & Budaya</span>
                    <span class="badge bg-light text-dark border">{{ $catCounts['Seni & Budaya'] }}</span>
                </a>
                <a href="#" class="sidebar-category-link" onclick="filterCategory('Lomba', this)">
                    <span class="d-flex align-items-center"><i class="fas fa-trophy me-3 text-warning"></i>Lomba</span>
                    <span class="badge bg-light text-dark border">{{ $catCounts['Lomba'] }}</span>
                </a>
                <a href="#" class="sidebar-category-link" onclick="filterCategory('Lainnya', this)">
                    <span class="d-flex align-items-center"><i class="fas fa-ellipsis-h me-3 text-secondary"></i>Lainnya</span>
                    <span class="badge bg-light text-dark border">{{ $catCounts['Lainnya'] }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- KONTEN UTAMA: Category filter tabs & cards list -->
    <div class="col-lg-8 col-xl-9" data-aos="fade-left">
        <!-- Horizontal Tabs Filter -->
        <div class="d-flex flex-wrap gap-2 mb-4 justify-content-start align-items-center py-1">
            <button class="filter-tab active" onclick="filterCategory('Semua', this)"><i class="fas fa-th-large me-2"></i>Semua Kegiatan</button>
            <button class="filter-tab" onclick="filterCategory('Akademik', this)"><i class="fas fa-book me-2"></i>Akademik</button>
            <button class="filter-tab" onclick="filterCategory('Olahraga', this)"><i class="fas fa-futbol me-2"></i>Olahraga</button>
            <button class="filter-tab" onclick="filterCategory('Seni & Budaya', this)"><i class="fas fa-guitar me-2"></i>Seni & Budaya</button>
            <button class="filter-tab" onclick="filterCategory('Lomba', this)"><i class="fas fa-trophy me-2"></i>Lomba</button>
            <button class="filter-tab" onclick="filterCategory('Lainnya', this)"><i class="fas fa-info-circle me-2"></i>Lainnya</button>
        </div>

        <!-- Agenda List Container -->
        <div class="d-flex flex-column gap-3" id="agenda-list-wrapper">
            @forelse($agenda as $item)
                @php
                    $mKategori = $item->kategori ?? 'Akademik';
                    $colorMap = [
                        'Akademik' => ['badge' => 'bg-primary text-white', 'icon' => 'fa-book-open text-primary'],
                        'Olahraga' => ['badge' => 'bg-success text-white', 'icon' => 'fa-running text-success'],
                        'Seni & Budaya' => ['badge' => 'bg-purple text-white', 'icon' => 'fa-palette text-purple'],
                        'Lomba' => ['badge' => 'bg-warning text-dark', 'icon' => 'fa-trophy text-warning'],
                        'Lainnya' => ['badge' => 'bg-secondary text-white', 'icon' => 'fa-info-circle text-secondary']
                    ];
                    $styles = $colorMap[$mKategori] ?? $colorMap['Lainnya'];
                    
                    // Day, Month translation
                    $indDays = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
                    $engDay = date('l', strtotime($item->tanggal_mulai));
                    $indDay = $indDays[$engDay] ?? $engDay;
                @endphp
                <div class="agenda-card p-3 p-md-4 shadow-sm" data-category="{{ $mKategori }}">
                    <div class="d-flex flex-column flex-md-row align-items-start gap-4">
                        
                        <!-- Date badge block -->
                        <div class="d-flex flex-row flex-md-column align-items-center gap-2 date-badge flex-shrink-0 mx-auto mx-md-0">
                            <span class="day-num">{{ date('d', strtotime($item->tanggal_mulai)) }}</span>
                            <div class="text-center">
                                <div class="month-yr">{{ date('M Y', strtotime($item->tanggal_mulai)) }}</div>
                                <div class="day-name">{{ $indDay }}</div>
                            </div>
                        </div>

                        <!-- Content info details -->
                        <div class="flex-grow-1 text-center text-md-start w-100">
                            <div class="d-flex align-items-center justify-content-center justify-content-md-between mb-2">
                                <span class="badge {{ $styles['badge'] }} px-3 py-1 rounded-pill fw-bold text-uppercase small">{{ $mKategori }}</span>
                                <span class="text-muted small d-none d-md-block"><i class="far fa-bell me-1"></i>Kegiatan Mendatang</span>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">{{ $item->judul }}</h5>
                            
                            <!-- Date & Location details rows -->
                            <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3 mb-3 text-muted small">
                                <span>
                                    <i class="far fa-calendar-alt text-primary me-2"></i>
                                    @if($item->tanggal_mulai == $item->tanggal_selesai)
                                        {{ date('d M Y', strtotime($item->tanggal_mulai)) }}
                                    @else
                                        {{ date('d M Y', strtotime($item->tanggal_mulai)) }} - {{ date('d M Y', strtotime($item->tanggal_selesai)) }}
                                    @endif
                                </span>
                                <span>
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    {{ $item->lokasi ?? 'SDN Demakijo 1' }}
                                </span>
                            </div>

                            <p class="text-secondary mb-0" style="line-height: 1.6;">{{ $item->deskripsi }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 rounded-4 text-center py-5 shadow-sm">
                    <i class="fas fa-inbox fa-3x mb-3 text-muted opacity-50"></i>
                    <h5 class="fw-bold text-dark">Belum ada Agenda</h5>
                    <p class="text-muted mb-0">Daftar agenda baru akan segera diupdate.</p>
                </div>
            @endforelse
        </div>

        <!-- Notification Banner at the bottom -->
        <div class="card border-0 rounded-4 shadow-sm text-white mt-4 p-4 d-flex flex-row align-items-center justify-content-between flex-wrap gap-3" 
             style="background: linear-gradient(135deg, #003366, #0056b3);">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                    <i class="fas fa-bell fa-lg"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Selalu Terhubung dengan Kegiatan Sekolah!</h6>
                    <p class="small text-white-50 mb-0">Pantau terus jadwal kegiatan akademik & non-akademik SDN Demakijo 1.</p>
                </div>
            </div>
            <button class="btn btn-warning fw-bold px-4 rounded-pill shadow-sm" onclick="alert('Anda akan mendapatkan update notifikasi!')">
                Berlangganan Update
            </button>
        </div>
    </div>
</div>

<!-- JS Interaktif untuk Kategori Filter -->
<script>
    function filterCategory(category, element) {
        // Prevent default click
        if(window.event) window.event.preventDefault();

        // Remove active class from all horizontal tabs and sidebar links
        document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.sidebar-category-link').forEach(link => link.classList.remove('active'));

        // Add active class to clicked tabs or links that match the category
        document.querySelectorAll('.filter-tab').forEach(tab => {
            if (tab.innerText.includes(category) || (category === 'Semua' && tab.innerText.includes('Semua'))) {
                tab.classList.add('active');
            }
        });
        document.querySelectorAll('.sidebar-category-link').forEach(link => {
            if (link.innerText.includes(category) || (category === 'Semua' && link.innerText.includes('Semua'))) {
                link.classList.add('active');
            }
        });

        // Filter cards in the wrapper
        const cards = document.querySelectorAll('.agenda-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            if (category === 'Semua' || cardCat === category) {
                card.style.display = 'block';
                card.style.opacity = '0';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.4s ease';
                    card.style.opacity = '1';
                }, 50);
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Toggle Empty state if no cards found
        let emptyState = document.getElementById('empty-agenda-state');
        if (visibleCount === 0) {
            if (!emptyState) {
                emptyState = document.createElement('div');
                emptyState.id = 'empty-agenda-state';
                emptyState.className = 'card border-0 rounded-4 text-center py-5 shadow-sm';
                emptyState.innerHTML = `
                    <i class="fas fa-inbox fa-3x mb-3 text-muted opacity-50"></i>
                    <h5 class="fw-bold text-dark">Tidak ada Kegiatan</h5>
                    <p class="text-muted mb-0">Belum ada kegiatan terdaftar di kategori ${category}.</p>
                `;
                document.getElementById('agenda-list-wrapper').appendChild(emptyState);
            } else {
                emptyState.style.display = 'block';
                emptyState.querySelector('p').innerText = `Belum ada kegiatan terdaftar di kategori ${category}.`;
            }
        } else {
            if (emptyState) {
                emptyState.style.display = 'none';
            }
        }
    }
</script>
@endsection