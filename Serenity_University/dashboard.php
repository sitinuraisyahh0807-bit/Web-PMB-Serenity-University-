<?php
require_once __DIR__ . '/config.php';

require_login();

if (user()['role'] === 'admin') {
    header('Location: /Serenity_University/admin/index.php');
    exit;
}

$pdo = db();
$userId = (int) user()['id'];
$msg = flash();
$error = '';

/*
|--------------------------------------------------------------------------
| Ambil data calon mahasiswa
|--------------------------------------------------------------------------
*/
function getApplicant(PDO $pdo, int $userId): ?array
{
    $stmt = $pdo->prepare(
        "SELECT
            a.*,
            u.nama,
            u.email,
            u.nik,
            u.telepon,
            ea.nilai,
            ea.lulus
         FROM applicants a
         JOIN users u ON u.id = a.user_id
         LEFT JOIN exam_attempts ea ON ea.applicant_id = a.id
         WHERE u.id = ?
         ORDER BY ea.id DESC
         LIMIT 1"
    );

    $stmt->execute([$userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ?: null;
}

try {
    $a = getApplicant($pdo, $userId);

    if (!$a) {
        throw new Exception(
            'Data pendaftaran tidak ditemukan. Silakan hubungi admin.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Proses formulir
    |--------------------------------------------------------------------------
    */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        check_csrf();

        $action = $_POST['action'] ?? '';

        /*
        | Simpan biodata
        */
        if ($action === 'biodata') {
            if ($a['status'] !== 'Biodata belum lengkap') {
                throw new Exception(
                    'Biodata sudah disimpan dan tidak dapat diubah melalui formulir ini.'
                );
            }

            $nama = trim($_POST['nama'] ?? '');
            $nik = preg_replace('/\D+/', '', $_POST['nik'] ?? '');
            $telepon = trim($_POST['telepon'] ?? '');
            $prodi = trim($_POST['prodi'] ?? '');
            $alamat = trim($_POST['alamat'] ?? '');
            $tanggalLahir = $_POST['tanggal_lahir'] ?? '';

            if ($nama === '' || mb_strlen($nama) < 3) {
                throw new Exception('Nama lengkap wajib diisi minimal 3 karakter.');
            }
            if (!preg_match('/^\d{16}$/', $nik)) {
                throw new Exception('NIK harus terdiri dari 16 angka.');
            }
            if ($telepon === '' || !preg_match('/^[0-9+().\- ]{8,20}$/', $telepon)) {
                throw new Exception('Nomor telepon tidak valid.');
            }

            $daftarProdi = [
                'Teknik Informatika',
                'Sistem Informasi',
                'Ilmu Komputer',
                'Rekayasa Perangkat Lunak',
                'Manajemen Informatika',
                'Cyber Security'
            ];

            if (!in_array($prodi, $daftarProdi, true)) {
                throw new Exception(
                    'Silakan pilih program studi yang tersedia.'
                );
            }

            if ($alamat === '') {
                throw new Exception('Alamat wajib diisi.');
            }

            if ($tanggalLahir !== '') {
                $date = DateTime::createFromFormat(
                    'Y-m-d',
                    $tanggalLahir
                );

                if (!$date || $date->format('Y-m-d') !== $tanggalLahir) {
                    throw new Exception('Tanggal lahir tidak valid.');
                }
            }

            $pdo->beginTransaction();
            $userUpdate = $pdo->prepare(
                'UPDATE users SET nama = ?, nik = ?, telepon = ? WHERE id = ?'
            );
            $userUpdate->execute([$nama, $nik, $telepon, $userId]);

            $nomorTes = 'SU-' . date('Y') . '-' . str_pad((string)$a['id'], 6, '0', STR_PAD_LEFT);
            $stmt = $pdo->prepare(
                "UPDATE applicants
                 SET nomor_tes = ?, prodi = ?, alamat = ?, tanggal_lahir = ?, status = 'Siap mengikuti ujian'
                 WHERE user_id = ? AND status = 'Biodata belum lengkap'"
            );
            $stmt->execute([$nomorTes, $prodi, $alamat, $tanggalLahir !== '' ? $tanggalLahir : null, $userId]);
            $pdo->commit();

            $_SESSION['user']['nama'] = $nama;
            flash('Biodata berhasil disimpan. Nomor tes Anda: ' . $nomorTes . '. Anda dapat langsung mengikuti ujian seleksi.');
            header('Location: dashboard.php');
            exit;
        }

        throw new Exception('Permintaan tidak dikenali.');
    }

    /*
    |--------------------------------------------------------------------------
    | Muat ulang data terbaru
    |--------------------------------------------------------------------------
    */
    $a = getApplicant($pdo, $userId);

    if (!$a) {
        throw new Exception('Data pendaftaran tidak ditemukan.');
    }

    $attemptStmt = $pdo->prepare(
        'SELECT id FROM exam_attempts WHERE applicant_id = ? LIMIT 1'
    );

    $attemptStmt->execute([$a['id']]);
    $hasAttempt = (bool) $attemptStmt->fetchColumn();

    $validationStmt = $pdo->prepare(
        'SELECT erv.status, erv.validated_at
         FROM exam_result_validations erv
         JOIN exam_attempts ea ON ea.id = erv.attempt_id
         WHERE ea.applicant_id = ?
         ORDER BY ea.id DESC LIMIT 1'
    );
    $validationStmt->execute([$a['id']]);
    $validation = $validationStmt->fetch(PDO::FETCH_ASSOC) ?: null;

} catch (Throwable $e) {
    $error = $e instanceof PDOException
        ? 'Terjadi kesalahan saat mengakses database. Periksa struktur tabel dan konfigurasi database.'
        : $e->getMessage();

    if (!isset($a) || !$a) {
        $a = [
            'nomor_tes' => '-',
            'prodi' => null,
            'status' => '-',
            'nilai' => null,
            'lulus' => 0,
            'nim' => null,
            'alamat' => '',
            'tanggal_lahir' => null,
            'nama' => '', 'nik' => '', 'telepon' => ''
        ];
    }

    $hasAttempt = false;
    $validation = null;
}

$nilai = $a['nilai'] ?? null;
$lulus = (int) ($a['lulus'] ?? 0) === 1;
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Pendaftar | Serenity University</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body class="app">

<div class="layout">

    <aside class="sidebar">
        <div class="brand">
            SERENITY<br>
            UNIVERSITY
        </div>

        <nav class="nav">
            <a class="active" href="dashboard.php">Dashboard</a>
            <a href="dashboard.php#biodata">Biodata & Program Studi</a>
            <a href="ujian.php">Ujian Seleksi</a>
            <a href="dashboard.php#hasil">Hasil Tes</a>
            <a href="dashboard.php#hasil">Hasil Tes</a>
            <a href="logout.php">Keluar</a>
        </nav>
    </aside>

    <main class="main">

        <header class="topbar">
            <strong>Portal Calon Mahasiswa · Jalur Mandiri</strong>
            <span><?= e(user()['nama']) ?></span>
        </header>

        <div class="content">

            <h1>Halo, <?= e(user()['nama']) ?>!</h1>

            <p class="muted">
                Pantau seluruh tahapan pendaftaran mahasiswa baru di sini.
            </p>

            <?php if ($msg): ?>
                <div class="alert">
                    <?= e($msg) ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <div class="cards">

                <div class="stat">
                    Nomor Tes
                    <strong><?= e($a['nomor_tes'] ?? '-') ?></strong>
                </div>

                <div class="stat">
                    Jalur Penerimaan
                    <strong>Mandiri</strong>
                </div>

                <div class="stat">
                    Program Studi
                    <strong style="font-size:18px">
                        <?= e($a['prodi'] ?: 'Belum dipilih') ?>
                    </strong>
                </div>

                <div class="stat">
                    Status
                    <strong style="font-size:18px">
                        <?= e($a['status']) ?>
                    </strong>
                </div>

            </div>

            <div class="dashboard-footer" style="margin-top: 20px;">
                <a href="index.php" class="btn">
                    <i class="fa-solid fa-house"></i>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- BIODATA -->
            <section class="panel" id="biodata" style="margin-top:18px">

                <h2>Lengkapi Biodata</h2>

                <?php if ($a['status'] === 'Biodata belum lengkap'): ?>

                    <form method="post">
                        <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
                        <input type="hidden" name="action" value="biodata">

                        <div class="formgrid">
                            <div>
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama" value="<?= e($a['nama'] ?? '') ?>" required maxlength="120">
                            </div>
                            <div>
                                <label>NIK (16 digit)</label>
                                <input type="text" name="nik" inputmode="numeric" pattern="[0-9]{16}" maxlength="16" required value="<?= e($a['nik'] ?? '') ?>">
                            </div>
                            <div>
                                <label>Nomor Telepon</label>
                                <input type="tel" name="telepon" required maxlength="30" value="<?= e($a['telepon'] ?? '') ?>">
                            </div>
                            <div>
                                <label>Program Studi Pilihan</label>

                                <select name="prodi" required>
                                    <option value="">Pilih program studi</option>

                                    <?php
                                    $daftarProdi = [
                                        'Teknik Informatika',
                                        'Sistem Informasi',
                                        'Ilmu Komputer',
                                        'Rekayasa Perangkat Lunak',
                                        'Manajemen Informatika',
                                        'Cyber Security'
                                    ];

                                    foreach ($daftarProdi as $p):
                                    ?>
                                        <option value="<?= e($p) ?>">
                                            <?= e($p) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label>Tanggal Lahir</label>
                                <input
                                    type="date"
                                    name="tanggal_lahir"
                                    value="<?= e($a['tanggal_lahir'] ?? '') ?>"
                                >
                            </div>

                            <div style="grid-column:1/-1">
                                <label>Alamat</label>
                                <textarea
                                    name="alamat"
                                    rows="3"
                                    required
                                ><?= e($a['alamat'] ?? '') ?></textarea>
                            </div>

                        </div>

                        <p style="margin-top: 15px;">
                            <button class="btn" type="submit">
                                Simpan Biodata
                            </button>
                        </p>
                    </form>

                <?php else: ?>

                    <p>
                        <b>Program studi:</b>
                        <?= e($a['prodi'] ?: 'Belum dipilih') ?>
                    </p>

                    <p>
                        <b>Nomor tes:</b>
                        <?= e($a['nomor_tes']) ?>
                    </p>

                <?php endif; ?>

            </section>

            <!-- UJIAN SELEKSI -->
<section class="panel" id="ujian" style="margin-top:18px">
    <h2>Ujian Seleksi</h2>
    <p>Ujian Jalur Mandiri terdiri dari 10 soal pilihan ganda sesuai program studi. Nilai kelulusan minimal 75 dan kesempatan ujian hanya satu kali.</p>

    <?php if (empty($a['prodi'])): ?>
        <p class="muted">Lengkapi biodata dan pilih program studi terlebih dahulu.</p>
    <?php elseif (!$hasAttempt): ?>
        <p class="muted">Biodata telah lengkap. Anda dapat langsung mengikuti ujian seleksi.</p>
        <a class="btn" href="ujian.php">Mulai Ujian</a>
    <?php else: ?>
        <p>Ujian telah dikerjakan. Hasil seleksi sedang menunggu validasi admin.</p>
        <span class="badge" style="background:#fff3cd;color:#795900">Menunggu validasi admin</span>
    <?php endif; ?>
</section>

            <!-- HASIL TES -->
<section class="panel" id="hasil" style="margin-top:18px">
    <h2>Hasil Tes</h2>

    <?php if (!$hasAttempt): ?>
        <p class="muted">Hasil akan tersedia setelah ujian diselesaikan.</p>
    <?php elseif (!$validation || $validation['status'] !== 'disetujui'): ?>
        <p>Ujian telah selesai. Hasil sedang menunggu validasi admin.</p>
        <span class="badge" style="background:#fff3cd;color:#795900">Menunggu validasi admin</span>
        <p class="muted">Nilai dan status kelulusan belum ditampilkan sampai admin menyelesaikan validasi.</p>
    <?php else: ?>
        <p>Hasil seleksi Anda telah divalidasi oleh admin.</p>
        <a class="btn gold" href="hasil-seleksi.php">Lihat Hasil Seleksi</a>
    <?php endif; ?>
</section>

        </div>
    </main>
</div>

</body>
</html>