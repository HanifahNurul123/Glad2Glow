<?php
session_start();
include 'function.php';

// Handle Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = query("SELECT * FROM user WHERE email='$email'");

    if ($user && password_verify($password, $user[0]['password'])) {
        $_SESSION['id_user'] = $user[0]['id'];
        $_SESSION['nama'] = $user[0]['nama'];
        header('location:index_pengunjung.php');
        exit;
    } else {
        echo '<script>alert("Email atau Password salah!");</script>';
    }
}

// Handle Register
if (isset($_POST['register'])) {
    $nama = $_POST['reg_nama'];
    $email = $_POST['reg_email'];
    $password = $_POST['reg_password'];

    // Cek apakah email sudah ada
    $cek = query("SELECT * FROM user WHERE email='$email'");

    if (count($cek) > 0) {
        echo '<script>alert("Email sudah terdaftar!");</script>';
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert ke database
        $query = "INSERT INTO user (nama, email, password) VALUES ('$nama', '$email', '$hashed_password')";
        if (mysqli_query($conn, $query)) {
            echo '<script>alert("Registrasi berhasil! Silakan login."); 
                  setTimeout(function(){ location.reload(); }, 500);
                  </script>';
        } else {
            echo '<script>alert("Registrasi gagal!");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - Glad2Glow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/login_pengunjung.css">
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <!-- Login Form -->
                <form action="" method="POST" class="sign-in-form">
                    <h2 class="title">Login</h2>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required />
                    </div>
                    <input type="submit" name="login" value="Login" class="btn solid" />
                    <p class="social-text" style="font-size: 0.9rem;">
                        Belum punya akun? <a href="#" id="sign-up-link" style="color: #ff6b9d; text-decoration: none; font-weight: 600;">Daftar disini</a>
                    </p>
                </form>

                <!-- Register Form -->
                <form action="" method="POST" class="sign-up-form">
                    <h2 class="title">Register</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="reg_nama" placeholder="Nama Lengkap" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="reg_email" placeholder="Email" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="reg_password" placeholder="Password" required />
                    </div>
                    <input type="submit" name="register" class="btn" value="Register" />
                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>Baru di sini?</h3>
                    <p>
                        Bergabunglah dengan Glad2Glow hari ini! Buat akun dan mulai perjalanan kecantikan Anda bersama kami.
                    </p>
                    <button class="btn transparent" id="sign-up-btn">
                        Register
                    </button>
                </div>
                <div class="image">
                    <img src="assets/img/loginregister.jpeg" alt="Your Beauty, Simplified">
                </div>
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>Sudah punya akun?</h3>
                    <p>
                        Selamat datang kembali! Login untuk mengakses akun Anda dan lanjutkan belanja.
                    </p>
                    <button class="btn transparent" id="sign-in-btn">
                        Login
                    </button>
                </div>
                <div class="image">
                    <img src="assets/img/loginregister.jpeg" alt="Your Beauty, Simplified">
                </div>
            </div>
        </div>
    </div>

    <script>
        const sign_in_btn = document.querySelector("#sign-in-btn");
        const sign_up_btn = document.querySelector("#sign-up-btn");
        const container = document.querySelector(".container");
        const sign_up_link = document.querySelector("#sign-up-link");

        sign_up_btn.addEventListener("click", () => {
            container.classList.add("sign-up-mode");
        });

        sign_in_btn.addEventListener("click", () => {
            container.classList.remove("sign-up-mode");
        });

        if (sign_up_link) {
            sign_up_link.addEventListener("click", (e) => {
                e.preventDefault();
                container.classList.add("sign-up-mode");
            });
        }
    </script>
</body>

</html>