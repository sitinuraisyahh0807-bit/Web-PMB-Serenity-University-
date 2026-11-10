<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Studi - Serenity University</title>
    <link rel="stylesheet" href="css/prodi.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <!-- NAVBAR UTAMA (Disamakan persis seperti referensi) -->
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
                <a href="index.php" class="">BERANDA</a>
                <a href="tentang-kami.php" class="">TENTANG KAMI</a>
                <a href="prodi.php" class="active">PRODI</a>
                <a href="contact.php" class="">CONTACT</a>
            </div>
            <a href="login.php" class="login-btn"><i class="fa-regular fa-user"></i> LOGIN</a>
        </div>
    </nav>
</header>

    <!-- Hero / Header Banner -->
    <section class="hero-section">
        <div class="hero-content">
            <span class="hero-subtitle">PROGRAM STUDI —</span>
            <h1>Pilih Program Studi<br>Sesuai Minat dan Masa Depanmu</h1>
            <p>Serenity University memiliki berbagai program studi unggulan yang dirancang untuk mencetak lulusan siap bersaing di era digital.</p>
        </div>
    </section>

    <!-- Section Judul Grid -->
    <div class="section-title-container">
        <h2>Program Studi Unggulan</h2>
        <p>Pilih program studi yang sesuai dengan passion dan tujuan karirmu.</p>
    </div>

    <!-- Container Grid Prodi -->
    <main class="prodi-container">
        <div class="prodi-grid">
            
            <!-- Card 1: Teknik Informatika -->
            <div class="prodi-card">
                <div class="card-image-wrapper">
                    <img src="images/teknik informatika.jpg" alt="Teknik Informatika">
                    <div class="card-icon">&lt;/&gt;</div>
                </div>
                <div class="card-content">
                    <h3>Teknik Informatika</h3>
                    <p>Mempelajari rekayasa perangkat lunak, jaringan komputer, dan teknologi informasi modern.</p>
                    <a href="detail-prodi.php#teknik-informatika">Selengkapnya &rarr;</a>
                </div>
            </div>

            <!-- Card 2: Sistem Informasi -->
            <div class="prodi-card">
                <div class="card-image-wrapper">
                    <img src="images/sistem informasi.jpg" alt="Sistem Informasi">
                    <div class="card-icon">&#9776;</div>
                </div>
                <div class="card-content">
                    <h3>Sistem Informasi</h3>
                    <p>Menggabungkan teknologi informasi dengan proses bisnis untuk solusi inovatif bagi organisasi.</p>
                    <a href="detail-prodi.php#sistem-informasi">Selengkapnya &rarr;</a>
                </div>
            </div>

            <!-- Card 3: Ilmu Komputer -->
            <div class="prodi-card">
                <div class="card-image-wrapper">
                    <img src="images/ilmu komputer.jpg" alt="Ilmu Komputer">
                    <div class="card-icon">&#128187;</div>
                </div>
                <div class="card-content">
                    <h3>Ilmu Komputer</h3>
                    <p>Mempelajari teori komputasi, algoritma, dan kecerdasan buatan untuk menciptakan inovasi digital.</p>
                    <a href="detail-prodi.php#ilmu-komputer">Selengkapnya &rarr;</a>
                </div>
            </div>

            <!-- Card 4: Rekayasa Perangkat Lunak -->
            <div class="prodi-card">
                <div class="card-image-wrapper">
                    <img src="images/rekayasa perangkat lunak.jpg" alt="Rekayasa Perangkat Lunak">
                    <div class="card-icon">&#9881;</div>
                </div>
                <div class="card-content">
                    <h3>Rekayasa Perangkat Lunak</h3>
                    <p>Jurusan yang mempelajari prinsip dan teknik dalam pengembangan perangkat lunak berkualitas.</p>
                    <a href="detail-prodi.php#rekayasa-perangkat-lunak">Selengkapnya &rarr;</a>
                </div>
            </div>

            <!-- Card 5: Manajemen Informatika -->
            <div class="prodi-card">
                <div class="card-image-wrapper">
                    <img src="images/manajemen informatika.jpg" alt="Manajemen Informatika">
                    <div class="card-icon">&#128202;</div>
                </div>
                <div class="card-content">
                    <h3>Manajemen Informatika</h3>
                    <p>Mempelajari teori-teori dalam teknologi informasi serta penerapannya untuk mendukung bisnis.</p>
                    <a href="detail-prodi.php#manajemen-informatika" class="detail-btn">Selengkapnya &rarr;</a>
                </div>
            </div>

            <!-- Card 6: Cyber Security -->
            <div class="prodi-card">
                <div class="card-image-wrapper">
                    <img src="images/cyber security.jpg" alt="Cyber Security">
                    <div class="card-icon">&#128737;</div>
                </div>
                <div class="card-content">
                    <h3>Cyber Security</h3>
                    <p>Mempelajari cara melindungi data dan sistem dari serangan dunia maya yang semakin kompleks.</p>
                    <a href="detail-prodi.php#cyber-security" class="detail-btn">Selengkapnya &rarr;</a>
                </div>
            </div>

        </div>
    </main>

</body>
</html>