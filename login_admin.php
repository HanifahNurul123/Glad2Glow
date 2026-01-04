<?php
session_start();
include 'function.php';

// Handle Login
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Ambil data admin berdasarkan email
    $result = mysqli_query($conn, "SELECT * FROM admin WHERE email='$email' AND status_aktif = 1");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Cek password (Verifikasi Hash ATAU Plain Text untuk migrasi)
        if (password_verify($password, $row['password'])) {
            // Password Hash Cocok
            $_SESSION['admin'] = true;
            $_SESSION['id_admin'] = $row['id_admin'];

            // update terakhir login
            mysqli_query($conn, "UPDATE admin SET terakhir_login = NOW() WHERE id_admin = {$row['id_admin']}");

            header('location:index_admin.php');
            exit;
        } elseif ($password == $row['password']) {
            // Password Plain Text Cocok (Migrasi ke Hash)
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $id_admin = $row['id_admin'];
            mysqli_query($conn, "UPDATE admin SET password='$newHash' WHERE id_admin='$id_admin'");

            $_SESSION['admin'] = true;
            $_SESSION['id_admin'] = $row['id_admin'];

            // update terakhir login
            mysqli_query($conn, "UPDATE admin SET terakhir_login = NOW() WHERE id_admin = {$row['id_admin']}");

            header('location:index_admin.php');
            exit;
        }
    }

    $error = true;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Glad2Glow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/login_admin.css">
</head>

<body>

    <div class="container">
        <!-- FORM SECTION -->
        <div class="login-form-container">
            <div class="admin-badge">
                <i class="fas fa-shield-halved"></i> Admin Portal
            </div>
            <h2 class="title">Welcome Back!</h2>
            <p class="subtitle">Silakan login untuk mengelola sistem</p>

            <?php if (isset($error)): ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle me-1"></i> Email atau Password salah!
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                </div>
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

        <!-- IMAGE SECTION -->
        <div class="banner-container">
            <img src="assets/img/admin.jpeg" alt="Admin Illustration" class="banner-image">
        </div>
    </div>

</body>

</html>