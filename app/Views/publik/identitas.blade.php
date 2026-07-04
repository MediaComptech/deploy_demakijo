@extends('publik.layout', ['title' => 'Identitas Sekolah', 'header_title' => 'Identitas Sekolah'])
@section('content')

<style>
    .profile-sidebar .nav-link {
        color: #495057;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
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

    .info-table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.95rem;
    }

    .info-table tr:not(:last-child) {
        border-bottom: 1px solid #f1f5f9;
    }

    .stat-card {
        border-radius: 1rem;
        border: 1px solid rgba(0, 0, 0, 0.04);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }
</style>

<div class="row g-4">
    <!-- Left Column: Sidebar -->
    <div class="col-lg-3 profile-sidebar" data-aos="fade-right">
        <div class="nav flex-column nav-pills gap-2">
            <a href="/identitas-sekolah" class="nav-link py-3 px-4 rounded-3 d-flex align-items-center gap-3 active">
                <i class="fas fa-id-card"></i> Identitas Sekolah
            </a>
            <a href="/sejarah" class="nav-link py-3 px-4 rounded-3 d-flex align-items-center gap-3">
                <i class="fas fa-history"></i> Sejarah
            </a>
            <a href="/akreditasi-sekolah" class="nav-link py-3 px-4 rounded-3 d-flex align-items-center gap-3">
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
            <div class="row g-4">
                <!-- Data Identitas -->
                <div class="col-md-7">
                    <h4 class="fw-bold text-dark mb-4">Identitas Sekolah</h4>
                    <table class="table info-table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td width="35%" class="text-muted fw-semibold">Nama Sekolah</td>
                                <td width="5%" class="text-muted">:</td>
                                <td class="fw-bold text-dark">{{ $profil->nama_sekolah ?? 'SDN Demakijo 1' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">NPSN</td>
                                <td class="text-muted">:</td>
                                <td class="text-dark">{{ $profil->npsn ?? '20401066' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Status Sekolah</td>
                                <td class="text-muted">:</td>
                                <td class="text-dark">{{ $profil->status_sekolah ?? 'Negeri' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Bentuk Pendidikan</td>
                                <td class="text-muted">:</td>
                                <td class="text-dark">{{ $profil->bentuk_pendidikan ?? 'SD' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Alamat</td>
                                <td class="text-muted">:</td>
                                <td class="text-dark">{{ $profil->alamat ?? 'Demakijo, Gamping, Sleman, D.I Yogyakarta' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Kode Pos</td>
                                <td class="text-muted">:</td>
                                <td class="text-dark">{{ $profil->kode_pos ?? '55294' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Telepon</td>
                                <td class="text-muted">:</td>
                                <td class="text-dark">{{ $profil->telepon ?? '(0274) 123456' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Email</td>
                                <td class="text-muted">:</td>
                                <td><a href="mailto:{{ $profil->email ?? 'info@sdndemakijo1.sch.id' }}" class="text-decoration-none">{{ $profil->email ?? 'info@sdndemakijo1.sch.id' }}</a></td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Kepala Sekolah</td>
                                <td class="text-muted">:</td>
                                <td class="text-dark">{{ $profil->nama_kepsek ?? 'Tri Lestari, S.Pd' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Tahun Berdiri</td>
                                <td class="text-muted">:</td>
                                <td class="text-dark">{{ $profil->tahun_berdiri ?? '1985' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Luas Tanah</td>
                                <td class="text-muted">:</td>
                                <td class="text-dark">{{ $profil->luas_tanah ?? '2.450 m²' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Akreditasi BAN-S/M</td>
                                <td class="text-muted">:</td>
                                <td>
                                    <a href="https://ban-pdm.id/satuanpendidikan/20401675" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-semibold text-primary">
                                        <i class="fas fa-external-link-alt me-1"></i>Lihat Data BAN-S/M
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Foto, Visi & Misi -->
                <div class="col-md-5 d-flex flex-column gap-3">
                    <div class="rounded-4 overflow-hidden shadow-sm" style="height: 160px;">
                        @if($profil && $profil->foto_identitas)
                            <img src="{{ asset('storage/'.$profil->foto_identitas) }}" alt="Foto Sekolah" class="w-100 h-100" style="object-fit: cover;">
                        @else
                            <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=600&q=80" alt="Foto Sekolah" class="w-100 h-100" style="object-fit: cover;">
                        @endif
                    </div>

                    <!-- Visi Sekolah -->
                    <div class="card border-0 rounded-4 p-3 text-white shadow-sm" style="background: linear-gradient(135deg, #003366, #0056b3);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0">Visi Sekolah</h6>
                            <i class="fas fa-bullseye text-warning"></i>
                        </div>
                        <p class="mb-0 small italic" style="font-style: italic; line-height: 1.5;">
                            {{ $profil->visi ?? '"Terwujudnya peserta didik yang berakhlak mulia, cerdas, mandiri, dan berprestasi."' }}
                        </p>
                    </div>

                    <!-- Misi Sekolah -->
                    <div class="card border-0 rounded-4 p-3 shadow-sm" style="background: #f8fafc; border-left: 4px solid #ffc107 !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-primary">Misi Sekolah</h6>
                            <i class="fas fa-list-check text-warning"></i>
                        </div>
                        <ul class="text-muted small mb-0 ps-3" style="line-height: 1.6; font-size: 0.82rem;">
                            @if(!empty($profil->misi))
                            {!! nl2br(e($profil->misi)) !!}
                            @else
                            <li class="mb-1">Menyelenggarakan pembelajaran aktif, inovatif, kreatif, dan menyenangkan.</li>
                            <li class="mb-1">Membentuk karakter siswa yang religius, jujur, dan disiplin.</li>
                            <li>Mengembangkan minat, bakat, dan potensi siswa secara optimal.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Counter cards at the bottom -->
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm stat-card bg-white p-3 d-flex flex-row align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width: 50px; height: 50px; flex-shrink: 0;">
                        <i class="fas fa-door-open fa-lg"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ $profil->jumlah_kelas ?? 24 }}</div>
                        <div class="text-muted small fw-semibold">Ruang Kelas</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm stat-card bg-white p-3 d-flex flex-row align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-success-subtle text-success" style="width: 50px; height: 50px; flex-shrink: 0;">
                        <i class="fas fa-chalkboard-teacher fa-lg"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ $profil->jumlah_guru ?? 32 }}</div>
                        <div class="text-muted small fw-semibold">Guru & Staf</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm stat-card bg-white p-3 d-flex flex-row align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning-subtle text-warning" style="width: 50px; height: 50px; flex-shrink: 0;">
                        <i class="fas fa-user-graduate fa-lg"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ $profil->jumlah_siswa ?? 412 }}</div>
                        <div class="text-muted small fw-semibold">Peserta Didik</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm stat-card bg-white p-3 d-flex flex-row align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger-subtle text-danger" style="width: 50px; height: 50px; flex-shrink: 0;">
                        <i class="fas fa-user-tie fa-lg"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ $profil->jumlah_tendik ?? 7 }}</div>
                        <div class="text-muted small fw-semibold">Tendik</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection