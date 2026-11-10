
<?php
require_once __DIR__ . '/config.php';

$loggedInUser = user();
$namaDepan = '';

if ($loggedInUser && $loggedInUser['role'] === 'user') {
    $namaDepan = explode(' ', trim($loggedInUser['nama']))[0];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serenity University</title>

    <link rel="stylesheet" href="css/beranda.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->
<header class="main-header">
    <nav class="navbar">
        <div class="brand">
            <img src="images/logo-univ.png" alt="Logo Serenity University">
            <div class="brand-text">
                <h2>SERENITY</h2>
                <h2>UNIVERSITY</h2>
            </div>
        </div>

        <div class="nav-right">
            <div class="nav-menu">
                <a href="index.php" class="active">BERANDA</a>
                <a href="tentang-kami.php">TENTANG KAMI</a>
                <a href="prodi.php">PRODI</a>
                <a href="contact.php">CONTACT</a>
            </div>

            <?php if ($loggedInUser && $loggedInUser['role'] === 'user'): ?>
                <a href="dashboard.php" class="login-btn">
                    <i class="fa-regular fa-user"></i>
                    <?= e($namaDepan) ?>
                </a>
            <?php elseif ($loggedInUser && $loggedInUser['role'] === 'admin'): ?>
                <a href="admin/index.php" class="login-btn">
                    <i class="fa-solid fa-user-shield"></i>
                    ADMIN
                </a>
            <?php else: ?>
                <a href="login.php" class="login-btn">
                    <i class="fa-regular fa-user"></i>
                    LOGIN
                </a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<!-- HERO -->
<section class="hero" id="beranda">
    <div class="hero-content">
        <p class="hero-label">PENERIMAAN MAHASISWA BARU</p>

        <h1>SERENITY UNIVERSITY</h1>
        <h2>Tahun Akademik 2026/2027</h2>

        <p class="hero-desc">
            Bergabunglah bersama kampus informatika terbaik yang
            mencetak generasi inovatif, berkarakter, dan siap
            menghadapi tantangan dunia digital.
        </p>

        <div class="hero-button">
            <a href="registrasi.php" class="btn-primary">
                Daftar Sekarang
                <i class="fa-solid fa-chevron-right"></i>
            </a>

            <a href="<?= $loggedInUser && $loggedInUser['role'] === 'user' ? 'dashboard.php' : 'login.php' ?>"
               class="btn-secondary">
                Cek Status Pendaftaran
                <i class="fa-regular fa-clipboard"></i>
            </a>
        </div>
    </div>
</section>

<!-- ALUR PENDAFTARAN -->
<section class="alur-section">
    <div class="alur-heading">
        <div class="alur-title-decoration">
            <span></span>
            <i class="fa-solid fa-graduation-cap"></i>
            <span></span>
        </div>

        <h2>ALUR PENDAFTARAN MAHASISWA BARU</h2>

        <p>
            Ikuti langkah-langkah berikut untuk memulai perjalanan akademik Anda
            bersama Serenity University
        </p>
    </div>

    <div class="alur-container">
        <div class="alur-card">
            <div class="alur-number">1</div>
            <div class="alur-icon"><i class="fa-solid fa-user-plus"></i></div>
            <h3>Registrasi Akun</h3>
            <div class="alur-line"></div>
            <p>Buat akun pendaftaran dengan email aktif dan data diri yang valid.</p>
        </div>

        <div class="alur-card">
            <div class="alur-number">2</div>
            <div class="alur-icon"><i class="fa-solid fa-id-card"></i></div>
            <h3>Lengkapi Biodata</h3>
            <div class="alur-line"></div>
            <p>Isi dan lengkapi biodata diri sesuai dengan data resmi.</p>
        </div>

        <div class="alur-card">
            <div class="alur-number">3</div>
            <div class="alur-icon"><i class="fa-solid fa-ticket"></i></div>
            <h3>Dapat Nomor Tes</h3>
            <div class="alur-line"></div>
            <p>Sistem akan memberikan nomor tes setelah biodata tervalidasi.</p>
        </div>

        <div class="alur-card">
            <div class="alur-number">4</div>
            <div class="alur-icon"><i class="fa-solid fa-pen-to-square"></i></div>
            <h3>Mengikuti Tes</h3>
            <div class="alur-line"></div>
            <p>Ikuti tes seleksi sesuai jadwal yang telah ditentukan.</p>
        </div>

        <div class="alur-card">
            <div class="alur-number">5</div>
            <div class="alur-icon"><i class="fa-solid fa-square-poll-vertical"></i></div>
            <h3>Lihat Hasil</h3>
            <div class="alur-line"></div>
            <p>Cek hasil seleksi melalui akun pendaftaran secara online.</p>
        </div>

        <div class="alur-card">
            <div class="alur-number">6</div>
            <div class="alur-icon"><i class="fa-solid fa-file-circle-check"></i></div>
            <h3>Validasi Hasil</h3>
            <div class="alur-line"></div>
            <p>Admin memeriksa dan memvalidasi hasil ujian sebelum pengumuman dibuka.</p>
        </div>

        <div class="alur-card">
            <div class="alur-number">7</div>
            <div class="alur-icon"><i class="fa-solid fa-graduation-cap"></i></div>
            <h3>Dapat NIM</h3>
            <div class="alur-line"></div>
            <p>Peserta yang dinyatakan lulus akan memperoleh NIM secara otomatis.</p>
        </div>
    </div>

    <div class="alur-info">
        <i class="fa-solid fa-circle-info"></i>
        <p>
            <strong>Pastikan semua data yang Anda masukkan benar dan valid.</strong>
            Jika mengalami kendala, silakan hubungi Tim Helpdesk melalui menu Contact.
        </p>
    </div>
</section>

<!-- PROGRAM AKADEMIK -->
<section class="akademik-section">
    <div class="akademik-overlay"></div>

    <div class="akademik-content">
        <h2 class="akademik-title">PROGRAM AKADEMIK</h2>

        <div class="akademik-title-line">
            <span></span>
            <i class="fa-solid fa-shield-halved"></i>
            <span></span>
        </div>

        <div class="akademik-container">
            <div class="akademik-card">
                <div class="akademik-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                <h3>Diploma (D3)</h3>
                <div class="akademik-line"></div>
                <p>Program diploma 3 tahun yang membekali keterampilan praktis dan profesional di bidangnya.</p>
                <a href="detail-akademik.php#diploma">Lihat Detail</a>
            </div>

            <div class="akademik-card">
                <div class="akademik-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                <h3>Sarjana (S1)</h3>
                <div class="akademik-line"></div>
                <p>Program sarjana 4 tahun untuk membentuk lulusan unggul, inovatif dan siap bersaing.</p>
                <a href="detail-akademik.php#sarjana">Lihat Detail</a>
            </div>

            <div class="akademik-card">
                <div class="akademik-icon"><i class="fa-solid fa-book-open"></i></div>
                <h3>Magister (S2)</h3>
                <div class="akademik-line"></div>
                <p>Program magister 2 tahun untuk mengembangkan keahlian dan penelitian lebih mendalam.</p>
                <a href="detail-akademik.php#magister">Lihat Detail</a>
            </div>

            <div class="akademik-card">
                <div class="akademik-icon"><i class="fa-solid fa-award"></i></div>
                <h3>Doktor (S3)</h3>
                <div class="akademik-line"></div>
                <p>Program doktor 3 tahun untuk mencetak ahli dan peneliti berkompetensi tinggi.</p>
                <a href="detail-akademik.php#doktor">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- PROGRAM STUDI -->
<section class="prodi-section" id="prodi">
    <h2 class="section-title">PROGRAM STUDI FAVORIT</h2>

    <div class="title-line">
        <span></span>
        <i class="fa-solid fa-shield-halved"></i>
        <span></span>
    </div>

    <div class="prodi-container">
        <div class="prodi-card">
            <div class="prodi-icon"><i class="fa-solid fa-code"></i></div>
            <div>
                <h3>Teknik Informatika</h3>
                <p>
                    Membekali mahasiswa dengan keahlian pemrograman,
                    rekayasa perangkat lunak dan kecerdasan buatan.
                </p>
                <a href="detail-prodi.php#teknik-informatika">Lihat Detail →</a>
            </div>
        </div>

        <div class="prodi-card">
            <div class="prodi-icon"><i class="fa-solid fa-database"></i></div>
            <div>
                <h3>Sistem Informasi</h3>
                <p>
                    Menggabungkan teknologi informasi dan manajemen
                    untuk menghasilkan solusi bisnis inovatif.
                </p>
                <a href="detail-prodi.php#sistem-informasi">Lihat Detail →</a>
            </div>
        </div>

        <div class="prodi-card">
            <div class="prodi-icon"><i class="fa-solid fa-brain"></i></div>
            <div>
                <h3>Ilmu Komputer</h3>
                <p>
                    Mempelajari teori komputasi, algoritma,
                    struktur data dan pengembangan sistem cerdas.
                </p>
                <a href="detail-prodi.php#ilmu-komputer">Lihat Detail →</a>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta">
    <div class="cta-left">
        <img src="images/logo-univ.png" alt="Logo Serenity University">

        <div>
            <h2>WUJUDKAN MASA DEPAN GEMILANG BERSAMA KAMI</h2>
            <p>
                Jangan lewatkan kesempatan menjadi bagian dari
                Serenity University.
            </p>
        </div>
    </div>

    <a href="registrasi.php" class="btn-primary">
        Daftar Sekarang
        <i class="fa-solid fa-chevron-right"></i>
    </a>
</section>

<!-- FOOTER -->
<footer id="contact">
    <div class="footer-content">
        <div class="footer-brand">
            <img src="images/logo-univ.png" alt="Logo Serenity University">
            <div>
                <h2>SERENITY</h2>
                <h2>UNIVERSITY</h2>
            </div>
        </div>

        <div>
            <h4>KONTAK KAMI</h4>
            <p><i class="fa-solid fa-location-dot"></i> Surabaya, Jawa Timur</p>
            <p><i class="fa-solid fa-phone"></i> +62 876-9832-6421</p>
            <p><i class="fa-regular fa-envelope"></i> serenityuniversity@gmail.com</p>
        </div>

        <div>
            <h4>LINK CEPAT</h4>
            <a href="#beranda">Beranda</a>
            <a href="tentang-kami.php">Tentang Kami</a>
            <a href="prodi.php">Program Studi</a>
            <a href="contact.php">Contact</a>
        </div>
    </div>

    <div class="copyright">
        © 2026 Serenity University. All rights reserved.
    </div>
</footer>

</body>
</html>
