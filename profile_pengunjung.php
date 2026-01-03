<?php
session_start();
include 'function.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: login_pengunjung.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$user = query("SELECT * FROM user WHERE id = $id_user")[0];

// Handle Update Profile
if (isset($_POST['update_profile'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Update Nama & Email
    $query_update = "UPDATE user SET nama='$nama', email='$email' WHERE id='$id_user'";

    // Jika password diisi, update password juga
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query_update = "UPDATE user SET nama='$nama', email='$email', password='$hashed_password' WHERE id='$id_user'";
    }

    if (mysqli_query($conn, $query_update)) {
        $_SESSION['nama'] = $nama; // Update session nama
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
<html lang="en">

<!-- Head reusing styles from login for consistency -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Glad2Glow</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff6b9d 0%, #ffc3a0 50%, #ff8fab 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            position: relative;
            width: 100%;
            max-width: 500px;
            background: #fff;
            border-radius: 30px;
            box-shadow: 0 50px 100px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            padding: 40px 30px;
            text-align: center;
        }

        .title {
            font-size: 2.2rem;
            color: #444;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .input-field {
            max-width: 100%;
            width: 100%;
            background-color: #f0f0f0;
            margin: 10px 0;
            height: 55px;
            border-radius: 55px;
            display: grid;
            grid-template-columns: 15% 85%;
            padding: 0 0.4rem;
            position: relative;
            align-items: center;
        }

        .input-field i {
            text-align: center;
            color: #acacac;
            font-size: 1.1rem;
        }

        .input-field input {
            background: none;
            outline: none;
            border: none;
            line-height: 1;
            font-weight: 400;
            font-size: 1rem;
            color: #333;
            font-family: 'Poppins', sans-serif;
            width: 100%;
        }

        .btn {
            width: 150px;
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            border: none;
            outline: none;
            height: 49px;
            border-radius: 49px;
            color: #fff;
            text-transform: uppercase;
            font-weight: 600;
            margin: 20px 0 10px 0;
            cursor: pointer;
            transition: 0.5s;
        }

        .btn:hover {
            background: linear-gradient(135deg, #ff5088 0%, #ff7a9a 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 107, 157, 0.5);
        }

        .btn-secondary {
            background: #6c757d;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background: #5a6268;
            box-shadow: 0 10px 30px rgba(90, 98, 104, 0.5);
        }

        .profile-img {
            width: 100px;
            height: 100px;
            background: #ff6b9d;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 3rem;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- ICON PROFILE -->
        <div class="profile-img">
            <i class="fa-solid fa-user"></i>
        </div>

        <h2 class="title">Edit Profile</h2>
        <p class="mb-4" style="color: #666; margin-bottom: 20px;">Halo, <b><?= $user['nama']; ?></b>!</p>

        <form action="" method="POST">
            <!-- NAMA -->
            <div class="input-field">
                <i class="fas fa-user"></i>
                <input type="text" name="nama" value="<?= $user['nama']; ?>" placeholder="Nama Lengkap" required />
            </div>

            <!-- EMAIL -->
            <div class="input-field">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" value="<?= $user['email']; ?>" placeholder="Email" required />
            </div>

            <!-- PASSWORD -->
            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password Baru (Opsional)" />
            </div>
            <small style="color: #888; display: block; text-align: left; margin-left: 20px; font-size: 0.8rem;">
                *Kosongkan jika tidak ingin mengganti password
            </small>

            <div style="display: flex; justify-content: center; gap: 10px;">
                <button type="submit" name="update_profile" class="btn">Simpan</button>
                <a href="index_pengunjung.php" class="btn btn-secondary" style="display: flex; justify-content: center; align-items: center; text-decoration: none;">Kembali</a>
            </div>
        </form>
    </div>

</body>

</html>