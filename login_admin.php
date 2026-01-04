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
            min-height: 500px;
            background: #fff;
            border-radius: 30px;
            box-shadow: 0 50px 100px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .forms-container {
            width: 50%;
            z-index: 5;
            padding: 0 2rem;
        }

        form {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 1rem;
            width: 100%;
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
            width: 100%;
            background-color: #f0f0f0;
            margin: 10px 0;
            height: 55px;
            border-radius: 55px;
            display: grid;
            grid-template-columns: 15% 85%;
            padding: 0 0.4rem;
            position: relative;
        }

        .input-field i {
            text-align: center;
            line-height: 55px;
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
        }

        .input-field input::placeholder {
            color: #aaa;
            font-weight: 400;
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

        /* Image Panel */
        .panel {
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            z-index: 6;
            background: linear-gradient(-45deg, #ff6b9d 0%, #ffc3a0 50%, #ff8fab 100%);
            height: 100%;
            position: absolute;
            right: 0;
            top: 0;
            border-top-left-radius: 30% 50%;
            border-bottom-left-radius: 30% 50%;
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

        .social-text {
            padding: 0.7rem 0;
            font-size: 0.85rem;
            color: #666;
        }

        /* Responsive */
        @media (max-width: 870px) {
            .container {
                flex-direction: column;
                min-height: 800px;
            }

            .forms-container {
                width: 100%;
                padding-top: 50px;
            }

            .panel {
                width: 100%;
                height: 40%;
                top: auto;
                bottom: 0;
                right: 0;
                border-radius: 0;
                border-top-left-radius: 50% 20%;
                border-top-right-radius: 50% 20%;
            }

            .image img {
                max-width: 200px;
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
        <!-- Login Form Container -->
        <div class="forms-container">
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
        </div>

        <!-- Image Panel -->
        <div class="panel">
            <div class="image">
                <img src="img/admin.jpeg" alt="Admin Dashboard">
            </div>
        </div>
    </div>
</body>

</html>