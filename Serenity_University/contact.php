<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Serenity University</title>
    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<header class="main-header">
    <nav class="navbar">
        <div class="brand">
            <img src="images/logo-univ.png" alt="Logo Serenity University">
            <div class="brand-text"><h2>SERENITY</h2><h2>UNIVERSITY</h2></div>
        </div>
        <div class="nav-right">
            <div class="nav-menu">
                <a href="index.php">BERANDA</a>
                <a href="tentang-kami.php">TENTANG KAMI</a>
                <a href="prodi.php">PRODI</a>
                <a href="contact.php" class="active">CONTACT</a>
            </div>
            <a href="login.php" class="login-btn"><i class="fa-regular fa-user"></i> LOGIN</a>
        </div>
    </nav>
</header>

<section class="hero-contact">
    <img src="images/Contact Us.jpg" alt="Hubungi Serenity University" class="hero-image">
    <div class="hero-overlay">
        <div>
            <span>LAYANAN INFORMASI & PMB</span>
            <h1>CONTACT US</h1>
            <p>Kami siap membantu menjawab pertanyaan seputar pendaftaran mahasiswa baru, program studi, dan layanan akademik.</p>
        </div>
    </div>
</section>

<section class="contact-section">
    <div class="contact-heading">
        <span>HUBUNGI KAMI</span>
        <h2>Butuh Bantuan? Kami Siap Membantu</h2>
        <p>Pilih kanal yang paling nyaman untuk mendapatkan informasi dari tim Serenity University.</p>
    </div>

    <div class="contact-cards">
        <div class="contact-card">
            <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
            <div><h3>Alamat Kampus</h3><p>Jl. Serenity University No. 10<br>Surabaya, Jawa Timur</p><a href="#lokasi">Lihat Lokasi →</a></div>
        </div>
        <div class="contact-card">
            <div class="contact-icon"><i class="fa-brands fa-whatsapp"></i></div>
            <div><h3>WhatsApp PMB</h3><p>+62 876-9832-6421<br>Senin–Jumat, 08.00–16.00 WIB</p><a href="https://wa.me/6287698326421">Chat Sekarang →</a></div>
        </div>
        <div class="contact-card">
            <div class="contact-icon"><i class="fa-regular fa-envelope"></i></div>
            <div><h3>Email Resmi</h3><p>serenityuniversity@gmail.com<br>Respons maksimal 1×24 jam</p><a href="mailto:serenityuniversity@gmail.com">Kirim Email →</a></div>
        </div>
    </div>

    <div class="contact-main">
        <div class="contact-form-box">
            <div class="box-heading"><span>FORMULIR PESAN</span><h2>Kirim Pertanyaan</h2></div>
            <form onsubmit="event.preventDefault(); document.getElementById('sentMessage').classList.add('show');">
                <div class="form-row">
                    <label>Nama Lengkap<input type="text" placeholder="Masukkan nama lengkap" required></label>
                    <label>Email<input type="email" placeholder="nama@email.com" required></label>
                </div>
                <label>Topik Pertanyaan
                    <select required><option value="">Pilih topik</option><option>Pendaftaran Mahasiswa Baru</option><option>Program Studi</option><option>Akademik</option><option>Informasi Umum</option></select>
                </label>
                <label>Pesan<textarea rows="6" placeholder="Tuliskan pertanyaan atau kebutuhan Anda..." required></textarea></label>
                <button type="submit"><i class="fa-regular fa-paper-plane"></i> Kirim Pesan</button>
                <p id="sentMessage" class="sent-message"><i class="fa-solid fa-circle-check"></i> Pesan siap dikirim. Tim kami akan segera menghubungi Anda.</p>
            </form>
        </div>

        <div class="help-box" id="lokasi">
            <div class="help-image"><img src="images/Contact Us.jpg" alt="Lokasi Serenity University"></div>
            <div class="help-content">
                <span>HELPDESK PMB</span>
                <h2>Informasi Pendaftaran</h2>
                <p>Untuk pertanyaan terkait akun, biodata, nomor tes, jadwal seleksi, hasil seleksi, atau daftar ulang, silakan hubungi Helpdesk PMB.</p>
                <div class="help-list">
                    <div><i class="fa-regular fa-clock"></i><span><b>Jam Layanan</b><small>Senin–Jumat, 08.00–16.00 WIB</small></span></div>
                    <div><i class="fa-solid fa-headset"></i><span><b>Helpdesk</b><small>Respons pertanyaan pendaftaran dan akun</small></span></div>
                    <div><i class="fa-solid fa-location-dot"></i><span><b>Kampus</b><small>Surabaya, Jawa Timur</small></span></div>
                </div>
                <a class="help-button" href="login.php">Cek Akun Pendaftaran <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<footer class="contact-footer">
    <div class="footer-col info-col">
        <h3>SERENITY UNIVERSITY</h3>
        <p>Universitas yang berfokus pada pendidikan informatika dan pengembangan teknologi.</p>
        <p class="copyright">© 2026 Serenity University. All rights reserved.</p>
    </div>
    <div class="footer-col">
        <h4>LINK CEPAT</h4>
        <a href="index.php">Beranda</a><a href="prodi.php">Program Studi</a><a href="tentang-kami.php">Tentang Kami</a>
    </div>
    <div class="footer-col wa-col">
        <div class="wa-icon-box"><img src="images/logo-wa.png" alt="WhatsApp"></div>
        <span class="wa-number">+62 876-9832-6421</span>
    </div>
</footer>
</body>
</html>