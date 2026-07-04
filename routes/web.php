<?php

use App\Core\Router;

/*
|--------------------------------------------------------------------------
| Web Routes - Native PHP MVC (demakijo1-konversi)
|--------------------------------------------------------------------------
*/

// ============================================================
// RUTE PUBLIK (Tanpa Login)
// ============================================================

Router::get('/', function () {
    $setting     = \App\Models\SettingWebsite::first();
    $keunggulan  = \App\Models\KeunggulanSekolah::where('is_active', true)->orderBy('urutan')->get();
    $siswaCount  = $setting && $setting->jumlah_siswa ? $setting->jumlah_siswa : \App\Models\Siswa::count();
    $guruCount   = $setting && $setting->jumlah_guru  ? $setting->jumlah_guru  : \App\Models\Guru::count();
    $alumniCount = $setting && $setting->jumlah_alumni ? $setting->jumlah_alumni : \App\Models\Alumni::where('is_verified', true)->count();
    $akreditasi  = $setting && $setting->akreditasi ? $setting->akreditasi : 'A';
    $latest_berita = \App\Models\Berita::with('kategori')->where('is_published', true)->latest()->take(3)->get();
    return \App\Core\View::render('welcome', compact('setting', 'keunggulan', 'siswaCount', 'guruCount', 'alumniCount', 'akreditasi', 'latest_berita'));
});

Router::get('/offline', function () {
    return \App\Core\View::render('offline');
});

Router::get('/profil', function () {
    $profil = \App\Models\SettingWebsite::first();
    return \App\Core\View::render('profil', compact('profil'));
});

Router::get('/identitas-sekolah', function () {
    $profil = \App\Models\SettingWebsite::first();
    return \App\Core\View::render('publik.identitas', compact('profil'));
});

Router::get('/sejarah', function () {
    $profil = \App\Models\SettingWebsite::first();
    return \App\Core\View::render('publik.sejarah', compact('profil'));
});

Router::get('/akreditasi-sekolah', function () {
    $profil = \App\Models\SettingWebsite::first();
    return \App\Core\View::render('publik.akreditasi', compact('profil'));
});

Router::get('/sarana-prasarana', function () {
    $fasilitas = \App\Models\Fasilitas::all();
    return \App\Core\View::render('publik.sarana_prasarana', compact('fasilitas'));
});

Router::get('/ekstrakurikuler', function () {
    $ekstra = \App\Models\Ekstrakurikuler::all();
    return \App\Core\View::render('publik.ekstrakurikuler', compact('ekstra'));
});

Router::get('/berita', function () {
    $berita = \App\Models\Berita::with('kategori')->where('is_published', true)->latest()->take(9)->get();
    return \App\Core\View::render('publik.berita', compact('berita'));
});

Router::get('/berita/{slug}', function ($slug) {
    $item    = \App\Models\Berita::where('slug', $slug)->where('is_published', true)->first();
    if (!$item) { http_response_code(404); die('Berita tidak ditemukan.'); }
    $related = \App\Models\Berita::where('kategori_id', $item->kategori_id)->where('id', '!=', $item->id)->where('is_published', true)->latest()->take(4)->get();
    return \App\Core\View::render('publik.berita_detail', compact('item', 'related'));
});

Router::get('/prestasi', function () {
    $kategori = $_GET['kategori'] ?? null;
    $sort = $_GET['sort'] ?? 'Terbaru';

    $query = \App\Models\Prestasi::query();
    
    if ($kategori && in_array($kategori, ['Akademik', 'Olahraga', 'Seni & Budaya', 'Lainnya'])) {
        $query->where('kategori', $kategori);
    }

    if ($sort === 'Terlama') {
        $query->orderBy('tanggal', 'asc');
    } else {
        $query->orderBy('tanggal', 'desc');
    }

    $prestasi = $query->get();
    return \App\Core\View::render('publik.prestasi', compact('prestasi', 'kategori', 'sort'));
});

Router::get('/struktur-organisasi', function () {
    return \App\Core\View::render('publik.struktur_organisasi');
});

Router::get('/agenda', function () {
    $agenda = \App\Models\Agenda::latest()->get();
    return \App\Core\View::render('publik.agenda', compact('agenda'));
});

Router::get('/guru-tendik', function () {
    $guru = \App\Models\Guru::latest()->get();
    return \App\Core\View::render('publik.guru_tendik', compact('guru'));
});

Router::get('/alumni', function () {
    $alumni = \App\Models\Alumni::where('is_verified', true)->latest()->get();
    return \App\Core\View::render('publik.alumni', compact('alumni'));
});

Router::post('/alumni/daftar', function () {
    $data = [];
    foreach ($_POST as $key => $val) {
        if ($key === '_token') continue;
        $data[$key] = is_string($val) ? htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES, 'UTF-8') : $val;
    }
    $data['is_verified'] = false;
    \App\Models\Alumni::create($data);
    \App\Core\Session::setFlash('success', 'Terima kasih, pendaftaran Anda berhasil. Data akan diverifikasi oleh Admin.');
    redirect('/alumni');
});

Router::get('/komite-sekolah', function () {
    $komite = \App\Models\Komite::orderBy('urutan')->get();
    return \App\Core\View::render('publik.komite', compact('komite'));
});

Router::get('/galeri/foto', function () {
    $kategori = $_GET['kategori'] ?? null;
    $sort = $_GET['sort'] ?? 'Terbaru';
    
    $query = \App\Models\Album::with('galeri');
    if ($kategori && in_array($kategori, ['Kegiatan Sekolah', 'Ekstrakurikuler', 'Prestasi', 'Kunjungan', 'Lainnya'])) {
        $query->where('kategori', $kategori);
    }
    
    if ($sort === 'Terlama') {
        $query->orderBy('created_at', 'asc');
    } elseif ($sort === 'Terbanyak') {
        $query->withCount('galeri')->orderBy('galeri_count', 'desc');
    } else {
        $query->orderBy('created_at', 'desc');
    }
    
    $album = $query->get();
    return \App\Core\View::render('publik.galeri_foto', compact('album', 'kategori', 'sort'));
});

Router::get('/galeri/video', function () {
    return \App\Core\View::render('publik.galeri_video');
});

Router::get('/unduhan', function () {
    $unduhan = \App\Models\Pengumuman::whereNotNull('file_lampiran')->latest()->get();
    return \App\Core\View::render('publik.unduhan', compact('unduhan'));
});

// PPDB
Router::get('/ppdb-online', function () {
    return \App\Core\View::render('publik.ppdb');
});
Router::post('/ppdb/daftar', 'PpdbPublikController@store');
Router::get('/ppdb/cek', 'PpdbPublikController@cek');

// ============================================================
// RUTE OTENTIKASI
// ============================================================
Router::get('/login', 'AuthController@loginForm');
Router::post('/login', 'AuthController@loginAction');
Router::post('/logout', 'AuthController@logoutAction');

// ============================================================
// RUTE ADMIN (Semua butuh login - dicek di masing-masing controller)
// ============================================================
Router::get('/dashboard', 'Admin\DashboardController@index');
Router::get('/admin', 'Admin\DashboardController@index');
Router::get('/admin/dashboard', 'Admin\DashboardController@index');


// Kategori Berita
Router::get('/admin/kategori-berita', 'Backend\KategoriBeritaController@index');
Router::get('/admin/kategori-berita/create', 'Backend\KategoriBeritaController@create');
Router::post('/admin/kategori-berita', 'Backend\KategoriBeritaController@store');
Router::get('/admin/kategori-berita/{id}/edit', 'Backend\KategoriBeritaController@edit');
Router::post('/admin/kategori-berita/{id}/update', 'Backend\KategoriBeritaController@update');
Router::post('/admin/kategori-berita/{id}/delete', 'Backend\KategoriBeritaController@destroy');

// Berita
Router::get('/admin/berita', 'Backend\BeritaController@index');
Router::get('/admin/berita/create', 'Backend\BeritaController@create');
Router::post('/admin/berita', 'Backend\BeritaController@store');
Router::get('/admin/berita/{id}/edit', 'Backend\BeritaController@edit');
Router::post('/admin/berita/{id}/update', 'Backend\BeritaController@update');
Router::post('/admin/berita/{id}/delete', 'Backend\BeritaController@destroy');

// Galeri
Router::get('/admin/album', 'Backend\AlbumController@index');
Router::get('/admin/album/create', 'Backend\AlbumController@create');
Router::post('/admin/album', 'Backend\AlbumController@store');
Router::get('/admin/album/{id}/edit', 'Backend\AlbumController@edit');
Router::post('/admin/album/{id}/update', 'Backend\AlbumController@update');
Router::post('/admin/album/{id}/delete', 'Backend\AlbumController@destroy');
Router::get('/admin/galeri', 'Backend\GaleriController@index');
Router::get('/admin/galeri/create', 'Backend\GaleriController@create');
Router::post('/admin/galeri', 'Backend\GaleriController@store');
Router::get('/admin/galeri/{id}/edit', 'Backend\GaleriController@edit');
Router::post('/admin/galeri/{id}/update', 'Backend\GaleriController@update');
Router::post('/admin/galeri/{id}/delete', 'Backend\GaleriController@destroy');

// Pengumuman
Router::get('/admin/pengumuman', 'Backend\PengumumanController@index');
Router::get('/admin/pengumuman/create', 'Backend\PengumumanController@create');
Router::post('/admin/pengumuman', 'Backend\PengumumanController@store');
Router::get('/admin/pengumuman/{id}/edit', 'Backend\PengumumanController@edit');
Router::post('/admin/pengumuman/{id}/update', 'Backend\PengumumanController@update');
Router::post('/admin/pengumuman/{id}/delete', 'Backend\PengumumanController@destroy');

// Agenda
Router::get('/admin/agenda', 'Backend\AgendaController@index');
Router::get('/admin/agenda/create', 'Backend\AgendaController@create');
Router::post('/admin/agenda', 'Backend\AgendaController@store');
Router::get('/admin/agenda/{id}/edit', 'Backend\AgendaController@edit');
Router::post('/admin/agenda/{id}/update', 'Backend\AgendaController@update');
Router::post('/admin/agenda/{id}/delete', 'Backend\AgendaController@destroy');

// Guru
Router::get('/admin/guru', 'Backend\GuruController@index');
Router::get('/admin/guru/create', 'Backend\GuruController@create');
Router::post('/admin/guru', 'Backend\GuruController@store');
Router::get('/admin/guru/{id}/edit', 'Backend\GuruController@edit');
Router::post('/admin/guru/{id}/update', 'Backend\GuruController@update');
Router::post('/admin/guru/{id}/delete', 'Backend\GuruController@destroy');

// Siswa
Router::get('/admin/siswa', 'Backend\SiswaController@index');
Router::get('/admin/siswa/create', 'Backend\SiswaController@create');
Router::post('/admin/siswa', 'Backend\SiswaController@store');
Router::get('/admin/siswa/{id}/edit', 'Backend\SiswaController@edit');
Router::post('/admin/siswa/{id}/update', 'Backend\SiswaController@update');
Router::post('/admin/siswa/{id}/delete', 'Backend\SiswaController@destroy');

// Prestasi
Router::get('/admin/prestasi', 'Backend\PrestasiController@index');
Router::get('/admin/prestasi/create', 'Backend\PrestasiController@create');
Router::post('/admin/prestasi', 'Backend\PrestasiController@store');
Router::get('/admin/prestasi/{id}/edit', 'Backend\PrestasiController@edit');
Router::post('/admin/prestasi/{id}/update', 'Backend\PrestasiController@update');
Router::post('/admin/prestasi/{id}/delete', 'Backend\PrestasiController@destroy');

// Fasilitas
Router::get('/admin/fasilitas', 'Backend\FasilitasController@index');
Router::get('/admin/fasilitas/create', 'Backend\FasilitasController@create');
Router::post('/admin/fasilitas', 'Backend\FasilitasController@store');
Router::get('/admin/fasilitas/{id}/edit', 'Backend\FasilitasController@edit');
Router::post('/admin/fasilitas/{id}/update', 'Backend\FasilitasController@update');
Router::post('/admin/fasilitas/{id}/delete', 'Backend\FasilitasController@destroy');

// Ekstrakurikuler
Router::get('/admin/ekstrakurikuler', 'Backend\EkstrakurikulerController@index');
Router::get('/admin/ekstrakurikuler/create', 'Backend\EkstrakurikulerController@create');
Router::post('/admin/ekstrakurikuler', 'Backend\EkstrakurikulerController@store');
Router::get('/admin/ekstrakurikuler/{id}/edit', 'Backend\EkstrakurikulerController@edit');
Router::post('/admin/ekstrakurikuler/{id}/update', 'Backend\EkstrakurikulerController@update');
Router::post('/admin/ekstrakurikuler/{id}/delete', 'Backend\EkstrakurikulerController@destroy');

// PPDB Admin
Router::get('/admin/ppdb', 'Backend\PpdbController@index');
Router::get('/admin/ppdb/create', 'Backend\PpdbController@create');
Router::post('/admin/ppdb', 'Backend\PpdbController@store');
Router::get('/admin/ppdb/{id}', 'Backend\PpdbController@edit');
Router::get('/admin/ppdb/{id}/edit', 'Backend\PpdbController@edit');
Router::post('/admin/ppdb/{id}/update', 'Backend\PpdbController@update');
Router::post('/admin/ppdb/{id}/delete', 'Backend\PpdbController@destroy');

// Komite
Router::get('/admin/komite', 'Backend\KomiteController@index');
Router::get('/admin/komite/create', 'Backend\KomiteController@create');
Router::post('/admin/komite', 'Backend\KomiteController@store');
Router::get('/admin/komite/{id}/edit', 'Backend\KomiteController@edit');
Router::post('/admin/komite/{id}/update', 'Backend\KomiteController@update');
Router::post('/admin/komite/{id}/delete', 'Backend\KomiteController@destroy');

// Alumni
Router::get('/admin/alumni', 'Backend\AlumniController@index');
Router::get('/admin/alumni/create', 'Backend\AlumniController@create');
Router::post('/admin/alumni', 'Backend\AlumniController@store');
Router::get('/admin/alumni/{id}/edit', 'Backend\AlumniController@edit');
Router::post('/admin/alumni/{id}/update', 'Backend\AlumniController@update');
Router::post('/admin/alumni/{id}/delete', 'Backend\AlumniController@destroy');

// User Management
Router::get('/admin/user', 'Backend\UserController@index');
Router::get('/admin/user/create', 'Backend\UserController@create');
Router::post('/admin/user', 'Backend\UserController@store');
Router::get('/admin/user/{id}/edit', 'Backend\UserController@edit');
Router::post('/admin/user/{id}/update', 'Backend\UserController@update');
Router::post('/admin/user/{id}/delete', 'Backend\UserController@destroy');

// Keunggulan Sekolah
Router::get('/admin/keunggulan', 'Backend\KeunggulanController@index');
Router::get('/admin/keunggulan/create', 'Backend\KeunggulanController@create');
Router::post('/admin/keunggulan', 'Backend\KeunggulanController@store');
Router::get('/admin/keunggulan/{id}/edit', 'Backend\KeunggulanController@edit');
Router::post('/admin/keunggulan/{id}/update', 'Backend\KeunggulanController@update');
Router::post('/admin/keunggulan/{id}/delete', 'Backend\KeunggulanController@destroy');

// Pengaturan
Router::get('/admin/pengaturan', 'Backend\PengaturanController@index');
Router::post('/admin/pengaturan', 'Backend\PengaturanController@store');

// Notifikasi
Router::get('/admin/notifikasi', 'Backend\NotifikasiController@index');
Router::post('/admin/notifikasi/send', 'Backend\NotifikasiController@send');
