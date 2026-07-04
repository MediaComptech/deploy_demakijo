<?php
namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Galeri;
use App\Models\Album;
use Illuminate\Database\Capsule\Manager as Capsule;

class GaleriController extends Controller
{
    /** Tipe MIME yang diizinkan */
    private const ALLOWED_MIME = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    /** Ukuran maksimal per file (bytes) = 5MB */
    private const MAX_SIZE_BYTES = 5 * 1024 * 1024;

    /** Maksimal foto per upload */
    private const MAX_FILES = 10;

    public function __construct()
    {
        parent::__construct();
        if (!Auth::check()) { redirect('/login'); }
        $this->checkSchema();
    }

    /**
     * Auto-create kolom baru jika belum ada (zero-downtime migration)
     */
    private function checkSchema()
    {
        try {
            $schema = Capsule::schema();
            $cols = ['url_gdrive' => 'text', 'keterangan' => 'text', 'urutan' => 'integer'];
            foreach ($cols as $col => $type) {
                if (!$schema->hasColumn('galeris', $col)) {
                    $schema->table('galeris', function ($t) use ($col, $type) {
                        if ($type === 'text') $t->text($col)->nullable();
                        if ($type === 'integer') $t->integer($col)->default(0)->nullable();
                    });
                }
            }
        } catch (\Exception $e) {}
    }

    public function index()
    {
        $data = Galeri::with('album')->latest()->get();
        return view('backend.galeri.index', compact('data'));
    }

    public function create()
    {
        $album = Album::orderBy('nama')->get();
        return view('backend.galeri.create', compact('album'));
    }

    /**
     * Store — mendukung upload multiple foto (maks 10) + URL GDrive
     */
    public function store(Request $request)
    {
        $albumId    = (int) $request->input('album_id');
        $judulBase  = trim(strip_tags($request->input('judul', '')));
        $urlGdrive  = $this->sanitizeUrl($request->input('url_gdrive', ''));
        $keterangan = strip_tags($request->input('keterangan', ''));

        if (!$albumId) {
            redirect('/admin/galeri/create')->with('error', 'Album harus dipilih.');
            return;
        }
        if (empty($judulBase)) {
            redirect('/admin/galeri/create')->with('error', 'Judul foto harus diisi.');
            return;
        }

        $files = $_FILES['files'] ?? null;
        $uploaded = 0;
        $errors   = [];

        // === Handle multiple file upload ===
        if ($files && !empty($files['name'][0])) {
            $total = count($files['name']);

            // Batasi maks 10 file
            if ($total > self::MAX_FILES) {
                redirect('/admin/galeri/create')->with('error', 'Maksimal ' . self::MAX_FILES . ' foto per upload.');
                return;
            }

            for ($i = 0; $i < $total; $i++) {
                if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

                $tmpPath  = $files['tmp_name'][$i];
                $origName = basename($files['name'][$i]);
                $size     = $files['size'][$i];
                $mime     = mime_content_type($tmpPath);

                // Validasi tipe (cek mime content, bukan ekstensi)
                if (!in_array($mime, self::ALLOWED_MIME)) {
                    $errors[] = "File '{$origName}': tipe tidak didukung ({$mime}). Gunakan JPG, PNG, WebP, atau GIF.";
                    continue;
                }

                // Validasi ukuran
                if ($size > self::MAX_SIZE_BYTES) {
                    $mb = round($size / 1024 / 1024, 1);
                    $errors[] = "File '{$origName}': terlalu besar ({$mb} MB). Maks. 5 MB.";
                    continue;
                }

                // Simpan file
                $ext      = pathinfo($origName, PATHINFO_EXTENSION) ?: 'jpg';
                $safeName = 'galeri_' . time() . '_' . $i . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
                $destDir  = public_path('storage/uploads');
                if (!is_dir($destDir)) mkdir($destDir, 0755, true);
                $destPath = $destDir . '/' . $safeName;

                if (move_uploaded_file($tmpPath, $destPath)) {
                    $judul = $total > 1 ? $judulBase . ' (' . ($uploaded + 1) . ')' : $judulBase;
                    Galeri::create([
                        'album_id'   => $albumId,
                        'judul'      => $judul,
                        'file'       => 'uploads/' . $safeName,
                        'url_gdrive' => '',
                        'keterangan' => $keterangan,
                        'urutan'     => 0,
                    ]);
                    $uploaded++;
                }
            }
        }

        // === Handle URL Google Drive (tanpa file) ===
        if ($urlGdrive && $uploaded === 0) {
            Galeri::create([
                'album_id'   => $albumId,
                'judul'      => $judulBase,
                'file'       => '',
                'url_gdrive' => $urlGdrive,
                'keterangan' => $keterangan,
                'urutan'     => 0,
            ]);
            $uploaded++;
        }

        // === Handle URL GDrive + ada file (URL diterapkan ke semua yang diupload) ===
        if ($urlGdrive && $uploaded > 1) {
            // URL hanya berlaku jika tidak ada file — sudah dihandle di atas
            // Tidak perlu tindakan tambahan
        }

        if (!empty($errors)) {
            $errMsg = implode(' | ', $errors);
            if ($uploaded > 0) {
                redirect('/admin/galeri')->with('success', "{$uploaded} foto berhasil diunggah. Peringatan: {$errMsg}");
            } else {
                redirect('/admin/galeri/create')->with('error', $errMsg);
            }
            return;
        }

        if ($uploaded === 0) {
            redirect('/admin/galeri/create')->with('error', 'Tidak ada foto atau URL yang diunggah.');
            return;
        }

        redirect('/admin/galeri')->with('success', "{$uploaded} foto berhasil ditambahkan ke galeri.");
    }

    public function edit($id)
    {
        $data  = Galeri::findOrFail((int) $id);
        $album = Album::orderBy('nama')->get();
        return view('backend.galeri.edit', compact('data', 'album'));
    }

    public function update(Request $request, $id)
    {
        $model      = Galeri::findOrFail((int) $id);
        $judulBaru  = trim(strip_tags($request->input('judul', $model->judul)));
        $albumId    = (int) $request->input('album_id', $model->album_id);
        $urlGdrive  = $this->sanitizeUrl($request->input('url_gdrive', ''));
        $keterangan = strip_tags($request->input('keterangan', ''));

        $input = [
            'judul'      => $judulBaru ?: $model->judul,
            'album_id'   => $albumId ?: $model->album_id,
            'url_gdrive' => $urlGdrive,
            'keterangan' => $keterangan,
        ];

        // Handle file upload (single saat edit)
        $file = $_FILES['file'] ?? null;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $tmpPath  = $file['tmp_name'];
            $origName = basename($file['name']);
            $size     = $file['size'];
            $mime     = mime_content_type($tmpPath);

            if (!in_array($mime, self::ALLOWED_MIME)) {
                redirect('/admin/galeri/' . $id . '/edit')->with('error', "Tipe file tidak didukung. Gunakan JPG, PNG, atau WebP.");
                return;
            }
            if ($size > self::MAX_SIZE_BYTES) {
                $mb = round($size / 1024 / 1024, 1);
                redirect('/admin/galeri/' . $id . '/edit')->with('error', "File terlalu besar ({$mb} MB). Maks. 5 MB.");
                return;
            }

            $ext      = pathinfo($origName, PATHINFO_EXTENSION) ?: 'jpg';
            $safeName = 'galeri_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
            $destDir  = public_path('storage/uploads');
            if (!is_dir($destDir)) mkdir($destDir, 0755, true);
            $destPath = $destDir . '/' . $safeName;

            if (move_uploaded_file($tmpPath, $destPath)) {
                if ($model->file) native_storage_delete($model->file);
                $input['file'] = 'uploads/' . $safeName;
            }
        }

        $model->update($input);
        redirect('/admin/galeri')->with('success', 'Foto berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $model = Galeri::findOrFail((int) $id);
        if ($model->file) native_storage_delete($model->file);
        $model->delete();
        redirect('/admin/galeri')->with('success', 'Foto berhasil dihapus.');
    }

    /**
     * Sanitize URL — hanya izinkan http/https dan domain Google Drive
     */
    private function sanitizeUrl(string $url): string
    {
        $url = trim($url);
        if (empty($url)) return '';

        // Hanya izinkan http/https
        if (!preg_match('/^https?:\/\//i', $url)) return '';

        // Whitelist domain yang diperbolehkan
        $parsed = parse_url($url);
        $host   = strtolower($parsed['host'] ?? '');
        $allowed = ['drive.google.com', 'docs.google.com', 'photos.google.com', 'lh3.googleusercontent.com'];
        foreach ($allowed as $a) {
            if ($host === $a || str_ends_with($host, '.' . $a)) {
                return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
            }
        }
        // Tidak dalam whitelist — tolak
        return '';
    }
}
