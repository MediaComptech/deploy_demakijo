<?php

namespace App\Core;

/**
 * Kelas Router
 * 
 * Sistem routing sederhana yang memetakan URL ke Controller dan Method.
 */
class Router
{
    private static $routes = [];

    /**
     * Mendaftarkan route GET
     */
    public static function get($uri, $action)
    {
        self::$routes['GET'][$uri] = $action;
    }

    /**
     * Mendaftarkan route POST
     */
    public static function post($uri, $action)
    {
        self::$routes['POST'][$uri] = $action;
    }

    /**
     * Menjalankan routing dengan mencocokkan URI yang diakses
     */
    public static function dispatch()
    {
        // Mendapatkan URL saat ini (tanpa query string)
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Menghapus basepath jika aplikasi tidak berada di root folder
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && $scriptName !== '\\') {
            $uri = str_replace($scriptName, '', $uri);
        }
        
        $uri = rtrim($uri, '/');
        if (empty($uri)) $uri = '/';

        // Cegah caching (browser & LiteSpeed Cache) untuk semua rute admin/dashboard
        if (str_starts_with($uri, '/admin') || str_starts_with($uri, '/dashboard') || $uri === '/admin') {
            if (!headers_sent()) {
                header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
                header("Cache-Control: post-check=0, pre-check=0", false);
                header("Pragma: no-cache");
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("X-LiteSpeed-Cache-Control: no-cache");
            }
        }

        $method = $_SERVER['REQUEST_METHOD'];

        // Log POST request data for tracking & auto-clear cache for instant local testing updates
        if ($method === 'POST') {
            // Verify CSRF Token
            \App\Core\Security::verifyCsrfToken();

            $postData = $_POST;
            if (isset($postData['password'])) {
                $postData['password'] = '********';
            }
            self::logAction('REQUEST_POST', [
                'post' => $postData,
                'files' => array_map(function($file) {
                    return [
                        'name' => $file['name'] ?? '',
                        'type' => $file['type'] ?? '',
                        'size' => $file['size'] ?? 0,
                        'error' => $file['error'] ?? 0
                    ];
                }, $_FILES)
            ]);

            // Auto-clear BladeOne view cache on POST (create/update/delete)
            $cacheDir = __DIR__ . '/../../storage/cache';
            if (is_dir($cacheDir)) {
                $files1 = glob($cacheDir . '/*.php') ?: [];
                $files2 = glob($cacheDir . '/*.bladec') ?: [];
                foreach (array_merge($files1, $files2) as $file) {
                    if (is_file($file) && basename($file) !== '.gitkeep') {
                        @unlink($file);
                    }
                }
            }
        }

        // Deteksi Ctrl + F5 (Cache-Control: no-cache atau Pragma: no-cache)
        $isCtrlF5 = (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'no-cache') || 
                    (isset($_SERVER['HTTP_PRAGMA']) && $_SERVER['HTTP_PRAGMA'] === 'no-cache');

        // Log GET requests (hanya untuk rute admin/dashboard agar file log tetap ringkas)
        if ($method === 'GET' && (str_starts_with($uri, '/admin') || str_starts_with($uri, '/dashboard') || $uri === '/admin')) {
            self::logAction($isCtrlF5 ? 'GET_CTRL_F5' : 'GET_NORMAL', [
                'cache_control' => $_SERVER['HTTP_CACHE_CONTROL'] ?? 'none',
                'pragma' => $_SERVER['HTTP_PRAGMA'] ?? 'none',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);

            // Jika user menekan Ctrl + F5 di browser, kita bantu hapus server-side BladeOne cache
            if ($isCtrlF5) {
                $cacheDir = __DIR__ . '/../../storage/cache';
                if (is_dir($cacheDir)) {
                    $files1 = glob($cacheDir . '/*.php') ?: [];
                    $files2 = glob($cacheDir . '/*.bladec') ?: [];
                    foreach (array_merge($files1, $files2) as $file) {
                        if (is_file($file) && basename($file) !== '.gitkeep') {
                            @unlink($file);
                        }
                    }
                }
            }
        }

        // Cek apakah method spoofing digunakan (misal: untuk PUT/DELETE via POST)
        if ($method === 'POST' && isset($_POST['_method'])) {
            $spoofed = strtoupper($_POST['_method']);
            if (isset(self::$routes[$spoofed])) {
                $method = $spoofed;
            }
        }

        // Cari route yang cocok
        if (isset(self::$routes[$method])) {
            foreach (self::$routes[$method] as $routeUri => $action) {
                // Konversi parameter route (misal: {id} menjadi regex) — dukung huruf, angka, underscore, dan hyphen
                $routeRegex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_\-]+)', $routeUri);
                $routeRegex = "#^" . $routeRegex . "$#";

                if (preg_match($routeRegex, $uri, $matches)) {
                    array_shift($matches); // Hapus hasil match lengkap, sisakan parameter

                    // Action bisa berupa fungsi anonymous (closure) atau string Controller@method
                    if (is_callable($action)) {
                        http_response_code(200);
                        self::logAction('DISPATCH_CLOSURE', ['uri' => $routeUri]);
                        call_user_func_array($action, $matches);
                        return;
                    }

                    if (is_string($action)) {
                        list($controller, $function) = explode('@', $action);
                        $controllerClass = "\\App\\Controllers\\" . $controller;

                        if (class_exists($controllerClass)) {
                            $instance = new $controllerClass();
                            if (method_exists($instance, $function)) {
                                // Dependency Injection untuk Request
                                $reflection = new \ReflectionMethod($instance, $function);
                                $params = $reflection->getParameters();
                                $finalArgs = [];
                                $matchIndex = 0;
                                
                                foreach ($params as $param) {
                                    $type = $param->getType();
                                    $typeName = $type ? $type->getName() : '';
                                    if ($typeName === 'App\Core\Request' || $typeName === 'Illuminate\Http\Request') {
                                        $finalArgs[] = new \App\Core\Request();
                                    } else {
                                        $finalArgs[] = $matches[$matchIndex] ?? null;
                                        $matchIndex++;
                                    }
                                }
                                
                                http_response_code(200);
                                self::logAction('DISPATCH_CONTROLLER', [
                                    'controller' => $controllerClass,
                                    'method' => $function,
                                    'args' => $finalArgs
                                ]);
                                try {
                                    call_user_func_array([$instance, $function], $finalArgs);
                                    self::logAction('SUCCESS', [
                                        'controller' => $controllerClass,
                                        'method' => $function
                                    ]);
                                } catch (\Illuminate\Database\QueryException $e) {
                                    error_log('[DB ERROR] ' . $e->getMessage());
                                    self::logAction('DB_ERROR', [
                                        'message' => $e->getMessage(),
                                        'sql' => $e->getSql(),
                                        'bindings' => $e->getBindings()
                                    ]);
                                    $msg = 'Terjadi kesalahan database.';
                                    $errMsg = $e->getMessage();
                                    if (str_contains($errMsg, 'Duplicate entry') || str_contains($errMsg, '1062')) {
                                        $msg = 'Data sudah ada (duplikat). Periksa kembali inputan Anda.';
                                    } elseif (str_contains($errMsg, 'cannot be null') || str_contains($errMsg, '1048')) {
                                        $msg = 'Semua field wajib harus diisi dengan benar.';
                                    } elseif (str_contains($errMsg, "doesn't have a default value") || str_contains($errMsg, '1364')) {
                                        $msg = 'Beberapa field wajib belum diisi. Periksa kembali form Anda.';
                                    } elseif (str_contains($errMsg, 'foreign key constraint') || str_contains($errMsg, '1452')) {
                                        $msg = 'Data referensi tidak ditemukan. Pastikan kategori/relasi sudah ada.';
                                    }
                                    \App\Core\Session::setFlash('error', $msg);
                                    $referer = $_SERVER['HTTP_REFERER'] ?? '/dashboard';
                                    if (!headers_sent()) { header('Location: ' . $referer); exit; }
                                } catch (\Throwable $e) {
                                    error_log('[APP ERROR] ' . get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
                                    self::logAction('APP_ERROR', [
                                        'class' => get_class($e),
                                        'message' => $e->getMessage(),
                                        'file' => $e->getFile(),
                                        'line' => $e->getLine(),
                                        'trace' => substr($e->getTraceAsString(), 0, 1000)
                                    ]);
                                    \App\Core\Session::setFlash('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
                                    $referer = $_SERVER['HTTP_REFERER'] ?? '/dashboard';
                                    if (!headers_sent()) { header('Location: ' . $referer); exit; }
                                }

                                return;
                            }
                        }
                    }
                }
            }
        }

        // Jika tidak ada route yang cocok, tampilkan 404
        self::abort(404);
    }

    /**
     * Menampilkan halaman error
     */
    public static function abort($code = 404)
    {
        http_response_code($code);
        if ($code == 404) {
            echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
        } else {
            echo "<h1>{$code} - Terjadi Kesalahan</h1>";
        }
        exit;
    }

    /**
     * Helper untuk mencatat log aktivitas aksess form ke file
     */
    private static function logAction($type, $data)
    {
        $logFile = __DIR__ . '/../../storage/action_tracker.log';
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $userId = $_SESSION['user_id'] ?? 'Guest';
        
        $logEntry = [
            'timestamp' => $timestamp,
            'ip' => $ip,
            'method' => $method,
            'uri' => $uri,
            'user_id' => $userId,
            'type' => $type,
            'data' => $data
        ];
        
        @file_put_contents($logFile, json_encode($logEntry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
    }
}
