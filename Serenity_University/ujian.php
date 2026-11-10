<?php
require_once __DIR__ . '/config.php';
require_login();

if (user()['role'] !== 'user') {
    header('Location: admin/index.php');
    exit;
}

$q = db()->prepare('SELECT a.*, u.id uid FROM applicants a JOIN users u ON u.id = a.user_id WHERE u.id = ?');
$q->execute([user()['id']]);
$a = $q->fetch();

if (!$a || !$a['prodi'] || $a['status'] === 'Biodata belum lengkap') {
    flash('Lengkapi biodata terlebih dahulu sebelum mengikuti ujian.');
    header('Location: dashboard.php');
    exit;
}

$q = db()->prepare('SELECT id FROM exam_attempts WHERE applicant_id = ?');
$q->execute([$a['id']]);
if ($q->fetchColumn()) {
    flash('Kesempatan ujian Anda sudah digunakan.');
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['exam_question_ids'][$a['id']])) {
    $ids = array_map('intval', $_SESSION['exam_question_ids'][$a['id']]);
    if (count($ids) !== 10) {
        $ids = [];
    }
} else {
    $ids = [];
}

if (!$ids) {
    $q = db()->prepare('SELECT id FROM questions WHERE prodi = ? AND active = 1 ORDER BY RAND() LIMIT 10');
    $q->execute([$a['prodi']]);
    $ids = array_map('intval', array_column($q->fetchAll(), 'id'));
    if (count($ids) === 10) {
        $_SESSION['exam_question_ids'][$a['id']] = $ids;
    }
}

if (count($ids) === 10) {
    $marks = implode(',', array_fill(0, count($ids), '?'));
    $q = db()->prepare("SELECT id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d FROM questions WHERE prodi = ? AND active = 1 AND id IN ($marks)");
    $q->execute([$a['prodi'], ...$ids]);
    $fetched = $q->fetchAll();
    
    $byId = [];
    foreach ($fetched as $row) {
        $byId[$row['id']] = $row;
    }
    
    $questions = [];
    foreach ($ids as $qid) {
        if (isset($byId[$qid])) {
            $questions[] = $byId[$qid];
        }
    }
} else {
    $questions = [];
}

$error = '';
if (count($questions) < 10) {
    $error = 'Soal untuk program studi ini belum lengkap. Hubungi admin.';
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        check_csrf();
        try {
            $pdo = db();
            $pdo->beginTransaction();

            $check = $pdo->prepare('SELECT id FROM exam_attempts WHERE applicant_id = ? FOR UPDATE');
            $check->execute([$a['id']]);
            if ($check->fetchColumn()) {
                throw new Exception('Ujian telah dikirim sebelumnya.');
            }

            $keys = array_keys($_POST['answers'] ?? []);
            if (count($keys) !== 10) {
                throw new Exception('Jawab semua 10 soal sebelum mengirim.');
            }

            $score = 0;
            $answers = [];
            $get = $pdo->prepare('SELECT id, jawaban_benar FROM questions WHERE id = ? AND prodi = ? AND active = 1');
            
            foreach ($questions as $question) {
                $id = (string)$question['id'];
                $ans = $_POST['answers'][$id] ?? '';
                if (!in_array($ans, ['A', 'B', 'C', 'D'], true)) {
                    throw new Exception('Semua jawaban harus berupa pilihan yang valid.');
                }
                
                $get->execute([$question['id'], $a['prodi']]);
                $correct = $get->fetch();
                if (!$correct) {
                    throw new Exception('Data soal berubah, silakan hubungi admin.');
                }

                $is = ($ans === $correct['jawaban_benar']);
                if ($is) {
                    $score += 10;
                }
                $answers[] = [$question['id'], $ans, $is ? 1 : 0];
            }

            // Sistem menghitung dan menyimpan hasil secara privat.
            // Peserta hanya melihat hasil setelah validasi admin.
            $lulus = $score >= 75 ? 1 : 0;

            $ins = $pdo->prepare('INSERT INTO exam_attempts (applicant_id, nilai, lulus) VALUES (?, ?, ?)');
            $ins->execute([$a['id'], $score, $lulus]);
            $attemptId = (int)$pdo->lastInsertId();

            $insAns = $pdo->prepare('INSERT INTO exam_answers (attempt_id, question_id, jawaban, benar) VALUES (?, ?, ?, ?)');
            foreach ($answers as $v) {
                $insAns->execute([$attemptId, ...$v]);
            }
            // Simpan antrean validasi admin; nilai tidak ditampilkan kepada peserta.
            $validationStmt = $pdo->prepare(
                "INSERT INTO exam_result_validations (attempt_id, status)
                 VALUES (?, 'menunggu')"
            );
            $validationStmt->execute([$attemptId]);

            $pdo->prepare(
                "UPDATE applicants SET status = 'Menunggu validasi admin' WHERE id = ?"
            )->execute([$a['id']]);

            $pdo->commit();
            unset($_SESSION['exam_question_ids'][$a['id']]);

            flash('Ujian berhasil dikirim. Hasil seleksi sedang menunggu validasi admin.');
            header('Location: dashboard.php#hasil');
            exit;

        } catch (Throwable $e) {
            if (db()->inTransaction()) {
                db()->rollBack();
            }
            $error = $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ujian Seleksi | Serenity University</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body class="app">
    <div class="layout">
        <aside class="sidebar">
            <div class="brand">SERENITY<br>UNIVERSITY</div>
            <nav class="nav">
                <a href="dashboard.php">Dashboard</a>
                <a class="active" href="ujian.php">Ujian Seleksi</a>
                <a href="logout.php">Keluar</a>
            </nav>
        </aside>
        <main class="main">
            <header class="topbar">
                <strong>Ujian Seleksi Jalur Mandiri</strong>
                <span><?= e(user()['nama']) ?></span>
            </header>
            <div class="content">
                <h1>Ujian <?= e($a['prodi']) ?></h1>
                <p class="muted">Nomor tes: <?= e($a['nomor_tes']) ?> · 10 soal · Kesempatan satu kali. Hasil diumumkan setelah divalidasi admin.</p>
                
                <?php if ($error): ?>
                    <div class="alert err"><?= e($error) ?></div>
                <?php endif; ?>

                <?php if (count($questions) === 10 && !$error || count($questions) === 10 && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                    <form method="post" onsubmit="return confirm('Yakin mengirim jawaban? Ujian hanya dapat dikerjakan satu kali.');">
                        <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
                        
                        <?php foreach ($questions as $i => $question): ?>
                            <section class="panel question">
                                <h3><?= ($i + 1) ?>. <?= e($question['pertanyaan']) ?></h3>
                                <?php foreach (['A' => 'opsi_a', 'B' => 'opsi_b', 'C' => 'opsi_c', 'D' => 'opsi_d'] as $letter => $field): ?>
                                    <label class="option">
                                        <input type="radio" name="answers[<?= e($question['id']) ?>]" value="<?= $letter ?>" required>
                                        <span><b><?= $letter ?>.</b> <?= e($question[$field]) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </section>
                        <?php endforeach; ?>

                        <button class="btn gold" type="submit">Kirim Jawaban Final</button>
                    </form>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>