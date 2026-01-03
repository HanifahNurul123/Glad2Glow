<?php
session_start();
include 'function.php';
if (isset($_SESSION['admin'])) {
    header("Location: index_admin.php");
    exit;
}
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
            overflow: hidden;
            position: relative;
        }

        /* Animated background particles */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        body::before {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        body::after {
            width: 400px;
            height: 400px;
            bottom: -200px;
            right: -200px;
            animation-delay: 3s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) translateX(0);
            }

            50% {
                transform: translateY(-20px) translateX(20px);
            }
        }

        .container {
            max-width: 900px;
            width: 100%;
            z-index: 10;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
            animation: fadeInDown 0.8s ease;
        }

        .header h1 {
            color: white;
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .header p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.1rem;
            font-weight: 300;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 0 20px;
        }

        .role-card {
            background: white;
            border-radius: 25px;
            padding: 40px 30px;
            text-decoration: none;
            color: #333;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease;
            animation-fill-mode: both;
        }

        .role-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ff6b9d, #ff8fab);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .role-card:hover::before {
            transform: scaleX(1);
        }

        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 80px rgba(255, 107, 157, 0.4);
        }

        .role-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 25px;
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 10px 30px rgba(255, 107, 157, 0.3);
            transition: all 0.3s ease;
        }

        .role-card:hover .role-icon {
            transform: rotateY(360deg);
            box-shadow: 0 15px 40px rgba(255, 107, 157, 0.5);
        }

        .role-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .role-description {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.6;
            text-align: center;
            margin-bottom: 25px;
        }

        .role-badge {
            display: inline-block;
            padding: 8px 20px;
            background: linear-gradient(135deg, #ff6b9d, #ff8fab);
            color: white;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(255, 107, 157, 0.3);
        }

        .admin-card .role-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .admin-card:hover .role-icon {
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
        }

        .admin-card .role-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .admin-card:hover {
            box-shadow: 0 30px 80px rgba(102, 126, 234, 0.4);
        }

        .features {
            list-style: none;
            padding: 0;
            margin: 20px 0 0 0;
        }

        .features li {
            padding: 8px 0;
            color: #666;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .features li i {
            color: #ff6b9d;
            font-size: 1rem;
        }

        .admin-card .features li i {
            color: #667eea;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .header p {
                font-size: 1rem;
            }

            .cards-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .role-card {
                padding: 30px 25px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Selamat Datang di Glad2Glow!</h1>
            <p>Silakan pilih role Anda untuk melanjutkan</p>
        </div>

        <div class="cards-container">
            <!-- Customer Card -->
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
                    <li><i class="fas fa-check-circle"></i> Tracking Pesanan</li>
                </ul>
            </a>

            <!-- Admin Card -->
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
                    <li><i class="fas fa-check-circle"></i> Manajemen Pesanan</li>
                    <li><i class="fas fa-check-circle"></i> Kontrol Penuh Sistem</li>
                </ul>
            </a>
        </div>
    </div>
</body>

</html>