<?php
session_start();
include 'function.php';

// ============================================================
// REDIRECT OTOMATIS
// ============================================================
// Jika sudah login sebagai admin, langsung ke dashboard admin
if (isset($_SESSION['admin'])) {
    header("Location: index_admin.php");
    exit;
}
// Jika sudah login sebagai user/pengunjung, langsung ke halaman utama pengunjung
if (isset($_SESSION['id_user'])) {
    header("Location: index_pengunjung.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Role - Glad2Glow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/pilihan.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Selamat Datang di Glad2Glow!</h1>
            <p>Silakan pilih role Anda untuk melanjutkan</p>
        </div>

        <div class="cards-container">
            <!-- 
                Kartu Pilihan Untuk CUSTOMER / PENGUNJUNG 
            -->
            <a href="login_pengunjung.php" class="role-card">
                <div class="role-badge">💄 Customer Portal</div>
                <div class="role-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h2 class="role-title">Pengunjung</h2>
                <p class="role-description">
                    Jelajahi koleksi produk kecantikan terbaik kami dan nikmati pengalaman belanja yang menyenangkan
                </p>
                <ul class="features">
                    <li><i class="fas fa-check-circle"></i> Belanja Produk Kosmetik</li>
                    <li><i class="fas fa-check-circle"></i> Lihat Katalog Lengkap</li>
                    <li><i class="fas fa-check-circle"></i> Kelola Keranjang Belanja</li>
                </ul>
            </a>

            <!-- 
                Kartu Pilihan Untuk ADMIN 
            -->
            <a href="login_admin.php" class="role-card admin-card">
                <div class="role-badge">🛡️ Admin Portal</div>
                <div class="role-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h2 class="role-title">Administrator</h2>
                <p class="role-description">
                    Kelola sistem, produk, dan data pelanggan dengan akses penuh ke dashboard admin
                </p>
                <ul class="features">
                    <li><i class="fas fa-check-circle"></i> Kelola Produk & Stok</li>
                    <li><i class="fas fa-check-circle"></i> Lihat Analytics & Report</li>
                    <li><i class="fas fa-check-circle"></i> Kontrol Penuh Sistem</li>
                </ul>
            </a>
        </div>
    </div>
</body>

</html>