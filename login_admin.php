<?php
session_start();
include 'function.php';

// ============================================================
// LOGIK LOGIN ADMIN
// ============================================================
// Mengecek apakah tombol 'login' ditekan
if (isset($_POST['login'])) {
    // Ambil input email dan bersihkan dari karakter berbahaya (SQL Injection prevention dasar)
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Query untuk mencari admin berdasarkan email dan status aktif
    $result = mysqli_query($conn, "SELECT * FROM admin WHERE email='$email' AND status_aktif = 1");

    // Jika email ditemukan (jumlah baris == 1)
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // ============================================================
        // VERIFIKASI PASSWORD
        // ============================================================
        // Skenario 1: Cek apakah password cocok dengan hash (Standard Modern)
        if (password_verify($password, $row['password'])) {
            // Set session login sukses
            $_SESSION['admin'] = true;
            $_SESSION['id_admin'] = $row['id_admin'];

            // Update timestamp login terakhir
            mysqli_query($conn, "UPDATE admin SET terakhir_login = NOW() WHERE id_admin = {$row['id_admin']}");

            // Redirect ke dashboard admin
            header('location:index_admin.php');
            exit;
        }
        // Skenario 2: Cek apakah password cocok dengan plain text (Legacy support / Migrasi)
        elseif ($password == $row['password']) {
            // Jika cocok, otomatis upgrade password ke hash agar lebih aman
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $id_admin = $row['id_admin'];

            // Update password di database menjadi hash
            mysqli_query($conn, "UPDATE admin SET password='$newHash' WHERE id_admin='$id_admin'");

            // Set session login sukses
            $_SESSION['admin'] = true;
            $_SESSION['id_admin'] = $row['id_admin'];

            // Update timestamp login terakhir
            mysqli_query($conn, "UPDATE admin SET terakhir_login = NOW() WHERE id_admin = {$row['id_admin']}");

            // Redirect ke dashboard admin
            header('location:index_admin.php');
            exit;
        }
    }

    // Jika email tidak ditemukan atau password salah, set flag error
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Glad2Glow</title>
    <!-- Font dan Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS Khusus Login Admin -->
    <link rel="stylesheet" href="assets/css/login_admin.css">
</head>

<body>

    <div class="container">
        <!-- SEKSI FORMULIR LOGIN -->
        <div class="login-form-container">
            <div class="admin-badge">
                <i class="fas fa-shield-halved"></i> Admin Portal
            </div>
            <h2 class="title">Welcome Back!</h2>
            <p class="subtitle">Silakan login untuk mengelola sistem</p>

            <!-- Tampilkan pesan error jika login gagal -->
            <?php if (isset($error)): ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle me-1"></i> Email atau Password salah!
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <!-- Input Email -->
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                </div>
                <!-- Input Password -->
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>

                <button type="submit" name="login" class="btn-login">
                    LOGIN SYSTEM
                </button>
            </form>

            <p class="footer-text">
                &copy; <?= date('Y'); ?> Glad2Glow Managament System
            </p>
        </div>

        <!-- SEKSI GAMBAR SAMPING -->
        <div class="banner-container">
            <img src="assets/img/admin.jpeg" alt="Admin Illustration" class="banner-image">
        </div>
    </div>

</body>

</html>