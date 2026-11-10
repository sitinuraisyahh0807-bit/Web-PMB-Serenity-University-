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

$letters = ['A', 'B', 'C', 'D'];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        check_csrf();

        $action = $_POST['action'] ?? '';

        if ($action === 'save') {
            $id = filter_var(
                $_POST['id'] ?? 0,
                FILTER_VALIDATE_INT
            );

            $id = $id === false ? 0 : (int) $id;

            $prodi = trim($_POST['prodi'] ?? '');
            $pertanyaan = trim($_POST['pertanyaan'] ?? '');

            $opsi = [
                'A' => trim($_POST['a'] ?? ''),
                'B' => trim($_POST['b'] ?? ''),
                'C' => trim($_POST['c'] ?? ''),
                'D' => trim($_POST['d'] ?? '')
            ];

            $jawabanBenar = strtoupper(
                trim($_POST['benar'] ?? '')
            );

            if (!in_array($prodi, $programs, true)) {
                throw new Exception('Program studi tidak valid.');
            }

            if ($pertanyaan === '') {
                throw new Exception('Pertanyaan wajib diisi.');
            }

            foreach ($opsi as $huruf => $isi) {
                if ($isi === '') {
                    throw new Exception(
                        "Opsi jawaban {$huruf} wajib diisi."
                    );
                }
            }

            if (!in_array($jawabanBenar, $letters, true)) {
                throw new Exception('Jawaban benar tidak valid.');
            }

            if ($id < 0) {
                throw new Exception('ID soal tidak valid.');
            }

            if ($id > 0) {
                $stmt = $pdo->prepare(
                    'UPDATE questions
                     SET prodi = ?,
                         pertanyaan = ?,
                         opsi_a = ?,
                         opsi_b = ?,
                         opsi_c = ?,
                         opsi_d = ?,
                         jawaban_benar = ?
                     WHERE id = ?'
                );

                $stmt->execute([
                    $prodi,
                    $pertanyaan,
                    $opsi['A'],
                    $opsi['B'],
                    $opsi['C'],
                    $opsi['D'],
                    $jawabanBenar,
                    $id
                ]);

                flash('Soal berhasil diperbarui.');
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO questions (
                        prodi, pertanyaan,
                        opsi_a, opsi_b, opsi_c, opsi_d,
                        jawaban_benar, active
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, 1)'
                );

                $stmt->execute([
                    $prodi,
                    $pertanyaan,
                    $opsi['A'],
                    $opsi['B'],
                    $opsi['C'],
                    $opsi['D'],
                    $jawabanBenar
                ]);

                flash('Soal berhasil ditambahkan.');
            }
        } elseif ($action === 'toggle') {
            $id = filter_var(
                $_POST['id'] ?? null,
                FILTER_VALIDATE_INT
            );

            if (!$id || $id < 1) {
                throw new Exception('ID soal tidak valid.');
            }

            $stmt = $pdo->prepare(
                'UPDATE questions
                 SET active = CASE
                     WHEN active = 1 THEN 0
                     ELSE 1
                 END
                 WHERE id = ?'
            );

            $stmt->execute([$id]);

            flash('Status soal berhasil diperbarui.');
        } elseif ($action === 'delete') {
            $id = filter_var(
                $_POST['id'] ?? null,
                FILTER_VALIDATE_INT
            );

            if (!$id || $id < 1) {
                throw new Exception('ID soal tidak valid.');
            }

            $stmt = $pdo->prepare(
                'UPDATE questions SET active = 0 WHERE id = ?'
            );

            $stmt->execute([$id]);

            flash('Soal berhasil dinonaktifkan.');
        } else {
            throw new Exception('Aksi tidak dikenali.');
        }

        header('Location: soal.php');
        exit;

    } catch (Throwable $e) {
        if ($e instanceof PDOException) {
            error_log($e->getMessage());
            $error = 'Terjadi kesalahan database.';
        } else {
            $error = $e->getMessage();
        }
    }
}

$edit = null;

if (isset($_GET['edit'])) {
    $editId = filter_var(
        $_GET['edit'],
        FILTER_VALIDATE_INT
    );

    if (!$editId || $editId < 1) {
        $error = 'ID soal tidak valid.';
    } else {
        $stmt = $pdo->prepare(
            'SELECT * FROM questions WHERE id = ? LIMIT 1'
        );

        $stmt->execute([$editId]);
        $edit = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$edit) {
            $error = 'Soal tidak ditemukan.';
        }
    }
}

$flashMessage = flash();

if (is_string($flashMessage) && $flashMessage !== '') {
    $message = $flashMessage;
}

$counts = $pdo->query(
    'SELECT prodi, COUNT(*) AS total
     FROM questions
     WHERE active = 1
     GROUP BY prodi'
)->fetchAll(PDO::FETCH_KEY_PAIR);

$rows = $pdo->query(
    'SELECT id, prodi, pertanyaan, active
     FROM questions
     ORDER BY prodi ASC, id DESC'
)->fetchAll(PDO::FETCH_ASSOC);

admin_header('Bank Soal Tes');
?>

<?php if ($message !== ''): ?>
    <div class="panel" role="status">
        <?= e($message) ?>
    </div>
<?php endif; ?>

<?php if ($error !== ''): ?>
    <div class="panel" role="alert"
         style="color: #9c2525; margin-bottom: 18px;">
        <?= e($error) ?>
    </div>
<?php endif; ?>

<div class="grid2">
    <section class="panel">
        <h2><?= $edit ? 'Edit Soal' : 'Tambah Soal' ?></h2>

        <form method="post"
              action="soal.php<?= $edit ? '?edit=' . (int) $edit['id'] : '' ?>">

            <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id"
                   value="<?= (int) ($edit['id'] ?? 0) ?>">

            <label for="prodi">Program Studi</label>
            <select id="prodi" name="prodi" required>
                <option value="">-- Pilih Program Studi --</option>

                <?php foreach ($programs as $program): ?>
                    <option value="<?= e($program) ?>"
                        <?= ($edit['prodi'] ?? '') === $program ? 'selected' : '' ?>>
                        <?= e($program) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="pertanyaan">Pertanyaan</label>
            <textarea id="pertanyaan" name="pertanyaan"
                      rows="4" required><?= e($edit['pertanyaan'] ?? '') ?></textarea>

            <?php foreach ($letters as $letter): ?>
                <?php
                $field = 'opsi_' . strtolower($letter);
                $inputName = strtolower($letter);
                ?>

                <label for="opsi_<?= $letter ?>">
                    Opsi <?= $letter ?>
                </label>

                <input type="text"
                       id="opsi_<?= $letter ?>"
                       name="<?= $inputName ?>"
                       value="<?= e($edit[$field] ?? '') ?>"
                       required>
            <?php endforeach; ?>

            <label for="benar">Jawaban Benar</label>
            <select id="benar" name="benar" required>
                <?php foreach ($letters as $letter): ?>
                    <option value="<?= $letter ?>"
                        <?= ($edit['jawaban_benar'] ?? 'A') === $letter ? 'selected' : '' ?>>
                        <?= $letter ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <p style="margin-top: 18px;">
                <button type="submit" class="btn">
                    <?= $edit ? 'Simpan Perubahan' : 'Simpan Soal' ?>
                </button>

                <?php if ($edit): ?>
                    <a href="soal.php" class="btn">Batal</a>
                <?php endif; ?>
            </p>
        </form>
    </section>

    <section class="panel">
        <h2>Ringkasan Bank Soal</h2>

        <?php foreach ($programs as $program): ?>
            <?php $jumlah = (int) ($counts[$program] ?? 0); ?>

            <p style="margin: 15px 0; overflow: auto;">
                <?= e($program) ?>
                <b style="float: right;">
                    <?= $jumlah ?> soal aktif
                </b>
            </p>
        <?php endforeach; ?>

        <p class="muted">
            Setiap program studi membutuhkan minimal 10 soal aktif
            agar ujian dapat berjalan. Jawaban benar tidak ditampilkan
            pada daftar soal.
        </p>
    </section>
</div>

<section class="panel" style="margin-top: 18px;">
    <h2>Daftar Soal</h2>

    <div class="tablewrap">
        <table>
            <thead>
                <tr>
                    <th>Program Studi</th>
                    <th>Pertanyaan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="4"
                            style="text-align: center; padding: 24px;">
                            Belum ada soal. Silakan tambahkan soal terlebih dahulu.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['prodi']) ?></td>
                            <td><?= e($row['pertanyaan']) ?></td>
                            <td>
                                <span class="badge">
                                    <?= (int) $row['active'] === 1 ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                            <td>
                                <a class="btn"
                                   href="soal.php?edit=<?= (int) $row['id'] ?>">
                                    Edit
                                </a>

                                <form method="post" action="soal.php"
                                      style="display: inline;">
                                    <input type="hidden" name="csrf"
                                           value="<?= e(csrf()) ?>">
                                    <input type="hidden" name="action"
                                           value="toggle">
                                    <input type="hidden" name="id"
                                           value="<?= (int) $row['id'] ?>">

                                    <button type="submit" class="btn gold">
                                        <?= (int) $row['active'] === 1
                                            ? 'Nonaktifkan'
                                            : 'Aktifkan' ?>
                                    </button>
                                </form>

                                <?php if ((int) $row['active'] === 1): ?>
                                    <form method="post" action="soal.php"
                                          style="display: inline;"
                                          onsubmit="return confirm('Nonaktifkan soal ini?');">
                                        <input type="hidden" name="csrf"
                                               value="<?= e(csrf()) ?>">
                                        <input type="hidden" name="action"
                                               value="delete">
                                        <input type="hidden" name="id"
                                               value="<?= (int) $row['id'] ?>">

                                        <button type="submit" class="btn red">
                                            Hapus
                                        </button>
                                    </form>
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