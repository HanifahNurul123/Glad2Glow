<?php
include 'function.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // default sesuai tabel
    $jabatan = "Super Admin";
    $status  = 1;

    // cek email unik
    $cek = query("SELECT * FROM admin WHERE email='$email'");
    if ($cek) {
        echo "<script>alert('Email sudah terdaftar!');</script>";
    } else {
        mysqli_query($conn, "
            INSERT INTO admin 
            (username, email, password, jabatan, status_aktif)
            VALUES 
            ('$username', '$email', '$password', '$jabatan', $status)
        ");

        header("Location: login_admin.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="width:380px;">
    <h4 class="text-center mb-4">Register Admin</h4>

    <form method="POST">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button name="register" class="btn btn-danger w-100">
            Register
        </button>
    </form>

    <div class="text-center mt-3">
        <a href="login_admin.php">Sudah punya akun? Login</a>
    </div>
</div>

</body>
</html>