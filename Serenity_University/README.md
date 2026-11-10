# Serenity University — PMB Jalur Mandiri (tanpa pembayaran)

## Prinsip revisi
Mockup dan desain halaman lama dipertahankan. Perubahan dilakukan pada alur/fungsi PMB. Halaman baru `hasil-seleksi.php` memiliki kartu pengumuman hasil seleksi dengan identitas Serenity University.

## Alur
1. Calon mahasiswa registrasi dan login.
2. Calon mahasiswa melengkapi biodata dan memilih program studi.
3. Sistem menerbitkan nomor tes otomatis.
4. Peserta langsung mengikuti ujian 10 soal sesuai program studi (satu kesempatan).
5. Sistem menghitung dan menyimpan nilai secara privat.
6. Dashboard peserta menampilkan status **Menunggu validasi admin** tanpa skor/hasil kelulusan.
7. Admin melihat nilai pada menu **Validasi Hasil Seleksi** dan menyetujui hasil.
8. Setelah disetujui, tombol **Lihat Hasil Seleksi** muncul di dashboard peserta.
9. Jika lulus, NIM diterbitkan otomatis saat admin memvalidasi hasil.

## Memperbarui instalasi lama
1. Cadangkan folder proyek dan database `serenity_pmb`.
2. Ekstrak folder `Serenity_University` ke `C:\xampp\htdocs\` dan izinkan replace file.
3. Di phpMyAdmin, pilih database `serenity_pmb`, buka tab SQL.
4. Jalankan satu kali `migrations/20261105_no_payment_admin_validation.sql`.
5. Jangan impor ulang `database.sql` ke database lama.
6. Buka `http://localhost/Serenity_University/`.

## Instalasi baru
1. Ekstrak folder ke `C:\xampp\htdocs\`.
2. Buat database dan impor `database.sql`.
3. Jalankan Apache dan MySQL dari XAMPP.
4. Buka `http://localhost/Serenity_University/`.

## Catatan
- Sistem tidak menggunakan pembayaran pendaftaran/daftar ulang.
- Migrasi untuk database lama tidak menghapus tabel pembayaran lama atau data pengguna.
- Nilai dan status kelulusan tidak ditampilkan ke peserta sebelum admin memvalidasi.
- Halaman pembayaran lama diarahkan kembali ke dashboard.
