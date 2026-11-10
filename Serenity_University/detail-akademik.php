<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Program Akademik - Serenity University</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/detail-akademik.css">
</head>
<body>

    <!-- NAVBAR UTAMA -->
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
                <a href="tentang-kami.php" class="">TENTANG KAMI</a>
                <a href="prodi.php" class="">PRODI</a>
                <a href="contact.php" class="">CONTACT</a>
            </div>
            <a href="login.php" class="login-btn"><i class="fa-regular fa-user"></i> LOGIN</a>
        </div>
    </nav>
</header>

    <div class="detail-wrapper">
        <!-- Tombol Kembali -->
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>

        <!-- ================= CARD 1: DIPLOMA ================= -->
        <div class="detail-card" id="diploma">
            <div class="card-body">
                <div class="col-left">
                    <h1 class="program-title">Diploma (D3/D4)</h1>
                    <div class="program-badge">
                        <i class="fas fa-certificate"></i> PROGRAM DIPLOMA
                    </div>
                    <p class="program-desc">
                        Program vokasi yang dirancang khusus untuk menghasilkan lulusan dengan keterampilan praktis tingkat tinggi yang siap langsung terjun dan bersaing di dunia industri.
                    </p>
                    <hr class="divider">
                    <h3 class="subtitle">Keunggulan Program</h3>
                    <ul class="features-list">
                        <li><i class="fas fa-check-circle"></i> 70% Praktik dan 30% Teori</li>
                        <li><i class="fas fa-check-circle"></i> Kurikulum disusun bersama mitra industri</li>
                        <li><i class="fas fa-check-circle"></i> Sertifikasi kompetensi keahlian</li>
                        <li><i class="fas fa-check-circle"></i> Fasilitas laboratorium berstandar industri</li>
                    </ul>
                </div>
                <div class="col-right">
                    <img src="images/kegiatan.png" alt="Ilustrasi Diploma" class="program-img">
                    <div class="info-box">
                        <div class="info-row">
                            <div class="info-label"><i class="far fa-calendar-alt"></i> Durasi Studi</div>
                            <div class="info-value">3 - 4 Tahun</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-user-graduate"></i> Gelar</div>
                            <div class="info-value">Ahli Madya / Sarjana Terapan</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-university"></i> Metode Pembelajaran</div>
                            <div class="info-value">Praktikum & Project Based</div>
                        </div>
                        <div class="info-row border-none">
                            <div class="info-label"><i class="fas fa-award"></i> Akreditasi</div>
                            <div class="info-value"><span class="akreditasi-a">(A)</span> Unggul</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= CARD 2: SARJANA (S1) ================= -->
        <div class="detail-card" id="sarjana">
            <div class="card-body">
                <div class="col-left">
                    <h1 class="program-title">Sarjana (S1)</h1>
                    <div class="program-badge">
                        <i class="fas fa-graduation-cap"></i> PROGRAM SARJANA
                    </div>
                    <p class="program-desc">
                        Program sarjana 4 tahun untuk membentuk lulusan unggul, inovatif dan siap bersaing di dunia kerja maupun melanjutkan ke jenjang pendidikan lebih tinggi.
                    </p>
                    <hr class="divider">
                    <h3 class="subtitle">Keunggulan Program</h3>
                    <ul class="features-list">
                        <li><i class="fas fa-check-circle"></i> Kurikulum berbasis kompetensi dan industri</li>
                        <li><i class="fas fa-check-circle"></i> Dosen berpengalaman dan profesional</li>
                        <li><i class="fas fa-check-circle"></i> Fasilitas belajar modern dan lengkap</li>
                        <li><i class="fas fa-check-circle"></i> Kesempatan magang dan kerja sama industri</li>
                        <li><i class="fas fa-check-circle"></i> Pengembangan soft skill dan leadership</li>
                    </ul>
                </div>
                <div class="col-right">
                    <img src="images/wisuda.png" alt="Ilustrasi Sarjana" class="program-img">
                    <div class="info-box">
                        <div class="info-row">
                            <div class="info-label"><i class="far fa-calendar-alt"></i> Durasi Studi</div>
                            <div class="info-value">4 Tahun (8 Semester)</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-user-graduate"></i> Gelar</div>
                            <div class="info-value">Sarjana (S.)</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-university"></i> Metode Pembelajaran</div>
                            <div class="info-value">Tatap Muka & Blended Learning</div>
                        </div>
                        <div class="info-row border-none">
                            <div class="info-label"><i class="fas fa-award"></i> Akreditasi</div>
                            <div class="info-value"><span class="akreditasi-a">(A)</span> Unggul</div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

        <!-- ================= CARD 3: MAGISTER (S2) ================= -->
        <div class="detail-card" id="magister">
            <div class="card-body">
                <div class="col-left">
                    <h1 class="program-title">Magister (S2)</h1>
                    <div class="program-badge">
                        <i class="fas fa-book-reader"></i> PROGRAM MAGISTER
                    </div>
                    <p class="program-desc">
                        Program pascasarjana yang berfokus pada pendalaman keilmuan, riset inovatif, dan pengembangan keterampilan manajerial strategis bagi profesional.
                    </p>
                    <hr class="divider">
                    <h3 class="subtitle">Keunggulan Program</h3>
                    <ul class="features-list">
                        <li><i class="fas fa-check-circle"></i> Jadwal kuliah fleksibel (Kelas Karyawan)</li>
                        <li><i class="fas fa-check-circle"></i> Fokus pada riset dan publikasi jurnal internasional</li>
                        <li><i class="fas fa-check-circle"></i> Pengajar profesor dan praktisi ahli</li>
                    </ul>
                </div>
                <div class="col-right">
                    <img src="images/aula.jpg" alt="Ilustrasi Magister" class="program-img">
                    <div class="info-box">
                        <div class="info-row">
                            <div class="info-label"><i class="far fa-calendar-alt"></i> Durasi Studi</div>
                            <div class="info-value">2 Tahun (4 Semester)</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-user-graduate"></i> Gelar</div>
                            <div class="info-value">Magister (M.)</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-university"></i> Metode Pembelajaran</div>
                            <div class="info-value">Hybrid & Research Based</div>
                        </div>
                        <div class="info-row border-none">
                            <div class="info-label"><i class="fas fa-award"></i> Akreditasi</div>
                            <div class="info-value"><span class="akreditasi-a">(A)</span> Unggul</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= CARD 4: DOKTOR (S3) ================= -->
        <div class="detail-card" id="doktor">
            <div class="card-body">
                <div class="col-left">
                    <h1 class="program-title">Doktor (S3)</h1>
                    <div class="program-badge">
                        <i class="fas fa-microscope"></i> PROGRAM DOKTOR
                    </div>
                    <p class="program-desc">
                        Program pendidikan akademik tertinggi yang bertujuan menghasilkan ilmuwan dan pemikir mandiri yang mampu menemukan kebaruan (novelty) dalam bidang ilmunya.
                    </p>
                    <hr class="divider">
                    <h3 class="subtitle">Keunggulan Program</h3>
                    <ul class="features-list">
                        <li><i class="fas fa-check-circle"></i> Kolaborasi riset dengan universitas luar negeri</li>
                        <li><i class="fas fa-check-circle"></i> Dukungan pendanaan dan hibah disertasi</li>
                        <li><i class="fas fa-check-circle"></i> Ekosistem akademik bertaraf internasional</li>
                    </ul>
                </div>
                <div class="col-right">
                    <img src="images/perpustakaan.jpg" alt="Ilustrasi Doktor" class="program-img">
                    <div class="info-box">
                        <div class="info-row">
                            <div class="info-label"><i class="far fa-calendar-alt"></i> Durasi Studi</div>
                            <div class="info-value">3 - 5 Tahun</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-user-graduate"></i> Gelar</div>
                            <div class="info-value">Doktor (Dr.)</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-university"></i> Metode Pembelajaran</div>
                            <div class="info-value">By Research</div>
                        </div>
                        <div class="info-row border-none">
                            <div class="info-label"><i class="fas fa-award"></i> Akreditasi</div>
                            <div class="info-value"><span class="akreditasi-a">(A)</span> Unggul</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</body>
</html>