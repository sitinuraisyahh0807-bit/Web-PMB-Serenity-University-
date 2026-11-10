<?php
require_once __DIR__ . '/config.php';
$pdo = db();
$count = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
$message = '';
if ($count > 0) {
    $message = 'Akun admin sudah tersedia. Hapus file setup_admin.php dari folder project.';
} else {
    $stmt = $pdo->prepare('INSERT INTO users(nama,nik,email,telepon,password,role) VALUES(?,?,?,?,?,?)');
    $stmt->execute(['Administrator', null, 'admin@serenity.ac.id', null, password_hash('Admin123!', PASSWORD_DEFAULT), 'admin']);
    $message = 'Admin berhasil dibuat. Email: admin@serenity.ac.id | Password: Admin123!. Segera login dan ganti password, lalu hapus file setup_admin.php.';
}
?><!doctype html><html lang="id"><meta charset="utf-8"><title>Setup Admin Serenity</title><body><h2><?=e($message)?></h2><p>Untuk keamanan, hapus file setup_admin.php setelah proses ini.</p></body></html>
