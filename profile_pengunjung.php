<?php
session_start();
include 'function.php';

// ============================================================
// CEK LOGIN
// ============================================================
if (!isset($_SESSION['id_user'])) {
    header("Location: login_pengunjung.php");
    exit;
}

$id_user = $_SESSION['id_user'];
// Ambil data user yang sedang login
$user = query("SELECT * FROM user WHERE id = $id_user")[0];

// ============================================================
// HANDLER UPDATE USER PROFILE
// ============================================================
if (isset($_POST['update_profile'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password']; // Password baru (opsional)

    // Query dasar update nama dan email
    $query_update = "UPDATE user SET nama='$nama', email='$email' WHERE id='$id_user'";

    // Jika kolom password diisi, maka update juga passwordnya
    if (!empty($password)) {
        // Hash password baru sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query_update = "UPDATE user SET nama='$nama', email='$email', password='$hashed_password' WHERE id='$id_user'";
    }

    // Eksekusi query update
    if (mysqli_query($conn, $query_update)) {
        $_SESSION['nama'] = $nama; // Update juga nama di session
        echo "<script>
            alert('Profil berhasil diperbarui!');
            window.location.href = 'profile_pengunjung.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal memperbarui profil!');
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Glad2Glow</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS Custom External -->
    <link rel="stylesheet" href="assets/css/profile.css">
</head>

<body>

    <div class="container">
        <!-- ICON PROFILE -->
        <div class="profile-img">
            <i class="fa-solid fa-user"></i>
        </div>

        <h2 class="title">Edit Profile</h2>
        <p class="mb-4" style="color: #666; margin-bottom: 20px;">Halo, <b><?= $user['nama']; ?></b>!</p>

        <!-- Form Edit Profile -->
        <form action="" method="POST">
            <!-- INPUT NAMA -->
            <div class="input-field">
                <i class="fas fa-user"></i>
                <input type="text" name="nama" value="<?= $user['nama']; ?>" placeholder="Nama Lengkap" required />
            </div>

            <!-- INPUT EMAIL -->
            <div class="input-field">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" value="<?= $user['email']; ?>" placeholder="Email" required />
            </div>

            <!-- INPUT PASSWORD BARU (OPSIONAL) -->
            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password Baru (Opsional)" />
            </div>
            <small style="color: #888; display: block; text-align: left; margin-left: 20px; font-size: 0.8rem;">
                *Kosongkan jika tidak ingin mengganti password
            </small>

            <!-- TOMBOL AKSI -->
            <div style="display: flex; justify-content: center; gap: 10px;">
                <button type="submit" name="update_profile" class="btn">Simpan</button>
                <a href="index_pengunjung.php" class="btn btn-secondary" style="display: flex; justify-content: center; align-items: center; text-decoration: none;">Kembali</a>
            </div>
        </form>
    </div>

</body>

</html>