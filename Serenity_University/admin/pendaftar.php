<?php

require_once __DIR__ . '/_init.php';

$programs = [
    'Teknik Informatika',
    'Sistem Informasi',
    'Ilmu Komputer',
    'Rekayasa Perangkat Lunak',
    'Manajemen Informatika',
    'Cyber Security'
];



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        check_csrf();

        $action = $_POST['action'] ?? '';

        if ($action === 'update') {
            $id = (int) ($_POST['id'] ?? 0);
            $prodi = $_POST['prodi'] ?? '';

            if ($id < 1) {
                throw new Exception('ID pendaftar tidak valid.');
            }

            if ($prodi !== '' && !in_array($prodi, $programs, true)) {
                throw new Exception('Program studi tidak valid.');
            }

            $check = $pdo->prepare('SELECT status FROM applicants WHERE id = ?');
            $check->execute([$id]);
            $currentStatus = $check->fetchColumn();
            if ($currentStatus !== 'Biodata belum lengkap') {
                throw new Exception('Program studi terkunci setelah biodata dikirim. Status seleksi diperbarui melalui ujian dan validasi hasil oleh admin.');
            }
            $stmt = $pdo->prepare('UPDATE applicants SET prodi = ? WHERE id = ?');
            $stmt->execute([$prodi !== '' ? $prodi : null, $id]);
            flash('Program studi pendaftar diperbarui.');

        } elseif ($action === 'delete') {
            $userId = (int) ($_POST['user_id'] ?? 0);

            if ($userId < 1) {
                throw new Exception('ID pengguna tidak valid.');
            }

            $stmt = $pdo->prepare(
                'DELETE FROM users WHERE id = ? AND role = ?'
            );

            $stmt->execute([$userId, 'user']);

            flash('Permintaan penghapusan data diproses.');

        } else {
            throw new Exception('Aksi tidak dikenali.');
        }

        header('Location: pendaftar.php');
        exit;

    } catch (Throwable $e) {
        $msg = $e instanceof PDOException
            ? 'Data gagal diproses. Periksa relasi database.'
            : $e->getMessage();
    }
}

$rows = $pdo->query(
    'SELECT a.*, u.nama, u.email, u.nik, u.telepon
     FROM applicants a
     JOIN users u ON u.id = a.user_id
     ORDER BY a.created_at DESC'
)->fetchAll(PDO::FETCH_ASSOC);

admin_header('Data Calon Maba');
?>

<section class="panel">
    <p>
        Status pendaftaran dikelola otomatis oleh sistem. Admin hanya dapat menghapus data yang tidak diperlukan.
    </p>

    <div class="tablewrap">
        <table>
            <thead>
                <tr>
                    <th>Nomor Tes / Nama</th>
                    <th>Kontak</th>
                    <th>Prodi & Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 24px;">
                            Belum ada data calon mahasiswa.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td>
                                <b><?= e($row['nomor_tes']) ?></b><br>
                                <?= e($row['nama']) ?><br>
                                <small>NIK: <?= e($row['nik']) ?></small>
                            </td>

                            <td>
                                <?= e($row['email']) ?><br>
                                <?= e($row['telepon']) ?>
                            </td>

                            <td>
                                <b><?= e($row['prodi'] ?? 'Belum dipilih') ?></b><br>
                                <small><?= e($row['status']) ?></small>
                            </td>

                            <td>
                                <form method="post"
                                      onsubmit="return confirm('Hapus data calon mahasiswa ini?');">
                                    <input type="hidden" name="csrf"
                                           value="<?= e(csrf()) ?>">
                                    <input type="hidden" name="action"
                                           value="delete">
                                    <input type="hidden" name="user_id"
                                           value="<?= (int) $row['user_id'] ?>">

                                    <button type="submit" class="btn red">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php admin_footer(); ?>