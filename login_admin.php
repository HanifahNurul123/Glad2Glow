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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: #fff;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 1000px;
            max-width: 100%;
            min-height: 600px;
            display: flex;
            flex-wrap: wrap;
        }

        /* Left Side - Form */
        .login-form-container {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: #ffffff;
        }

        /* Right Side - Image/Banner */
        .banner-container {
            flex: 1;
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .banner-container::before {
            content: "";
            position: absolute;
            width: 150%;
            height: 150%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
            top: -25%;
            left: -25%;
        }

        .banner-image {
            width: 80%;
            max-width: 350px;
            z-index: 2;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .banner-image:hover {
            transform: scale(1.02);
        }

        form {
            width: 100%;
            max-width: 400px;
        }

        .title {
            font-size: 2.2rem;
            color: #333;
            margin-bottom: 5px;
            font-weight: 700;
            text-align: center;
        }

        .subtitle {
            font-size: 0.95rem;
            color: #888;
            margin-bottom: 30px;
            text-align: center;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px 15px 50px;
            border: none;
            background: #f4f8f7;
            border-radius: 50px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }

        .form-control:focus {
            background: #fff;
            box-shadow: 0 5px 20px rgba(255, 107, 157, 0.15);
            outline: none;
        }

        .admin-badge {
            background: #ffe0eb;
            color: #ff6b9d;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, #ff6b9d, #ff8fab);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(255, 107, 157, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(255, 107, 157, 0.4);
        }

        .footer-text {
            margin-top: 25px;
            text-align: center;
            color: #aaa;
            font-size: 0.85rem;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                flex-direction: column-reverse;
                height: auto;
                min-height: auto;
            }

            .banner-container {
                padding: 40px;
                min-height: 200px;
            }

            .banner-image {
                width: 150px;
            }
        }
    </style>
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
            <img src="img/admin.jpeg" alt="Admin Illustration" class="banner-image">
        </div>
    </div>

</body>

</html>