<?php
require_once __DIR__ . '/config.php';
require_login();

if (user()['role'] !== 'user') {
    header('Location: admin/index.php');
    exit;
}

$stmt = db()->prepare(
    'SELECT a.nomor_tes, a.prodi, a.status, a.nim, u.nama,
            ea.id AS attempt_id, ea.lulus,
            erv.status AS validation_status, erv.validated_at
     FROM applicants a
     JOIN users u ON u.id = a.user_id
     JOIN exam_attempts ea ON ea.applicant_id = a.id
     LEFT JOIN exam_result_validations erv ON erv.attempt_id = ea.id
     WHERE a.user_id = ?
     LIMIT 1'
);
$stmt->execute([(int) user()['id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result || $result['validation_status'] !== 'disetujui') {
    flash('Hasil seleksi belum tersedia. Silakan menunggu validasi admin.');
    header('Location: dashboard.php#hasil');
    exit;
}

$lulus = (int) $result['lulus'] === 1;
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengumuman Hasil Seleksi | Serenity University</title>
    <link rel="stylesheet" href="css/app.css">
    <style>
        /* Gaya khusus halaman pengumuman; tidak mengubah mockup halaman lain. */
        .announcement-wrap{max-width:1180px;margin:8px auto 24px;padding:42px 64px 30px;border-radius:22px;color:#fff;text-align:center;background:linear-gradient(115deg,#789bd7 0%,#65a9d0 52%,#49bdc9 100%);box-shadow:0 18px 42px rgba(26,66,112,.18);position:relative;overflow:hidden;min-height:650px}
        .announcement-wrap:before,.announcement-wrap:after{content:"";position:absolute;bottom:-100px;width:55%;height:210px;border-radius:50% 50% 0 0;background:rgba(255,255,255,.10);pointer-events:none}
        .announcement-wrap:before{left:-12%;transform:rotate(12deg)}.announcement-wrap:after{right:-12%;transform:rotate(-12deg)}
        .announcement-wrap>*{position:relative;z-index:1}
        .announcement-brand{display:flex;align-items:center;justify-content:center;gap:20px;margin-bottom:28px;color:#174f9b;text-align:left}
        .announcement-mark{width:86px;height:86px;border:3px solid rgba(255,255,255,.65);border-radius:50%;display:grid;place-items:center;background:rgba(255,255,255,.22);color:#fff;font-size:28px;font-weight:900;letter-spacing:2px}
        .announcement-university{font-size:29px;font-weight:900;letter-spacing:2px}.announcement-caption{color:#174f78;font-size:13px;letter-spacing:2px}
        .announcement-title{margin:0 0 8px;color:#fff;font-size:clamp(25px,3vw,38px);font-weight:900}
        .announcement-subtitle{margin:0;color:#fff;font-size:clamp(22px,2.5vw,32px);font-weight:900;line-height:1.3}
        .announcement-meta{margin-top:10px;font-size:14px;letter-spacing:2px}
        .announcement-person{margin:34px auto 0;font-size:20px;line-height:1.6}.announcement-person strong{display:block;font-size:30px}
        .announcement-number{font-weight:800;font-size:24px}
        .announcement-decision{margin:20px 0 0;font-size:25px}.announcement-decision strong{font-weight:900}
        .announcement-message{max-width:760px;margin:30px auto 18px;font-size:clamp(26px,3vw,38px);font-weight:900;line-height:1.35}
        .announcement-nim{display:inline-flex;flex-direction:column;gap:5px;margin:0 auto 18px;padding:11px 24px;border:1px solid rgba(255,255,255,.5);border-radius:12px;background:rgba(255,255,255,.14)}
        .announcement-nim span{font-size:12px;letter-spacing:1.5px}.announcement-nim strong{font-size:21px}
        .announcement-footer{display:flex;justify-content:space-between;gap:18px;margin-top:28px;padding-top:14px;border-top:1px solid rgba(255,255,255,.35);font-size:12px}
        .announcement-actions{display:flex;justify-content:center;gap:12px;flex-wrap:wrap}
        .announcement-actions .btn-success{background:#278b39}
        @media(max-width:900px){.announcement-wrap{min-height:0;padding:32px 28px 24px}}
        @media(max-width:600px){.announcement-wrap{padding:25px 18px 20px;border-radius:14px}.announcement-mark{width:62px;height:62px;font-size:21px}.announcement-university{font-size:18px}.announcement-caption{font-size:9px}.announcement-person{font-size:16px}.announcement-person strong{font-size:23px}.announcement-number{font-size:19px}.announcement-decision{font-size:20px}.announcement-footer{flex-direction:column}}
        @media print{.sidebar,.topbar,.announcement-actions{display:none!important}.main{margin:0!important}.content{max-width:none!important;padding:0!important}.announcement-wrap{max-width:none;min-height:0;margin:0;border-radius:0;box-shadow:none;print-color-adjust:exact;-webkit-print-color-adjust:exact}}
    </style>
</head>
<body class="app">
<div class="layout">
    <aside class="sidebar">
        <div class="brand">SERENITY<br>UNIVERSITY</div>
        <nav class="nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="dashboard.php#biodata">Biodata & Program Studi</a>
            <a href="ujian.php">Ujian Seleksi</a>
            <a class="active" href="hasil-seleksi.php">Hasil Seleksi</a>
            <a href="logout.php">Keluar</a>
        </nav>
    </aside>
    <main class="main">
        <header class="topbar">
            <strong>Portal Calon Mahasiswa · Jalur Mandiri</strong>
            <span><?= e($result['nama']) ?></span>
        </header>
        <div class="content">
            <article class="announcement-wrap">
                <div class="announcement-brand">
                    <div class="announcement-mark">SU</div>
                    <div>
                        <div class="announcement-university">SERENITY UNIVERSITY</div>
                        <div class="announcement-caption">PENERIMAAN MAHASISWA BARU</div>
                    </div>
                </div>
                <h1 class="announcement-title">PENGUMUMAN HASIL</h1>
                <h2 class="announcement-subtitle">SELEKSI PMB SERENITY UNIVERSITY 2026</h2>
                <div class="announcement-meta">JALUR MANDIRI · TAHUN AKADEMIK 2026/2027</div>

                <div class="announcement-person">
                    PESERTA ATAS NAMA
                    <strong><?= e(mb_strtoupper($result['nama'])) ?></strong>
                    DENGAN NOMOR PESERTA
                    <div class="announcement-number"><?= e($result['nomor_tes']) ?></div>
                    <div class="announcement-decision">
                        DINYATAKAN <strong><?= $lulus ? 'LULUS' : 'TIDAK LULUS' ?></strong>
                    </div>
                    SELEKSI PMB SERENITY UNIVERSITY 2026.
                </div>

                <?php if ($lulus): ?>
                    <div class="announcement-message">SELAMAT DAN SUKSES!<br>SELAMAT BERGABUNG DENGAN SERENITY UNIVERSITY!</div>
                    <div class="announcement-nim">
                        <span>NOMOR INDUK MAHASISWA</span>
                        <strong><?= e($result['nim'] ?: 'Dalam proses penerbitan') ?></strong>
                    </div>
                <?php else: ?>
                    <div class="announcement-message">JANGAN PUTUS ASA<br>DAN TETAP SEMANGAT!</div>
                <?php endif; ?>

                <div class="announcement-footer">
                    <span>Hasil seleksi telah divalidasi oleh panitia PMB Serenity University.</span>
                    <span><?= e($result['validated_at'] ? date('d-m-Y', strtotime($result['validated_at'])) : '') ?></span>
                </div>
            </article>
            <div class="announcement-actions">
                <a class="btn btn-success" href="dashboard.php">Kembali ke Dashboard</a>
                <button class="btn" type="button" onclick="window.print()">Cetak Pengumuman</button>
            </div>
        </div>
    </main>
</div>
</body>
</html>
