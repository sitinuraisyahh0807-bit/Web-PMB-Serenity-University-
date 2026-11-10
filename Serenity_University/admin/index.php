<?php

require_once __DIR__ . '/_init.php';

$totalPendaftar = (int) $pdo
    ->query('SELECT COUNT(*) FROM applicants')
    ->fetchColumn();

$totalSudahTes = (int) $pdo
    ->query('SELECT COUNT(*) FROM exam_attempts')
    ->fetchColumn();

$totalLulus = (int) $pdo
    ->query('SELECT COUNT(*) FROM exam_attempts WHERE lulus = 1')
    ->fetchColumn();

$totalDaftarUlang = (int) $pdo
    ->query("SELECT COUNT(*) FROM exam_result_validations WHERE status = 'menunggu'")
    ->fetchColumn();

$sql = '
    SELECT
        a.nomor_tes,
        u.nama,
        a.prodi,
        a.status,
        a.created_at
    FROM applicants AS a
    INNER JOIN users AS u ON u.id = a.user_id
    ORDER BY a.id DESC
    LIMIT 8
';

$latestApplicants = $pdo
    ->query($sql)
    ->fetchAll(PDO::FETCH_ASSOC);

admin_header('Dashboard Admin');
?>

<p class="muted">
    Ringkasan monitoring Penerimaan Mahasiswa Baru
    tahun akademik 2026/2027.
</p>

<div class="cards">
    <div class="stat">
        Total Pendaftar
        <strong><?= $totalPendaftar ?></strong>
    </div>

    <div class="stat">
        Sudah Tes
        <strong><?= $totalSudahTes ?></strong>
    </div>

    <div class="stat">
        Lulus Tes
        <strong><?= $totalLulus ?></strong>
    </div>

    <div class="stat">
        Menunggu Validasi
        <strong><?= $totalDaftarUlang ?></strong>
    </div>
</div>

<section class="panel">
    <h2>Menu Cepat</h2>

    <div class="quick">
        <a class="btn" href="pendaftar.php">
            Kelola Calon Maba
        </a>

        <a class="btn" href="soal.php">
            Kelola Soal Tes
        </a>

        <a class="btn" href="hasil.php">
            Lihat Hasil Tes
        </a>

        <a class="btn gold" href="hasil.php">Validasi Hasil Seleksi</a>
    </div>
</section>

<section class="panel" style="margin-top: 18px;">
    <h2>Pendaftar Terbaru</h2>

    <div class="tablewrap">
        <table>
            <thead>
                <tr>
                    <th>Nomor Tes</th>
                    <th>Nama</th>
                    <th>Program Studi</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($latestApplicants)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 24px;">
                            Belum ada calon mahasiswa yang mendaftar.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($latestApplicants as $applicant): ?>
                        <tr>
                            <td><?= e($applicant['nomor_tes']) ?></td>
                            <td><?= e($applicant['nama']) ?></td>
                            <td><?= e($applicant['prodi'] ?? '-') ?></td>
                            <td>
                                <span class="badge">
                                    <?= e($applicant['status']) ?>
                                </span>
                            </td>
                            <td><?= e($applicant['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php admin_footer(); ?>