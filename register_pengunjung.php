<?php
include 'function.php';

if (isset($_POST['register'])) {
    $nama     = htmlspecialchars($_POST['nama']);
    $email    = htmlspecialchars($_POST['email']);
    $alamat   = htmlspecialchars($_POST['alamat']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek email sudah terdaftar atau belum
    $cek = query("SELECT * FROM user WHERE email='$email'");
    if ($cek) {
        echo "<script>
            alert('Email sudah terdaftar!');
            window.location='register_pengunjung.php';
        </script>";
        exit;
    }

    mysqli_query($conn, "
        INSERT INTO user (nama, email, password, alamat)
        VALUES ('$nama', '$email', '$password', '$alamat')
    ");

    echo "<script>
        alert('Register berhasil, silakan login');
        window.location='login_pengunjung.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Pengunjung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4 mx-auto" style="max-width:400px">
        <h4 class="text-center mb-3">Register Pengunjung</h4>

        <form method="POST">
            <input class="form-control mb-2" name="nama" placeholder="Nama Lengkap" required>
            <input class="form-control mb-2" name="email" placeholder="Email" required>
            <textarea class="form-control mb-2" name="alamat" placeholder="Alamat" required></textarea>
            <input type="password" class="form-control mb-3" name="password" placeholder="Password" required>

            <button name="register" class="btn btn-primary w-100">
                Register
            </button>
        </form>

        <div class="text-center mt-3">
            Sudah punya akun? <a href="login_pengunjung.php">Login</a>
        </div>
    </div>
</div>

</body>
</html>