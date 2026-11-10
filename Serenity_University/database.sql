CREATE DATABASE IF NOT EXISTS serenity_pmb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE serenity_pmb;

-- Skema instalasi baru. Tidak ada DROP TABLE agar data tidak terhapus.
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(120) NULL,
  nik CHAR(16) NULL UNIQUE,
  email VARCHAR(190) NOT NULL UNIQUE,
  telepon VARCHAR(30) NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS applicants (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL UNIQUE,
  nomor_tes VARCHAR(30) NULL UNIQUE,
  jalur ENUM('Mandiri') NOT NULL DEFAULT 'Mandiri',
  prodi VARCHAR(100) NULL,
  alamat TEXT NULL,
  tanggal_lahir DATE NULL,
  status VARCHAR(80) NOT NULL DEFAULT 'Biodata belum lengkap',
  nim VARCHAR(30) NULL UNIQUE,
  verified_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_applicant_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  prodi VARCHAR(100) NOT NULL,
  pertanyaan TEXT NOT NULL,
  opsi_a VARCHAR(255) NOT NULL,
  opsi_b VARCHAR(255) NOT NULL,
  opsi_c VARCHAR(255) NOT NULL,
  opsi_d VARCHAR(255) NOT NULL,
  jawaban_benar ENUM('A','B','C','D') NOT NULL,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS exam_attempts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  applicant_id INT NOT NULL UNIQUE,
  nilai DECIMAL(5,2) NOT NULL,
  lulus TINYINT(1) NOT NULL,
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_attempt_applicant FOREIGN KEY(applicant_id) REFERENCES applicants(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS exam_result_validations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  attempt_id INT NOT NULL UNIQUE,
  status ENUM('menunggu','disetujui') NOT NULL DEFAULT 'menunggu',
  validated_by INT NULL,
  validated_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_result_validation_attempt FOREIGN KEY(attempt_id) REFERENCES exam_attempts(id) ON DELETE CASCADE,
  CONSTRAINT fk_result_validation_admin FOREIGN KEY(validated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS exam_answers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  attempt_id INT NOT NULL,
  question_id INT NOT NULL,
  jawaban ENUM('A','B','C','D') NOT NULL,
  benar TINYINT(1) NOT NULL,
  CONSTRAINT fk_answer_attempt FOREIGN KEY(attempt_id) REFERENCES exam_attempts(id) ON DELETE CASCADE,
  CONSTRAINT fk_answer_question FOREIGN KEY(question_id) REFERENCES questions(id)
) ENGINE=InnoDB;

INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Teknik Informatika','Apa fungsi utama algoritma?','Menyusun langkah penyelesaian masalah','Menghias tampilan','Menyimpan listrik','Mengganti perangkat keras','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Teknik Informatika','Perangkat untuk memasukkan teks adalah?','Monitor','Keyboard','Speaker','Proyektor','B');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Teknik Informatika','Satuan data terkecil komputer adalah?','Byte','Kilobyte','Bit','Megabyte','C');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Teknik Informatika','HTML digunakan terutama untuk?','Mengatur struktur halaman web','Mengelola jaringan listrik','Mengedit video','Mengompilasi kernel','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Teknik Informatika','Manakah contoh bahasa pemrograman?','HTTP','CSS','Python','JPEG','C');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Teknik Informatika','RAM bersifat?','Penyimpanan permanen','Memori sementara','Media cetak','Perangkat keluaran','B');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Teknik Informatika','Sistem operasi berfungsi untuk?','Mengelola sumber daya komputer','Mencetak dokumen saja','Membuat kabel','Mendinginkan CPU','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Teknik Informatika','Hasil 10 divisi 2 adalah?','2','5','8','20','B');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Teknik Informatika','Database digunakan untuk?','Mengelola data terstruktur','Memperbesar monitor','Mengisi baterai','Mengganti mouse','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Teknik Informatika','Debugging berarti?','Menambah iklan','Mencari dan memperbaiki kesalahan program','Menghapus sistem operasi','Mendesain casing','B');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Sistem Informasi','Sistem informasi menggabungkan manusia, proses, dan...?','Informasi dan teknologi','Cat dan kertas saja','Mesin kendaraan','Peralatan olahraga','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Sistem Informasi','Tujuan utama basis data adalah?','Mengelola data terstruktur','Mengolah suara saja','Mengganti monitor','Mengirim listrik','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Sistem Informasi','Diagram alir disebut juga?','Flowchart','Spreadsheet','Firewall','Compiler','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Sistem Informasi','Data yang telah diolah menjadi bermakna disebut?','Folder','Informasi','Perangkat','Kode sumber','B');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Sistem Informasi','Contoh perangkat lunak spreadsheet adalah?','Excel','Router','RAM','Scanner','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Sistem Informasi','Analisis kebutuhan dilakukan untuk?','Memahami kebutuhan pengguna','Menghias ruang server','Mengganti kabel','Menghapus data acak','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Sistem Informasi','Primary key berfungsi sebagai?','Identitas unik baris data','Warna tabel','Nama aplikasi','Cadangan listrik','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Sistem Informasi','ERP biasanya membantu mengintegrasikan?','Proses bisnis organisasi','Warna layar','Kabel jaringan saja','Akun media sosial','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Sistem Informasi','Backup data berguna untuk?','Pemulihan saat data hilang','Memperlambat sistem','Menghapus pengguna','Mengubah monitor','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Sistem Informasi','UAT adalah pengujian oleh?','Pengguna akhir','Pemasok listrik','Printer','Sistem pendingin','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Ilmu Komputer','Struktur data untuk prinsip LIFO adalah?','Queue','Stack','Tree','Graph','B');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Ilmu Komputer','Kompleksitas O(n) menunjukkan waktu yang?','Konstan','Sejalan dengan ukuran input','Selalu nol','Eksponensial','B');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Ilmu Komputer','Bilangan biner menggunakan digit?','0 dan 1','1 sampai 9','A sampai F','2 dan 3','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Ilmu Komputer','CPU adalah singkatan dari?','Central Processing Unit','Computer Personal Utility','Control Program User','Central Print Unit','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Ilmu Komputer','Compiler bertugas untuk?','Menerjemahkan kode sumber','Membersihkan layar','Mencetak data','Menyimpan foto','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Ilmu Komputer','Struktur data FIFO adalah?','Stack','Queue','Heap','Tree','B');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Ilmu Komputer','Dalam logika, AND bernilai benar jika?','Kedua input benar','Salah satu input benar','Kedua input salah','Selalu benar','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Ilmu Komputer','SQL digunakan untuk?','Mengelola dan mengkueri basis data','Mendesain poster','Mengedit suara','Mengukur suhu','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Ilmu Komputer','Enkripsi bertujuan untuk?','Melindungi kerahasiaan data','Memperbesar file','Menghapus jaringan','Mempercepat kipas','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Ilmu Komputer','Sebuah byte umumnya terdiri dari berapa bit?','4','8','16','32','B');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Rekayasa Perangkat Lunak','Tahap awal pengembangan perangkat lunak biasanya?','Analisis kebutuhan','Pemeliharaan','Penghapusan','Promosi','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Rekayasa Perangkat Lunak','Git digunakan untuk?','Version control','Mengedit foto','Mengukur jaringan','Mengelola listrik','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Rekayasa Perangkat Lunak','Pengujian unit menguji?','Komponen kecil secara terpisah','Seluruh perusahaan','Monitor','Kabel listrik','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Rekayasa Perangkat Lunak','CSS digunakan untuk?','Tampilan halaman web','Query database','Kompresi suara','Sistem operasi','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Rekayasa Perangkat Lunak','Prinsip DRY berarti?','Dont Repeat Yourself','Data Runs Yearly','Do Read Yesterday','Direct Router Yield','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Rekayasa Perangkat Lunak','API memungkinkan?','Komunikasi antar perangkat lunak','Monitor lebih terang','Baterai tahan lama','Keyboard nirkabel','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Rekayasa Perangkat Lunak','Bug adalah?','Kesalahan perangkat lunak','Fitur baru pasti','Kabel data','Jenis monitor','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Rekayasa Perangkat Lunak','UML digunakan untuk?','Memodelkan sistem','Mengisi baterai','Mengedit musik','Memindai virus saja','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Rekayasa Perangkat Lunak','Pengujian regresi memastikan?','Perubahan tidak merusak fungsi lama','Kode selalu pendek','Database kosong','UI tanpa warna','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Rekayasa Perangkat Lunak','Deployment berarti?','Merilis aplikasi ke lingkungan tujuan','Membuat rancangan kertas','Menghapus semua tes','Mematikan internet','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Manajemen Informatika','Fungsi utama manajemen proyek adalah?','Merencanakan dan mengendalikan pekerjaan','Menghapus laporan','Mengganti perangkat keras','Memutus jaringan','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Manajemen Informatika','KPI digunakan untuk?','Mengukur kinerja','Mengatur warna layar','Menyimpan password','Mengompres gambar','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Manajemen Informatika','Spreadsheet cocok untuk?','Pengolahan data tabular','Memperbaiki kabel','Mendinginkan CPU','Mengatur kamera','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Manajemen Informatika','SOP adalah?','Prosedur operasi standar','Sistem output printer','Satuan online program','Server office protocol','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Manajemen Informatika','Risiko proyek sebaiknya?','Diidentifikasi dan dimitigasi','Diabaikan','Disembunyikan','Dihapus tanpa analisis','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Manajemen Informatika','ERP membantu?','Integrasi proses bisnis','Menggambar animasi saja','Mempercepat kipas','Mengganti jaringan listrik','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Manajemen Informatika','Dokumentasi berguna untuk?','Mencatat proses dan keputusan','Membuat file rahasia tanpa alasan','Menghapus komunikasi','Mengganti sistem operasi','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Manajemen Informatika','LAN adalah jaringan?','Area lokal','Antarbenua saja','Satelit luar angkasa','Tanpa perangkat','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Manajemen Informatika','Hak akses berbasis peran disebut?','RBAC','HTML','CPU','JPEG','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Manajemen Informatika','Rapat evaluasi digunakan untuk?','Meninjau hasil dan tindak lanjut','Menghindari target','Menghapus tugas','Mengganti data acak','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Cyber Security','Phishing adalah upaya untuk?','Menipu korban agar memberikan informasi sensitif','Mempercepat komputer','Membuat backup','Memperbaiki layar','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Cyber Security','Autentikasi multifaktor menambah?','Lapisan verifikasi identitas','Ukuran monitor','Kapasitas printer','Kecepatan kipas','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Cyber Security','Firewall berfungsi untuk?','Memfilter lalu lintas jaringan','Mencetak dokumen','Membuat desain','Mengisi baterai','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Cyber Security','Password yang kuat sebaiknya?','Panjang dan unik','Nama sendiri saja','123456','Sama di semua akun','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Cyber Security','Enkripsi mengubah data menjadi?','Bentuk yang tidak terbaca tanpa kunci','File gambar','Data publik','Kertas cetak','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Cyber Security','Patch keamanan digunakan untuk?','Memperbaiki kerentanan','Menghapus monitor','Menambah spam','Membuka akses semua orang','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Cyber Security','Malware adalah?','Perangkat lunak berbahaya','Aplikasi spreadsheet','Kabel jaringan','Jenis monitor','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Cyber Security','Prinsip least privilege berarti?','Hak akses minimum yang diperlukan','Semua orang admin','Tanpa kata sandi','Semua data publik','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Cyber Security','HTTPS membantu melindungi?','Komunikasi web melalui enkripsi TLS','Keyboard dari debu','Baterai laptop','Kertas cetak','A');
INSERT INTO questions(prodi,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES('Cyber Security','Jika menerima lampiran mencurigakan, sebaiknya?','Verifikasi pengirim sebelum membuka','Langsung aktifkan makro','Sebarkan ke semua orang','Matikan firewall','A');
