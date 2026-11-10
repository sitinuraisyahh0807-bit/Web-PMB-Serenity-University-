
<?php
require_once __DIR__ . '/config.php';

$error = '';
$mode = (($_GET['mode'] ?? $_POST['mode'] ?? '') === 'register')
    ? 'register'
    : 'login';

$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = db();
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Masukkan alamat email yang valid.');
        }

        if ($mode === 'login') {
            $stmt = $pdo->prepare(
                'SELECT * FROM users WHERE email = ? LIMIT 1'
            );
            $stmt->execute([$email]);
            $account = $stmt->fetch();

            if (
                !$account ||
                !password_verify($password, $account['password'])
            ) {
                throw new Exception('Email atau password tidak sesuai.');
            }

            session_regenerate_id(true);

            $_SESSION['user'] = [
                'id' => (int) $account['id'],
                'nama' => $account['nama'],
                'email' => $account['email'],
                'role' => $account['role'],
            ];

            header(
                'Location: ' .
                ($account['role'] === 'admin'
                    ? 'admin/index.php'
                    : 'dashboard.php')
            );
            exit;
        }

        // REGISTER: hanya email, password, konfirmasi password.
        $konfirmasi = $_POST['konfirmasi'] ?? '';

        if (strlen($password) < 8) {
            throw new Exception('Password minimal 8 karakter.');
        }

        if ($password !== $konfirmasi) {
            throw new Exception('Konfirmasi password tidak sama.');
        }

        $check = $pdo->prepare(
            'SELECT id FROM users WHERE email = ? LIMIT 1'
        );
        $check->execute([$email]);

        if ($check->fetch()) {
            throw new Exception(
                'Email sudah terdaftar. Silakan login atau gunakan email lain.'
            );
        }

        /*
         * Buat akun sementara.
         * Nama, NIK, telepon, dan biodata lain diisi di dashboard.
         */
        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'INSERT INTO users (nama, nik, email, telepon, password, role)
             VALUES (?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            'Calon Mahasiswa',
            null,
            $email,
            null,
            password_hash($password, PASSWORD_DEFAULT),
            'user'
        ]);

        $userId = (int) $pdo->lastInsertId();

        // Nomor tes dibuat setelah biodata lengkap di dashboard.
        $stmt = $pdo->prepare(
            'INSERT INTO applicants (user_id, jalur, status)
             VALUES (?, ?, ?)'
        );

        $stmt->execute([
            $userId,
            'Mandiri',
            'Biodata belum lengkap'
        ]);

        $pdo->commit();

        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id' => $userId,
            'nama' => 'Calon Mahasiswa',
            'email' => $email,
            'role' => 'user',
        ];

        header('Location: dashboard.php');
        exit;

    } catch (Throwable $e) {
        if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
            $pdo->rollBack();
        }

        if ($e instanceof PDOException) {
            error_log('[Serenity Auth] ' . $e->getMessage());

            if (($e->errorInfo[1] ?? null) === 1062) {
                $error = 'Email sudah terdaftar.';
            } else {
                $error = 'Terjadi kesalahan database. Periksa struktur tabel users dan applicants.';
            }
        } else {
            $error = $e->getMessage();
        }
    }
}

if (user()) {
    header(
        'Location: ' .
        (user()['role'] === 'admin' ? 'admin/index.php' : 'dashboard.php')
    );
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $mode === 'register' ? 'Register' : 'Login' ?> | Serenity University</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .login-card .form-group { margin-bottom: 16px; }
        .login-card .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
        }
        .login-card .form-group input {
            display: block;
            width: 100%;
            box-sizing: border-box;
            padding: 12px 14px;
            border: 1px solid #d8dce3;
            border-radius: 7px;
            font: inherit;
        }
        .login-card .login-error {
            margin-bottom: 16px;
            padding: 11px 13px;
            border-radius: 7px;
            background: #fff0f0;
            color: #8b1e1e;
        }
        .login-card .register-link {
            margin-top: 20px;
            text-align: center;
        }
        .login-card .register-link a {
            color: #173a67;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="background"></div>
    <div class="overlay"></div>

    <header class="main-header">
        <nav class="navbar">
            <a href="index.php" class="brand">
                <img src="images/logo-univ.png" alt="Logo Serenity University">
                <div class="brand-text">
                    <h2>SERENITY</h2>
                    <h2>UNIVERSITY</h2>
                </div>
            </a>

            <div class="nav-right">
                <div class="nav-menu">
                    <a href="index.php">BERANDA</a>
                    <a href="tentang-kami.php">TENTANG KAMI</a>
                    <a href="prodi.php">PRODI</a>
                    <a href="contact.php">CONTACT</a>
                </div>
                <a href="login.php" class="login-btn active-login">
                    <i class="fa-regular fa-user"></i> LOGIN
                </a>
            </div>
        </nav>
    </header>

    <main class="login-page">
        <section class="welcome-section">
            <div class="welcome-content">
                <div class="small-title">
                    <span>SELAMAT DATANG DI</span>
                    <div class="line"></div>
                </div>

                <h1>Serenity<br>University</h1>
                <div class="gold-line"></div>

                <p class="welcome-description">
                    Bergabunglah bersama kami dan wujudkan masa depan
                    cerah melalui pendidikan berkualitas dan inovatif.
                </p>

                <div class="registration-info">
                    <div class="graduation-icon">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div class="registration-text">
                        <strong>Pendaftaran Mahasiswa Baru</strong>
                        <span>Tahun Akademik 2026/2027 · Jalur Mandiri</span>
                    </div>
                </div>

                <div class="quote">
                    <span>❝</span>
                    <i>Your Future, Our Priority</i>
                    <span>❞</span>
                </div>
            </div>
        </section>

        <section class="login-card">
            <div class="login-logo">
                <img src="images/logo-univ.png" alt="Logo Serenity University">
            </div>

            <h1><?= $mode === 'register' ? 'REGISTER' : 'LOGIN' ?></h1>
            <div class="title-decoration"><span></span></div>

            <?php if ($error !== ''): ?>
                <div class="login-error" role="alert"><?= e($error) ?></div>
            <?php endif; ?>

            <form class="login-form" method="post" action="login.php">
                <input type="hidden" name="mode" value="<?= e($mode) ?>">

                <div class="form-group">
                    <label for="email">Email Aktif</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        placeholder="nama@email.com"
                        maxlength="190"
                        autocomplete="email"
                        value="<?= e($email) ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Masukkan password"
                        minlength="<?= $mode === 'register' ? '8' : '1' ?>"
                        autocomplete="<?= $mode === 'register' ? 'new-password' : 'current-password' ?>"
                        required
                    >
                </div>

                <?php if ($mode === 'register'): ?>
                    <div class="form-group">
                        <label for="konfirmasi">Konfirmasi Password</label>
                        <input
                            id="konfirmasi"
                            type="password"
                            name="konfirmasi"
                            placeholder="Ulangi password"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                    </div>
                <?php endif; ?>

                <button type="submit" class="submit-login">
                    <?= $mode === 'register' ? 'DAFTAR' : 'LOGIN' ?>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <?php if ($mode === 'register'): ?>
                <p class="register-link">
                    Sudah punya akun?
                    <a href="login.php">Login di sini</a>
                </p>
            <?php else: ?>
                <p class="register-link">
                    Belum punya akun?
                    <a href="login.php?mode=register">Register di sini</a>
                </p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
