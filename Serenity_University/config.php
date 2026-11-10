
<?php

session_start();

/*
|--------------------------------------------------------------------------
| KONFIGURASI DATABASE
|--------------------------------------------------------------------------
*/

const DB_HOST = 'localhost';
const DB_NAME = 'serenity_pmb';
const DB_USER = 'root';
const DB_PASS = '';

/*
|--------------------------------------------------------------------------
| KONEKSI DATABASE
|--------------------------------------------------------------------------
*/

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST
             . ';dbname=' . DB_NAME
             . ';charset=utf8mb4';

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $pdo = new PDO(
            $dsn,
            DB_USER,
            DB_PASS,
            $options
        );
    }

    return $pdo;
}

/*
|--------------------------------------------------------------------------
| FUNGSI KEAMANAN OUTPUT
|--------------------------------------------------------------------------
*/

function e($value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

/*
|--------------------------------------------------------------------------
| DATA PENGGUNA YANG SEDANG LOGIN
|--------------------------------------------------------------------------
*/

function user(): ?array
{
    return $_SESSION['user'] ?? null;
}

/*
|--------------------------------------------------------------------------
| ALAMAT LOGIN
|--------------------------------------------------------------------------
*/

function login_url(): string
{
    return '/Serenity_University/login.php';
}

/*
|--------------------------------------------------------------------------
| PEMERIKSAAN LOGIN PENGGUNA
|--------------------------------------------------------------------------
*/

function require_login(): void
{
    if (!user()) {
        header('Location: ' . login_url());
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| PEMERIKSAAN AKSES ADMIN
|--------------------------------------------------------------------------
*/

function require_admin(): void
{
    $currentUser = user();

    if (
        !$currentUser ||
        ($currentUser['role'] ?? '') !== 'admin'
    ) {
        header('Location: ' . login_url());
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| PESAN NOTIFIKASI SESSION
|--------------------------------------------------------------------------
*/

function flash($message = null)
{
    if ($message !== null) {
        $_SESSION['flash'] = $message;
        return null;
    }

    $message = $_SESSION['flash'] ?? '';

    unset($_SESSION['flash']);

    return $message;
}

/*
|--------------------------------------------------------------------------
| TOKEN CSRF
|--------------------------------------------------------------------------
*/

function csrf(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(
            random_bytes(24)
        );
    }

    return $_SESSION['csrf'];
}

/*
|--------------------------------------------------------------------------
| VALIDASI TOKEN CSRF
|--------------------------------------------------------------------------
*/

function check_csrf(): void
{
    $sessionToken = $_SESSION['csrf'] ?? '';
    $formToken = $_POST['csrf'] ?? '';

    if (
        !is_string($sessionToken) ||
        !is_string($formToken) ||
        $sessionToken === '' ||
        !hash_equals($sessionToken, $formToken)
    ) {
        http_response_code(403);

        exit(
            'Permintaan tidak valid. ' .
            'Muat ulang halaman dan coba lagi.'
        );
    }
}

/* Pengaturan sistem PMB */
function setting(string $key, string $default = '0'): string
{
    $stmt = db()->prepare('SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1');
    $stmt->execute([$key]);
    $value = $stmt->fetchColumn();
    return $value === false ? $default : (string) $value;
}

function rupiah($amount): string
{
    return 'Rp' . number_format((float) $amount, 0, ',', '.');
}
