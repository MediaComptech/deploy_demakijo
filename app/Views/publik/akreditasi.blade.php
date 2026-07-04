@extends('publik.layout', ['title' => 'Akreditasi Sekolah', 'header_title' => 'Akreditasi Sekolah'])
@section('content')

<style>
    .profile-sidebar .nav-link {
        color: #495057;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 1px solid rgba(0,0,0,0.05);
        background: #ffffff;
    }
    .profile-sidebar .nav-link:hover {
        background: rgba(0, 51, 102, 0.04);
        color: #003366;
    }
    .profile-sidebar .nav-link.active {
        background: #0056b3 !important;
        color: #ffffff !important;
        border-color: #0056b3 !important;
        box-shadow: 0 4px 10px rgba(0, 86, 179, 0.15);
    }
    
    /* Golden Ribbon Badge styling */
    .accreditation-badge {
        position: relative;
        width: 140px;
        height: 140px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle, #fcd34d 0%, #fbbf24 60%, #d97706 100%);
        border-radius: 50%;
        border: 8px double #ffffff;
        box-shadow: 0 8px 16px rgba(217, 119, 6, 0.2);
    }
    .accreditation-badge::before, .accreditation-badge::after {
        content: '';
        position: absolute;
        bottom: -30px;
        width: 35px;
        height: 70px;
        background: #0056b3;
        z-index: -1;
        clip-path: polygon(0% 0%, 100% 0%, 50% 100%);
    }
    .accreditation-badge::before {
        left: 20px;
        transform: rotate(15deg);
    }
    .accreditation-badge::after {
        right: 20px;
        transform: rotate(-15deg);
    }
    .accreditation-letter {
        font-size: 4.5rem;
        font-weight: 900;
        color: #78350f;
        text-shadow: 2px 2px 0px rgba(255, 255, 255, 0.4);
        line-height: 1;
    }
    
    .akreditasi-table td {
        padding: 0.65rem 0.5rem;
        font-size: 0.95rem;
    }
    .akreditasi-table tr:not(:last-child) {
        border-bottom: 1px solid #f1f5f9;
    }
    
    .standard-progress-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
    }
    .standard-progress-score {
        font-size: 0.9rem;
        font-weight: 700;
        color: #0f172a;
        width: 30px;
        text-align: right;
    }
</style>

<div class="row g-4">
    <!-- Left Column: Sidebar -->
    <div class="col-lg-3 profile-sidebar" data-aos="fade-right">
        <div class="nav flex-column nav-pills gap-2">
            <a href="/identitas-sekolah" class="nav-link py-3 px-4 rounded-3 d-flex align-items-center gap-3">
                <i class="fas fa-id-card"></i> Identitas Sekolah
            </a>
            <a href="/sejarah" class="nav-link py-3 px-4 rounded-3 d-flex align-items-center gap-3">
                <i class="fas fa-history"></i> Sejarah
            </a>
            <a href="/akreditasi-sekolah" class="nav-link py-3 px-4 rounded-3 d-flex align-items-center gap-3 active">
                <i class="fas fa-award"></i> Akreditasi Sekolah
            </a>
            <a href="/sarana-prasarana" class="nav-link py-3 px-4 rounded-3 d-flex align-items-center gap-3">
                <i class="fas fa-building"></i> Sarana Prasarana
            </a>
            <a href="/komite-sekolah" class="nav-link py-3 px-4 rounded-3 d-flex align-items-center gap-3">
                <i class="fas fa-sitemap"></i> Struktur Komite
            </a>
        </div>
    </div>

    <!-- Right Column: Content -->
    <div class="col-lg-9" data-aos="fade-left">
        <div class="card border-0 rounded-4 shadow-sm p-4 mb-4">
            
            <h4 class="fw-bold text-dark mb-4">Akreditasi Sekolah</h4>
            
            <!-- Akreditasi Details -->
            <div class="row g-4 align-items-center mb-5">
                <!-- Golden Badge & Download Button -->
                <div class="col-md-4 text-center">
                    <div class="accreditation-badge mb-4">
                        <span class="accreditation-letter">{{ $profil->akreditasi ?? 'A' }}</span>
                    </div>
                    @if(!empty($profil->akreditasi_sertifikat_file))
                        <a href="{{ asset('storage/' . $profil->akreditasi_sertifikat_file) }}" target="_blank" class="btn btn-primary rounded-pill px-3 py-2 fw-bold w-100 shadow-sm">
                            <i class="fas fa-file-pdf me-2"></i>Lihat Sertifikat Akreditasi
                        </a>
                    @else
                        <a href="#" class="btn btn-secondary rounded-pill px-3 py-2 fw-bold w-100 shadow-sm disabled" onclick="alert('File sertifikat belum diunggah!'); return false;">
                            <i class="fas fa-file-pdf me-2"></i>Sertifikat Belum Tersedia
                        </a>
                    @endif
                </div>
                
                <!-- Keterangan & Detail table -->
                <div class="col-md-8">
                    <div class="d-flex align-items-start gap-2 mb-3">
                        <i class="fas fa-check-circle text-primary fa-lg mt-1"></i>
                        <p class="fw-bold text-dark mb-0 fs-5" style="line-height: 1.4;">
                            SDN Demakijo 1 telah terakreditasi dengan predikat {{ $profil->akreditasi_peringkat ?? 'A (Unggul)' }} berdasarkan penilaian BAN-S/M.
                        </p>
                    </div>
                    <table class="table akreditasi-table table-borderless mb-0 mt-2">
                        <tbody>
                            <tr>
                                <td width="35%" class="text-muted fw-semibold">Nomor Sertifikat</td>
                                <td width="5%" class="text-muted">:</td>
                                <td class="text-dark">{{ $profil->akreditasi_no_sertifikat ?? '1234/BAN-SM/AK/XII/2022' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Tahun Akreditasi</td>
                                <td class="text-muted">:</td>
                                <td class="text-dark">{{ $profil->akreditasi_tahun ?? '2022' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Peringkat</td>
                                <td class="text-muted">:</td>
                                <td class="text-dark"><span class="badge bg-success-subtle text-success px-2 py-1 rounded">{{ $profil->akreditasi_peringkat ?? 'A (Unggul)' }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Berlaku Hingga</td>
                                <td class="text-muted">:</td>
                                <td class="text-dark">{{ $profil->akreditasi_berlaku ?? '2027' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Link BAN-S/M</td>
                                <td class="text-muted">:</td>
                                <td>
                                    <a href="https://ban-pdm.id/satuanpendidikan/20401675" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-bold text-primary">
                                        <i class="fas fa-external-link-alt me-1"></i>Lihat Data BAN-S/M
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Komponen Penilaian -->
            <div class="border-top pt-4">
                <h5 class="fw-bold text-dark mb-4"><i class="fas fa-tasks text-primary me-2"></i>Komponen Penilaian</h5>
                
                @php
                    $standards = [
                        ['name' => 'Standar Isi', 'score' => $profil->akreditasi_standar_isi ?? 93],
                        ['name' => 'Standar Proses', 'score' => $profil->akreditasi_standar_proses ?? 92],
                        ['name' => 'Standar Kompetensi Lulusan', 'score' => $profil->akreditasi_standar_skl ?? 95],
                        ['name' => 'Standar Pendidik & Tenaga Kependidikan', 'score' => $profil->akreditasi_standar_ptk ?? 93],
                        ['name' => 'Standar Sarana & Prasarana', 'score' => $profil->akreditasi_standar_sarpras ?? 90],
                        ['name' => 'Standar Pengelolaan', 'score' => $profil->akreditasi_standar_pengelolaan ?? 94],
                        ['name' => 'Standar Pembiayaan', 'score' => $profil->akreditasi_standar_pembiayaan ?? 91],
                        ['name' => 'Standar Penilaian Pendidikan', 'score' => $profil->akreditasi_standar_penilaian ?? 92]
                    ];
                @endphp
                
                <div class="row g-3">
                    @foreach($standards as $std)
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="standard-progress-label">{{ $std['name'] }}</span>
                            <span class="standard-progress-score">{{ $std['score'] }}</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px; background: #e2e8f0;">
                            <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $std['score'] }}%; background-color: #0056b3;" aria-valuenow="{{ $std['score'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Illustration decorator -->
            <div class="d-flex align-items-center gap-3 mt-5 p-3 rounded-4 bg-light border border-light">
                <div class="bg-white rounded-circle p-3 shadow-sm text-warning d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-trophy fa-2x"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-1">Komitmen Mutu Pendidikan</h6>
                    <p class="text-secondary small mb-0">SDN Demakijo 1 terus berinovasi dalam pemenuhan seluruh standar mutu pendidikan demi masa depan gemilang anak didik.</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection