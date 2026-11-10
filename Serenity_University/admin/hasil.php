<?php

require_once __DIR__ . '/_init.php';

$rows = $pdo->query(
    "SELECT
        ea.id AS attempt_id,
        a.id AS applicant_id,
        a.nomor_tes,
        u.nama,
        u.email,
        a.prodi,
        a.status,
        a.nim,
        ea.nilai,
        ea.lulus,
        ea.submitted_at,
        erv.status AS validation_status,
        erv.validated_at
     FROM exam_attempts ea
     JOIN applicants a ON a.id = ea.applicant_id
     JOIN users u ON u.id = a.user_id
     LEFT JOIN exam_result_validations erv ON erv.attempt_id = ea.id
     ORDER BY ea.submitted_at DESC"
)->fetchAll(PDO::FETCH_ASSOC);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        check_csrf();
        $attemptId = (int) ($_POST['attempt_id'] ?? 0);
        if ($attemptId < 1) {
            throw new Exception('Data hasil ujian tidak valid.');
        }

        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'SELECT ea.id, ea.applicant_id, ea.lulus, a.nim
             FROM exam_attempts ea
             JOIN applicants a ON a.id = ea.applicant_id
             WHERE ea.id = ? FOR UPDATE'
        );
        $stmt->execute([$attemptId]);
        $attempt = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$attempt) {
            throw new Exception('Data ujian tidak ditemukan.');
        }

        $validation = $pdo->prepare(
            'SELECT status FROM exam_result_validations WHERE attempt_id = ? FOR UPDATE'
        );
        $validation->execute([$attemptId]);
        $currentStatus = $validation->fetchColumn();

        if ($currentStatus === 'disetujui') {
            throw new Exception('Hasil ini sudah divalidasi.');
        }

        $saveValidation = $pdo->prepare(
            "INSERT INTO exam_result_validations
                (attempt_id, status, validated_by, validated_at)
             VALUES (?, 'disetujui', ?, NOW())
             ON DUPLICATE KEY UPDATE
                status = 'disetujui',
                validated_by = VALUES(validated_by),
                validated_at = NOW()"
        );
        $saveValidation->execute([$attemptId, (int) user()['id']]);

        if ((int) $attempt['lulus'] === 1) {
            $nim = $attempt['nim'] ?: (
                'SU-' . date('Y') . '-' .
                str_pad((string) $attempt['applicant_id'], 6, '0', STR_PAD_LEFT)
            );
            $update = $pdo->prepare(
                "UPDATE applicants SET status = 'Lulus', nim = ? WHERE id = ?"
            );
            $update->execute([$nim, $attempt['applicant_id']]);
        } else {
            $update = $pdo->prepare(
                "UPDATE applicants SET status = 'Tidak lulus' WHERE id = ?"
            );
            $update->execute([$attempt['applicant_id']]);
        }

        $pdo->commit();
        flash('Hasil seleksi berhasil divalidasi. Peserta sekarang dapat melihat pengumuman.');
        header('Location: hasil.php');
        exit;

    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $error = $e instanceof PDOException
            ? 'Validasi gagal. Pastikan migrasi database sudah dijalankan.'
            : $e->getMessage();
    }
}

if ($error !== '') {
    $msg = $error;
}

$rows = $pdo->query(
    "SELECT
        ea.id AS attempt_id,
        a.id AS applicant_id,
        a.nomor_tes,
        u.nama,
        u.email,
        a.prodi,
        a.status,
        a.nim,
        ea.nilai,
        ea.lulus,
        ea.submitted_at,
        erv.status AS validation_status,
        erv.validated_at
     FROM exam_attempts ea
     JOIN applicants a ON a.id = ea.applicant_id
     JOIN users u ON u.id = a.user_id
     LEFT JOIN exam_result_validations erv ON erv.attempt_id = ea.id
     ORDER BY ea.submitted_at DESC"
)->fetchAll(PDO::FETCH_ASSOC);

admin_header('Validasi Hasil Seleksi');
?>

<section class="panel">
    <p>Nilai dan hasil perhitungan hanya ditampilkan pada panel admin. Setelah diperiksa, admin dapat memvalidasi hasil agar pengumuman terbuka pada dashboard peserta.</p>

    <?php if (!empty($msg)): ?>
        <div class="alert"><?= e($msg) ?></div>
    <?php endif; ?>

    <div class="tablewrap">
        <table>
            <thead>
                <tr>
                    <th>Nomor Tes</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Program Studi</th>
                    <th>Nilai</th>
                    <th>Prediksi Sistem</th>
                    <th>Status Validasi</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="9" style="text-align:center;padding:24px;">
                            Belum ada hasil tes.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['nomor_tes'] ?? '-') ?></td>
                            <td><?= e($row['nama']) ?></td>
                            <td><?= e($row['email']) ?></td>
                            <td><?= e($row['prodi']) ?></td>
                            <td><?= e($row['nilai'] ?? 0) ?>/100</td>
                            <td>
                                <?php if ((int) $row['lulus'] === 1): ?>
                                    <span class="badge" style="background:#16a34a;color:#fff;">Memenuhi nilai minimum</span>
                                <?php else: ?>
                                    <span class="badge" style="background:#dc2626;color:#fff;">Tidak memenuhi nilai minimum</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['validation_status'] === 'disetujui'): ?>
                                    <span class="badge" style="background:#16a34a;color:#fff;">Sudah divalidasi</span>
                                <?php else: ?>
                                    <span class="badge" style="background:#fff3cd;color:#795900;">Menunggu validasi</span>
                                <?php endif; ?>
                            </td>
                            <td><?= e($row['submitted_at']) ?></td>
                            <td>
                                <?php if ($row['validation_status'] !== 'disetujui'): ?>
                                    <form method="post" onsubmit="return confirm('Validasi hasil seleksi peserta ini?');">
                                        <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
                                        <input type="hidden" name="attempt_id" value="<?= (int) $row['attempt_id'] ?>">
                                        <button class="btn gold" type="submit">Validasi</button>
                                    </form>
                                <?php else: ?>
                                    Selesai
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php admin_footer(); ?>
