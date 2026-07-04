<?php

namespace App\Core;

class Request
{
    private $data;
    private $files;

    public function __construct()
    {
        $this->data = array_merge($_GET, $_POST);
        $this->files = $_FILES;

        // Bersihkan parameter routing yang masuk ke $_GET akibat rewrite rule htaccess (.php?$1)
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
        $scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        if ($scriptName !== '/' && $scriptName !== '\\') {
            $uri = str_replace($scriptName, '', $uri);
        }
        $path = trim($uri, '/');
        
        if (isset($this->data[$path])) {
            unset($this->data[$path]);
        }
        if (isset($this->data[$path . '/'])) {
            unset($this->data[$path . '/']);
        }
    }

    public function input($key = null, $default = null)
    {
        if ($key === null) return $this->data;
        return $this->data[$key] ?? $default;
    }

    public function all()
    {
        return $this->data;
    }

    public function get($key, $default = null)
    {
        return $this->input($key, $default);
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function hasFile($key)
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] !== UPLOAD_ERR_NO_FILE;
    }

    public function file($key)
    {
        if ($this->hasFile($key)) {
            return new NativeUploadedFile($this->files[$key]);
        }
        return null;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Cek apakah key ada dan nilainya tidak kosong.
     */
    public function filled($key): bool
    {
        return isset($this->data[$key]) && $this->data[$key] !== '' && $this->data[$key] !== null;
    }

    /**
     * Kembalikan semua data kecuali key yang disebutkan.
     */
    public function except(string ...$keys): array
    {
        $data = $this->data;
        foreach ($keys as $key) {
            unset($data[$key]);
        }
        return $data;
    }

    /**
     * Validasi sederhana. Redirect balik dengan flash error jika gagal.
     * Rules yang didukung: required, email, min:N, unique:table[,column[,ignoreId]]
     */
    public function validate(array $rules, array $messages = []): bool
    {
        $validator = new Validator();
        if (!$validator->make($this->data, $rules, $messages)) {
            Session::setFlash('errors', $validator->errors());
            foreach ($this->data as $k => $v) {
                Session::setFlash('old_' . $k, $v);
            }
            $referer = $_SERVER['HTTP_REFERER'] ?? '/';
            header('Location: ' . $referer);
            exit;
        }
        return true;
    }

    /**
     * Magic getter: $request->nama === $request->input('nama')
     */
    public function __get(string $key)
    {
        return $this->input($key);
    }

    /**
     * Cek apakah URL saat ini cocok dengan pattern yang diberikan.
     */
    public function is(string $pattern): bool
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $uri = '/' . trim($uri, '/');

        if ($pattern === $uri) return true;

        $regex = '#^' . str_replace('\*', '.*', preg_quote($pattern, '#')) . '$#';
        return (bool) preg_match($regex, $uri);
    }
}

/**
 * Wrapper untuk file upload agar kompatibel dengan sintaks Laravel:
 * $request->file('foto')->store('uploads', 'public')
 */
class NativeUploadedFile
{
    private array $fileInfo;

    public function __construct(array $fileInfo)
    {
        $this->fileInfo = $fileInfo;
    }

    public function getClientOriginalName(): string
    {
        return $this->fileInfo['name'] ?? 'upload';
    }

    public function getClientOriginalExtension(): string
    {
        return pathinfo($this->fileInfo['name'] ?? '', PATHINFO_EXTENSION);
    }

    /**
     * Simpan file ke public/storage/{path}/
     * Returns: path relatif, misal "uploads/abc123.jpg"
     */
    public function store(string $path, string $disk = 'public'): string
    {
        $ext       = strtolower($this->getClientOriginalExtension());
        
        // Whitelist ekstensi aman untuk mencegah eksekusi file berbahaya (RCE)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar', 'mp4', 'txt'];
        if (!in_array($ext, $allowedExtensions)) {
            http_response_code(400);
            die("Error: Tipe file ." . htmlspecialchars($ext) . " tidak diizinkan untuk alasan keamanan.");
        }

        $filename  = uniqid('', true) . '.' . $ext;
        $storageDir = __DIR__ . '/../../public/storage/' . trim($path, '/');

        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $destination = $storageDir . '/' . $filename;
        move_uploaded_file($this->fileInfo['tmp_name'], $destination);

        return trim($path, '/') . '/' . $filename;
    }

    public function getSize(): int
    {
        return $this->fileInfo['size'] ?? 0;
    }

    public function isValid(): bool
    {
        return $this->fileInfo['error'] === UPLOAD_ERR_OK;
    }
}
