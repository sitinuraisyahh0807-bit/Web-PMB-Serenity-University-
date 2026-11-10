<?php

require_once dirname(__DIR__) . '/config.php';

require_admin();

$pdo = db();
$msg = flash();

function admin_header($title)
{
    ?>
    <!doctype html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= e($title) ?> | Admin Serenity</title>
        <link rel="stylesheet" href="../css/app.css">
    </head>
    <body class="app">
        <div class="layout">
            <aside class="sidebar">
                <div class="brand">
                    SERENITY<br>UNIVERSITY
                </div>

                <nav class="nav">
                    <a href="index.php">Dashboard</a>
                    <a href="pendaftar.php">Data Calon Maba</a>
                    <a href="soal.php">Bank Soal</a>
                    <a href="hasil.php">Validasi Hasil Seleksi</a>
                    <a href="../logout.php">Keluar</a>
                </nav>
            </aside>

            <main class="main">
                <header class="topbar">
                    <strong>Panel Admin · PMB Jalur Mandiri</strong>
                    <span><?= e(user()['nama']) ?></span>
                </header>

                <div class="content">
                    <h1><?= e($title) ?></h1>

                    <?php if (!empty($GLOBALS['msg'])): ?>
                        <div class="alert">
                            <?= e($GLOBALS['msg']) ?>
                        </div>
                    <?php endif; ?>
    <?php
}

function admin_footer()
{
    ?>
                </div>
            </main>
        </div>
    </body>
    </html>
    <?php
}