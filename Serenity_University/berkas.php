<?php
require_once __DIR__ . '/config.php';
require_login();
flash('Pengunggahan berkas persyaratan belum termasuk tahapan wajib pada alur PMB ini. Silakan lanjutkan melalui dashboard.');
header('Location: dashboard.php');
exit;
