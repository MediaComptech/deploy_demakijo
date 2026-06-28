@extends('publik.layout', ['title' => 'Profil Sekolah', 'header_title' => 'Profil Sekolah'])
@section('content')
<div class='card shadow-sm border-0 rounded-4 overflow-hidden'>
    <div class='card-body p-0'>
        <!-- Header Profil -->
        <div class='bg-primary text-white p-5 text-center position-relative' style='background: linear-gradient(135deg, var(--primary) 0%, #004488 100%);'>
            <h2 class='fw-bold' style='font-family: "Fredoka One", cursive;'>Profil Sekolah</h2>
            <p class='mb-0'>Mengenal lebih dekat SDN Demakijo 1</p>
        </div>
        
        <!-- Sambutan Kepala Sekolah -->
        <div class='p-4 p-md-5'>
            <div class='row align-items-center mb-5 pb-4 border-bottom'>
                <div class='col-md-3 text-center mb-4 mb-md-0'>
                    <div class='d-inline-flex align-items-center justify-content-center rounded-circle bg-warning text-primary shadow' style='width: 150px; height: 150px; border: 5px solid white;'>
                        <h1 class='fw-bold mb-0'>KS</h1>
                    </div>
                    <h5 class='fw-bold text-primary mt-3 mb-0'>Kepala Sekolah</h5>
                </div>
                <div class='col-md-9'>
                    <h4 class='fw-bold text-primary mb-3'>Sambutan Kepala Sekolah</h4>
                    <p class='text-muted fst-italic' style='line-height: 1.8;'>"Selamat datang di website resmi sekolah kami. Kami berkomitmen memberikan pendidikan terbaik dan lingkungan yang menyenangkan bagi putra-putri Anda untuk berkembang secara akademis dan karakter."</p>
                </div>
            </div>

            <!-- Visi & Misi Block -->
            <div class='row g-4 mb-5 pb-4 border-bottom'>
                <!-- Visi -->
                <div class='col-md-6'>
                    <h4 class='fw-bold text-primary mb-3'>Visi</h4>
                    <div class='p-4 bg-primary-subtle rounded-3' style='border-left: 5px solid var(--primary);'>
                        <p class='mb-0 fw-bold text-dark'>"Menjadi sekolah dasar unggulan yang mencetak generasi berprestasi, berkarakter, dan berwawasan global."</p>
                    </div>
                </div>
                <!-- Misi -->
                <div class='col-md-6'>
                    <h4 class='fw-bold text-primary mb-3'>Misi</h4>
                    <div class='p-4 border rounded-3'>
                        <ul class='text-muted mb-0 ps-3' style='line-height: 1.8;'>
                            <li class='mb-2'>Menyelenggarakan pembelajaran aktif, inovatif, kreatif, efektif, dan menyenangkan (PAIKEM).</li>
                            <li class='mb-2'>Meningkatkan kualitas tenaga pendidik melalui pelatihan berkelanjutan.</li>
                            <li class='mb-2'>Membentuk karakter siswa yang religius, jujur, disiplin, dan bertanggung jawab.</li>
                            <li>Mengembangkan minat, bakat, dan potensi siswa melalui kegiatan ekstrakurikuler.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Informasi Kontak & Lokasi -->
            <div class='row g-4'>
                <div class='col-md-5'>
                    <h4 class='fw-bold text-primary mb-4'>Informasi Kontak & Lokasi</h4>
                    <ul class='list-unstyled text-muted'>
                        <li class='mb-3 pb-3 border-bottom d-flex align-items-center'>
                            <i class='fas fa-map-marker-alt text-danger fs-5 me-3'></i>
                            Jl. Godean, Nogotirto, Gamping, Sleman, Yogyakarta 55293
                        </li>
                        <li class='mb-3 pb-3 border-bottom d-flex align-items-center'>
                            <i class='fas fa-phone-alt text-success fs-5 me-3'></i>
                            0274-123456
                        </li>
                        <li class='mb-3 pb-3 border-bottom d-flex align-items-center'>
                            <i class='fab fa-whatsapp text-success fs-5 me-3'></i>
                            0812-3456-7890
                        </li>
                        <li class='mb-3 d-flex align-items-center'>
                            <i class='fas fa-envelope text-primary fs-5 me-3'></i>
                            info@sdndemakijo1.sch.id
                        </li>
                    </ul>
                </div>
                <div class='col-md-7'>
                    <div class='rounded-3 overflow-hidden border shadow-sm' style='height: 320px;'>
                        <!-- Google Maps Embed - SDN Demakijo 1, Nogotirto, Gamping, Sleman -->
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.1100386346!2d110.3368605!3d-7.778155799999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a580b6005a465%3A0x378bf13b6e8cf15b!2sSD%20Negeri%20Demakijo%201!5e0!3m2!1sid!2sid!4v1782648104368!5m2!1sid!2sid"
                            width="100%"
                            height="320"
                            style="border:0; display:block;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Peta Lokasi SDN Demakijo 1 Gamping Sleman"
                        ></iframe>
                    </div>
                    <div class="mt-2 text-center">
                        <a href="https://maps.app.goo.gl/SDNDemakijo1" target="_blank" class="text-decoration-none text-muted small">
                            <i class="fas fa-external-link-alt me-1"></i>Buka di Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection