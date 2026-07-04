@extends('publik.layout')

@section('content')

{{-- Hero Carousel Section --}}
<style>
    /* ===== Hero Slider - Boxed Container Layout ===== */
    .hero-boxed-wrapper {
        padding: 20px 0 0;
        background: #f1f5f9;
    }
    .hero-boxed-wrapper .container {
        max-width: 1280px;
    }
    #heroCarousel {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    }

    /* Slide Image - proportional, no cropping */
    .hero-slide-img {
        display: block;
        width: 100%;
        height: auto;
        max-height: 520px;
        object-fit: contain;
        background: #0a1628;
    }

    /* Overlay on top of image for text */
    .hero-slide-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(to bottom, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.55) 100%);
        padding: 20px;
    }
    .hero-slide-text {
        text-align: center;
        color: white;
        width: 100%;
        max-width: 750px;
    }
    .hero-title {
        font-family: 'Fredoka One', cursive;
        font-size: 2.5rem;
        line-height: 1.2;
        text-shadow: 0 2px 8px rgba(0,0,0,0.5);
    }
    .hero-desc {
        text-shadow: 0 1px 4px rgba(0,0,0,0.5);
    }

    /* Custom-image slides: no overlay text (image already has its own design) */
    .hero-slide-custom .hero-slide-overlay {
        background: linear-gradient(to bottom, rgba(0,0,0,0) 55%, rgba(0,0,0,0.4) 100%);
    }
    .hero-slide-custom .hero-slide-text-inner {
        display: none;
    }
    /* Custom slides still show the action buttons at the bottom */
    .hero-slide-custom .hero-btn-group {
        display: flex !important;
    }

    /* Custom Bootstrap carousel controls */
    #heroCarousel .carousel-control-prev,
    #heroCarousel .carousel-control-next {
        width: 44px;
        height: 44px;
        background: rgba(255,222,89,0.85);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 1;
        margin: 0 12px;
        transition: background 0.2s;
    }
    #heroCarousel .carousel-control-prev:hover,
    #heroCarousel .carousel-control-next:hover {
        background: #ffde59;
    }
    #heroCarousel .carousel-control-prev-icon,
    #heroCarousel .carousel-control-next-icon {
        filter: invert(20%) sepia(60%) saturate(500%) hue-rotate(195deg);
        width: 20px;
        height: 20px;
    }
    #heroCarousel .carousel-indicators {
        margin-bottom: 10px;
    }
    #heroCarousel .carousel-indicators button {
        width: 28px;
        height: 4px;
        border-radius: 2px;
        background: rgba(255,255,255,0.5);
        border: none;
        margin: 0 3px;
        transition: all 0.3s;
    }
    #heroCarousel .carousel-indicators .active {
        background: #ffde59;
        width: 40px;
    }

    @media (max-width: 768px) {
        .hero-boxed-wrapper {
            padding: 12px 0 0;
        }
        #heroCarousel {
            border-radius: 10px;
        }
        .hero-slide-img {
            max-height: 240px;
        }
        .hero-title {
            font-size: 1.4rem !important;
        }
        .hero-desc {
            font-size: 0.82rem !important;
        }
        .hero-btn-group .btn {
            font-size: 0.82rem !important;
            padding: 7px 16px !important;
        }
        #heroCarousel .carousel-control-prev,
        #heroCarousel .carousel-control-next {
            width: 32px;
            height: 32px;
            margin: 0 4px;
        }
    }
</style>
{{-- Boxed hero slider wrapper --}}
<div class="hero-boxed-wrapper">
    <div class="container">
        @php
            $sliders = [];
            $isCustomSlider = false;
            for ($i = 1; $i <= 5; $i++) {
                $f = 'slider_'.$i;
                if ($setting && $setting->$f) {
                    $sliders[] = asset('storage/'.$setting->$f);
                    $isCustomSlider = true;
                }
            }
            if (empty($sliders)) {
                $sliders = [
                    'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1920&q=80',
                    'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=1920&q=80',
                    'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1920&q=80',
                ];
            }
            $slideTitles = [
                'Selamat Datang di SDN Demakijo 1',
                'Fasilitas Belajar Modern',
                'Pendidik Profesional',
                'Prestasi Gemilang',
                'Lingkungan Belajar Nyaman',
            ];
            $slideDescs = [
                'Mencetak generasi unggul yang beriman, kreatif, berprestasi, berkarakter, dan berbudaya.',
                'Ruang kelas nyaman dan teknologi pendukung yang lengkap untuk pembelajaran optimal.',
                'Dibimbing oleh tenaga pendidik bersertifikasi dan berkompeten di bidangnya.',
                'Mendukung siswa meraih prestasi di bidang akademik maupun non-akademik.',
                'Suasana sekolah yang kondusif, aman, dan menyenangkan untuk semua siswa.',
            ];
        @endphp

        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

            {{-- Slide indicators --}}
            <div class="carousel-indicators">
                @foreach($sliders as $idx => $s)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $idx }}"
                    class="{{ $idx == 0 ? 'active' : '' }}" aria-label="Slide {{ $idx+1 }}"></button>
                @endforeach
            </div>

            {{-- Slides --}}
            <div class="carousel-inner">
                @foreach($sliders as $idx => $slide)
                <div class="carousel-item {{ $idx == 0 ? 'active' : '' }}">
                    <div class="position-relative">
                        {{-- Gambar: proporsional, tidak dipotong --}}
                        <img src="{{ $slide }}" alt="Slide {{ $idx+1 }}" class="hero-slide-img">

                        {{-- Overlay layer --}}
                        <div class="hero-slide-overlay {{ $isCustomSlider ? 'hero-slide-custom' : '' }}">
                            <div class="hero-slide-text" data-aos="zoom-in">

                                {{-- Teks bawaan sistem — disembunyikan jika gambar kustom --}}
                                <div class="hero-slide-text-inner">
                                    @if($idx == 0)
                                        <span class="badge bg-warning text-dark px-3 py-2 mb-3 rounded-pill fw-bold"
                                              style="letter-spacing:1px; font-size:0.75rem;">BERANDA SEKOLAH</span>
                                    @endif
                                    <h1 class="fw-bold mb-3 text-white hero-title">
                                        {{ $slideTitles[$idx] ?? 'SDN Demakijo 1' }}
                                    </h1>
                                    <p class="lead mb-4 mx-auto hero-desc" style="max-width:700px;">
                                        {{ $slideDescs[$idx] ?? '' }}
                                    </p>
                                </div>

                                {{-- Tombol aksi — selalu tampil --}}
                                <div class="d-flex justify-content-center gap-3 hero-btn-group flex-wrap">
                                    <a href="/profil" class="btn btn-warning btn-lg px-4 fw-bold shadow-sm rounded-pill text-primary">Profil Kami</a>
                                    <a href="/ppdb-online" class="btn btn-outline-light btn-lg px-4 fw-bold shadow-sm rounded-pill">Daftar PPDB</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Tombol Navigasi --}}
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>

{{-- Kenapa Memilih Kami Section --}}
<section class="py-5" style="background-color: var(--primary); color: white;">
    <div class="container py-5">
        <h2 class="text-center fw-bold mb-5" style="font-family:'Fredoka One',cursive;">Kenapa Memilih Kami?</h2>
        <div class="row g-4">
            @forelse($keunggulan as $idx => $item)
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ ($idx+1) * 100 }}">
                <i class="{{ $item->icon }} fs-2 mb-3 text-warning"></i>
                <h5 class="fw-bold mb-3">{{ $item->judul }}</h5>
                <p class="small" style="opacity:0.9;">{{ $item->deskripsi }}</p>
            </div>
            @empty
            {{-- Default items jika DB kosong --}}
            @php $defaults = [
                ['fas fa-map-marker-alt','Lokasi Strategis','Letak sekolah sangat strategis di pusat lingkungan Yogyakarta dekat pusat kegiatan sosial, pemerintahan dan ekonomi.'],
                ['fas fa-star','Kegiatan Ekskul Variatif','Kegiatan Ekskul yang mampu mendukung psikologi, kemandirian, kreatifitas, prestasi dan inovasi anak.'],
                ['fas fa-building','Fasilitas Lengkap','Gedung permanen representatif, setiap kelas dilengkapi AC, LCD, dan fasilitas belajar yang memadai.'],
                ['fas fa-user-tie','Pendidik Profesional','Guru sarjana bersertifikasi pendidik dan didukung tenaga kependidikan yang berkompeten.'],
                ['fas fa-briefcase','Kerjasama Program','Memiliki kerjasama dengan pihak ketiga untuk mendukung kesiapan anak menghadapi masa depan.'],
                ['fas fa-heart','Pendidikan Karakter','Pembiasaan pengamalan karakter dan nilai moral secara istiqomah dan konsisten.'],
                ['fas fa-book-open','Pembelajaran Dinamis','Sistem pengajaran dinamis sesuai perkembangan dan kebutuhan anak.'],
                ['fas fa-smile','Pendampingan Psikolog','Anak didampingi pendidik yang membantu setiap permasalahan tumbuh kembang peserta didik.'],
            ]; @endphp
            @foreach($defaults as $di => $d)
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ ($di+1)*100 }}">
                <i class="{{ $d[0] }} fs-2 mb-3 text-warning"></i>
                <h5 class="fw-bold mb-3">{{ $d[1] }}</h5>
                <p class="small" style="opacity:0.9;">{{ $d[2] }}</p>
            </div>
            @endforeach
            @endforelse
        </div>
    </div>
</section>

{{-- Sambutan Kepala Sekolah --}}
<section class="py-5" style="background-color:#fff; margin-top:-50px; position:relative; z-index:10;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-4 bg-primary text-center p-4 d-flex flex-column justify-content-center align-items-center">
                            @if($setting && $setting->foto_kepsek)
                                <img src="{{ asset('storage/'.$setting->foto_kepsek) }}"
                                     alt="{{ $setting->nama_kepsek ?? 'Kepala Sekolah' }}"
                                     class="img-fluid rounded-circle border border-4 border-warning shadow mb-3"
                                     style="width:150px; height:150px; object-fit:cover;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($setting->nama_kepsek ?? 'Kepala Sekolah') }}&background=ffde59&color=004aad&size=200"
                                     alt="Kepala Sekolah" class="img-fluid rounded-circle border border-4 border-warning shadow mb-3" style="width:150px;">
                            @endif
                            <h5 class="text-white fw-bold mb-0">{{ $setting->nama_kepsek ?? 'Kepala Sekolah' }}</h5>
                            <small class="text-warning">Kepala Sekolah</small>
                            @if($setting && $setting->nip_kepsek)
                            <small class="text-white-50 mt-1">NIP: {{ substr($setting->nip_kepsek,0,4).str_repeat('*',max(0,strlen($setting->nip_kepsek)-4)) }}</small>
                            @endif
                        </div>
                        <div class="col-md-8 p-4 p-lg-5 bg-white">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-quote-left text-warning fs-1 me-3 opacity-50"></i>
                                <h3 class="fw-bold text-primary mb-0">Sambutan Kepala Sekolah</h3>
                            </div>
                            <p class="text-muted" style="line-height:1.8;">
                                @if($setting && $setting->sambutan_kepsek_singkat)
                                    {{ $setting->sambutan_kepsek_singkat }}
                                @else
                                    Assalamu'alaikum Wr. Wb.<br>
                                    Selamat datang di website resmi SDN Demakijo 1. Di era digital ini, kami berkomitmen untuk menyediakan sarana informasi yang cepat dan transparan bagi seluruh civitas akademika dan masyarakat luas.
                                @endif
                            </p>
                            <a href="/profil" class="btn btn-outline-primary mt-2 fw-bold">Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Statistik --}}
<section class="py-5" style="background-color:var(--light);">
    <div class="container py-4">
        <div class="row g-4 text-center justify-content-center">
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="p-4 bg-white rounded-4 shadow-sm border-bottom border-primary border-4 h-100">
                    <i class="fas fa-user-graduate text-primary fs-1 mb-3"></i>
                    <h2 class="fw-bold text-dark display-5 mb-1">{{ $siswaCount > 0 ? $siswaCount.'+' : '350+' }}</h2>
                    <p class="text-muted fw-bold mb-0">Siswa Aktif</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="p-4 bg-white rounded-4 shadow-sm border-bottom border-warning border-4 h-100">
                    <i class="fas fa-chalkboard-teacher text-warning fs-1 mb-3"></i>
                    <h2 class="fw-bold text-dark display-5 mb-1">{{ $guruCount > 0 ? $guruCount : '24' }}</h2>
                    <p class="text-muted fw-bold mb-0">Guru & Tendik</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="p-4 bg-white rounded-4 shadow-sm border-bottom border-success border-4 h-100">
                    <i class="fas fa-award text-success fs-1 mb-3"></i>
                    <h2 class="fw-bold text-dark display-5 mb-1">{{ $akreditasi }}</h2>
                    <p class="text-muted fw-bold mb-0">Akreditasi</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="400">
                <div class="p-4 bg-white rounded-4 shadow-sm border-bottom border-danger border-4 h-100">
                    <i class="fas fa-users text-danger fs-1 mb-3"></i>
                    <h2 class="fw-bold text-dark display-5 mb-1">{{ $alumniCount > 0 ? $alumniCount.'+' : '1500+' }}</h2>
                    <p class="text-muted fw-bold mb-0">Alumni</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Artikel & Berita --}}
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-end mb-5 border-bottom pb-3">
            <div>
                <h2 class="fw-bold text-primary mb-2" style="font-family:'Fredoka One',cursive;">Berita & Artikel Terkini</h2>
                <p class="text-muted mb-0">Ikuti update terbaru seputar kegiatan dan informasi sekolah kami.</p>
            </div>
            <a href="/berita" class="btn btn-primary rounded-pill fw-bold px-4 d-none d-md-inline-block">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            @forelse($latest_berita as $item)
            <div class="col-md-4" data-aos="fade-up">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                    @if($item->gambar)
                        <img src="{{ asset('storage/'.$item->gambar) }}" class="card-img-top" alt="{{ $item->judul }}" style="height:200px; object-fit:cover;">
                    @else
                        <img src="https://images.unsplash.com/photo-1546410531-bea5aad1028f?auto=format&fit=crop&w=500&q=60" class="card-img-top" alt="News" style="height:200px; object-fit:cover;">
                    @endif
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold">
                                {{ $item->kategori->nama ?? 'Berita' }}
                            </span>
                            <small class="text-muted fw-bold"><i class="far fa-calendar-alt text-warning"></i> {{ $item->created_at->format('d M Y') }}</small>
                        </div>
                        <h5 class="card-title fw-bold mb-3 lh-base">
                            <a href="/berita/{{ $item->slug }}" class="text-dark text-decoration-none">{{ $item->judul }}</a>
                        </h5>
                        <p class="card-text text-muted mb-4">{{ \Illuminate\Support\Str::limit(strip_tags($item->konten), 100) }}</p>
                        <a href="/berita/{{ $item->slug }}" class="text-primary fw-bold text-decoration-none mt-auto">Selengkapnya <i class="fas fa-angle-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-newspaper fa-3x text-muted mb-3 d-block opacity-25"></i>
                <p class="text-muted fs-5">Belum ada berita terbaru. Tambahkan dari <a href="/login">panel admin</a>.</p>
            </div>
            @endforelse
        </div>
        <div class="text-center mt-4 d-md-none">
            <a href="/berita" class="btn btn-primary rounded-pill fw-bold px-4">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>
</section>

{{-- Video Profile --}}
<section class="py-5" style="background-color: var(--primary);">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0 text-white" data-aos="fade-right">
                <h2 class="fw-bold mb-4" style="font-family:'Fredoka One',cursive;">Mengenal Lebih Dekat SDN Demakijo 1</h2>
                <p class="lead mb-4 opacity-75">Tonton video profil sekolah kami untuk melihat fasilitas, program unggulan, dan keseharian siswa-siswi berprestasi kami.</p>
                @if($setting && $setting->youtube)
                <a href="{{ $setting->youtube }}" target="_blank" class="btn btn-warning btn-lg rounded-pill fw-bold text-primary shadow">
                    <i class="fab fa-youtube text-danger me-2 fs-4 align-middle"></i> Buka di YouTube
                </a>
                @endif
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg border border-5 border-white">
                    @php $ytId = $setting->youtube_embed ?? 'dQw4w9WgXcQ'; @endphp
                    <iframe src="https://www.youtube.com/embed/{{ $ytId }}?rel=0" title="Video Profil SDN Demakijo 1" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
