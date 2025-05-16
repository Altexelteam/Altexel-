<?php
session_start();
?>

<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Altexel Platforması</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<header>
    Altexel Platforması
</header>

<nav style="text-align:center; padding: 15px 0;">
    <?php if (isset($_SESSION['username'])): ?>
        <a href="index.php">Əsas səhifə</a> |
        <a href="upload.php">Video Yüklə</a> |
        <a href="chat.php">Sohbet</a> |
        <a href="admin.php">Admin Panel</a> |
        Salam, <?=htmlspecialchars($_SESSION['username'])?> |
        <a href="logout.php">Çıxış</a>
    <?php else: ?>
        <a href="login_register.php">Daxil ol / Qeydiyyat</a>
    <?php endif; ?>
</nav>

<div class="container">
