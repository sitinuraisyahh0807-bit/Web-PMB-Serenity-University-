<?php
require_once __DIR__ . '/config.php';
require_login();
flash('PMB Jalur Mandiri Serenity University tidak menggunakan pembayaran pendaftaran maupun daftar ulang.');
header('Location: dashboard.php');
exit;
