<?php
session_start();
include 'function.php';

// Handle Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $admin = query("
        SELECT * FROM admin 
        WHERE email='$email' 
        AND password='$password' 
        AND status_aktif = 1
    ");

    if ($admin) {
        $_SESSION['admin'] = true;
        $_SESSION['id_admin'] = $admin[0]['id_admin'];

        // update terakhir login
        mysqli_query($conn, "
            UPDATE admin 
            SET terakhir_login = NOW() 
            WHERE id_admin = {$admin[0]['id_admin']}
        ");

        header('location:index_admin.php');
        exit;
    } else {
        echo '<script>alert("Login gagal! Periksa email dan password Anda.");</script>';
    }
}

// Handle Register
if (isset($_POST['register'])) {
    $nama = $_POST['reg_nama'];
    $email = $_POST['reg_email'];
    $password = $_POST['reg_password'];

    // cek email unik
    $cek = query("SELECT * FROM admin WHERE email='$email'");
    if ($cek) {
        echo '<script>alert("Email sudah terdaftar!");</script>';
    } else {
        mysqli_query($conn, "
            INSERT INTO admin 
            (nama_lengkap, email, password, status_aktif)
            VALUES 
            ('$nama', '$email', '$password', 1)
        ");

        echo '<script>alert("Registrasi berhasil! Silakan login."); 
              setTimeout(function(){ location.reload(); }, 500);
              </script>';
    }
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
            max-width: 850px;
            min-height: 550px;
            background: #fff;
            border-radius: 30px;
            box-shadow: 0 50px 100px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .forms-container {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        .signin-signup {
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            left: 75%;
            width: 50%;
            transition: 1s 0.7s ease-in-out;
            display: grid;
            grid-template-columns: 1fr;
            z-index: 5;
        }

        form {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 5rem;
            transition: all 0.2s 0.7s;
            overflow: hidden;
            grid-column: 1 / 2;
            grid-row: 1 / 2;
        }

        form.sign-up-form {
            opacity: 0;
            z-index: 1;
        }

        form.sign-in-form {
            z-index: 2;
        }

        .title {
            font-size: 2.2rem;
            color: #444;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .subtitle {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
        }

        .input-field {
            max-width: 380px;
            width: 100%;
            background-color: #f0f0f0;
            margin: 10px 0;
            height: 55px;
            border-radius: 55px;
            display: grid;
            grid-template-columns: 15% 85%;
            padding: 0 0.4rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .input-field:focus-within {
            background-color: #e8e8e8;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .input-field i {
            text-align: center;
            line-height: 55px;
            color: #acacac;
            transition: 0.5s;
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
        }

        .input-field input::placeholder {
            color: #aaa;
            font-weight: 400;
        }

        .social-text {
            padding: 0.7rem 0;
            font-size: 0.85rem;
            color: #666;
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 10px;
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
            margin: 10px 0;
            cursor: pointer;
            transition: 0.5s;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
        }

        .btn:hover {
            background: linear-gradient(135deg, #ff5088 0%, #ff7a9a 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 107, 157, 0.5);
        }

        .panels-container {
            position: absolute;
            height: 100%;
            width: 100%;
            top: 0;
            left: 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }

        .container:before {
            content: "";
            position: absolute;
            height: 2000px;
            width: 2000px;
            top: -10%;
            right: 48%;
            transform: translateY(-50%);
            background-image: linear-gradient(-45deg, #ff6b9d 0%, #ffc3a0 50%, #ff8fab 100%);
            transition: 1.8s ease-in-out;
            border-radius: 50%;
            z-index: 6;
        }

        .panel {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-around;
            text-align: center;
            z-index: 6;
        }

        .left-panel {
            pointer-events: all;
            padding: 3rem 17% 2rem 12%;
        }

        .right-panel {
            pointer-events: none;
            padding: 3rem 12% 2rem 17%;
        }

        .panel .content {
            color: #fff;
            transition: transform 0.9s ease-in-out;
            transition-delay: 0.6s;
        }

        .panel h3 {
            font-weight: 600;
            line-height: 1;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .panel p {
            font-size: 0.95rem;
            padding: 0.7rem 0;
            opacity: 0.9;
        }

        .btn.transparent {
            margin: 0;
            background: none;
            border: 2px solid #fff;
            width: 130px;
            height: 41px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .btn.transparent:hover {
            background: #fff;
            color: #ff6b9d;
        }

        .right-panel .image,
        .right-panel .content {
            transform: translateX(800px);
        }

        /* ANIMATION */
        .container.sign-up-mode:before {
            transform: translate(100%, -50%);
            right: 52%;
        }

        .container.sign-up-mode .left-panel .image,
        .container.sign-up-mode .left-panel .content {
            transform: translateX(-800px);
        }

        .container.sign-up-mode .signin-signup {
            left: 25%;
        }

        .container.sign-up-mode form.sign-up-form {
            opacity: 1;
            z-index: 2;
        }

        .container.sign-up-mode form.sign-in-form {
            opacity: 0;
            z-index: 1;
        }

        .container.sign-up-mode .right-panel .image,
        .container.sign-up-mode .right-panel .content {
            transform: translateX(0%);
        }

        .container.sign-up-mode .left-panel {
            pointer-events: none;
        }

        .container.sign-up-mode .right-panel {
            pointer-events: all;
        }

        .image {
            width: 100%;
            transition: transform 1.1s ease-in-out;
            transition-delay: 0.4s;
        }

        .image img {
            width: 100%;
            max-width: 320px;
            border-radius: 20px;
            object-fit: cover;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        /* Responsive */
        @media (max-width: 870px) {
            .container {
                min-height: 800px;
                height: 100vh;
            }

            .signin-signup {
                width: 100%;
                top: 95%;
                transform: translate(-50%, -100%);
                transition: 1s 0.8s ease-in-out;
            }

            .signin-signup,
            .container.sign-up-mode .signin-signup {
                left: 50%;
            }

            .panels-container {
                grid-template-columns: 1fr;
                grid-template-rows: 1fr 2fr 1fr;
            }

            .panel {
                flex-direction: row;
                justify-content: space-around;
                align-items: center;
                padding: 2.5rem 8%;
                grid-column: 1 / 2;
            }

            .right-panel {
                grid-row: 3 / 4;
            }

            .left-panel {
                grid-row: 1 / 2;
            }

            .image {
                width: 200px;
                transition: transform 0.9s ease-in-out;
                transition-delay: 0.6s;
            }

            .panel .content {
                padding-right: 15%;
                transition: transform 0.9s ease-in-out;
                transition-delay: 0.8s;
            }

            .panel h3 {
                font-size: 1.2rem;
            }

            .panel p {
                font-size: 0.7rem;
                padding: 0.5rem 0;
            }

            .btn.transparent {
                width: 110px;
                height: 35px;
                font-size: 0.7rem;
            }

            .container:before {
                width: 1500px;
                height: 1500px;
                transform: translateX(-50%);
                left: 30%;
                bottom: 68%;
                right: initial;
                top: initial;
                transition: 2s ease-in-out;
            }

            .container.sign-up-mode:before {
                transform: translate(-50%, 100%);
                bottom: 32%;
                right: initial;
            }

            .container.sign-up-mode .left-panel .image,
            .container.sign-up-mode .left-panel .content {
                transform: translateY(-300px);
            }

            .container.sign-up-mode .right-panel .image,
            .container.sign-up-mode .right-panel .content {
                transform: translateY(0px);
            }

            .right-panel .image,
            .right-panel .content {
                transform: translateY(300px);
            }

            .container.sign-up-mode .signin-signup {
                top: 5%;
                transform: translate(-50%, 0);
            }
        }

        @media (max-width: 570px) {
            form {
                padding: 0 1.5rem;
            }

            .image {
                display: none;
            }

            .panel .content {
                padding: 0.5rem 1rem;
            }

            .container {
                padding: 1.5rem;
            }

            .container:before {
                bottom: 72%;
                left: 50%;
            }

            .container.sign-up-mode:before {
                bottom: 28%;
                left: 50%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <!-- Login Form -->
                <form action="" method="POST" class="sign-in-form">
                    <span class="admin-badge">
                        <i class="fas fa-shield-halved"></i>
                        Admin Portal
                    </span>
                    <h2 class="title">Login Admin</h2>
                    <p class="subtitle">Glad2Glow Management System</p>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email Admin" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required />
                    </div>
                    <input type="submit" name="login" value="Login" class="btn solid" />
                    <p class="social-text">Akses terbatas untuk administrator</p>
                </form>

                <!-- Register Form -->
                <form action="" method="POST" class="sign-up-form">
                    <span class="admin-badge">
                        <i class="fas fa-user-plus"></i>
                        Register Admin
                    </span>
                    <h2 class="title">Daftar Admin</h2>
                    <p class="subtitle">Buat akun administrator baru</p>
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
                    <p class="social-text">Status: Super Admin</p>
                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>Admin Baru?</h3>
                    <p>
                        Daftar sebagai administrator untuk mengelola produk kosmetik dan sistem Glad2Glow.
                    </p>
                    <button class="btn transparent" id="sign-up-btn">
                        Register
                    </button>
                </div>
                <div class="image">
                    <img src="img/admin.jpeg" alt="Admin Dashboard">
                </div>
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>Sudah Terdaftar?</h3>
                    <p>
                        Masuk ke dashboard admin untuk mengelola produk, pesanan, dan data pelanggan.
                    </p>
                    <button class="btn transparent" id="sign-in-btn">
                        Login
                    </button>
                </div>
                <div class="image">
                    <img src="img/admin.jpeg" alt="Admin Management">
                </div>
            </div>
        </div>
    </div>

    <script>
        const sign_in_btn = document.querySelector("#sign-in-btn");
        const sign_up_btn = document.querySelector("#sign-up-btn");
        const container = document.querySelector(".container");

        sign_up_btn.addEventListener("click", () => {
            container.classList.add("sign-up-mode");
        });

        sign_in_btn.addEventListener("click", () => {
            container.classList.remove("sign-up-mode");
        });
    </script>
</body>

</html>